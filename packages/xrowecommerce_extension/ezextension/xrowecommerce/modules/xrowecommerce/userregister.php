<?php

$http = eZHTTPTool::instance();
$module = $Params['Module'];

$tpl = eZTemplate::factory();

if ( $module->isCurrentAction( 'Cancel' ) )
{
    $module->redirectTo( 'shop/basket' );
    return;
}

$user = eZUser::currentUser();

// Initialize variables
$email = $first_name = $last_name = $shippingtype = $shipping = $s_email = $s_last_name = $s_first_name = $s_address1 = $s_address2 = $s_zip = $s_city = $s_state = $s_country = $s_phone = $s_mi = $address1 = $address2 = $zip = $city = $state = $country = $phone = $recaptcha = $mi = null;
$userobject = $user->attribute( 'contentobject' );
$xini = eZINI::instance( 'xrowecommerce.ini' );
if ( $xini->hasVariable( 'ShopAccountHandlerDefaults', 'CountryCode' ) )
{
    $country = $xini->variable( 'ShopAccountHandlerDefaults', 'DefaultCountryCode' );
}
if ( $user->isLoggedIn() and in_array( $userobject->attribute( 'class_identifier' ), $xini->variable( 'Settings', 'ShopUserClassList' ) ) )
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
        if ( $userMap['tax_id']->attribute( 'data_int' ) == xrowTINType::STATUS_VALIDATED_BY_ADMIN )
        {
            $tax_id_valid = xrowTINType::STATUS_VALIDATED_BY_ADMIN;
        }
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
        /*Warning  datatype MIGHT return different values depending on setup*/
        $country = $userMap['country']->content();
        if ( is_array( $country['value'] ) )
        {
            $country = array_shift( $country['value'] );
        }
        else
        {
            if (key_exists('value', $country )) {
                $country = $country['value'];
            }
            if ( strlen( $country ) == 3 ) {
                $country = eZCountryType::fetchCountry( $country, 'Alpha3' );
            } elseif ( strlen( $country ) == 2 ) {
                $country = eZCountryType::fetchCountry( $country, 'Alpha2' );
            } else {
                $country = eZCountryType::fetchCountry( $country, false );
            }
        }
        $country = $country['Alpha3'];
    }
    if ( isset( $userMap['state'] ) )
    {
        $state = $userMap['state']->content();
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

    if ( $shipping != '1' )
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
            $s_country = $userMap['s_country']->content();
            if ( is_array( $s_country['value'] ) )
            {
                $s_country = array_shift( $s_country['value'] );
            }
            else
            {
            if ( key_exists('value', $s_country ) ) {
                $s_country = $s_country['value'];
            }
            if ( strlen( $s_country ) == 3 ) {
                $s_country = eZCountryType::fetchCountry( $s_country, 'Alpha3' );
            } elseif ( strlen( $country ) == 2 ) {
                $s_country = eZCountryType::fetchCountry( $s_country, 'Alpha2' );
            } else {
                $s_country = eZCountryType::fetchCountry( $s_country, false );
                }
            }
            $s_country = $s_country['Alpha3'];
        }
        if ( isset( $userMap['s_state'] ) )
        {
            $s_state = $userMap['s_state']->content();
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

$fields = array();
$field_keys = array( 'company_name',
                     'company_additional',
                     'tax_id',
                     'first_name',
                     'mi',
                     'last_name',
                     'address1',
                     'address2',
                     'zip',
                     'city',
                     'state',
                     'country',
                     'phone',
                     'fax',
                     'email',
                     's_company_name',
                     's_company_additional',
                     's_first_name',
                     's_mi',
                     's_last_name',
                     's_address1',
                     's_address2',
                     's_zip',
                     's_city',
                     's_state',
                     's_country',
                     's_phone',
                     's_fax',
                     's_email',
                     'client_ip' );
foreach( $field_keys as $key )
{
    $fields[$key] = array();
    if ( $xini->hasVariable( 'Fields', $key ) )
    {
        $field_settings  = $xini->variable( 'Fields', $key );
        if ( isset( $field_settings['required'] ) AND $field_settings['required'] == 'true' )
        {
            $fields[$key]['enabled'] = true;
            $fields[$key]['required'] = true;
        }
        else
        {
            $fields[$key]['required'] = false;
        }
        if ( isset( $field_settings['enabled'] ) AND $field_settings['enabled'] == 'false' )
        {
            $fields[$key]['enabled'] = false;
        }
        else
        {
            $fields[$key]['enabled'] = true;
        }
    }
    else
    {
        eZDebug::writeError( "$key variable not set in xrowecommerce.ini", __FILE__ . ':' . __LINE__ );
    }
}

$tpl->setVariable( 'input_error', false );
if ( $module->isCurrentAction( 'Store' ) )
{
    $inputIsValid = true;
    $error_fields = array();

    if ( $fields['company_name']['enabled'] == true )
    {
        $company_name = trim( $http->postVariable( 'company_name' ) );
        if ( $company_name == '' and $fields['company_name']['required'] == true )
        {
                $inputIsValid = false;
                $fields['company_name']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The billing company name is not given.' );
        }
    }

    if ( $fields['company_additional']['enabled'] == true )
    {
        $company_additional = trim( $http->postVariable( 'company_additional' ) );
        if ( $company_additional == '' and $fields['company_additional']['required'] == true )
        {
            $inputIsValid = false;
            $fields['company_additional']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The billing company additional is not given.' );
        }
    }

    if ( $fields['first_name']['enabled'] == true )
    {
        $first_name = trim( $http->postVariable( 'first_name' ) );
        if ( $fields['first_name']['required'] == true )
        {
            if ( $first_name == '' )
            {
                $inputIsValid = false;
                $fields['first_name']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The billing first name is not given.' );
            }
        }
    }

    if ( $fields['last_name']['enabled'] == true )
    {
        $last_name = trim( $http->postVariable( 'last_name' ) );
        if ( $last_name == '' and $fields['last_name']['required'] == true )
        {
            $inputIsValid = false;
            $fields['last_name']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The billing last name is not given.' );
        }
    }

    if ( $fields['mi']['enabled'] == true )
    {
        $mi = trim( $http->postVariable( 'mi' ) );
        if ( $mi == '' and $fields['mi']['required'] == true )
        {
            $inputIsValid = false;
            $fields['mi']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The billing mi is not given' );
        }
    }

    if ( $fields['email']['enabled'] == true )
    {
        $email = trim( $http->postVariable( 'email' ) );
        if ( $fields['email']['required'] == true )
        {
            if ( empty( $email ) )
            {
                $inputIsValid = false;
                $errors[] = ezpI18n::tr( 'extension/xrowecommerce', 'The email address is not given.' );
                $fields['email']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The billing email address is not given.' );
            }
            else
            {
                if ( !eZMail::validate( $email ) )
                {
                    $inputIsValid = false;
                    $errors[] = ezpI18n::tr( 'extension/xrowecommerce', 'The email address is not valid.' );
                    $fields['email']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The billing email address is not valid.' );
                }
            }
        }
    }

    if ( $fields['address1']['enabled'] == true )
    {
        $address1 = trim( $http->postVariable( 'address1' ) );
        if ( $fields['address1']['required'] == true )
        {
            if ( $address1 == '' )
            {
                $inputIsValid = false;
                $fields['address1']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The billing address is not given.' );
            }
        }
    }

    if ( $fields['address2']['enabled'] == true )
    {
        $address2 = trim( $http->postVariable( 'address2' ) );
        if ( $fields['address2']['required'] == true )
        {
            if ( $address2 == '' )
            {
                $inputIsValid = false;
                $fields['address2']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The billing address 2 is not given.' );
            }
        }
    }

    if ( $fields['country']['enabled'] == true )
    {
        $country = trim( $http->postVariable( 'country' ) );
        if ( $country == '' and $fields['country']['required'] == true)
        {
            $inputIsValid = false;
            $fields['country']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'No billing country has been selected.' );
        }
        else
        {
            if ( $xini->hasVariable( 'Settings', 'CountryWihtStatesList' ) and
                 in_array( $country, $xini->variable( 'Settings', 'CountryWihtStatesList' ) ) and $state == '' )
            {
                $inputIsValid = false;
                $fields['country']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'No billing country has been selected.' );
            }
        }
    }

    if ( $fields['state']['enabled'] == true and $fields['state']['required'] == true)
    {
        $state = trim( $http->postVariable( 'state' ) );
        if ( $state == '' || !xrowGeonames::getSubdivisionName( $country, $state ) )
        {
            $inputIsValid = false;
            $fields['state']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'No billing state has been selected.' );
        }
    }

    if ( $fields['city']['enabled'] == true )
    {
        $city = trim( $http->postVariable( 'city' ) );
        if ( $city == '' and $fields['city']['required'] == true )
        {
            $inputIsValid = false;
            $fields['city']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The billing city is not given.' );
        }
    }

    if ( $fields['zip']['enabled'] == true )
    {
        $zip = trim( $http->postVariable( 'zip' ) );
        if ( $zip == '' and $fields['zip']['required'] == true)
        {
            $inputIsValid = false;
            $fields['zip']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The billing zip is not given.' );
        }
    }

    if ( $fields['tax_id']['enabled'] == true )
    {
        if ( $http->hasPostVariable( 'tax_id' ) and $tax_id_valid != xrowTINType::STATUS_VALIDATED_BY_ADMIN )
        {
            $merchantcountries = xrowECommerce::merchantsCountries();
            $ezcountry = eZCountryType::fetchCountry( $country, 'Alpha3' );
            $Alpha2 = $ezcountry['Alpha2'];
            /* EU doesn`t use ISO all the time */
            if ( $Alpha2 == 'GR' )
            {
                $Alpha2 = 'EL';
            }
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
            $tax_id = strtoupper( trim( $http->postVariable( 'tax_id' ) ) );
            if ( $fields['tax_id']['required'] == true )
            {
                if ( in_array( $Alpha2, $ids ) and $tax_id == '' and $company_name != '')
                {
                    $fields['tax_id']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The companies tax ID number is not given.' );
                    $errors[] = ezpI18n::tr( 'extension/xrowecommerce', 'The companies tax ID number is not given.' );
                    $inputIsValid = false;
                }
                elseif ( empty( $tax_id ) and $company_name and in_array( $Alpha2, $ids ) and ! in_array( $Alpha2, $merchantcountries ) )
                {
                    $errors[] = ezpI18n::tr( 'extension/xrowecommerce', 'The companies tax ID number is not given.' );
                    $fields['tax_id']['errors'][1] = ezpI18n::tr( 'extension/xrowecommerce', 'The companies tax ID number is not given.' );
                    $inputIsValid = false;
                }
                elseif ( in_array( $Alpha2, $ids ) and $company_name )
                {
                    $matches = array();
                    if ( preg_match( "/^(" . join( '|', $ids ) . ")([a-z0-9]+)/i", $tax_id, $matches ) )
                    {
                        if ( $Alpha2 != $matches[1] )
                        {
                            $inputIsValid = false;
                            $errors[] = ezpI18n::tr( 'extension/xrowecommerce', 'Country doesn`t match tax ID number.' );
                            $fields['tax_id']['errors'][2] = ezpI18n::tr( 'extension/xrowecommerce', 'Country doesn`t match tax ID number.' );
                        }
                        try
                        {
                            $ret = xrowECommerce::checkVat( $ezcountry['Alpha2'], $matches[2] );
                            if ( ! $ret )
                            {
                                $inputIsValid = false;
                                $errors[] = ezpI18n::tr( 'extension/xrowecommerce', 'Your companies tax ID number is not valid.' );
                                $fields['tax_id']['errors'][3] = ezpI18n::tr( 'extension/xrowecommerce', 'Your companies tax ID number is not valid.' );
                            }
                            else
                            {
                                $tax_id_valid = 1;
                            }
                        }
                        catch ( Exception $e )
                        {
                            eZDebug::writeError( $e->getMessage(), 'TAX ID Validation problem.' );
                        }
                    }
                    elseif ( in_array( $Alpha2, $merchantcountries ) )
                    {
                        if ( empty( $tax_id ) )
                        {

                        }
                        elseif ( !xrowECommerce::validateTIN( $Alpha2, $tax_id, $errors2 ) )
                        {
                            $inputIsValid = false;
                            $errors = array_merge( $errors, $errors2 );
                            $fields['tax_id']['errors'][4] = ezpI18n::tr( 'extension/xrowecommerce', 'Your companies tax ID number is not valid.' );
                        }
                    }
                    else
                    {
                        $inputIsValid = false;
                        $errors[] = ezpI18n::tr( 'extension/xrowecommerce', 'Your companies tax ID number is not valid.' );
                        $fields['tax_id']['errors'][5] = ezpI18n::tr( 'extension/xrowecommerce', 'Your companies tax ID number is not valid.' );
                    }
                }
            }
        }
    }

    if ( $fields['phone']['enabled'] == true )
    {
        $phone = trim( $http->postVariable( 'phone' ) );
        if ( $fields['phone']['required'] == true and $phone == '' )
        {
            $inputIsValid = false;
            $fields['phone']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The billing phone number is not given.' );
        }
    }

    if ( $fields['fax']['enabled'] == true )
    {
        $fax = trim( $http->postVariable( 'fax' ) );
        if ( $fields['fax']['required'] == true and $fax == '' )
        {
            $inputIsValid = false;
            $fields['fax']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The billing fax number is not given.' );
        }
    }

    if ( $http->hasPostVariable( 'PaymentMethod' ) )
    {
        $payment_method = $http->postVariable( 'PaymentMethod' );
    }

    if ( $http->hasPostVariable( 'reference' ) )
    {
        $reference = $http->postVariable( 'reference' );
    }

    if ( $http->hasPostVariable( 'message' ) )
    {
        $message = $http->postVariable( 'message' );
    }
    
    $no_partial_delivery_temp = $xini->variable( 'Fields', 'NoPartialDelivery' );
    
    if ( $http->hasPostVariable( 'no_partial_delivery' ) )
    {
        $no_partial_delivery = '1';
    }
    elseif ( !$http->hasPostVariable( 'no_partial_delivery' ) and
            $no_partial_delivery_temp['enabled'] == 'true' )
    {
        $no_partial_delivery = '0';
    }

    $newsletter_temp = $xini->variable( 'Fields', 'Newsletter' );
    
    if ( $http->hasPostVariable( 'newsletter' ) and $newsletter_temp['enabled'] == 'true' )
    {
        $newsletter = '1';
    }
    else
    {
        $newsletter = '0';
    }

    if ( $http->hasPostVariable( 'shipping' ) )
    {
        $shipping = '1';
    }
    else
    {
        $shipping = '0';
    }
    $shippingtype = $http->postVariable( 'shippingtype' );
    if ( $shippingtype == "" ) {
        $inputIsValid = false;
        $fields['shippingtype']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The shipping type is not given.' );
    } elseif ( ( $shippingtype == "usps_international" or $shippingtype == "usps_international_guaranteed" ) and $country == "USA" ) {
        $inputIsValid = false;
        $fields['shippingtype']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'Please select a proper shipping method for your destination.' );
    } elseif ( ( $shippingtype == "ups_ground" or $shippingtype == "ups_air_2ndday" or $shippingtype == "ups_air_nextday") and $country != "USA" ) {
        $fields['shippingtype']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'Please select a proper shipping method for your destination.' );
    }
$gateway = xrowShippingInterface::instanceByMethod( $shippingtype );

$basket = eZBasket::currentBasket();
$productcollection = $basket->productCollection();
$items = $productcollection->itemList();
$hazardous = array();
foreach ( $items as $item )
{
            $co = eZContentObject::fetch( $item->attribute( 'contentobject_id' ) );
            // Fetch object datamap
            $dm = $co->dataMap();

            // Hazardous Item check
            if ( array_key_exists( 'hazardous', $dm ) and $dm["hazardous"]->DataInt == 1 )
            {
                if ( $gateway->is_air === true )
                {
                    $hazardous[] = $item;
	            $inputIsValid = false;
                }
            }
}

    $shippingdestination = $country;

    if ( $shipping != '1' )
    {
        if ( $fields['s_company_name']['enabled'] == true )
        {
            $s_company_name = trim( $http->postVariable( 's_company_name' ) );
            if ( $s_first_name == '' and $fields['s_company_name']['required'] == true )
            {
                $inputIsValid = false;
                $fields['s_company_name']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The shipping company name is not given.' );
            }
        }

        if ( $fields['s_company_additional']['enabled'] == true )
        {
            $s_company_additional = trim( $http->postVariable( 's_company_additional' ) );
            if ( $s_company_additional == '' and $fields['s_company_additional']['required'] == true )
            {
                $inputIsValid = false;
                $fields['s_company_additional']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The shipping company additional field is not given.' );
            }
        }

        if ( $fields['s_first_name']['enabled'] == true )
        {
            $s_first_name = trim( $http->postVariable( 's_first_name' ) );
            if ( $s_first_name == '' and $fields['s_first_name']['required'] == true )
            {
                $inputIsValid = false;
                $fields['s_first_name']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The shipping first name is not given.' );
            }
        }

        if ( $fields['s_last_name']['enabled'] == true )
        {
            $s_last_name = trim( $http->postVariable( 's_last_name' ) );
            if ( $s_last_name == '' and $fields['s_last_name']['required'] == true )
            {
                $inputIsValid = false;
                $fields['s_last_name']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The shipping last name is not given.' );
            }
        }

        if ( $fields['s_mi']['enabled'] == true )
        {
            $s_mi = trim( $http->postVariable( 's_mi' ) );
            if ( $s_mi == '' and $fields['s_mi']['required'] == true )
            {
                $inputIsValid = false;
                $fields['s_mi']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The shipping mi is not given.' );
            }
        }

        if ( $fields['s_email']['enabled'] == true )
        {
            $s_email = trim( $http->postVariable( 's_email' ) );
            if ( empty( $s_email ) )
            {
                $inputIsValid = false;
                $errors[] = ezpI18n::tr( 'extension/xrowecommerce', 'The email address is not given.' );
                $fields['s_email']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The shipping email address is not given.' );
            }
            else
            {
                if ( !eZMail::validate( $s_email ) )
                {
                    $inputIsValid = false;
                    $errors[] = ezpI18n::tr( 'extension/xrowecommerce', 'The email address is not valid.' );
                    $fields['s_email']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The shipping email address is not valid' );
                }
            }
        }

        if ( $fields['s_address1']['enabled'] == true )
        {
            $s_address1 = trim( $http->postVariable( 's_address1' ) );
            if ( $s_address1 == '' and $fields['s_address1']['required'] == true )
            {
                $inputIsValid = false;
                $fields['s_address1']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The shipping address is not given.' );
            }
        }

        if ( $fields['s_address2']['enabled'] == true )
        {
            $s_address2 = trim( $http->postVariable( 's_address2' ) );
            if ( $s_address2 == '' and $fields['s_address2']['required'] == true )
            {
                $inputIsValid = false;
                $fields['s_address2']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The shipping address 2 is not given.' );
            }
        }

        if ( $fields['s_city']['enabled'] == true )
        {
            $s_city = trim( $http->postVariable( 's_city' ) );
            if ( $s_city == '' and  $fields['s_city']['required'] == true )
            {
                $inputIsValid = false;
                $fields['s_city']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The shipping city is not given.' );
            }
        }

        if ( $fields['s_zip']['enabled'] == true )
        {
            $s_zip = trim( $http->postVariable( 's_zip' ) );
            if ( $s_zip == '' and $fields['s_zip']['required'] == true )
            {
                $inputIsValid = false;
                $fields['s_zip']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The shipping zip is not given.' );
            }
        }

        if ( $fields['s_country']['enabled'] == true )
        {
            $s_country = trim( $http->postVariable( 's_country' ) );
            if ( $s_country == '' and $fields['s_country']['required'] == true )
            {
                $inputIsValid = false;
                $fields['s_country']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'No shipping country has been selected.' );
            }
            else
            {
                if ( in_array( $s_country, $xini->variable( 'Settings', 'CountryWithStatesList' ) ) and $s_state == '' )
                {
                    $inputIsValid = false;
                    $fields['s_country']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'No shipping country has been selected.' );
                }
            }
        }

        if ( $fields['s_state']['enabled'] == true and $fields['s_state']['required'] == true )
        {
            $s_state = trim( $http->postVariable( 's_state' ) );
            if ( $s_state == '' || !xrowGeonames::getSubdivisionName( $s_country, $s_state )  )
            {
                $inputIsValid = false;
                $fields['s_state']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'No shipping state has been selected.' );
            }
        }

        if ( $fields['s_phone']['enabled'] == true )
        {
            $s_phone = trim( $http->postVariable( 's_phone' ) );
            if ( $s_phone == '' )
            {
                $inputIsValid = false;
                $fields['s_phone']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The shipping phone number is not given.' );
            }
        }


        if ( $fields['s_fax']['enabled'] == true and $fields['s_fax']['required'] == true)
        {
            $s_fax = trim( $http->postVariable( 's_fax' ) );
            if ( $s_fax == '' )
            {
                $inputIsValid = false;
                $fields['s_fax']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'The shipping fax number is not given.' );
            }
        }


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
            try
            {
                if ( ! $gateway->methodCheck( $shippingdestination ) )
                {
                    $inputIsValid = false;
                    $errors[] = ezpI18n::tr( 'extension/xrowecommerce', 'Shipping method is not allowed for destination.' );
                    $fields['shippinginterface']['errors'][0] = ezpI18n::tr( 'extension/xrowecommerce', 'Shipping method is not allowed for destination.' );
                }
            }
            catch ( xrowShippingException $e )
            {
                $fields['shippinginterface']['errors'][1] = ezpI18n::tr( 'extension/xrowecommerce', 'Shipping method is not allowed for destination.' );
                $errors[] = ezpI18n::tr( 'extension/xrowecommerce', 'Shipping method is not allowed for destination.' );
                $inputIsValid = false;
            }
            try
            {
                if ( ! $gateway->destinationCheck( $shippingdestination ) )
                {
                    $fields['shippinginterface']['errors'][2] = ezpI18n::tr( 'extension/xrowecommerce', 'Shipping destination is not allowed.' );
                    $errors[] = ezpI18n::tr( 'extension/xrowecommerce', 'Shipping destination is not allowed.' );
                    $inputIsValid = false;
                }
            }
            catch ( xrowShippingException $e )
            {
                $fields['shippinginterface']['errors'][3] = ezpI18n::tr( 'extension/xrowecommerce', 'Shipping destination is not allowed.' );
                $errors[] = ezpI18n::tr( 'extension/xrowecommerce', 'Shipping destination is not allowed.' );
                $inputIsValid = false;
            }
        }
    }
    /* Coupon check */
    $fields_coupon = $xini->variable( 'Fields', 'Coupon' );
    if ( class_exists( 'xrowCoupon' ) and $fields_coupon['enabled'] == 'true' )
    {
        $coupon = new xrowCoupon( $http->postVariable( 'coupon_code' ) );
        $coupon_code = $coupon->code;
    }
    $currentUser = eZUser::currentUser();
    $accessAllowed = $currentUser->hasAccessTo( 'xrowecommerce', 'bypass_captcha' );
    /* Captcha check */
    $fields_captcha = $xini->variable( 'Fields', 'Captcha' );
    if ( class_exists( 'xrowVerification' ) AND
         $fields_captcha['enabled'] == 'true' AND
         $accessAllowed['accessWord'] != 'yes' AND
         empty( $_SESSION['xrowCaptchaSolved'] ) )
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
        $siteaccessNode = $doc->createElement( 'siteaccess', $GLOBALS['eZCurrentAccess']['name'] );

        $root->appendChild( $siteaccessNode );

        $company_nameNode = $doc->createElement( 'company_name', $company_name );
        $root->appendChild( $company_nameNode );

        $company_additionalNode = $doc->createElement( 'company_additional', $company_additional );
        $root->appendChild( $company_additionalNode );

        $tax_idNode = $doc->createElement( 'tax_id', $tax_id );
        $root->appendChild( $tax_idNode );
        if ( $tax_id and $tax_id_valid )
        {
            $tax_idNode = $doc->createElement( 'tax_id_valid', $tax_id_valid );
            $root->appendChild( $tax_idNode );
        }
        elseif ( $tax_id )
        {
            $tax_idNode = $doc->createElement( 'tax_id_valid', '0' );
            $root->appendChild( $tax_idNode );
        }
        $first_nameNode = $doc->createElement( 'first_name', $first_name );
        $root->appendChild( $first_nameNode );

        $miNode = $doc->createElement( 'mi', $mi );
        $root->appendChild( $miNode );

        $last_nameNode = $doc->createElement( 'last_name' );
        $last_nameNode->appendChild( $doc->createTextNode( $last_name ) );
        $root->appendChild( $last_nameNode );

        $address1Node = $doc->createElement( 'address1' );
        $address1Node->appendChild( $doc->createTextNode( $address1 ) );
        $root->appendChild( $address1Node );

        $address2Node = $doc->createElement( 'address2' );
        $address2Node->appendChild( $doc->createTextNode( $address2 ) );
        $root->appendChild( $address2Node );

        $cityNode = $doc->createElement( 'city', $city );
        $root->appendChild( $cityNode );

        $stateNode = $doc->createElement( 'state', $state );
        $root->appendChild( $stateNode );

        $zipNode = $doc->createElement( 'zip', $zip );
        $root->appendChild( $zipNode );

        $countryNode = $doc->createElement( 'country', $country );
        $root->appendChild( $countryNode );

        $phoneNode = $doc->createElement( 'phone', $phone );
        $root->appendChild( $phoneNode );

        $faxNode = $doc->createElement( 'fax', $fax );
        $root->appendChild( $faxNode );

        $emailNode = $doc->createElement( 'email', $email );
        $root->appendChild( $emailNode );

        $shippingNode = $doc->createElement( 'shipping', $shipping );
        $root->appendChild( $shippingNode );

        $shippingTypeNode = $doc->createElement( 'shippingtype', $shippingtype );
        $root->appendChild( $shippingTypeNode );

        $recaptacheNode = $doc->createElement( 'captcha', $captcha );
        $root->appendChild( $recaptacheNode );
        if ( ! empty( $payment_method ) )
        {
            $payment_methodNode = $doc->createElement( xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD, $payment_method );
            $root->appendChild( $payment_methodNode );
        }
        if ( $coupon_code )
        {
            $coupon_codeNode = $doc->createElement( 'coupon_code', $coupon_code );
            $root->appendChild( $coupon_codeNode );
        }
        else
        {
            $coupon_codeNode = $doc->createElement( 'coupon_code', '' );
            $root->appendChild( $coupon_codeNode );
        }
        if ( isset( $no_partial_delivery ) )
        {
            $partial_deliveryNode = $doc->createElement( 'no_partial_delivery', $no_partial_delivery );
            $root->appendChild( $partial_deliveryNode );
        }
        $referenceNode = $doc->createElement( 'reference', $reference );
        $root->appendChild( $referenceNode );

        $messageNode = $doc->createElement( 'message', $message );
        $root->appendChild( $messageNode );

        if ( isset($_SERVER['REMOTE_ADDR']) )
        {
            $remote_address = $_SERVER['REMOTE_ADDR'];
            $client_ip = $doc->createElement( 'client_ip', $remote_address );
            $root->appendChild( $client_ip );
        }

        if ( $shipping != '1' )
        {
            /* Shipping address*/

            $s_company_nameNode = $doc->createElement( 's_company_name', $s_company_name );

            $root->appendChild( $s_company_nameNode );

            $s_company_additionalNode = $doc->createElement( 's_company_additional', $s_company_additional );
            $root->appendChild( $s_company_additionalNode );

            $s_first_nameNode = $doc->createElement( 's_first_name', $s_first_name );
            $root->appendChild( $s_first_nameNode );

            $s_miNode = $doc->createElement( 's_mi', $s_mi );
            $root->appendChild( $s_miNode );

            $s_last_nameNode = $doc->createElement( 's_last_name', $s_last_name );
            $root->appendChild( $s_last_nameNode );

            $s_address1Node = $doc->createElement( 's_address1', $s_address1 );
            $root->appendChild( $s_address1Node );

            $s_address2Node = $doc->createElement( 's_address2', $s_address2 );
            $root->appendChild( $s_address2Node );

            $s_cityNode = $doc->createElement( 's_city', $s_city );
            $root->appendChild( $s_cityNode );

            $s_stateNode = $doc->createElement( 's_state', $s_state );
            $root->appendChild( $s_stateNode );

            $s_zipNode = $doc->createElement( 's_zip', $s_zip );
            $root->appendChild( $s_zipNode );

            $s_countryNode = $doc->createElement( 's_country', $s_country );
            $root->appendChild( $s_countryNode );

            $s_phoneNode = $doc->createElement( 's_phone', $s_phone );
            $root->appendChild( $s_phoneNode );

            $s_faxNode = $doc->createElement( 's_fax', $s_fax );
            $root->appendChild( $s_faxNode );

            $s_emailNode = $doc->createElement( 's_email', $s_email );
            $root->appendChild( $s_emailNode );

        /* Shipping address*/
        } /* Shippingaddress is equal or not */
        else
        {
            $s_company_nameNode = $doc->createElement( 's_company_name', $company_name );
            $root->appendChild( $s_company_nameNode );

            $s_company_additionalNode = $doc->createElement( 's_company_additional', $company_additional );
            $root->appendChild( $s_company_additionalNode );

            $s_first_nameNode = $doc->createElement( 's_first_name', $first_name );
            $root->appendChild( $s_first_nameNode );

            $s_miNode = $doc->createElement( 's_mi', $mi );
            $root->appendChild( $s_miNode );

            $s_last_nameNode = $doc->createElement( 's_last_name', $last_name );
            $root->appendChild( $s_last_nameNode );

            $s_address1Node = $doc->createElement( 's_address1', $address1 );
            $root->appendChild( $s_address1Node );

            $s_address2Node = $doc->createElement( 's_address2', $address2 );
            $root->appendChild( $s_address2Node );

            $s_cityNode = $doc->createElement( 's_city', $city );
            $root->appendChild( $s_cityNode );

            $s_stateNode = $doc->createElement( 's_state', $state );
            $root->appendChild( $s_stateNode );

            $s_zipNode = $doc->createElement( 's_zip', $zip );
            $root->appendChild( $s_zipNode );

            $s_countryNode = $doc->createElement( 's_country', $country );
            $root->appendChild( $s_countryNode );

            $s_phoneNode = $doc->createElement( 's_phone', $phone );
            $root->appendChild( $s_phoneNode );

            $s_faxNode = $doc->createElement( 's_fax', $fax );
            $root->appendChild( $s_faxNode );

            $s_emailNode = $doc->createElement( 's_email', $email );
            $root->appendChild( $s_emailNode );
        }

        $order->setAttribute( 'data_text_1', $doc->saveXML() );
        $shopAccountINI = eZINI::instance( 'shopaccount.ini' );

        $order->setAttribute( 'account_identifier', $shopAccountINI->variable( 'AccountSettings', 'Handler' ) );

        $order->setAttribute( 'ignore_vat', 0 );

        $order->store();
        $db->commit();

        $http->setSessionVariable( 'MyTemporaryOrderID', $order->attribute( 'id' ) );

        $module->redirectTo( '/xrowecommerce/confirmorder/' );
        return;
    }
    else
    {
        $tpl->setVariable( 'input_error', true );
    }
}

$tpl->setVariable( 'company_name', $company_name );
$tpl->setVariable( 'company_additional', $company_additional );
$tpl->setVariable( 'tax_id', $tax_id );
$tpl->setVariable( 'tax_id_valid', $tax_id_valid );
$tpl->setVariable( 'first_name', $first_name );
$tpl->setVariable( 'mi', $mi );
$tpl->setVariable( 'last_name', $last_name );
$tpl->setVariable( 'email', $email );
$tpl->setVariable( 'address1', $address1 );
$tpl->setVariable( 'address2', $address2 );
$tpl->setVariable( 'city', $city );
$tpl->setVariable( 'state', $state );
$tpl->setVariable( 'zip', $zip );
$tpl->setVariable( 'country', $country );
$tpl->setVariable( 'phone', $phone );
$tpl->setVariable( 'fax', $fax );
$tpl->setVariable( 'client_ip', $remote_address );

// default value for shipping
if ( !isset( $shipping ) )
{
    $shipping = '1';
}

$tpl->setVariable( 'shipping', $shipping );

$tpl->setVariable( 'shippingtype', $shippingtype );
if ( isset( $payment_method ) )
{
    $tpl->setVariable( 'payment_method', $payment_method );

}
$tpl->setVariable( 'recaptcha', $recaptcha );
$tpl->setVariable( 's_company_name', $s_company_name );
$tpl->setVariable( 's_company_additional', $s_company_additional );
$tpl->setVariable( 's_first_name', $s_first_name );
$tpl->setVariable( 's_mi', $s_mi );
$tpl->setVariable( 's_last_name', $s_last_name );
$tpl->setVariable( 's_email', $s_email );
$tpl->setVariable( 's_address1', $s_address1 );
$tpl->setVariable( 's_address2', $s_address2 );
$tpl->setVariable( 's_city', $s_city );
$tpl->setVariable( 's_state', $s_state );
$tpl->setVariable( 's_zip', $s_zip );
$tpl->setVariable( 's_country', $s_country );
$tpl->setVariable( 's_phone', $s_phone );
$tpl->setVariable( 's_fax', $s_fax );
$tpl->setVariable( 'errors', $errors );
$tpl->setVariable( 'coupon_code', $coupon_code );
$tpl->setVariable( 'reference', $reference );
$tpl->setVariable( 'message', $message );
$tpl->setVariable( 'no_partial_delivery', $no_partial_delivery );
$tpl->setVariable( 'fields', $fields );
$tpl->setVariable( 'countries', xrowGeonames::getCountries() );
$tpl->setVariable( 'hazardous', $hazardous );
if ( !isset( $country ) )
{
    $tmp = xrowGeonames::getCountries();
    $tmp = array_shift( $tmp );
    $country  = $tmp['Alpha3'];
}
if ( !isset( $s_country ) )
{
    $tmp = xrowGeonames::getCountries();
    $tmp = array_shift( $tmp );
    $s_country  = $tmp['Alpha3'];
}
$tpl->setVariable( 'states', xrowGeonames::getSubdivisions( $country ) );
$tpl->setVariable( 's_states', xrowGeonames::getSubdivisions( $s_country ) );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:shop/userregister.tpl' );
$Result['path'] = array(
    array(
        'url' => false ,
        'text' => ezpI18n::tr( 'extension/xrowecommerce', 'Enter account information' )
    )
);
?>
