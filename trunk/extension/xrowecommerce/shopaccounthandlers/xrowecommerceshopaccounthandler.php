<?php

include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'extension/coupon/classes/xrowcoupon.php' );
class xrowECommerceShopAccountHandler
{
    /*!
    */
    function xrowECommerceShopAccountHandler()
    {

    }
    /**
     * \public
     * @return array First element country, Second state
     */
    function merchantsLocations()
    {
        return array( 'USA', array( 'NY', 'CT' ) );
    }
    /*!
     Will verify that the user has supplied the correct user information.
     Returns true if we have all the information needed about the user.
    */
    function verifyAccountInformation()
    {
        if ( eZSys::isShellExecution() )
        {
            //TODO verfiy that account is cool then do return true
            return true;
        }
        else
            return false;
    }
    function fillAccountArray( $user = null )
    {
        if ( $user === null )
            $user = eZUser::currentUser();
        $userObject = $user->attribute( 'contentobject' );
        $userMap = $userObject->dataMap();
        $billing = array();
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
        $billing['shipping'] = $userMap['shippingaddress']->content();
        $billing['shippingtype'] = $userMap['shippingtype']->content();
        $billing['email'] = $user->attribute( 'email' );
        $shipping = array();
        if ( $shipping !="1" )
        {
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
            $shipping['s_phone'] = $userMap['s_email']->content();
        }
        $tmpcreditcard = $userMap['creditcard']->content();
        if ( file_exists( eZExtension::baseDirectory() . '/ezauthorize' ) )
        {
            $creditcard['ezauthorize-card-name'] = $tmpcreditcard['name'];
            $creditcard['ezauthorize-card-number'] = $tmpcreditcard['number'];
            $creditcard['ezauthorize-card-date'] = $tmpcreditcard['month'].$tmpcreditcard['year'];
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
        $xml = new eZXML();
        $xmlDoc = $order->attribute( 'data_text_1' );
        if( $xmlDoc != null )
        {
                $dom = $xml->domTree( $xmlDoc );
                $id = $dom->elementsByName( "ezauthorize-transaction-id" );
                return $id[0]->textContent();
        }
        else
            return false;
    }

    /*!
     \return the account information for the given order
    */
    function email( $order )
    {
        $xml = new eZXML();
        $xmlDoc = $order->attribute( 'data_text_1' );
        if( $xmlDoc != null )
        {
            $dom = $xml->domTree( $xmlDoc );
            $email = $dom->elementsByName( "email" );
            if ( isset( $email[0] ) )
            {
                return $email[0]->textContent();
            } else {
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
        $xml = new eZXML();
        $xmlDoc = $order->attribute( 'data_text_1' );
        if( $xmlDoc != null )
        {
            $dom =& $xml->domTree( $xmlDoc );
            $firstName = $dom->elementsByName( "first-name" );
            $mi = $dom->elementsByName( "mi" );

            if( array_key_exists( 0, $mi ) and is_object( $mi[0] ) )
            {
                $mi_tc = $mi[0]->textContent();
            }
            else
            {
                $mi_tc = '';
            }

            $lastName = $dom->elementsByName( "last-name" );

            if( isset( $firstName[0] ) and isset( $lastName[0] ) )
                $accountName = $firstName[0]->textContent() . " " . $mi_tc ." " . $lastName[0]->textContent();
        }

        return $accountName;
    }

    function accountInformation( $order )
    {
        $xml = new eZXML();
        $xmlDoc = $order->attribute( 'data_text_1' );
        $dom = $xml->domTree( $xmlDoc );

        $firstName = $dom->elementsByName( "first-name" );
        $mi = $dom->elementsByName( "mi" );
        $lastName = $dom->elementsByName( "last-name" );

        $address1 = $dom->elementsByName( "address1" );
        $address2 = $dom->elementsByName( "address2" );

        $city = $dom->elementsByName( "city" );
        $state = $dom->elementsByName( "state" );
        $zip = $dom->elementsByName( "zip" );
        $country = $dom->elementsByName( "country" );

        $phone = $dom->elementsByName( "phone" );
        $email = $dom->elementsByName( "email" );

        $shipping = $dom->elementsByName( "shipping" );
        $shippingtype = $dom->elementsByName( "shippingtype" );

        $s_firstName = $dom->elementsByName( "s_first-name" );
        $s_mi = $dom->elementsByName( "s_mi" );
        $s_lastName = $dom->elementsByName( "s_last-name" );

        $s_address1 = $dom->elementsByName( "s_address1" );
        $s_address2 = $dom->elementsByName( "s_address2" );

        $s_city = $dom->elementsByName( "s_city" );
        $s_state = $dom->elementsByName( "s_state" );
        $s_zip = $dom->elementsByName( "s_zip" );
        $s_country = $dom->elementsByName( "s_country" );

        $s_phone = $dom->elementsByName( "s_phone" );
        $s_email = $dom->elementsByName( "s_email" );

        // $comment =& $dom->elementsByName( "comment" );

        $firstNameText = "";
        if ( isset( $firstName[0] ) )
            $firstNameText = $firstName[0]->textContent();

        $miText = "";
        if( isset( $mi[0] ) )
        {
        if ( isset( $mi[0] ) )
            $miText = $mi[0]->textContent();
        }

        $lastNameText = "";
        if ( isset( $lastName[0] ) )
            $lastNameText = $lastName[0]->textContent();

        $address1Text = "";
        if ( isset( $address1[0] ) )
            $address1Text = $address1[0]->textContent();

        $address2Text = "";
        if ( isset( $address2[0] ) )
            $address2Text = $address2[0]->textContent();

        $cityText = "";
        if ( isset( $city[0] ) )
            $cityText = $city[0]->textContent();

        $stateText = "";
        if ( isset( $state[0] ) )
            $stateText = $state[0]->textContent();

        $zipText = "";
        if ( isset( $zip[0] ) )
            $zipText = $zip[0]->textContent();

        $countryText = "";
        if ( isset( $country[0] ) )
            $countryText = $country[0]->textContent();

        $phoneText = "";
        if ( isset( $phone[0] ) )
            $phoneText = $phone[0]->textContent();

        $emailText = "";
        if ( isset( $email[0] ) )
            $emailText = $email[0]->textContent();

        $shippingText = "";
        if ( isset( $shipping[0] ) ) {
            if ( isset( $shipping[0] ) )
                $shippingText = $shipping[0]->textContent();
        }

        $shippingTypeText = "";
        if ( isset( $shippingtype[0] ) )
            $shippingTypeText = $shippingtype[0]->textContent();

        // ezDebug::writeDebug( count($s_firstName), 'eZUser Information'  );

        $s_firstNameText = "";
        if ( count($s_firstName) > 0 and isset( $s_firstName[0] ) )
            $s_firstNameText = $s_firstName[0]->textContent();

        $s_miText = "";
        if ( isset( $s_mi[0] ) ) {
            if ( count($s_firstName) > 0 and isset( $s_mi[0] ) )
                $s_miText = $s_mi[0]->textContent();
        }

        $s_lastNameText = "";
        if ( count($s_firstName) > 0 and isset( $s_lastName[0] ) )
            $s_lastNameText = $s_lastName[0]->textContent();

        $s_address1Text = "";
        if ( count($s_firstName) > 0 and isset( $s_address1[0] ) )
            $s_address1Text = $s_address1[0]->textContent();

        $s_address2Text = "";
        if ( isset( $s_address2[0] ) ) {
            if ( count($s_firstName) > 0 and isset( $s_address2[0] ) )
                $s_address2Text = $s_address2[0]->textContent();
        }

        $s_cityText = "";
        if ( count($s_firstName) > 0 and isset( $s_city[0] ) )
            $s_cityText = $s_city[0]->textContent();

        $s_stateText = "";
        if ( count($s_firstName) > 0 and isset( $s_state[0] ) )
            $s_stateText = $s_state[0]->textContent();

        $s_zipText = "";
        if ( count($s_firstName) > 0 and isset( $s_zip[0] ) )
            $s_zipText = $s_zip[0]->textContent();

        $s_countryText = "";
        if ( count($s_firstName) > 0 and isset( $s_country[0] ) )
            $s_countryText = $s_country[0]->textContent();

        $s_phoneText = "";
        if ( count($s_firstName) > 0 and isset( $s_phone[0] ) )
            $s_phoneText = $s_phone[0]->textContent();

        $s_emailText = "";
        if ( count($s_firstName) > 0 and isset( $s_email[0] ) )
            $s_emailText = $s_email[0]->textContent();

        // If order has a shipping country use it instead.
        if ( isset( $shippingText ) and $shippingText == 0  and $s_countryText != '' )
            $shipping_country = $s_countryText;
        else
            $shipping_country = $countryText;

        // Check for defered international shipping address cost calculation error by vendor.
        /* deprecated
        if ( isset( $shipping_country ) and $shipping_country !== 'USA' and $shipping_country !== 'CAN' )
        {
            $shippingTypeText = "2";
        }
        */
        /**
         * Coupon extension
         */
        $CouponNode = $dom->elementsByName( "coupon-code" );
        $CouponCode = "";
        if ( array_key_exists( 0, $CouponNode ) and is_object( $CouponNode[0] ) )
            $CouponCode = $CouponNode[0]->textContent();

        /*
         eZ Authorize - Support Display of Payment information
        */
        $s_ezauthorize_transaction_id = $dom->elementsByName( "ezauthorize-transaction-id" );
        $s_ezauthorize_card_name = $dom->elementsByName( "ezauthorize-card-name" );
        $s_ezauthorize_card_number = $dom->elementsByName( "ezauthorize-card-number" );
        $s_ezauthorize_card_date = $dom->elementsByName( "ezauthorize-card-date" );
        $s_ezauthorize_card_type = $dom->elementsByName( "ezauthorize-card-type" );

        if ( isset( $s_ezauthorize_transaction_id[0] ) ){
            $s_ezauthorize_transaction_idText = "";
            if ( $s_ezauthorize_transaction_id[0] != '' and isset( $s_ezauthorize_transaction_id[0] ) )
                $s_ezauthorize_transaction_idText = $s_ezauthorize_transaction_id[0]->textContent();

            $s_ezauthorize_card_nameText = "";
            if ( $s_ezauthorize_transaction_id[0] != '' and isset( $s_ezauthorize_card_name[0] ) )
                $s_ezauthorize_card_nameText = $s_ezauthorize_card_name[0]->textContent();

            $s_ezauthorize_card_numberText = "";
            if ( $s_ezauthorize_transaction_id[0] != '' and isset( $s_ezauthorize_card_number[0] ) )
                $s_ezauthorize_card_numberText = $s_ezauthorize_card_number[0]->textContent();

            $s_ezauthorize_card_dateText = "";
            if ( $s_ezauthorize_transaction_id[0] != '' and isset( $s_ezauthorize_card_date[0] ) )
                $s_ezauthorize_card_dateText = $s_ezauthorize_card_date[0]->textContent();

            $s_ezauthorize_card_typeText = "";
            if ( $s_ezauthorize_transaction_id[0] != '' and isset( $s_ezauthorize_card_type[0] ) )
                $s_ezauthorize_card_typeText = $s_ezauthorize_card_type[0]->textContent();
        }
        else
        {
            $s_ezauthorize_transaction_idText = "";
            $s_ezauthorize_card_nameText = "";
            $s_ezauthorize_card_numberText = "";
            $s_ezauthorize_card_dateText = "";
            $s_ezauthorize_card_typeText = "";
        }

        // print_r( $s_ezauthorize_transaction_id );
        // die( $s_ezauthorize_card_name[0]->textContent() .'<hr />' );

        return array( 'first_name' => $firstNameText,
                      'mi' => $miText,
                      'last_name' => $lastNameText,
                      'address1' => $address1Text,
                      'address2' => $address2Text,
                      'city' => $cityText,
                      'state' => $stateText,
                      'zip' => $zipText,
                      'country' => $countryText,
                      'phone' => $phoneText,
                      'email' => $emailText,
                      'shipping' => $shippingText,
                      'shippingtype' => $shippingTypeText,
                      's_first_name' => $s_firstNameText,
                      's_mi' => $s_miText,
                      's_last_name' => $s_lastNameText,
                      's_address1' => $s_address1Text,
                      's_address2' => $s_address2Text,
                      's_city' => $s_cityText,
                      's_state' => $s_stateText,
                      's_zip' => $s_zipText,
                      's_country' => $s_countryText,
                      's_phone' => $s_phoneText,
                      'coupon_code' => $CouponCode,
                      'ezauthorize_transaction_id' => $s_ezauthorize_transaction_idText,
                      'ezauthorize_card_name' => $s_ezauthorize_card_nameText,
                      'ezauthorize_card_number' => $s_ezauthorize_card_numberText,
                      'ezauthorize_card_date' => $s_ezauthorize_card_dateText,
                      'ezauthorize_card_type' => $s_ezauthorize_card_typeText,
                      's_email' => $s_emailText );
    }
}

?>
