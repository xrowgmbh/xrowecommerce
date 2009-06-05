<?php

class xrowComdirectBaseGateway extends xrowEPaymentGateway
{
    const MAX_STRING_LEN = 27;

    public function cancel( eZOrder $order )
    {
        // client data
        // get order information out of eZXML
        $xmlDoc = $order->attribute( 'data_text_1' );
        $clientInfo = simplexml_load_string( $xmlDoc );
        
        $fields['trefnum'] = (string) $clientInfo->{xrowECommerce::ACCOUNT_KEY_TRANSACTIONID};
        
        $fields['command'] = 'reversal';
        $serverAnswer = self::transaction( $fields );
        if ( $serverAnswer['rc'] === '000' )
        {
            return true;
        }
        else
        {
            return false;
        }
    
    }

    public function capture( eZOrder $order )
    {
        // client data
        // get order information out of eZXML
        $xmlDoc = $order->attribute( 'data_text_1' );
        $clientInfo = simplexml_load_string( $xmlDoc );
        
        $fields['trefnum'] = (string) $clientInfo->{xrowECommerce::ACCOUNT_KEY_TRANSACTIONID};
        // get total order amount, including tax
        $amount = $order->attribute( 'total_inc_vat' );
        if ( $amount < 1 )
        {
            throw new Exception( 'Will not accept tiny payments' );
        }
        // amount in cent
        $fields['amount'] = floor( $amount * 100 );
        $fields['command'] = 'capture';
        $serverAnswer = self::transaction( $fields );
        if ( $serverAnswer['rc'] === '000' )
        {
            return true;
        }
        else
        {
            return false;
        }
    
    }

    static function transaction( $fields )
    {
        $xrowcomdirectINI = eZINI::instance( 'xrowcomdirect.ini' );
        
        $user = $xrowcomdirectINI->variable( 'ServerSettings', 'Username' );
        $password = $xrowcomdirectINI->variable( 'ServerSettings', 'Password' );
        $serverLink = $xrowcomdirectINI->variable( 'ServerSettings', 'ServerRequestLink' );
        $requestTimeout = $xrowcomdirectINI->variable( 'ServerSettings', 'RequestTimeout' );
        
        $options['timeout'] = 30;
        $options['httpauth'] = $user . ':' . $password;
        $options['httpauthtype'] = HTTP_AUTH_BASIC;
        
        $request = new HttpRequest( $serverLink, HTTP_METH_POST, $options );
        $request->addPostFields( $fields );
        $file = eZSys::rootDir() . '/' . eZExtension::baseDirectory() . '/xrowcomdirect/cacert.pem';
        if ( ! file_exists( $file ) )
        {
            throw new Exception( "Certificate file cacert.pem not found." );
        }
        eZDebug::writeDebug( $fields, 'Payment server request' );
        $result = $request->send()->getBody();
        eZDebug::writeDebug( $result, 'Payment server answer' );
        $resultArray = explode( '&', $result );
        $serverAnswer = array();
        foreach ( $resultArray as $item )
        {
            $dummy = explode( '=', $item, 2 );
            if ( count( $dummy ) == 2 )
            {
                $serverAnswer[urldecode( $dummy[0] )] = urldecode( $dummy[1] );
            }
        }
        eZDebug::writeDebug( $serverAnswer, 'Payment server response' );
        return $serverAnswer;
    }

    function execute( $process, $event )
    {
        $http = eZHTTPTool::instance();
        $shopINI = eZINI::instance( 'shop.ini' );
        $xrowcomdirectINI = eZINI::instance( 'xrowcomdirect.ini' );
        
        // make the order object
        $processParams = $process->attribute( 'parameter_list' );
        
        // get order id
        $order_id = $processParams['order_id'];
        
        $order = eZOrder::fetch( $processParams['order_id'] );
        // client data
        // get order information out of eZXML
        $xmlDoc = $order->attribute( 'data_text_1' );
        $clientInfo = simplexml_load_string( $xmlDoc );
        $this->data = $clientInfo[xrowECommerce::ACCOUNT_KEY_CREDITCARD];
        
        if ( $http->hasPostVariable( 'validate' ) )
        {
            if ( $http->postVariable( 'cardtype' ) == xrowEPayment::EUROCARD )
            {
                $this->data[xrowECommerce::ACCOUNT_KEY_ECNAME] = trim( $http->postVariable( 'name' ) );
                $this->data[xrowECommerce::ACCOUNT_KEY_TYPE] = $http->postVariable( 'cardtype' );
                $this->data[xrowECommerce::ACCOUNT_KEY_ACCOUNTNUMBER] = $http->postVariable( 'number' );
                $this->data[xrowECommerce::ACCOUNT_KEY_BANKCODE] = $http->postVariable( 'bankcode' );
            }
            else
            {
                $this->data[xrowECommerce::ACCOUNT_KEY_NAME] = trim( $http->postVariable( 'name' ) );
                $this->data[xrowECommerce::ACCOUNT_KEY_TYPE] = $http->postVariable( 'cardtype' );
                $this->data[xrowECommerce::ACCOUNT_KEY_NUMBER] = $http->postVariable( 'number' );
                $this->data[xrowECommerce::ACCOUNT_KEY_MONTH] = $http->postVariable( 'expirationmonth' );
                $this->data[xrowECommerce::ACCOUNT_KEY_YEAR] = $http->postVariable( 'expirationyear' );
                $this->data[xrowECommerce::ACCOUNT_KEY_SECURITYCODE] = $http->postVariable( 'securitycode' );
            }
        }
        $errors = array();
        
        if ( $this->data === null or ( $this->data and ! xrowEPayment::validateCardData( $this->data, $errors ) ) )
        {
            $process->Template = array();
            $process->Template['templateName'] = constant( get_class( $this ) . '::TEMPLATE' );
            $process->Template['templateVars'] = array( 
                'event' => $event , 
                'errors' => $errors 
            );
            if ( $this->data )
            {
                $process->Template['templateVars'] = array_merge( $process->Template['templateVars'], $this->data );
            }
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
        if ( xrowEPayment::paymentRequestType() == xrowEPayment::PAYMENT_REQUEST_TYPE_AUTH_AND_CAPTURE )
        {
            $command = 'authorization';
        }
        else
        {
            $command = 'preauthorization';
        }
        
        if ( $order->OrderNr )
        {
            $transactionstring = $comini->variable( 'Settings', 'BookingString' );
            $number = $order->OrderNr;
        }
        else
        {
            $transactionstring = $comini->variable( 'Settings', 'TransactionString' );
            $number = $order->ID;
        }
        $number = $number . " " . time();
        $maxlen = self::MAX_STRING_LEN - strlen( $number ) - 1;
        $bookingstr = substr( $transactionstring, 0, $maxlen ) . " " . $number;
        //alllowed a-zA-Z0-9._-
        $allowed = "/[^a-z0-9\\.\\-\\_]/i";
        $bookingstr = preg_replace( $allowed, "_", $bookingstr );
        if ( $this->data[xrowECommerce::ACCOUNT_KEY_TYPE] == xrowEPayment::EUROCARD )
        {
            $fields = array( 
                'amount' => $amount , 
                'cname' => $codepage->convertStringFromUTF8( $this->data[xrowECommerce::ACCOUNT_KEY_ECNAME] ) , 
                'bankcode' => $this->data[xrowECommerce::ACCOUNT_KEY_BANKCODE] , 
                'account' => $this->data[xrowECommerce::ACCOUNT_KEY_ACCOUNTNUMBER] , 
                'orderid' => $bookingstr , 
                'command' => $command , 
                'currency' => $currencyCode 
            );
        }
        else
        {
            $year = '';
            if ( strlen( $this->data['year'] ) == 4 )
            {
                $year = substr( $this->data['year'], 2, 2 );
            }
            elseif ( strlen( $this->data['year'] ) == 2 )
            {
                $year = $this->data['year'];
            }
            $expdat = $year . self::addLeadingZero( $this->data['month'] );
            
            $fields = array( 
                'creditc' => $this->data[xrowECommerce::ACCOUNT_KEY_NUMBER] , 
                'expdat' => $expdat , 
                'orderid' => $bookingstr , 
                'command' => $command , 
                'currency' => $currencyCode , 
                'amount' => $amount 
            );
        }
        
        try
        {
            
            $serverAnswer = self::transaction( $fields );
            $this->data['servercode'] = $serverAnswer['rc'];
            $this->data['transactionid'] = $serverAnswer['trefnum'];
            $this->data['servermsg'] = $serverAnswer['rmsg'];
            
            $errors[] = $serverAnswer['rmsg'];
            # Umlaut character are converted. They shouldn`t since coposweb says they are latin one.
            #$errors[] = $codepage->convertString( $serverAnswer['rmsg'] );

            if ( $serverAnswer['rc'] === '000' )
            {
                // payment is approved
                eZDebug::writeDebug( 'Payment accepted: ' . $this->data['servermsg'], 'Payment' );
                if ( $command == 'authorization' and (int) $xrowcomdirectINI->variable( 'Settings', 'StatusID' ) )
                {
                    $order->modifyStatus( (int) $xrowcomdirectINI->variable( 'Settings', 'StatusID' ) );
                }
                
                $xmlstring = $order->attribute( 'data_text_1' );
                if ( $xmlstring != null )
                {
                    $doc = new DOMDocument( );
                    $doc->loadXML( $xmlstring );
                    $root = $doc->documentElement;
                    $invoice = $doc->createElement( xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD, constant( get_class( $this ) . '::GATEWAY_STRING' ) );
                    $root->appendChild( $invoice );
                    foreach ( $this->data as $key => $value )
                    {
                    	if( xrowEPayment::storePaymentInformation() )
                    	{
                            $node = $doc->createElement( $key, $value );
                            $root->appendChild( $node );
                    	}
                    	elseif ( in_array( $key, array( 'servercode', 'transactionid', 'servermsg' ) ) )
                    	{
                            $node = $doc->createElement( $key, $value );
                            $root->appendChild( $node );
                    	}
                    }
                    $order->setAttribute( 'data_text_1', $doc->saveXML() );
                    $order->store();
                }
                $payment = xrowPaymentObject::createNew( $process->ID, $order->ID, $bookingstr );
                $payment->store();
                if ( xrowEPayment::paymentRequestType() == xrowEPayment::PAYMENT_REQUEST_TYPE_AUTH_AND_CAPTURE )
                {
                    $payment->modifyStatus( xrowPaymentObject::STATUS_APPROVED );
                }
                return eZWorkflowType::STATUS_ACCEPTED;
            }
            else
            {
                $errors[] = $this->data['servermsg'];
                $process->Template = array();
                $process->Template['templateName'] = constant( get_class( $this ) . '::TEMPLATE' );
                $process->Template['templateVars'] = array( 
                    'event' => $event , 
                    'errors' => $errors 
                );
                if ( $this->data )
                {
                    $process->Template['templateVars'] = array_merge( $process->Template['templateVars'], $this->data );
                }
                return eZWorkflowType::STATUS_FETCH_TEMPLATE_REPEAT;
            }
        }
        catch ( HttpException $e )
        {
            eZDebug::writeError( $e->getMessage(), 'error' );
        }
        
        return eZWorkflowType::STATUS_REJECTED;
    }

    /*!
        Creates new xrowcomdirect payment object.
    */
    function &createPaymentObject( &$processID, &$orderID )
    {
        return eZPaymentObject::createNew( $processID, $orderID, self::GATEWAY_STRING );
    }

    static function addLeadingZero( $value )
    {
        return sprintf( "%02d", $value );
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

?>