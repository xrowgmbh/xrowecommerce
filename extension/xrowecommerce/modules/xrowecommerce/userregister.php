<?php

$http = eZHTTPTool::instance();
$module = $Params["Module"];

include_once ( 'kernel/common/template.php' );

$tpl = templateInit();

if ( $module->isCurrentAction( 'Cancel' ) )
{
    $module->redirectTo( '/shop/basket/' );
    return;
}

$user = eZUser::currentUser();

$firstName = '';
$lastName = '';
$email = '';
// Initialize variables
$shippingtype = $shipping = $s_email = $s_lastName = $s_firstName = $s_address1 = $s_address2 = $s_zip = $s_city = $s_state = $s_country = $s_phone = $s_mi = $address1 = $address2 = $zip = $city = $state = $country = $phone = $recaptcha = $mi = '';
$userobject = $user->attribute( 'contentobject' );

if ( $user->isLoggedIn() and in_array( $userobject->attribute( 'class_identifier' ), eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'ShopUserClassList' ) ) )
{
    $userObject = $user->attribute( 'contentobject' );
    $userMap = $userObject->dataMap();
    if ( isset( $userMap['company_name'] ) )
    {
        $companyName = $userMap['company_name']->content();
    }
    if ( isset( $userMap['company_additional'] ) )
    {
        $companyAdditional = $userMap['company_additional']->content();
    }
    if ( isset( $userMap['tax_id'] ) )
    {
        $taxID = $userMap['tax_id']->content();
    }
    if ( isset( $userMap['first_name'] ) )
    {
        $firstName = $userMap['first_name']->content();
    }
    if ( isset( $userMap['last_name'] ) )
    {
        $lastName = $userMap['last_name']->content();
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
    if ( isset( $userMap['zip_code'] ) )
    {
    	$zip = $userMap['zip_code']->content();
    }
    if ( isset( $userMap['city'] ) )
    {
        $city = $userMap['city']->content();
    }
    if ( isset( $userMap['country'] ) )
    {
    	$country = $userMap['country']->attribute( 'data_text' );
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
        $paymentMethod = $userMap['payment_method']->content();
    }
    $email = $user->attribute( 'email' );
    
    if ( $shipping != "1" )
    {
        if ( isset( $userMap['s_company_name'] ) )
        {
            $s_companyName = $userMap['s_company_name']->content();
        }
        if ( isset( $userMap['s_company_additional'] ) )
        {
            $s_companyAdditional = $userMap['s_company_additional']->content();
        }
        if ( isset( $userMap['s_first_name'] ) )
        {
            $s_firstName = $userMap['s_first_name']->content();
        }
        if ( isset( $userMap['s_last_name'] ) )
        {
        	$s_lastName = $userMap['s_last_name']->content();
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
        if ( isset( $userMap['s_zip_code'] ) )
        {
            $s_zip = $userMap['s_zip_code']->content();
        }
        if ( isset( $userMap['s_country'] ) )
        {
        	$s_country = $userMap['s_country']->attribute( 'data_text' );
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

// Check if user has an earlier order, copy order info from that one
$orderList = eZOrder::activeByUserID( $user->attribute( 'contentobject_id' ) );
if ( count( $orderList ) > 0 and $user->isLoggedIn() )
{
    $accountInfo = $orderList[0]->accountInformation();
}

$tpl->setVariable( "input_error", false );
if ( $module->isCurrentAction( 'Store' ) )
{
    $inputIsValid = true;
    
    $companyName = $http->postVariable( "companyname" );
    
    $companyAdditional = $http->postVariable( "companyadditional" );
    
    $taxID = $http->postVariable( "TaxID" );
    
    $firstName = $http->postVariable( "FirstName" );
    if ( trim( $firstName ) == "" )
        $inputIsValid = false;
    
    $lastName = $http->postVariable( "LastName" );
    if ( trim( $lastName ) == "" )
        $inputIsValid = false;
     
    $mi = $http->postVariable( "MI" );
    if ( eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'MI' ) == 'enabled' )
    {
        if ( trim( $mi ) == "" )
            $inputIsValid = false;
    }
    $email = $http->postVariable( "EMail" );
    if ( ! eZMail::validate( $email ) )
        $inputIsValid = false;
    
    $address1 = $http->postVariable( "Address1" );
    $address2 = $http->postVariable( "Address2" );
    if ( trim( $address1 ) == "" )
        $inputIsValid = false;
    
    $state = $http->postVariable( "State" );

    if ( eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'State' ) == 'enabled' )
    {
        if ( trim( $state ) == "" )
            $inputIsValid = false;
    }

    $city = $http->postVariable( "City" );
    if ( trim( $city ) == "" )
        $inputIsValid = false;
    
    $zip = $http->postVariable( "Zip" );
    if ( trim( $zip ) == "" )
        $inputIsValid = false;
    
    $country = $http->postVariable( "Country" );
    if ( trim( $country ) == "" )
    {
        $inputIsValid = false;
    }
    else
    {
        if ( in_array( $country, eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'CountryWihtStatesList' ) ) 
             and $state == '' )
    	{
            $inputIsValid = false;
        }
    }
    
    $phone = $http->postVariable( "Phone" );
    if ( trim( $phone ) == "" )
        $inputIsValid = false;
    
    $fax = $http->postVariable( "Fax" );
    if ( $http->hasPostVariable( "PaymentMethod" ) )
    {
        $paymentMethod = $http->postVariable( "PaymentMethod" );
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
    elseif ( !$http->hasPostVariable( "no_partial_delivery" ) and eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'NoPartialDelivery' ) == 'enabled' )
    {
    	$no_partial_delivery = '0';
    }
    
    $shipping = $http->postVariable( "Shipping" );
    $shippingtype = $http->postVariable( "ShippingType" );
    $shippingdestination = $country;

    if ($shipping != "1")
    {
        $s_companyName = $http->postVariable( "s_CompanyName" );
        
        $s_companyAdditional = $http->postVariable( "s_CompanyAdditional" );
   
        $s_firstName = $http->postVariable( "s_FirstName" );
        if ( trim( $s_firstName ) == "" )
            $inputIsValid = false;
        $s_lastName = $http->postVariable( "s_LastName" );
        if ( trim( $s_lastName ) == "" )
            $inputIsValid = false;
        $s_mi = $http->postVariable( "s_MI" );

        $s_email = $http->postVariable( "s_EMail" );
        if ( ! eZMail::validate( $s_email ) )
            $inputIsValid = false;

        $s_address1 = $http->postVariable( "s_Address1" );
        $s_address2 = $http->postVariable( "s_Address2" );
        if ( trim( $s_address1 ) == "" )
            $inputIsValid = false;


        $s_city = $http->postVariable( "s_City" );
        if ( trim( $s_city ) == "" )
            $inputIsValid = false;

        $s_zip = $http->postVariable( "s_Zip" );
        if ( trim( $s_zip ) == "" )
            $inputIsValid = false;
        
        $s_state = $http->postVariable( "s_State" );

        $s_country = $http->postVariable( "s_Country" );
        if ( trim( $s_country ) == "" )
        {
            $inputIsValid = false;
        }
        else
        {
            if ( in_array( $s_country, eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'CountryWithStatesList' ) ) 
                 and $s_state == '' )
            {
                $inputIsValid = false;
            }
        }

        $s_phone = $http->postVariable( "s_Phone" );
        if ( trim( $s_phone ) == "" )
            $inputIsValid = false;

        $s_fax = $http->postVariable( "s_Fax" );
        
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
    	$gateway  = xrowShippingInterface::instanceByMethod( $shippingtype );
    	if ( $gateway instanceof ShippingInterface )
    	{
        	$gateway->method = $shippingtype;
            if ( !$gateway->destinationCheck( $shippingdestination ) )
            {
            	$errors[] = "Shipping destionation is not allowed.";
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
    if ( class_exists( 'xrowVerification' ) and eZINI::instance( 'xrowecommerce.ini' )->variable('Settings', 'Captcha' ) == 'enabled' and $accessAllowed["accessWord"] != 'yes' and empty($_SESSION['xrowCaptchaSolved'] ))
    {
    	$captcha = true;
    	$verification = new xrowVerification();
    	$answer = $verification->verify( $http );
    	if( $answer != true )
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
        
        $companyNameNode = $doc->createElement( "company_name", $companyName );
        $root->appendChild( $companyNameNode );
        
        $companyAdditionalNode = $doc->createElement( "company_additional", $companyAdditional );
        $root->appendChild( $companyAdditionalNode );
        
        $taxIdNode = $doc->createElement( "tax_id", $taxID );
        $root->appendChild( $taxIdNode );
        
        $firstNameNode = $doc->createElement( "first-name", $firstName );
        $root->appendChild( $firstNameNode );
        
        $miNode = $doc->createElement( "mi", $mi );
        $root->appendChild( $miNode );
        
        $lastNameNode = $doc->createElement( "last-name" );
        $lastNameNode->appendChild( $doc->createTextNode( $lastName ) );
        $root->appendChild( $lastNameNode );
        
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
        
        $paymentMethodNode = $doc->createElement( xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD, $paymentMethod );
        $root->appendChild( $paymentMethodNode );

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
            
            $s_companyNameNode = $doc->createElement( "scompanyname", $s_companyName );

            $root->appendChild( $s_companyNameNode );
            
            $s_companyAdditionalNode = $doc->createElement( "scompanyadditional", $s_companyAdditional );
            $root->appendChild( $s_companyAdditionalNode );
            
            $s_firstNameNode = $doc->createElement( "s_first-name", $s_firstName );
            $root->appendChild( $s_firstNameNode );
            
            $s_miNode = $doc->createElement( "s_mi", $s_mi );
            $root->appendChild( $s_miNode );
            
            $s_lastNameNode = $doc->createElement( "s_last-name", $s_lastName );
            $root->appendChild( $s_lastNameNode );
            
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

$tpl->setVariable( "company_name", $companyName );
$tpl->setVariable( "company_additional", $companyAdditional );
$tpl->setVariable( "tax_id", $taxID );
$tpl->setVariable( "first_name", $firstName );
$tpl->setVariable( "mi", $mi );
$tpl->setVariable( "last_name", $lastName );
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
$tpl->setVariable( "payment_method", $paymentMethod );
$tpl->setVariable( "recaptcha", $recaptcha );

$tpl->setVariable( "s_company_name", $s_companyName );
$tpl->setVariable( "s_company_additional", $s_companyAdditional );
$tpl->setVariable( "s_first_name", $s_firstName );
$tpl->setVariable( "s_mi", $s_mi );
$tpl->setVariable( "s_last_name", $s_lastName );
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
        'text' => ezi18n( 'kernel/shop', 'Enter account information' ) 
    ) 
);
?>
