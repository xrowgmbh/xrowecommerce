<?php

class xrowSagePayGateway extends xrowEPaymentGateway
{
    const GATEWAY_STRING = "xrowSagePay";
    const AUTOMATIC_STATUS = false;

    function name()
    {
        return ezpI18n::tr( 'kernel/classes/datatypes', 'Sage Pay' );
    }

    function execute( $process, $event )
    {
        $spini = eZINI::instance( 'xrowsagepay.ini' );
        $ini = eZINI::instance( 'site.ini' );
        $shopini = eZINI::instance( 'shop.ini' );
        
        $vendor_email = $ini->variable( 'MailSettings', 'EmailSender' );
        $currency = $shopini->variable( 'CurrencySettings', 'PreferredCurrency' );
        $email_mode = $spini->variable( 'Settings', 'EmailSendingMode' );
        $vendorID = $spini->variable( 'Account', 'VendorName' );
        
        if ( trim( $currency ) == "" || is_null( $currency ) )
            eZDebug::writeError( "PreferredCurrency not set in shop.ini", __METHOD__ );
        if ( trim( $vendor_email ) == "" || is_null( $vendor_email ) )
            eZDebug::writeError( "AdminEmail not set in site.ini", __METHOD__ );
        if ( ! $vendorID || trim( $vendorID ) == "" )
            eZDebug::writeError( "Vendor ID not set in xrowsagepay.ini", __METHOD__ );
        if ( trim( $email_mode ) == "" || is_null( $email_mode ) )
        {
            $email_mode = "0";
            eZDebug::writeError( "EmailSendingMode not set in xrow4sagepay.ini", __METHOD__ );
        }
        
        $processParams = $process->attribute( 'parameter_list' );
        $processID = $process->attribute( 'id' );
        $orderID = $processParams['order_id'];
        $order = eZOrder::fetch( $orderID );
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
        
        $postString = "VendorTxCode=" . $vendorID . "-" . date( "ymdHis", time() ) . "-" . $orderID . " .
                       &Amount=11.45
                       &Currency=$currency
                       &Description=
                       &SuccessURL=http://sage.example.com/SagePayFormKit/orderSuccessful.php
                       &FailureURL=http://sage.example.com/SagePayFormKit/orderFailed.php
                       &CustomerName=$customerName
                       &SendEMail=$email_mode
                       &VendorEMail=$vendor_email
                       &CustomerEMail=$email
                       &eMailMessage=$eMailMessage
                       
                       &BillingFirstnames=$first_name
                       &BillingSurname=$last_name
                       &BillingAddress1=$address1
                       &BillingAddress2=$address2
                       &BillingCity=$city
                       &BillingPostCode=$zip
                       &BillingCountry=$country
                       &BillingState=$state
                       &BillingPhone=$phone
                       
                       &DeliveryFirstnames=$s_first_name
                       &DeliverySurname=$s_last_name
                       &DeliveryAddress1=$s_address1
                       &DeliveryAddress2=$s_address2
                       &DeliveryCity=$s_city
                       &DeliveryPostCode=$s_zip
                       &DeliveryCountry=$s_country
                       &DeliveryState=$s_state
                       &DeliveryPhone=$s_phone
                       &Basket=2:Shaolin Soccer:1:8.47:1.48:9.95:9.95:Delivery:1:1.50:---:1.50:1.50
                       &AllowGiftAid=0
                       &ApplyAVSCV2=0
                       &Apply3DSecure=0";
                       //Basket Syntax:
                       //String start    &Basket=<total items count>
                       //Item1          :<Quantity>:<Item Price excl. VAT>:<Item VAT>:<Item Price incl. VAT>:<Item Price incl. VAT * Quantity>
                       //Item2          :<Quantity>:<Item Price excl. VAT>:<Item VAT>:<Item Price incl. VAT>:<Item Price incl. VAT * Quantity>
        
        $xmlstring = $order->attribute( 'data_text_1' );
        if ( $xmlstring != null )
        {
            $doc = new DOMDocument();
            $doc->loadXML( $xmlstring );
            $root = $doc->documentElement;
            $invoice = $doc->createElement( xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD, xrowSagePayGateway::GATEWAY_STRING );
            $root->appendChild( $invoice );
            $order->setAttribute( 'data_text_1', $doc->saveXML() );
            $order->store();
        }

        return eZWorkflowType::STATUS_ACCEPTED;
    }

    function encryptAndEncode( $string )
    {
        $spini = eZINI::instance( 'xrowsagepay.ini' );
        
        $TransactionType = $spini->variable( 'Settings', 'TransactionType' );
        $EncryptionPassword = $spini->variable( 'Settings', 'EncryptionPassword' );
        
        $IV = $EncryptionPassword;
        $string = xrowSagePayGateway::addPKCS5Padding( $string );
        $cipher = mcrypt_module_open( MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '' );
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
        $cipher = mcrypt_module_open( MCRYPT_RIJNDAEL_128, '', 'MCRYPT_MODE_CBC', '' );
        mcrypt_generic_init( $cipher, $EncryptionPassword, $IV );
        $decrypted = mdecrypt_generic( $cipher, $string );
        mcrypt_generic_deinit( $cipher );
        return removePKCS5Padding( $decrypted );

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
}
xrowEPayment::registerGateway( xrowSagePayGateway::GATEWAY_STRING, "xrowsagepaygateway" );
