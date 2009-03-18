<?php

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/shop/classes/ezpaymentobject.php' );
include_once( 'kernel/classes/workflowtypes/event/ezpaymentgateway/ezpaymentgatewaytype.php' );
include_once( eZExtension::baseDirectory() . '/recurringorders/datatypes/ezcreditcardtype/ezcreditcard.php' );

define( "EZ_PAYMENT_GATEWAY_TYPE_XROWCOMDIRECT", "xrowcomdirect" );
define( "EZ_PAYMENT_GATEWAY_TYPE_XROWCOMDIRECT_MAX_STRING_LEN", 27 );

class xrowComdirectGateway
{
    /*!
     Constructor
    */
    function xrowComdirectGateway()
    {
    }

    function createArrayfromXML( $xmlDoc )
    {
        $result = array();
        $xml = new eZXML();
        $dom = $xml->domTree( $xmlDoc );
        $node = $dom->get_root();
        $children = $node->children();
        foreach ( $children as $child )
        {
            $contentnode = $child->firstChild();
            if ( $contentnode->type === EZ_XML_NODE_TEXT )
            {
                $result[$child->name()] = $contentnode->textContent();
            }
            else
            {
                // do something recurisve here, there is currently no need
            }
        }
        return $result;
    }

    /*!
        Creates new xrowcomdirect payment object.
    */
    function &createPaymentObject( &$processID, &$orderID )
    {
        return eZPaymentObject::createNew( $processID, $orderID, 'xrowComdirect' );
    }

    function execute( &$process, &$event )
    {
        include_once( eZExtension::baseDirectory() . '/ezpowerlib/ezpowerlib.php' );
        require_once( 'Net/Curl.php' );
        include_once( 'lib/ezi18n/classes/ezutf8codec.php' );
        include_once( 'lib/ezi18n/classes/ezcodepage.php' );

        $shopINI = eZINI::instance( 'shop.ini' );
        $xrowcomdirectINI = eZINI::instance( 'xrowcomdirect.ini' );

        $user           = $xrowcomdirectINI->variable('ServerSettings','Username');
        $password       = $xrowcomdirectINI->variable('ServerSettings','Password');
        $serverLink     = $xrowcomdirectINI->variable('ServerSettings','ServerRequestLink');
        $requestTimeout = $xrowcomdirectINI->variable('ServerSettings','RequestTimeout');

        // make the order object
        $processParams = $process->attribute( 'parameter_list' );

        // get order id
        $order_id = $processParams['order_id'];

        // get order
        include_once( 'kernel/classes/ezorder.php' );
        $order = eZOrder::fetch( $processParams['order_id'] );

        // get total order amount, including tax
        $amount = $order->attribute( 'total_inc_vat' );
        if ( $amount < 1 )
        {
            eZDebug::writeDebug ( 'Will not accept tiny payments', 'xrowComdirectGateway::execute()' );
            return EZ_WORKFLOW_TYPE_STATUS_REJECTED;
        }
        // amount in cent
        $amount = floor ( $amount * 100 );

        // client data
        // get order information out of eZXML
        $xml = new eZXML();
        $xmlDoc = $order->attribute( 'data_text_1' );

        $clientInfo = XROWRecurringordersCommonFunctions::createArrayfromXML($xmlDoc);
        $this->data = $clientInfo[XROWCREDITCARD_MAINKEY_CREDITCARD];


        //allowed charset is latin-1
        $codepage = eZCodePage::instance("iso-8859-1");
        $comini = eZINI::instance( 'xrowcomdirect.ini' );
        // only EUR is supported
        $currencyCode = 'EUR';
        $command = 'authorization';
        $maxlen = EZ_PAYMENT_GATEWAY_TYPE_XROWCOMDIRECT_MAX_STRING_LEN - strlen( $order->OrderNr ) - 1;
        $bookingstr = substr( $comini->variable( 'Settings', 'BookingString' ), 0, $maxlen ) . " " . $order->OrderNr; 
        //alllowed a-zA-Z0-9._-
        $allowed = "/[^a-z0-9\\.\\-\\_]/i";
  		$bookingstr = preg_replace( $allowed, "_", $bookingstr );
        if ( $clientInfo[XROWCREDITCARD_MAINKEY_CREDITCARD][XROWCREDITCARD_KEY_TYPE] == XROWCREDITCARD_TYPE_EUROCARD )
        {
            $fields = array (
                    'amount'        => $amount,
                    'cname'         => $codepage->convertStringFromUTF8 ( $clientInfo[XROWCREDITCARD_MAINKEY_CREDITCARD][XROWCREDITCARD_KEY_ECNAME] ),
                    'bankcode'     => $clientInfo[XROWCREDITCARD_MAINKEY_CREDITCARD][XROWCREDITCARD_KEY_BANKCODE],
                    'account'       => $clientInfo[XROWCREDITCARD_MAINKEY_CREDITCARD][XROWCREDITCARD_KEY_ACCOUNTNUMBER],
                    'orderid'       => $bookingstr,
                    'command'       => $command,
                    'currency'      => $currencyCode
                );
        }
        else
        {
            $year = '';
            if ( strlen( $clientInfo[XROWCREDITCARD_MAINKEY_CREDITCARD]['year'] ) == 4 )
                $year = substr( $clientInfo[XROWCREDITCARD_MAINKEY_CREDITCARD]['year'], 2, 2 );
            else if ( strlen( $clientInfo[XROWCREDITCARD_MAINKEY_CREDITCARD]['year'] ) == 2 )
                $year = $clientInfo[XROWCREDITCARD_MAINKEY_CREDITCARD]['year'];

            $expdat = $year . XROWRecurringordersCommonFunctions::addLeadingZero( $clientInfo[XROWCREDITCARD_MAINKEY_CREDITCARD]['month'] );
            
            $fields = array (
                    'creditc'       => $clientInfo[XROWCREDITCARD_MAINKEY_CREDITCARD]['number'],
                    'expdat'        => $expdat,
                    'orderid'       => $bookingstr,
                    'command'       => $command,
                    'currency'      => $currencyCode,
                    'amount'        => $amount
                );
        }

        $curl = new Net_Curl($serverLink);
        $curl->username     = $user;
        $curl->password     = $password;
        $curl->type         = 'post';
        $curl->fields       = $fields;
        $curl->mute         = true;
        $curl->timeout      = 30;

        $file = eZSys::rootDir() . '/' . eZExtension::baseDirectory() . '/xrowcomdirect/cacert.pem';
        if( !file_exists( $file ) )
            return false;

        $result = $curl->create();

        if ( !PEAR::isError( $result ) )
        {
            // Set other options here with Net_Curl::setOption()
            $curl->caInfo = $file;
            $curl->verbose = true;
            $curl->verifyPeer = true;
            $curl->verifyHost = 2;

            $result = $curl->execute();

            if (!PEAR::isError($result))
            {
                
                eZDebug::writeDebug ( $result, 'Payment server answer' );
                $resultArray = explode ( '&', $result );
                $serverAnswer = array();
                foreach ( $resultArray as $item )
                {
                   $dummy = explode ( '=', $item, 2 );
                   if ( count ( $dummy ) == 2 )
                    $serverAnswer[ urldecode ( $dummy[0] ) ] = urldecode ( $dummy[1] );
                }

                $this->data['servercode']     = $serverAnswer['rc'];
                $this->data['transactionid']  = $serverAnswer['trefnum'];
                $this->data['servermsg']      = $codepage->convertString ( $serverAnswer['rmsg'] );
                $this->_storeAccountHandlerData($process);

                if ( $serverAnswer['rc'] === '000')
                {
                   // payment is approved
                   eZDebug::writeDebug( 'Payment accepted: ' . $this->data['servermsg'], 'Payment' );
                   $order->modifyStatus (3);
                   $curl->close();
                   return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
                }
                else
                {
                    // payment isn't approved
                    eZDebug::writeDebug( 'Payment rejected, reason: ' . $this->data['servermsg'], 'Payment' );
                }
            }
            else
            {
                eZDebug::writeError( $result->getMessage(), 'curl error');
            }

            $curl->close();
        }
        else
        {
            eZDebug::writeError( $result->getMessage(), 'curl error');
        }
        return EZ_WORKFLOW_TYPE_STATUS_REJECTED;
    }

    function _storeAccountHandlerData( &$process )
    {
        $processParams = $process->attribute( 'parameter_list' );
        $order = eZOrder::fetch( $processParams['order_id'] );

        $data = $this->data;

        $doc = new eZDOMDocument( 'account_information' );
        $root = XROWRecurringordersCommonFunctions::createDOMTreefromArray( 'shop_account', $data );
        $doc->setRoot( $root );
        $order->setAttribute( 'data_text_1', $doc->toString() );
        $order->store();
    }

    function needCleanup()
    {
        return false;
    }

    function cleanup( &$process, &$event )
    {
    }

    /*!
        Creates short description of order. Usually this string is
        passed to payment site as describtion of payment.
    */
    function &createShortDescription( &$order, $maxDescLen )
    {

        $descText       = '';
        $productItems   = $order->productItems();

        foreach( $productItems as $item )
        {
            $descText .= $item['object_name'] . ',';
        }
        $descText   = rtrim( $descText, "," );

        $descLen    = strlen( $descText );
        if( ($maxDescLen > 0) && ($descLen > $maxDescLen) )
        {
            $descText = substr($descText, 0, $maxDescLen - 3) ;
            $descText .= '...';
        }

        return $descText;
    }

    var $logger;
    // shop account information
    var $data;


}

eZPaymentGatewayType::registerGateway( EZ_PAYMENT_GATEWAY_TYPE_XROWCOMDIRECT, "xrowcomdirectgateway", "Comdirect" );

?>