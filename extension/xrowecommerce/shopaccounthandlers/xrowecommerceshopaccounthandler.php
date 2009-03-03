<?php

class xrowECommerceShopAccountHandler
{

    /**
     * [MerchantLocations]
     * Locations[]=USA
     * Locations[]=GER
     * USA[]=CT
     * USA[]=NY
     * 
     * \public
     * @return array First element country, Second state
     */
    function merchantsLocations()
    {
        $ini = eZINI::instance( 'xrowecommerce.ini' );
        $LocationArray = array();
        foreach ( $ini->variable( 'MerchantLocations', 'Location' ) as $location )
        {
            if ( $ini->hasVariable( 'MerchantLocations', $location ) )
            {
                $LocationArray[] = array( 
                    $location , 
                    $ini->variable( 'MerchantLocations', $location ) 
                );
            }
            else
            {
                $LocationArray[] = array( 
                    $location 
                );
            }
        }
        return $LocationArray;
        #return array( 'USA', array( 'NY', 'CT' ) );
    }

    /**
     * Will verify that the user has supplied the correct user information.
     * Returns true if we have all the information needed about the user.
     */
    function verifyAccountInformation()
    {
        
        if ( eZSys::isShellExecution() )
        {
            eZDebug::writeDebug( "No need for account information", "xrowECommerceShopAccountHandler::verifyAccountInformation()" );
            //TODO verfiy that account is cool then do return true
            return true;
        }
        else
        {
            eZDebug::writeDebug( "Need account information", "xrowECommerceShopAccountHandler::verifyAccountInformation()" );
            return false;
        }
    }

    function fillAccountArray( $user = null )
    {
        if ( $user === null )
            $user = eZUser::currentUser();
        $userObject = $user->attribute( 'contentobject' );
        $userMap = $userObject->dataMap();
        $billing = array();
        $billing['companyname'] = $userMap['company_name']->content();
        $billing['companyadditional'] = $userMap['company_additional']->content();
        $billing['tax_id'] = $userMap['tax_id']->content();
        $billing['first-name'] = $userMap['first_name']->content();
        $billing['mi'] = $userMap['mi']->content();
        $billing['last-name'] = $userMap['last_name']->content();
        $billing['address1'] = $userMap['address1']->content();
        $billing['address2'] = $userMap['address2']->content();
        $billing['state'] = $userMap['state']->content();
        $billing['zip'] = $userMap['zip_code']->content();
        $billing['city'] = $userMap['city']->content();
        $billing['country'] = $userMap['country']->content();
        $billing['phone'] = $userMap['phone']->content();
        $billing['fax'] = $userMap['fax']->content();
        $billing['shipping'] = $userMap['shippingaddress']->content();
        $billing['shippingtype'] = $userMap['shippingtype']->content();
        $billing['paymentmethod'] = $userMap['paymentmethod']->content();
        $billing['email'] = $user->attribute( 'email' );
        $shipping = array();
        if ( $shipping != "1" )
        {
            $shipping['s_companyname'] = $userMap['scompanyname']->content();
            $shipping['s_companyadditional'] = $userMap['scompanyadditional']->content();
            $shipping['s_tax_id'] = $userMap['s_tax_id']->content();
            $shipping['s_first-name'] = $userMap['s_first_name']->content();
            $shipping['s_mi'] = $userMap['s_last_name']->content();
            $shipping['s_last-name'] = $userMap['s_mi']->content();
            $shipping['s_address1'] = $userMap['s_address1']->content();
            $shipping['s_address2'] = $userMap['s_address2']->content();
            $shipping['s_city'] = $userMap['s_city']->content();
            $shipping['s_state'] = $userMap['s_state']->content();
            $shipping['s_zip'] = $userMap['s_zip_code']->content();
            $shipping['s_country'] = $userMap['s_country']->content();
            $shipping['s_phone'] = $userMap['s_phone']->content();
            $shipping['s_fax'] = $userMap['s_fax']->content();
            $shipping['s_enail'] = $userMap['s_email']->content();
        }
        $tmpcreditcard = $userMap['creditcard']->content();
        if ( file_exists( eZExtension::baseDirectory() . '/ezauthorize' ) )
        {
            $creditcard['ezauthorize-card-name'] = $tmpcreditcard['name'];
            $creditcard['ezauthorize-card-number'] = $tmpcreditcard['number'];
            $creditcard['ezauthorize-card-date'] = $tmpcreditcard['month'] . $tmpcreditcard['year'];
            $creditcard['ezauthorize-card-type'] = $tmpcreditcard['type'];
        }
        
        $returnArray = array_merge( $billing, $shipping, $creditcard );
        return $returnArray;
    }

    /*!
     Redirectes to the user registration page.
    */
    function fetchAccountInformation( &$module )
    {
        $module->redirectTo( '/xrowecommerce/userregister' );
    }

    /*!
     \return the transaction id information for the given order
    */
    function transactionID( $order )
    {
        $xmlString = $order->attribute( 'data_text_1' );
        if ( $xmlString != null )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $xmlString );
            $id = $dom->getElementsByTagName( "ezauthorize-transaction-id" )->item( 0 );
            return $id->textContent;
        }
        else
            return false;
    }

    /*!
     \return the account information for the given order
    */
    function email( $order )
    {
        $xmlString = $order->attribute( 'data_text_1' );
        if ( $xmlString != null )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $xmlString );
            $email = $dom->getElementsByTagName( "email" )->item( 0 );
            if ( $email )
            {
                return $email->textContent;
            }
            else
            {
                return false;
            }
        }
        else
            return false;
    }

    /*!
     \return the account information for the given order
    */
    function accountName( $order )
    {
        $accountName = "";
        $xmlString = $order->attribute( 'data_text_1' );
        if ( $xmlString != null )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $xmlString );

            $firstName = $dom->getElementsByTagName( "first-name" )->item( 0 );
            $mi = $dom->getElementsByTagName( "mi" )->item( 0 );
            $lastName = $dom->getElementsByTagName( "last-name" )->item( 0 );

            if ( is_object( $mi ) )
            {
            	$accountName = $firstName->textContent . " " . $mi_tc . " " . $lastName->textContent;
            }
            else
            {
                $mi_tc = '';
            }
        }
        return $accountName;
    }

    function accountInformation( $order )
    {
        $result = array();
        $dom = new DOMDocument( '1.0', 'utf-8' );
        $xmlString = $order->attribute( 'data_text_1' );
        if ( empty( $xmlString ) )
        {
            return array();
        }
        $success = $dom->loadXML( $xmlString );
        $fields = array( 
            'company_name' , 
            'company_additional' , 
            'tax_id' , 
            'first-name' , 
            'mi' , 
            'last-name' , 
            'address1' , 
            'address2' , 
            'city' , 
            'state' , 
            'zip' , 
            'country' , 
            'phone' , 
            'fax' , 
            'email' , 
            'shipping' , 
            'shippingtype' , 
            'paymentmethod' , 
            's_company_name' , 
            's_company_additional' , 
            's_first-name' , 
            's_mi' , 
            's_last-name' , 
            's_address1' , 
            's_address2' , 
            's_city' , 
            's_state' , 
            's_zip' , 
            's_country' , 
            's_phone' , 
            's_fax' , 
            's_email' , 
            'reference' , 
            'message' , 
            'no_partial_delivery' , 
            'coupon-code' , 
            'ezauthorize-transaction-id' , 
            'ezauthorize-card-name' , 
            'ezauthorize-card-number' , 
            'ezauthorize-card-date' , 
            'ezauthorize-card-type' 
        )
        ;
        foreach ( $fields as $field )
        {
            $node = $dom->getElementsByTagName( $field )->item( 0 );
            if ( $node )
            {
                $result[str_ireplace( '-', '_', $field )] = $node->textContent;
            }
        }
        
        // If order has a shipping country use it instead.
        if ( isset( $result['s_country'] ) and $result['s_country'] != '' )
        {
            $result['shipping_country'] = $result['s_country'];
        }
        else
        {
            $result['shipping_country'] = $result['country'];
        }
        
        return $result;
        /* old result array for reference
        $ttest = array( 
            'companyname' => $companyNameText , 
            'companyadditional' => $companyAdditionalText , 
            'tax_id' => $taxIdText , 
            'first_name' => $firstNameText , 
            'mi' => $miText , 
            'last_name' => $lastNameText , 
            'address1' => $address1Text , 
            'address2' => $address2Text , 
            'city' => $cityText , 
            'state' => $stateText , 
            'zip' => $zipText , 
            'country' => $countryText , 
            'phone' => $phoneText , 
            'fax' => $faxText , 
            'email' => $emailText , 
            'shipping' => $shippingText , 
            'shippingtype' => $shippingTypeText , 
            'paymentmethod' => $paymentMethodText , 
            's_company_name' => $s_companyNameText , 
            's_company_additional' => $s_companyAdditionalText , 
            's_first_name' => $s_firstNameText , 
            's_mi' => $s_miText , 
            's_last_name' => $s_lastNameText , 
            's_address1' => $s_address1Text , 
            's_address2' => $s_address2Text , 
            's_city' => $s_cityText , 
            's_state' => $s_stateText , 
            's_zip' => $s_zipText , 
            's_country' => $s_countryText , 
            's_phone' => $s_phoneText , 
            's_fax' => $s_faxText , 
            'coupon_code' => $CouponCode , 
            'ezauthorize_transaction_id' => $s_ezauthorize_transaction_idText , 
            'ezauthorize_card_name' => $s_ezauthorize_card_nameText , 
            'ezauthorize_card_number' => $s_ezauthorize_card_numberText , 
            'ezauthorize_card_date' => $s_ezauthorize_card_dateText , 
            'ezauthorize_card_type' => $s_ezauthorize_card_typeText , 
            's_email' => $s_emailText 
        );
*/
    }
}

?>