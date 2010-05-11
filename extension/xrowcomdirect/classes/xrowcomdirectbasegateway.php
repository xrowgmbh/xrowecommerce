<?php

class xrowComdirectBaseGateway extends xrowEPaymentGateway
{
    const MAX_STRING_LEN = 27;

    static function getErrorText( $id )
    {
        $list = self::getErrorList();
        if ( array_key_exists( $id, $list ) and (int)$id > 0 )
        {
            return $list[$id];
        }
        else
        {
            return false;
        }
    }

    static function getErrorList()
    {
        return array( 
            '005' => ezi18n( 'extension/xrowcomdirect/errors', 'The Credit card was not approved.' ) , 
            '033' => ezi18n( 'extension/xrowcomdirect/errors', 'The expiry date is invalid.' ) , 
            '090' => ezi18n( 'extension/xrowcomdirect/errors', 'The card issuer is temporarily not reachable.' ) , 
            '091' => ezi18n( 'extension/xrowcomdirect/errors', 'The card issuer is temporarily not reachable.' ) , 
            '096' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present.' ) , 
            '100' => ezi18n( 'extension/xrowcomdirect/errors', 'The transaction is not completed successfully.' ) , 
            '2014' => ezi18n( 'extension/xrowcomdirect/errors', 'The card number is incorrect.' ) , 
            '2016' => ezi18n( 'extension/xrowcomdirect/errors', 'The expiry date is incorrect.' ) , 
            '2018' => ezi18n( 'extension/xrowcomdirect/errors', 'The security code is incorrect.' ) , 
            '2040' => ezi18n( 'extension/xrowcomdirect/errors', 'The first part or length of the card number is incorrect.' ) , 
            '2042' => ezi18n( 'extension/xrowcomdirect/errors', 'The check sum of the card number is incorrect.' ) , 
            '2048' => ezi18n( 'extension/xrowcomdirect/errors', 'The card has expired.' ) , 
            '2090' => ezi18n( 'extension/xrowcomdirect/errors', 'The bank code is incorrect.' ) , 
            '2092' => ezi18n( 'extension/xrowcomdirect/errors', 'The account number is incorrect.' ) , 
            '2094' => ezi18n( 'extension/xrowcomdirect/errors', 'The name of account holder is incorrect.' ) , 
            '2202' => ezi18n( 'extension/xrowcomdirect/errors', 'The bank code is unknown.' ) , 
            '2204' => ezi18n( 'extension/xrowcomdirect/errors', 'The account number does not correspond to the bank code.' ) 
        );
    }

    static function getAdditionalErrorText( $id )
    {
        $list = self::getAdditionalErrorList();
        if ( array_key_exists( $id, $list ) and (int)$id > 0 )
        {
            return $list[$id];
        }
        else
        {
            return false;
        }
    }

    static function getAdditionalErrorList()
    {
        return array( 
            '006' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '009' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '090' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '091' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '096' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '8001' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '8003' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '8005' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '8007' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '8009' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '8011' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '8031' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '8033' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '9001' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '9901' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) , 
            '9903' => ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Try again later or select a different method of payment.' ) 
        );
    }

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
            throw new xrowPaymentErrorException( "Error #" . $serverAnswer['posherr'] . ": " . $serverAnswer['rmsg'] );        
        }
    
    }

    public function capture( eZOrder $order )
    {
        // client data
        // get order information out of XML
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
            throw new xrowPaymentErrorException( "Error #" . $serverAnswer['posherr'] . ": " . $serverAnswer['rmsg'] );
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
        
        try
        {
            $result = $request->send()->getBody();
        }
        catch ( Exception $e )
        {
        	eZDebug::writeError( $e->getMessage(), 'Payment server request error' );
            throw new xrowPaymentErrorException( "Payment gateway not available" );
        }
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
                $this->data[xrowECommerce::ACCOUNT_KEY_ACCOUNTNUMBER] = preg_replace( '/\s/', '', $http->postVariable( 'number' ) );
                $this->data[xrowECommerce::ACCOUNT_KEY_BANKCODE] = preg_replace( '/\s/', '', $http->postVariable( 'bankcode' ) );
            }
            else
            {
                $this->data[xrowECommerce::ACCOUNT_KEY_NAME] = trim( $http->postVariable( 'name' ) );
                $this->data[xrowECommerce::ACCOUNT_KEY_TYPE] = $http->postVariable( 'cardtype' );
                $this->data[xrowECommerce::ACCOUNT_KEY_NUMBER] = preg_replace( '/\s/', '', $http->postVariable( 'number' ) );
                $this->data[xrowECommerce::ACCOUNT_KEY_MONTH] = $http->postVariable( 'expirationmonth' );
                $this->data[xrowECommerce::ACCOUNT_KEY_YEAR] = $http->postVariable( 'expirationyear' );
                $this->data[xrowECommerce::ACCOUNT_KEY_SECURITYCODE] = preg_replace( '/\s/', '', $http->postVariable( 'securitycode' ) );
            }
        }
        $errors = array();
        
        if ( $this->data === null or ( $this->data and ! xrowEPayment::validateCardData( $this->data, $errors ) ) )
        {
            $process->Template = array();
            $process->Template['templateName'] = constant( get_class( $this ) . '::TEMPLATE' );
            $process->Template['path'] = array( array( 'url' => false, 'text' => ezi18n( 'extension/xrowcomdirect', 'Payment Information' ) ) );
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
                'amount' => $amount ,
                'cvcode' => $this->data[xrowECommerce::ACCOUNT_KEY_SECURITYCODE]
            );
        }
        
        try
        {
            
            $serverAnswer = self::transaction( $fields );

            if ( array_key_exists( 'rc', $serverAnswer ) and $serverAnswer['rc'] === '000' )
            {
                $this->data['transactionid'] = $serverAnswer['trefnum'];
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
                    	if ( $key == xrowECommerce::ACCOUNT_KEY_SECURITYCODE )
                    	{
                    		continue;
                    	}
                    	$obscure = array( xrowECommerce::ACCOUNT_KEY_ACCOUNTNUMBER, xrowECommerce::ACCOUNT_KEY_NUMBER );
                    	if ( in_array( $key, $obscure ) )
                    	{
                    		$value = xrowEPayment::obscureString( $value );
                    	}

                        if ( xrowEPayment::storePaymentInformation() )
                        {
                            $node = $doc->createElement( $key, $value );
                            $root->appendChild( $node );
                        }
                        elseif ( in_array( $key, array( 
                            'servercode' , 
                            'transactionid' , 
                            'servermsg' 
                        ) ) )
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
				if( array_key_exists( 'rc', $serverAnswer ) && (int)$serverAnswer['rc'] < 100 )
            	{
            		$errornumber = $serverAnswer['rc']; 
            	}
            	else
            	{
            		$errornumber = $serverAnswer['posherr'];
            	}
                if ( isset( $errornumber ) and self::getErrorText( $errornumber ) )
                {
                    $this->data['servermsg'] = self::getErrorText( $errornumber );
                }
                elseif ( isset( $errornumber ) and self::getAdditionalErrorText( $errornumber ) === false )
                {
                    $this->data['servermsg'] = ezi18n( 'extension/xrowcomdirect/errors', 'Not able to process at present. Select a different method of payment.' );
                }
                # Umlaut character are converted. They shouldn`t since coposweb says they are latin one.
                #$errors[] = $codepage->convertString( $serverAnswer['rmsg'] );
                if ( isset( $this->data['servermsg'] ) )
                {
                	$errors[] = $this->data['servermsg'];
                }

                if ( isset( $errornumber ) and self::getAdditionalErrorText( $errornumber ) )
                {
                    $errors[] = self::getAdditionalErrorText( $errornumber );
                }

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
