<?php

$http = eZHTTPTool::instance();
$module = $Params["Module"];

include_once ( 'kernel/common/template.php' );

$tpl = templateInit();

if ( $module->isCurrentAction( 'Cancel' ) )
{
    $module->redirectTo( 'shop/basket' );
    return;
}

$user = eZUser::currentUser();

$first_name = '';
$last_name = '';
$email = '';
// Initialize variables
$shippingtype = $shipping = $s_email = $s_last_name = $s_first_name = $s_address1 = $s_address2 = $s_zip = $s_city = $s_state = $s_country = $s_phone = $s_mi = $address1 = $address2 = $zip = $city = $state = $country = $phone = $recaptcha = $mi = '';
$userobject = $user->attribute( 'contentobject' );
$country = eZINI::instance( 'site.ini' )->variable( 'ShopAccountHandlerDefaults', 'DefaultCountryCode' );

if ( $user->isLoggedIn() and in_array( $userobject->attribute( 'class_identifier' ), eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'ShopUserClassList' ) ) )
{
    $userObject = $user->attribute( 'contentobject' );
    $userMap = $userObject->dataMap();
    if ( isset( $userMap['company_name'] ) )
    {
        $company_name = $userMap['company_name']->content();
    }
    if ( isset( $userMap['company_additional'] ) )
    {
        $company_additional = $userMap['company_additional']->content();
    }
    if ( isset( $userMap['tax_id'] ) )
    {
        $tax_id = $userMap['tax_id']->content();
    }
    if ( isset( $userMap['first_name'] ) )
    {
        $first_name = $userMap['first_name']->content();
    }
    if ( isset( $userMap['last_name'] ) )
    {
        $last_name = $userMap['last_name']->content();
    }
    if ( isset( $userMap['mi'] ) )
    {
        $mi = $userMap['mi']->content();
    }
    if ( isset( $userMap['address1'] ) )
    {
        $address1 = $userMap['address1']->content();
    }
    if ( isset( $userMap['address2'] ) )
    {
        $address2 = $userMap['address2']->content();
    }
    if ( isset( $userMap['state'] ) )
    {
        $state = $userMap['state']->content();
    }
    if ( isset( $userMap['zip'] ) )
    {
        $zip = $userMap['zip']->content();
    }
    if ( isset( $userMap['city'] ) )
    {
        $city = $userMap['city']->content();
    }
    if ( isset( $userMap['country'] ) )
    {
    	$country = $userMap['country']->content();
        $country = eZCountryType::fetchCountry( $country['value'], false );
        $country = $country['Alpha3'];
    }
    if ( isset( $userMap['phone'] ) )
    {
        $phone = $userMap['phone']->content();
    }
    if ( isset( $userMap['fax'] ) )
    {
        $fax = $userMap['fax']->content();
    }
    if ( isset( $userMap['shippingaddress'] ) )
    {
        $shipping = $userMap['shippingaddress']->content();
    }
    if ( isset( $userMap['shippingtype'] ) )
    {
        $shippingtype = $userMap['shippingtype']->content();
    }
    if ( array_key_exists( 'payment_method', $userMap ) )
    {
        $payment_method = $userMap['payment_method']->content();
    }
    $email = $user->attribute( 'email' );
    
    if ( $shipping != "1" )
    {
        if ( isset( $userMap['s_company_name'] ) )
        {
            $s_company_name = $userMap['s_company_name']->content();
        }
        if ( isset( $userMap['s_company_additional'] ) )
        {
            $s_company_additional = $userMap['s_company_additional']->content();
        }
        if ( isset( $userMap['s_first_name'] ) )
        {
            $s_first_name = $userMap['s_first_name']->content();
        }
        if ( isset( $userMap['s_last_name'] ) )
        {
            $s_last_name = $userMap['s_last_name']->content();
        }
        if ( isset( $userMap['s_mi'] ) )
        {
            $s_mi = $userMap['s_mi']->content();
        }
        if ( isset( $userMap['s_address1'] ) )
        {
            $s_address1 = $userMap['s_address1']->content();
        }
        if ( isset( $userMap['s_address2'] ) )
        {
            $s_address2 = $userMap['s_address2']->content();
        }
        if ( isset( $userMap['s_state'] ) )
        {
            $s_state = $userMap['s_state']->content();
        }
        if ( isset( $userMap['s_city'] ) )
        {
            $s_city = $userMap['s_city']->content();
        }
        if ( isset( $userMap['s_zip'] ) )
        {
            $s_zip = $userMap['s_zip']->content();
        }
        if ( isset( $userMap['s_country'] ) )
        {
            $s_country = $userMap['s_country']->content();
            $s_country = eZCountryType::fetchCountry( $s_country['value'], false );
            $s_country = $s_country['Alpha3'];
        }
        if ( isset( $userMap['s_phone'] ) )
        {
            $s_phone = $userMap['s_phone']->content();
        }
        if ( isset( $userMap['s_fax'] ) )
        {
            $s_fax = $userMap['s_fax']->content();
        }
        if ( isset( $userMap['s_email'] ) )
        {
            $s_email = $userMap['s_email']->content();
        }
    }
}
$orderID = $http->sessionVariable( 'MyTemporaryOrderID' );
$order = eZOrder::fetch( $orderID );
if ( $order instanceof eZOrder )
{
    if ( $order->attribute( 'is_temporary' ) )
    {
        
        $accountInfo = $order->accountInformation();
        foreach ( $accountInfo as $name => $value )
        {
            $$name = $value;
        }
    }
}
/*
// Check if user has an earlier order, copy order info from that one
$orderList = eZOrder::activeByUserID( $user->attribute( 'contentobject_id' ) );
if ( count( $orderList ) > 0 and $user->isLoggedIn() )
{
    $accountInfo = $orderList[0]->accountInformation();
}
*/

$tpl->setVariable( "input_error", false );
if ( $module->isCurrentAction( 'Store' ) )
{
    $inputIsValid = true;
    
    $company_name = $http->postVariable( "company_name" );
    
    $company_additional = $http->postVariable( "company_additional" );
    
    $first_name = $http->postVariable( "first_name" );
    if ( trim( $first_name ) == "" )
        $inputIsValid = false;
    
    $last_name = $http->postVariable( "last_name" );
    if ( trim( $last_name ) == "" )
        $inputIsValid = false;
    
    $mi = $http->postVariable( "mi" );
    if ( eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'MI' ) == 'enabled' )
    {
        if ( trim( $mi ) == "" )
            $inputIsValid = false;
    }
    $email = $http->postVariable( "email" );
    if ( empty( $email ) )
    {
        $errors[] = ezi18n( 'extension/xrowecommerce', "The email address isn't given." );
        $inputIsValid = false;
    }
    else
    {
        if ( ! eZMail::validate( $email ) )
        {
            $errors[] = ezi18n( 'extension/xrowecommerce', "The email address isn't valid." );
            $inputIsValid = false;
        }
    }
    $address1 = $http->postVariable( "address1" );
    $address2 = $http->postVariable( "address2" );
    if ( trim( $address1 ) == "" )
        $inputIsValid = false;
    
    $state = $http->postVariable( "state" );
    
    if ( eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'State' ) == 'enabled' )
    {
        if ( trim( $state ) == "" )
            $inputIsValid = false;
    }
    
    $city = $http->postVariable( "city" );
    if ( trim( $city ) == "" )
        $inputIsValid = false;
    
    $zip = $http->postVariable( "zip" );
    if ( trim( $zip ) == "" )
        $inputIsValid = false;
    
    $country = $http->postVariable( "country" );
    if ( trim( $country ) == "" )
    {
        $inputIsValid = false;
    }
    else
    {
        if ( eZINI::instance( 'xrowecommerce.ini' )->hasVariable( 'Settings', 'CountryWihtStatesList' ) and in_array( $country, eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'CountryWihtStatesList' ) ) and $state == '' )
        {
            $inputIsValid = false;
        }
    }
    if ( $http->hasPostVariable( "tax_id" ) )
    {
        $ezcountry = eZCountryType::fetchCountry( $country, 'Alpha3' );
        $Alpha2 = $ezcountry['Alpha2'];
        $ids = array( 
            "AT" , 
            "BE" , 
            "BG" , 
            "CY" , 
            "CZ" , 
            "DE" , 
            "DK" , 
            "EE" , 
            "EL" , 
            "ES" , 
            "FI" , 
            "FR" , 
            "GB" , 
            "HU" , 
            "IE" , 
            "IT" , 
            "LT" , 
            "LU" , 
            "LV" , 
            "MT" , 
            "NL" , 
            "PL" , 
            "PT" , 
            "RO" , 
            "SE" , 
            "SI" , 
            "SK" 
        );
        $tax_id = strtoupper( str_replace( " ", "", trim( $http->postVariable( "tax_id" ) ) ) );
        if ( empty( $tax_id ) and $company_name and in_array( $Alpha2, $ids ) )
        {
            $errors[] = ezi18n( 'extension/xrowecommerce', 'Please enter a your companies tax ID number.' );
            $inputIsValid = false;
        }
        if ( in_array( $Alpha2, $ids ) and $company_name )
        {
            $matches = array();
            if ( preg_match( "/^(" . join( '|', $ids ) . ")([0-9]+)/i", $tax_id, $matches ) )
            {
                if ( $Alpha2 != $matches[1] )
                {
                    $errors[] = ezi18n( 'extension/xrowecommerce', 'Country doesn`t match tax ID number.' );
                    $inputIsValid = false;
                }
                try
                {
                    $ret = xrowECommerce::checkVat( $ezcountry['Alpha2'], $matches[2] );
                    if ( ! $ret )
                    {
                        $errors[] = ezi18n( 'extension/xrowecommerce', 'Your companies tax ID number is not valid.' );
                        $inputIsValid = false;
                    }
                    else 
                    {
                    	$tax_id_valid = true;
                    }
                }
                catch ( Exception $e )
                {
                    eZDebug::writeError( $e->getMessage(), 'TAX ID Validation problem' );
                }
            }
            else
            {
                $errors[] = ezi18n( 'extension/xrowecommerce', 'Your companies tax ID number is not valid.' );
                $inputIsValid = false;
            }
        }
    }
    $phone = $http->postVariable( "phone" );
    if ( trim( $phone ) == "" )
        $inputIsValid = false;
    
    $fax = $http->postVariable( "fax" );
    if ( $http->hasPostVariable( "PaymentMethod" ) )
    {
        $payment_method = $http->postVariable( "PaymentMethod" );
    }
    
    if ( $http->hasPostVariable( "reference" ) )
    {
        $reference = $http->postVariable( "reference" );
    }
    if ( $http->hasPostVariable( "message" ) )
    {
        $message = $http->postVariable( "message" );
    }
    if ( $http->hasPostVariable( "no_partial_delivery" ) )
    {
        $no_partial_delivery = '1';
    }
    elseif ( ! $http->hasPostVariable( "no_partial_delivery" ) and eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'NoPartialDelivery' ) == 'enabled' )
    {
        $no_partial_delivery = '0';
    }
    if ( $http->hasPostVariable( "shipping" ) )
    {
        $shipping = true;
    }
    else
    {
        $shipping = false;
    }
    $shippingtype = $http->postVariable( "shippingtype" );
    $shippingdestination = $country;
    
    if ( $shipping != "1" )
    {
        $s_company_name = $http->postVariable( "s_companyname" );
        
        $s_company_additional = $http->postVariable( "s_companyadditional" );
        
        $s_first_name = $http->postVariable( "s_firstname" );
        if ( trim( $s_first_name ) == "" )
            $inputIsValid = false;
        $s_last_name = $http->postVariable( "s_lastname" );
        if ( trim( $s_last_name ) == "" )
            $inputIsValid = false;
        $s_mi = $http->postVariable( "s_mi" );
        
        $s_email = $http->postVariable( "s_email" );
        if ( empty( $s_email ) )
        {
            $errors[] = ezi18n( 'extension/xrowecommerce', "The email address isn't given." );
            $inputIsValid = false;
        }
        else
        {
            if ( ! eZMail::validate( $s_email ) )
            {
                $errors[] = ezi18n( 'extension/xrowecommerce', "The email address isn't valid." );
                $inputIsValid = false;
            }
        }
        $s_address1 = $http->postVariable( "s_address1" );
        $s_address2 = $http->postVariable( "s_address2" );
        if ( trim( $s_address1 ) == "" )
            $inputIsValid = false;
        
        $s_city = $http->postVariable( "s_city" );
        if ( trim( $s_city ) == "" )
            $inputIsValid = false;
        
        $s_zip = $http->postVariable( "s_zip" );
        if ( trim( $s_zip ) == "" )
            $inputIsValid = false;
        
        $s_state = $http->postVariable( "s_state" );
        
        $s_country = $http->postVariable( "s_country" );
        if ( trim( $s_country ) == "" )
        {
            $inputIsValid = false;
        }
        else
        {
            if ( in_array( $s_country, eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'CountryWithStatesList' ) ) and $s_state == '' )
            {
                $inputIsValid = false;
            }
        }
        
        $s_phone = $http->postVariable( "s_phone" );
        if ( trim( $s_phone ) == "" )
            $inputIsValid = false;
        
        $s_fax = $http->postVariable( "s_fax" );
        
        $shippingdestination = $s_country;
        /*
        if ($s_country !="USA" and $shippingtype <= "5" )
            $inputIsValid = false;
        
        if ($s_country =="USA" and $shippingtype >= "6" )
            $inputIsValid = false;
*/
    }
    
    /* Shipping check */
    if ( class_exists( 'xrowShippingInterface' ) )
    {
        $gateway = xrowShippingInterface::instanceByMethod( $shippingtype );
        if ( $gateway instanceof ShippingInterface )
        {
            $gateway->method = $shippingtype;
            if ( ! $gateway->destinationCheck( $shippingdestination ) )
            {
                $errors[] = ezi18n( 'extension/xrowecommerce', 'Shipping destionation is not allowed.' );
                $inputIsValid = false;
            }
        }
    }
    /* Coupon check */
    if ( class_exists( 'xrowCoupon' ) and eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'Coupon' ) == 'enabled' )
    {
        $coupon = new xrowCoupon( $http->postVariable( "coupon_code" ) );
        $coupon_code = $coupon->code;
    }
    $currentUser = eZUser::currentUser();
    $accessAllowed = $currentUser->hasAccessTo( 'xrowecommerce', 'bypass_captcha' );
    /* Captcha check */
    if ( class_exists( 'xrowVerification' ) and eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'Captcha' ) == 'enabled' and $accessAllowed["accessWord"] != 'yes' and empty( $_SESSION['xrowCaptchaSolved'] ) )
    {
        $captcha = true;
        $verification = new xrowVerification( );
        $answer = $verification->verify( $http );
        if ( $answer != true )
        {
            $captcha = false;
            $inputIsValid = false;
        }
        else
        {
            $_SESSION['xrowCaptchaSolved'] = 1;
        }
    }
    
    if ( $inputIsValid == true )
    {
        // Check for validation
        $basket = eZBasket::currentBasket();
        
        $db = eZDB::instance();
        $db->begin();
        $order = $basket->createOrder();
        
        $doc = new DOMDocument( '1.0', 'utf-8' );
        $root = $doc->createElement( 'shop_account' );
        $doc->appendChild( $root );
        $siteaccessNode = $doc->createElement( "siteaccess", $GLOBALS['eZCurrentAccess']['name'] );
        
        $root->appendChild( $siteaccessNode );
        
        $company_nameNode = $doc->createElement( "company_name", $company_name );
        $root->appendChild( $company_nameNode );
        
        $company_additionalNode = $doc->createElement( "company_additional", $company_additional );
        $root->appendChild( $company_additionalNode );
        
        $tax_idNode = $doc->createElement( "tax_id", $tax_id );
        $root->appendChild( $tax_idNode );
        if ( $tax_id and $tax_id_valid )
        {
        	$tax_idNode = $doc->createElement( "tax_id_valid", '1' );
            $root->appendChild( $tax_idNode );
        }
        elseif( $tax_id )
        {
        	$tax_idNode = $doc->createElement( "tax_id_valid", '0' );
            $root->appendChild( $tax_idNode );
        }
        $first_nameNode = $doc->createElement( "first_name", $first_name );
        $root->appendChild( $first_nameNode );
        
        $miNode = $doc->createElement( "mi", $mi );
        $root->appendChild( $miNode );
        
        $last_nameNode = $doc->createElement( "last_name" );
        $last_nameNode->appendChild( $doc->createTextNode( $last_name ) );
        $root->appendChild( $last_nameNode );
        
        $address1Node = $doc->createElement( "address1" );
        $address1Node->appendChild( $doc->createTextNode( $address1 ) );
        $root->appendChild( $address1Node );
        
        $address2Node = $doc->createElement( "address2" );
        $address2Node->appendChild( $doc->createTextNode( $address2 ) );
        $root->appendChild( $address2Node );
        
        $cityNode = $doc->createElement( "city", $city );
        $root->appendChild( $cityNode );
        
        $stateNode = $doc->createElement( "state", $state );
        $root->appendChild( $stateNode );
        
        $zipNode = $doc->createElement( "zip", $zip );
        $root->appendChild( $zipNode );
        
        $countryNode = $doc->createElement( "country", $country );
        $root->appendChild( $countryNode );
        
        $phoneNode = $doc->createElement( "phone", $phone );
        $root->appendChild( $phoneNode );
        
        $faxNode = $doc->createElement( "fax", $fax );
        $root->appendChild( $faxNode );
        
        $emailNode = $doc->createElement( "email", $email );
        $root->appendChild( $emailNode );
        
        $shippingNode = $doc->createElement( "shipping", $shipping );
        $root->appendChild( $shippingNode );
        
        $shippingTypeNode = $doc->createElement( "shippingtype", $shippingtype );
        $root->appendChild( $shippingTypeNode );
        
        $recaptacheNode = $doc->createElement( "captcha", $captcha );
        $root->appendChild( $recaptacheNode );
        if ( ! empty( $payment_method ) )
        {
            $payment_methodNode = $doc->createElement( xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD, $payment_method );
            $root->appendChild( $payment_methodNode );
        }
        if ( $coupon_code )
        {
            $coupon_codeNode = $doc->createElement( "coupon_code", $coupon_code );
            $root->appendChild( $coupon_codeNode );
        }
        else
        {
            $coupon_codeNode = $doc->createElement( "coupon_code", '' );
            $root->appendChild( $coupon_codeNode );
        }
        
        $referenceNode = $doc->createElement( "reference", $reference );
        $root->appendChild( $referenceNode );
        
        $messageNode = $doc->createElement( "message", $message );
        $root->appendChild( $messageNode );
        
        if ( $shipping != "1" )
        {
            /* Shipping address*/
            
            $s_company_nameNode = $doc->createElement( "s_company_name", $s_company_name );
            
            $root->appendChild( $s_company_nameNode );
            
            $s_company_additionalNode = $doc->createElement( "s_company_additional", $s_company_additional );
            $root->appendChild( $s_company_additionalNode );
            
            $s_first_nameNode = $doc->createElement( "s_first_name", $s_first_name );
            $root->appendChild( $s_first_nameNode );
            
            $s_miNode = $doc->createElement( "s_mi", $s_mi );
            $root->appendChild( $s_miNode );
            
            $s_last_nameNode = $doc->createElement( "s_last_name", $s_last_name );
            $root->appendChild( $s_last_nameNode );
            
            $s_address1Node = $doc->createElement( "s_address1", $s_address1 );
            $root->appendChild( $s_address1Node );
            
            $s_address2Node = $doc->createElement( "s_address2", $s_address2 );
            $root->appendChild( $s_address2Node );
            
            $s_cityNode = $doc->createElement( "s_city", $s_city );
            $root->appendChild( $s_cityNode );
            
            $s_stateNode = $doc->createElement( "s_state", $s_state );
            $root->appendChild( $s_stateNode );
            
            $s_zipNode = $doc->createElement( "s_zip", $s_zip );
            $root->appendChild( $s_zipNode );
            
            $s_countryNode = $doc->createElement( "s_country", $s_country );
            $root->appendChild( $s_countryNode );
            
            $s_phoneNode = $doc->createElement( "s_phone", $s_phone );
            $root->appendChild( $s_phoneNode );
            
            $s_faxNode = $doc->createElement( "s_fax" );
            $s_faxNode->appendChild( $doc->createTextNode( $s_fax ) );
            $root->appendChild( $s_faxNode );
            
            $s_emailNode = $doc->createElement( "s_email", $s_email );
            $root->appendChild( $s_emailNode );
            
        /* Shipping address*/
        } /* Shippingaddress is equal or not */
        
        $order->setAttribute( 'data_text_1', $doc->saveXML() );
        $shopAccountINI = eZINI::instance( 'shopaccount.ini' );
        
        $order->setAttribute( 'account_identifier', $shopAccountINI->variable( 'AccountSettings', 'Handler' ) );
        
        $order->setAttribute( 'ignore_vat', 0 );
        
        $order->store();
        $db->commit();
        
        $http->setSessionVariable( 'MyTemporaryOrderID', $order->attribute( 'id' ) );
        
        $module->redirectTo( '/shop/confirmorder/' );
        return;
    }
    else
    {
        $tpl->setVariable( "input_error", true );
    }
}

$tpl->setVariable( "company_name", $company_name );
$tpl->setVariable( "company_additional", $company_additional );
$tpl->setVariable( "tax_id", $tax_id );
$tpl->setVariable( "first_name", $first_name );
$tpl->setVariable( "mi", $mi );
$tpl->setVariable( "last_name", $last_name );
$tpl->setVariable( "email", $email );

$tpl->setVariable( "address1", $address1 );
$tpl->setVariable( "address2", $address2 );
$tpl->setVariable( "city", $city );
$tpl->setVariable( "state", $state );
$tpl->setVariable( "zip", $zip );
$tpl->setVariable( "country", $country );
$tpl->setVariable( "phone", $phone );
$tpl->setVariable( "fax", $fax );
$tpl->setVariable( "shipping", $shipping );

$tpl->setVariable( "shippingtype", $shippingtype );
if ( isset( $payment_method ) )
{
    $tpl->setVariable( "payment_method", $payment_method );

}
$tpl->setVariable( "recaptcha", $recaptcha );
$tpl->setVariable( "s_company_name", $s_company_name );
$tpl->setVariable( "s_company_additional", $s_company_additional );
$tpl->setVariable( "s_first_name", $s_first_name );
$tpl->setVariable( "s_mi", $s_mi );
$tpl->setVariable( "s_last_name", $s_last_name );
$tpl->setVariable( "s_email", $s_email );
$tpl->setVariable( "s_address1", $s_address1 );
$tpl->setVariable( "s_address2", $s_address2 );
$tpl->setVariable( "s_city", $s_city );
$tpl->setVariable( "s_state", $s_state );
$tpl->setVariable( "s_zip", $s_zip );
$tpl->setVariable( "s_country", $s_country );
$tpl->setVariable( "s_phone", $s_phone );
$tpl->setVariable( "s_fax", $s_fax );
$tpl->setVariable( "errors", $errors );
$tpl->setVariable( "coupon_code", $coupon_code );
$tpl->setVariable( "reference", $reference );
$tpl->setVariable( "message", $message );
$tpl->setVariable( "no_partial_delivery", $no_partial_delivery );
$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/userregister.tpl" );
$Result['path'] = array( 
    array( 
        'url' => false , 
        'text' => ezi18n( 'extension/xrowecommerce', 'Enter account information' ) 
    ) 
);
?>