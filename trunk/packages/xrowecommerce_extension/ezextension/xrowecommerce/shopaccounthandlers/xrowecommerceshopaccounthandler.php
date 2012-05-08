<?php

class xrowECommerceShopAccountHandler
{

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
        {
            $user = eZUser::currentUser();
        }
        $userObject = $user->attribute( 'contentobject' );
        $userMap = $userObject->dataMap();
        $billing = array();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['company_name']['enabled'] != 'false')
        $billing['company_name'] = $userMap['company_name']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['company_additional']['enabled'] != 'false')
        $billing['company_additional'] = $userMap['company_additional']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['tax_id']['enabled'] != 'false')
        $billing['tax_id'] = $userMap['tax_id']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['title']['enabled'] != 'false')
        $billing['title'] = $userMap['title']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['first_name']['enabled'] != 'false')
        $billing['first_name'] = $userMap['first_name']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['mi']['enabled'] != 'false')
        $billing['mi'] = $userMap['mi']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['last_name']['enabled'] != 'false')
        $billing['last_name'] = $userMap['last_name']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['address1']['enabled'] != 'false')
        $billing['address1'] = $userMap['address1']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['address2']['enabled'] != 'false')
        $billing['address2'] = $userMap['address2']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['state']['enabled'] != 'false')
        $billing['state'] = $userMap['state']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['zip_code']['enabled'] != 'false')
        $billing['zip'] = $userMap['zip_code']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['city']['enabled'] != 'false')
        $billing['city'] = $userMap['city']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['country']['enabled'] != 'false')
        $billing['country'] = $userMap['country']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['phone']['enabled'] != 'false')
        $billing['phone'] = $userMap['phone']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['fax']['enabled'] != 'false')
        $billing['fax'] = $userMap['fax']->content();
        if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['shippingaddress']['enabled'] != 'false')
        $billing['shipping'] = $userMap['shippingaddress']->content();
        $billing['shippingtype'] = $userMap['shippingtype']->content();
        if( is_object( $userMap[xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD] ) )
        {
            $billing[xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD] = $userMap[xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD]->content();
        }
        $billing['email'] = $user->attribute( 'email' );
        $shipping = array();
        if ( $shipping != "1" )
        {
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_companyname']['enabled'] != 'false')
            $shipping['s_companyname'] = $userMap['s_company_name']->content();
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_company_additional']['enabled'] != 'false')
            $shipping['s_company_additional'] = $userMap['s_company_additional']->content();
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_title']['enabled'] != 'false')
            $shipping['s_title'] = $userMap['s_title']->content();
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_first_name']['enabled'] != 'false')
            $shipping['s_first_name'] = $userMap['s_first_name']->content();
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_mi']['enabled'] != 'false')
            $shipping['s_mi'] = $userMap['s_mi']->content();
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_last_name']['enabled'] != 'false')
            $shipping['s_last_name'] = $userMap['s_last_name']->content();
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_address1']['enabled'] != 'false')
            $shipping['s_address1'] = $userMap['s_address1']->content();
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_address2']['enabled'] != 'false')
            $shipping['s_address2'] = $userMap['s_address2']->content();
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_city']['enabled'] != 'false')
            $shipping['s_city'] = $userMap['s_city']->content();
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_state']['enabled'] != 'false')
            $shipping['s_state'] = $userMap['s_state']->content();
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_zip_code']['enabled'] != 'false')
            $shipping['s_zip'] = $userMap['s_zip_code']->content();
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_country']['enabled'] != 'false')
            $shipping['s_country'] = $userMap['s_country']->content();
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_phone']['enabled'] != 'false')
            $shipping['s_phone'] = $userMap['s_phone']->content();
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_fax']['enabled'] != 'false')
            $shipping['s_fax'] = $userMap['s_fax']->content();
            if (eZINI::instance( 'xrowecommerce.ini' )->BlockValues['Fields']['s_email']['enabled'] != 'false')
            $shipping['s_email'] = $userMap['s_email']->content();
        }
        if( is_object( $userMap[xrowECommerce::ACCOUNT_KEY_CREDITCARD] ) )
        {
            $tmpcreditcard = $userMap[xrowECommerce::ACCOUNT_KEY_CREDITCARD]->content();
            if ( file_exists( eZExtension::baseDirectory() . '/ezauthorize' ) )
            {
                $creditcard['ezauthorize-card-name'] = $tmpcreditcard['name'];
                $creditcard['ezauthorize-card-number'] = $tmpcreditcard['number'];
                $creditcard['ezauthorize-card-date'] = $tmpcreditcard['month'] . $tmpcreditcard['year'];
                $creditcard['ezauthorize-card-type'] = $tmpcreditcard['type'];
            }
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
            $id = $dom->getElementsByTagName( xrowECommerce::ACCOUNT_KEY_TRANSACTIONID )->item( 0 );
            return xrowECommerce::decodeString( $id->textContent );
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
                return xrowECommerce::decodeString( $email->textContent );
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
            
            $firstName = $dom->getElementsByTagName( "first_name" )->item( 0 );
            $mi = $dom->getElementsByTagName( "mi" )->item( 0 );
            $lastName = $dom->getElementsByTagName( "last_name" )->item( 0 );
            
            if ( is_object( $mi ) )
            {
                $accountName = $firstName->textContent . " " . $mi->textContent . " " . $lastName->textContent;
            }
            else
            {
                $accountName = $firstName->textContent . " " . $lastName->textContent;
            }
        }
        return xrowECommerce::decodeString( $accountName );
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
            'tax_id_valid' , 
            'title' , 
            'first_name' , 
            'mi' , 
            'last_name' , 
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
            's_company_name' , 
            's_company_additional' , 
            's_title' ,
            's_first_name' , 
            's_mi' , 
            's_last_name' , 
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
            'coupon_code' ,
            xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD, 
            xrowECommerce::ACCOUNT_KEY_TRANSACTIONID, 
            xrowECommerce::ACCOUNT_KEY_NUMBER, 
            xrowECommerce::ACCOUNT_KEY_BANKCODE, 
            xrowECommerce::ACCOUNT_KEY_ACCOUNTNUMBER, 
            xrowECommerce::ACCOUNT_KEY_MONTH, 
            xrowECommerce::ACCOUNT_KEY_NAME, 
            xrowECommerce::ACCOUNT_KEY_YEAR, 
            xrowECommerce::ACCOUNT_KEY_TYPE 
        );
        foreach ( $fields as $field )
        {
            $node = $dom->getElementsByTagName( $field )->item( 0 );
            if ( $node )
            {
                $result[str_ireplace( '-', '_', $field )] = xrowECommerce::decodeString( $node->textContent );
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
    }
}

?>
