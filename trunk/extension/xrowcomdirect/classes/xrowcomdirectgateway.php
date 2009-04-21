<?php

class xrowComdirectGateway extends eZPaymentGateway
{
    const GATEWAY_STRING = 'xrowComdirect';
    const MAX_STRING_LEN = 27;

    function createArrayfromXML( $xmlDoc )
    {
        $result = array();
        $xml = new eZXML( );
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
    static function addLeadingZero( $value )
    {
        return sprintf("%02d", $value);
    }
    function execute( $process, $event )
    {
    	
        $http = eZHTTPTool::instance();
        $shopINI = eZINI::instance( 'shop.ini' );
        $xrowcomdirectINI = eZINI::instance( 'xrowcomdirect.ini' );
        
        $user = $xrowcomdirectINI->variable( 'ServerSettings', 'Username' );
        $password = $xrowcomdirectINI->variable( 'ServerSettings', 'Password' );
        $serverLink = $xrowcomdirectINI->variable( 'ServerSettings', 'ServerRequestLink' );
        $requestTimeout = $xrowcomdirectINI->variable( 'ServerSettings', 'RequestTimeout' );
        
        // make the order object
        $processParams = $process->attribute( 'parameter_list' );
        
        // get order id
        $order_id = $processParams['order_id'];
        
        $order = eZOrder::fetch( $processParams['order_id'] );
            // client data
        // get order information out of eZXML
        $xml = new eZXML( );
        $xmlDoc = $order->attribute( 'data_text_1' );
        $clientInfo = simplexml_load_string($xmlDoc);
        $this->data = $clientInfo[xrowECommerce::ACCOUNT_KEY_CREDITCARD];
        /*
    const ACCOUNT_KEY_ECNAME = 'ecname';
    const ACCOUNT_KEY_ACCOUNTNUMBER = 'accountnumber';
    const ACCOUNT_KEY_BANKCODE = 'bankcode';
    const ACCOUNT_KEY_TYPE = 'type';
    const ACCOUNT_KEY_NUMBER = 'number';
    const ACCOUNT_KEY_SECURITYCODE = 'securitycode';
    const ACCOUNT_KEY_MONTH = 'month';
    const ACCOUNT_KEY_YEAR = 'year';
    const ACCOUNT_KEY_CREDITCARD = 'creditcard';
* */
        if ( $http->hasPostVariable( 'validate' ) )
        {
        	
        	$this->data[xrowECommerce::ACCOUNT_KEY_NAME] = trim( $http->postVariable( 'name' ) );
            $this->data[xrowECommerce::ACCOUNT_KEY_TYPE] = strtolower( $http->postVariable( 'cardtype' ) );
            $this->data[xrowECommerce::ACCOUNT_KEY_NUMBER] = $http->postVariable( 'number' );
            $this->data[xrowECommerce::ACCOUNT_KEY_MONTH] = $http->postVariable( 'expirationmonth' );
            $this->data[xrowECommerce::ACCOUNT_KEY_YEAR] = $http->postVariable( 'expirationyear' );
            $this->data[xrowECommerce::ACCOUNT_KEY_SECURITYCODE] = $http->postVariable( 'securitycode' );
        }
        $errors = array();
        
        if( $this->data === null or ( $this->data and !xrowEPayment::validateCardData( $this->data, $errors ) ) )
        {
        	var_dump($this->data);
                $process->Template = array();
                $process->Template['templateName'] = 'design:workflow/comdirectgateway.tpl';
                $process->Template['templateVars'] = array( 
                    'event' => $event, 
                    'errors' => $errors
                );
                $process->Template['templateVars'] = array_merge( $process->Template['templateVars'], $this->data );
                return eZWorkflowType::STATUS_FETCH_TEMPLATE_REPEAT;
        }
        
        
        // get total order amount, including tax
        $amount = $order->attribute( 'total_inc_vat' );
        if ( $amount < 1 )
        {
            eZDebug::writeDebug( 'Will not accept tiny payments', 'xrowComdirectGateway::execute()' );
            return eZWorkflowType::STATUS_ACCEPTED;
        }
        // amount in cent
        $amount = floor( $amount * 100 );
        

        //allowed charset is latin-1
        $codepage = eZCodePage::instance( "iso-8859-1" );
        $comini = eZINI::instance( 'xrowcomdirect.ini' );
        // only EUR is supported
        $currencyCode = 'EUR';
        $command = 'authorization';
        $maxlen = self::MAX_STRING_LEN - strlen( $order->OrderNr ) - 1;
        $bookingstr = substr( $comini->variable( 'Settings', 'BookingString' ), 0, $maxlen ) . " " . $order->OrderNr;
        //alllowed a-zA-Z0-9._-
        $allowed = "/[^a-z0-9\\.\\-\\_]/i";
        $bookingstr = preg_replace( $allowed, "_", $bookingstr );
        if ( $clientInfo[xrowECommerce::ACCOUNT_KEY_TYPE] == xrowEPayment::EUROCARD )
        {
            $fields = array( 
                'amount' => $amount , 
                'cname' => $codepage->convertStringFromUTF8( $clientInfo[xrowECommerce::ACCOUNT_KEY_ECNAME] ) , 
                'bankcode' => $clientInfo[xrowECommerce::ACCOUNT_KEY_BANKCODE] , 
                'account' => $clientInfo[xrowECommerce::ACCOUNT_KEY_ACCOUNTNUMBER] , 
                'orderid' => $bookingstr , 
                'command' => $command , 
                'currency' => $currencyCode 
            );
        }
        else
        {
            $year = '';
            if ( strlen( $clientInfo['year'] ) == 4 )
            {
            	$year = substr( $clientInfo['year'], 2, 2 );
            }
            elseif ( strlen( $clientInfo['year'] ) == 2 )
            {
                $year = $clientInfo['year'];
            }
            $expdat = $year . xrowComdirectGateway::addLeadingZero( $clientInfo[XROWCREDITCARD_MAINKEY_CREDITCARD]['month'] );
            
            $fields = array( 
                'creditc' => $clientInfo[xrowECommerce::ACCOUNT_KEY_NUMBER] , 
                'expdat' => $expdat , 
                'orderid' => $bookingstr , 
                'command' => $command , 
                'currency' => $currencyCode , 
                'amount' => $amount 
            );
        }
        var_dump($fields);
        $options['timeout'] = 30;
        $options['httpauth'] = $user.':'.$password; 
        $request = new HttpRequest( $serverLink, HTTP_METH_POST, $options );
        #$curl = new Net_Curl( $serverLink );
        #$curl->username = $user;
        #$curl->password = $password;
        #$curl->type = 'post';
        #$curl->fields = $fields;
        #$curl->mute = true;
        #$curl->timeout = 30;
        $request->addPostFields( $fields ); 
        $file = eZSys::rootDir() . '/' . eZExtension::baseDirectory() . '/xrowcomdirect/cacert.pem';
        if ( ! file_exists( $file ) )
        {
        	throw new Exception( "Certificate file cacert.pem not found." );
        }
            
        
        #$result = $curl->create();
        $result = $request->send();
         
        if ( ! PEAR::isError( $result ) )
        {
            // Set other options here with Net_Curl::setOption()
            $curl->caInfo = $file;
            $curl->verbose = true;
            $curl->verifyPeer = true;
            $curl->verifyHost = 2;
            
            $result = $curl->execute();
            
            if ( ! PEAR::isError( $result ) )
            {
                
                eZDebug::writeDebug( $result, 'Payment server answer' );
                $resultArray = explode( '&', $result );
                $serverAnswer = array();
                foreach ( $resultArray as $item )
                {
                    $dummy = explode( '=', $item, 2 );
                    if ( count( $dummy ) == 2 )
                        $serverAnswer[urldecode( $dummy[0] )] = urldecode( $dummy[1] );
                }
                
                $this->data['servercode'] = $serverAnswer['rc'];
                $this->data['transactionid'] = $serverAnswer['trefnum'];
                $this->data['servermsg'] = $codepage->convertString( $serverAnswer['rmsg'] );
                $this->_storeAccountHandlerData( $process );
                
                if ( $serverAnswer['rc'] === '000' )
                {
                    // payment is approved
                    eZDebug::writeDebug( 'Payment accepted: ' . $this->data['servermsg'], 'Payment' );
                    $order->modifyStatus( 3 );
                    $curl->close();
                    $xmlstring = $order->attribute( 'data_text_1' );
                    if ( $xmlstring != null )
                    {
                        $doc = new DOMDocument( );
                        $doc->loadXML( $xmlstring );
                        $root = $doc->documentElement;
                        $invoice = $doc->createElement( xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD, self::GATEWAY_STRING );
                        $root->appendChild( $invoice );
                        $order->setAttribute( 'data_text_1', $doc->saveXML() );
                        $order->store();
                    }
                    return eZWorkflowType::STATUS_ACCEPTED;
                }
                else
                {
                    // payment isn't approved
                    eZDebug::writeDebug( 'Payment rejected, reason: ' . $this->data['servermsg'], 'Payment' );
                }
            }
            else
            {
                eZDebug::writeError( $result->getMessage(), 'curl error' );
            }
            
            $curl->close();
        }
        else
        {
            eZDebug::writeError( $result->getMessage(), 'curl error' );
        }
        return eZWorkflowType::STATUS_REJECTED;
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
    /*!
        Creates short description of order. Usually this string is
        passed to payment site as describtion of payment.
    */
    function createShortDescription( $order, $maxDescLen )
    {
        
        $descText = '';
        $productItems = $order->productItems();
        
        foreach ( $productItems as $item )
        {
            $descText .= $item['object_name'] . ',';
        }
        $descText = rtrim( $descText, "," );
        
        $descLen = strlen( $descText );
        if ( ( $maxDescLen > 0 ) && ( $descLen > $maxDescLen ) )
        {
            $descText = substr( $descText, 0, $maxDescLen - 3 );
            $descText .= '...';
        }
        
        return $descText;
    }
    
    var $logger;
    // shop account information
    var $data;

}

xrowEPayment::registerGateway( xrowComdirectGateway::GATEWAY_STRING, "xrowcomdirectgateway", ezi18n( 'kernel/classes/datatypes', 'Pay by credit card' ) );

?>