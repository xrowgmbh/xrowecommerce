<?php

class xrowSagePayGateway extends xrowEPaymentGateway
{
    const GATEWAY_STRING = "xrowSagePay";
    const AUTOMATIC_STATUS = false;
    const TEMPLATE = 'design:workflow/sagepayform.tpl';

    function name()
    {
        return ezpI18n::tr( 'extension/xrowsagepay', 'Sage Pay' );
    }

    function execute( $process, $event )
    {
        $errors = array();
        $processParams = $process->attribute( 'parameter_list' );
        $processID = $process->attribute( 'id' );
        $orderID = $processParams['order_id'];
        $order = eZOrder::fetch( $orderID );

        if ( isset( $_GET['crypt'] ) )
        {
            $strDecoded = self::decodeAndDecrypt( $_GET['crypt'] );
            $values = self::getToken( $strDecoded );
            
            if ( isset( $values['Status'] ) and $values['Status'] === 'OK' and isset( $values['TxAuthNo'] ) and isset( $values['VPSTxId'] ) )
            {
                $xmlstring = $order->attribute( 'data_text_1' );
                if ( $xmlstring != null )
                {
                    $doc = new DOMDocument();
                    $doc->loadXML( $xmlstring );
                    $root = $doc->documentElement;
                    $invoice = $doc->createElement( xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD, xrowSagePayGateway::GATEWAY_STRING );
                    $root->appendChild( $invoice );
                    $tx = $doc->createElement( xrowECommerce::ACCOUNT_KEY_TRANSACTIONID, $values['VPSTxId'] );
                    $root->appendChild( $tx );
                    $order->setAttribute( 'data_text_1', $doc->saveXML() );
                    $order->store();
                }
                return eZWorkflowType::STATUS_ACCEPTED;
            }
            $errors[] = ezpI18n::tr( 'extension/xrowsagepay', "There was a problem with your Sage Pay transaction please try again." );
        }
        $spini = eZINI::instance( 'xrowsagepay.ini' );
        $ini = eZINI::instance( 'site.ini' );
        $shopini = eZINI::instance( 'shop.ini' );
        
        $vendor_email = $ini->variable( 'MailSettings', 'EmailSender' );
        $email_mode = $spini->variable( 'Settings', 'EmailSendingMode' );
        $vendorID = $spini->variable( 'Settings', 'VendorName' );
        
        if ( trim( $vendor_email ) == "" || is_null( $vendor_email ) )
            eZDebug::writeError( "AdminEmail not set in site.ini", __METHOD__ );
        if ( ! $vendorID || trim( $vendorID ) == "" )
            eZDebug::writeError( "Vendor ID not set in xrowsagepay.ini", __METHOD__ );
        if ( trim( $email_mode ) == "" || is_null( $email_mode ) )
        {
            $email_mode = "0";
            eZDebug::writeError( "EmailSendingMode not set in xrow4sagepay.ini", __METHOD__ );
        }

        #string to array for data_text_1 
        $xml = simplexml_load_string( $order->attribute( 'data_text_1' ) );
        $json = json_encode( $xml );
        $order_data = json_decode( $json, TRUE );
        $customerName = $order_data['first_name'] . " " . $order_data['mi'] . " " . $order_data['last_name'];
        $eMailMessage = "";
        if ( (bool) $order_data["shipping"] == true )
        {
            $first_name = $order_data['first_name'];
            $last_name = $order_data['last_name'];
            $address1 = $order_data["address1"];
            $address2 = $order_data["address2"];
            $city = $order_data["city"];
            $zip = $order_data["zip"];
            $country = $order_data["country"];
            $state = $order_data["state"];
            $phone = $order_data["phone"];
            $email = $order_data["email"];
            
            $s_first_name = $order_data['first_name'];
            $s_last_name = $order_data['last_name'];
            $s_address1 = $order_data["address1"];
            $s_address2 = $order_data["address2"];
            $s_city = $order_data["city"];
            $s_zip = $order_data["zip"];
            $s_country = $order_data["country"];
            $s_state = $order_data["state"];
            $s_phone = $order_data["phone"];
        }
        else
        {
            $first_name = $order_data['first_name'];
            $last_name = $order_data['last_name'];
            $address1 = $order_data["address1"];
            $address2 = $order_data["address2"];
            $city = $order_data["city"];
            $zip = $order_data["zip"];
            $country = $order_data["country"];
            $state = $order_data["state"];
            $phone = $order_data["phone"];
            $email = $order_data["email"];
            
            $s_first_name = $order_data['s_first_name'];
            $s_last_name = $order_data['s_last_name'];
            $s_address1 = $order_data["s_address1"];
            $s_address2 = $order_data["s_address2"];
            $s_city = $order_data["s_city"];
            $s_zip = $order_data["s_zip"];
            $s_country = $order_data["s_country"];
            $s_state = $order_data["s_state"];
            $s_phone = $order_data["s_phone"];
        }
        
        $tempateVars = array();
        $PaymentVars = array();
        $PaymentVars['VendorTxCode'] = $vendorID . "-" . date( "ymhis", time() ) . $orderID;
        $PaymentVars['Amount'] = $order->attribute( 'total_inc_vat' );
        $PaymentVars['Currency'] = $order->currencyCode();
        $PaymentVars['Description'] = ezpI18n::tr( 'extension/xrowsagepay', "Payment for shopping basket" ) . ' #' . $orderID;
        $PaymentVars['SuccessURL'] = eZSys::serverURL() . eZSys::indexDir() . "/xrowecommerce/checkout";
        $PaymentVars['FailureURL'] = eZSys::serverURL() . eZSys::indexDir() . "/xrowecommerce/checkout";
        $PaymentVars['CustomerName'] = $customerName;
        $PaymentVars['SendEMail'] = $email_mode;
        $PaymentVars['VendorEMail'] = $vendor_email;
        $PaymentVars['CustomerEMail'] = $email;
        $PaymentVars['eMailMessage'] = $eMailMessage;
        $PaymentVars['BillingFirstnames'] = $first_name;
        $PaymentVars['BillingSurname'] = $last_name;
        $PaymentVars['BillingAddress1'] = $address1;
        $PaymentVars['BillingAddress2'] = $address2;
        $PaymentVars['BillingCity'] = $city;
        $PaymentVars['BillingPostCode'] = $zip;
        $countryDef = xrowGeonames::getCountry( $country );
        $PaymentVars['BillingCountry'] = $countryDef['Alpha2'];
        #$PaymentVars['BillingState'] = $state;
        $PaymentVars['BillingPhone'] = $phone;
        $PaymentVars['DeliveryFirstnames'] = $s_first_name;
        $PaymentVars['DeliverySurname'] = $s_last_name;
        $PaymentVars['DeliveryAddress1'] = $s_address1;
        $PaymentVars['DeliveryAddress2'] = $s_address2;
        $PaymentVars['DeliveryCity'] = $s_city;
        $PaymentVars['DeliveryPostCode'] = $s_zip;
        $s_countryDef = xrowGeonames::getCountry( $s_country );
        $PaymentVars['DeliveryCountry'] = $s_countryDef['Alpha2'];
        #$PaymentVars['DeliveryState'] = $s_state;
        $PaymentVars['DeliveryPhone'] = $s_phone;
        
        $PaymentVars['AllowGiftAid'] = '0';
        $PaymentVars['ApplyAVSCV2'] = '0';
        $PaymentVars['Apply3DSecure'] = '0';

        // not needed for implementation
        #$PaymentVars['Basket'] = '2:Shaolin Soccer:1:8.47:1.48:9.95:9.95:Delivery:1:1.50:---:1.50:1.50';
        //Basket Syntax:
        //String start    &Basket=<total items count>
        //Item1          :<Quantity>:<Item Price excl. VAT>:<Item VAT>:<Item Price incl. VAT>:<Item Price incl. VAT * Quantity>
        //Item2          :<Quantity>:<Item Price excl. VAT>:<Item VAT>:<Item Price incl. VAT>:<Item Price incl. VAT * Quantity>
        

        $tempateVars['Crypt'] = '';
        foreach ( $PaymentVars as $key => $value )
        {
            $tempateVars['Crypt'] .= $key . '=' . $value . '&';
        }
        $tempateVars['Crypt'] = rtrim( $tempateVars['Crypt'], '&' );

        $tempateVars['Crypt'] = self::encryptAndEncode( $tempateVars['Crypt'] );
        
        $process->Template = array();
        $process->Template['templateName'] = self::TEMPLATE;
        $process->Template['templateVars'] = array( 
            'event' => $event , 
            'errors' => $errors 
        );
        
        $process->Template['templateVars'] = array_merge( $process->Template['templateVars'], $tempateVars );
        
        return eZWorkflowType::STATUS_FETCH_TEMPLATE_REPEAT;
    }

    function encryptAndEncode( $string )
    {
        $spini = eZINI::instance( 'xrowsagepay.ini' );
        
        $TransactionType = $spini->variable( 'Settings', 'TransactionType' );
        $EncryptionPassword = $spini->variable( 'Settings', 'EncryptionPassword' );
        
        $IV = $EncryptionPassword;
        $string = xrowSagePayGateway::addPKCS5Padding( $string );
        $cipher = mcrypt_module_open( 'rijndael-128', '', 'cbc', '' );
        mcrypt_generic_init( $cipher, $EncryptionPassword, $IV );
        $encrypted = mcrypt_generic( $cipher, $string );
        mcrypt_generic_deinit( $cipher );
        return "@" . bin2hex( $encrypted );
    }

    function decodeAndDecrypt( $string )
    {
        $spini = eZINI::instance( 'xrowsagepay.ini' );
        $EncryptionPassword = $spini->variable( 'Settings', 'EncryptionPassword' );
        
        $IV = $EncryptionPassword;
        $string = substr( $string, 1 );
        $string = pack( 'H*', $string );
        $cipher = mcrypt_module_open( 'rijndael-128', '', 'cbc', '' );
        mcrypt_generic_init( $cipher, $EncryptionPassword, $IV );
        $decrypted = mdecrypt_generic( $cipher, $string );
        mcrypt_generic_deinit( $cipher );
        return self::removePKCS5Padding( $decrypted );
    
    }

    function addPKCS5Padding( $string )
    {
        $blocksize = 16;
        $padding = "";
        $padlength = $blocksize - ( strlen( $string ) % $blocksize );
        for ( $i = 1; $i <= $padlength; $i ++ )
        {
            $padding .= chr( $padlength );
        }
        return $string . $padding;
    }

    function removePKCS5Padding( $string )
    {
        $padChar = ord( $string[strlen( $string ) - 1] );
        return substr( $string, 0, - $padChar );
    }

    /* The getToken function.                                                                                         **
** NOTE: A function of convenience that extracts the value from the "name=value&name2=value2..." reply string **
** Works even if one of the values is a URL containing the & or = signs.                                            */
    function getToken( $thisString )
    {
        
        // List the possible tokens
        $Tokens = array( 
            "Status" , 
            "StatusDetail" , 
            "VendorTxCode" , 
            "VPSTxId" , 
            "TxAuthNo" , 
            "Amount" , 
            "AVSCV2" , 
            "AddressResult" , 
            "PostCodeResult" , 
            "CV2Result" , 
            "GiftAid" , 
            "3DSecureStatus" , 
            "CAVV" , 
            "AddressStatus" , 
            "CardType" , 
            "Last4Digits" , 
            "PayerStatus" 
        );
        
        // Initialise arrays
        $output = array();
        $resultArray = array();
        
        // Get the next token in the sequence
        for ( $i = count( $Tokens ) - 1; $i >= 0; $i -- )
        {
            // Find the position in the string
            $start = strpos( $thisString, $Tokens[$i] );
            // If it's present
            if ( $start !== false )
            {
                $resultArray[$i] = new stdClass();
                // Record position and token name
                $resultArray[$i]->start = $start;
                $resultArray[$i]->token = $Tokens[$i];
            }
        }
        
        // Sort in order of position
        sort( $resultArray );
        // Go through the result array, getting the token values
        for ( $i = 0; $i < count( $resultArray ); $i ++ )
        {
            // Get the start point of the value
            $valueStart = $resultArray[$i]->start + strlen( $resultArray[$i]->token ) + 1;
            // Get the length of the value
            if ( $i == ( count( $resultArray ) - 1 ) )
            {
                $output[$resultArray[$i]->token] = substr( $thisString, $valueStart );
            }
            else
            {
                $valueLength = $resultArray[$i + 1]->start - $resultArray[$i]->start - strlen( $resultArray[$i]->token ) - 2;
                $output[$resultArray[$i]->token] = substr( $thisString, $valueStart, $valueLength );
            }
        
        }
        
        // Return the ouput array
        return $output;
    }

}
xrowEPayment::registerGateway( xrowSagePayGateway::GATEWAY_STRING, "xrowsagepaygateway" );
