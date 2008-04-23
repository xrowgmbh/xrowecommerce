<?php
//
// Created on: <04-Mar-2003 10:22:42 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

$http =& eZHTTPTool::instance();
$module =& $Params["Module"];

include_once( 'kernel/common/template.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'kernel/classes/ezbasket.php' );
include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'lib/ezutils/classes/ezmail.php' );
include_once( 'extension/coupon/classes/xrowcoupon.php' );
$tpl =& templateInit();

if ( $module->isCurrentAction( 'Cancel' ) )
{
    $module->redirectTo( '/shop/basket/' );
    return;
}

$user =& eZUser::currentUser();

$firstName = '';
$lastName = '';
$email = '';
// Initialize variables
$shippingtype = $shipping = $s_email = $s_lastName = $s_firstName = $s_address1 = $s_address2 = $s_zip = $s_city = $s_state = $s_country = $s_phone = $s_mi = $address1 = $address2 = $zip = $city = $state = $country = $phone = $mi = '';
$userobject = $user->attribute( 'contentobject' );
if ( $user->isLoggedIn() and $userobject->attribute( 'class_identifier' ) == 'xrow_user' )
{
    $userObject = $user->attribute( 'contentobject' );
    $userMap = $userObject->dataMap();
    $firstName = $userMap['first_name']->content();
    $lastName = $userMap['last_name']->content();
    $mi = $userMap['mi']->content();
    $address1 = $userMap['address1']->content();
    $address2 = $userMap['address2']->content();
    $state = $userMap['state']->content();
    $zip = $userMap['zip_code']->content();
    $city = $userMap['city']->content();
    $country = $userMap['country']->content();
    $phone = $userMap['phone']->content();
    $shipping = $userMap['shippingaddress']->content();
    $shippingtype = $userMap['shippingtype']->content();
    $email = $user->attribute( 'email' );

    if ($shipping !="1")
    {
        $s_firstName = $userMap['s_first_name']->content();
        $s_lastName = $userMap['s_last_name']->content();
        $s_mi = $userMap['s_mi']->content();
        $s_address1 = $userMap['s_address1']->content();
        $s_address2 = $userMap['s_address2']->content();
        $s_state = $userMap['s_state']->content();
        $s_city = $userMap['s_city']->content();
        $s_zip = $userMap['s_zip_code']->content();
        $s_country = $userMap['s_country']->content();
        $s_phone = $userMap['s_phone']->content();
        $s_email = $userMap['s_email']->content();
    }
}


// Check if user has an earlier order, copy order info from that one
$orderList = eZOrder::activeByUserID( $user->attribute( 'contentobject_id' ) );
if ( count( $orderList ) > 0 and  $user->isLoggedIn() )
{
    $accountInfo = $orderList[0]->accountInformation();
}

$tpl->setVariable( "input_error", false );
if ( $module->isCurrentAction( 'Store' ) )
{
    $inputIsValid = true;

    $firstName = $http->postVariable( "FirstName" );
    if ( trim( $firstName ) == "" )
        $inputIsValid = false;

    $lastName = $http->postVariable( "LastName" );
    if ( trim( $lastName ) == "" )
        $inputIsValid = false;
    $mi = $http->postVariable( "MI" );

    $email = $http->postVariable( "EMail" );
    if ( ! eZMail::validate( $email ) )
        $inputIsValid = false;

    $address1 = $http->postVariable( "Address1" );
    $address2 = $http->postVariable( "Address2" );
    if ( trim( $address1 ) == "" )
        $inputIsValid = false;

    $state = $http->postVariable( "State" );
    /* if ( trim( $state ) == "" )
        $inputIsValid = false; */

    $city = $http->postVariable( "City" );
    if ( trim( $city ) == "" )
        $inputIsValid = false;

    $zip = $http->postVariable( "Zip" );
    if ( trim( $zip ) == "" )
        $inputIsValid = false;

    $country = $http->postVariable( "Country" );
    if ( trim( $country ) == "" )
        $inputIsValid = false;

    $phone = $http->postVariable( "Phone" );
    if ( trim( $phone ) == "" )
        $inputIsValid = false;

    $shipping = $http->postVariable( "Shipping" );
    $shippingtype = $http->postVariable( "ShippingType" );
    
    if ($shipping == "1" and $country !="USA" and $shippingtype <= "5" )
        $inputIsValid = false;
        
    if ($shipping == "1" and $country =="USA" and $shippingtype >= "6" )
        $inputIsValid = false;

    if ($shipping != "1")
    {
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

        $s_state = $http->postVariable( "s_State" );
        /* if ( trim( $s_state ) == "" )
            $inputIsValid = false; */

        $s_city = $http->postVariable( "s_City" );
        if ( trim( $s_city ) == "" )
            $inputIsValid = false;

        $s_zip = $http->postVariable( "s_Zip" );
        if ( trim( $s_zip ) == "" )
            $inputIsValid = false;

        $s_country = $http->postVariable( "s_Country" );
        if ( trim( $s_country ) == "" )
            $inputIsValid = false;

        $s_phone = $http->postVariable( "s_Phone" );
        if ( trim( $s_phone ) == "" )
            $inputIsValid = false;
            
        if ($s_country !="USA" and $shippingtype <= "5" )
            $inputIsValid = false;
        
        if ($s_country =="USA" and $shippingtype >= "6" )
            $inputIsValid = false;
    }
    $coupon = new xrowCoupon( $http->postVariable( "coupon_code" ) );
    $coupon_code = $coupon->code;
    
    if ( $inputIsValid == true )
    {
        // Check for validation
        $basket =& eZBasket::currentBasket();

        $db =& eZDB::instance();
        $db->begin();
        $order = $basket->createOrder();

        $doc = new eZDOMDocument( 'account_information' );

        $root = $doc->createElementNode( "shop_account" );
        $doc->setRoot( $root );

        $firstNameNode = $doc->createElementNode( "first-name" );
        $firstNameNode->appendChild( $doc->createTextNode( $firstName ) );
        $root->appendChild( $firstNameNode );

        $miNode = $doc->createElementNode( "mi" );
        $miNode->appendChild( $doc->createTextNode( $mi ) );
        $root->appendChild( $miNode );

        $lastNameNode = $doc->createElementNode( "last-name" );
        $lastNameNode->appendChild( $doc->createTextNode( $lastName ) );
        $root->appendChild( $lastNameNode );

        $address1Node = $doc->createElementNode( "address1" );
        $address1Node->appendChild( $doc->createTextNode( $address1 ) );
        $root->appendChild( $address1Node );

        $address2Node = $doc->createElementNode( "address2" );
        $address2Node->appendChild( $doc->createTextNode( $address2 ) );
        $root->appendChild( $address2Node );

        $cityNode = $doc->createElementNode( "city" );
        $cityNode->appendChild( $doc->createTextNode( $city ) );
        $root->appendChild( $cityNode );

        $stateNode = $doc->createElementNode( "state" );
        $stateNode->appendChild( $doc->createTextNode( $state ) );
        $root->appendChild( $stateNode );

        $zipNode = $doc->createElementNode( "zip" );
        $zipNode->appendChild( $doc->createTextNode( $zip ) );
        $root->appendChild( $zipNode );

        $countryNode = $doc->createElementNode( "country" );
        $countryNode->appendChild( $doc->createTextNode( $country ) );
        $root->appendChild( $countryNode );

        $phoneNode = $doc->createElementNode( "phone" );
        $phoneNode->appendChild( $doc->createTextNode( $phone ) );
        $root->appendChild( $phoneNode );

        $emailNode = $doc->createElementNode( "email" );
        $emailNode->appendChild( $doc->createTextNode( $email ) );
        $root->appendChild( $emailNode );

        $shippingNode = $doc->createElementNode( "shipping" );
        $shippingNode->appendChild( $doc->createTextNode( $shipping ) );
        $root->appendChild( $shippingNode );

        $shippingTypeNode = $doc->createElementNode( "shippingtype" );
        $shippingTypeNode->appendChild( $doc->createTextNode( $shippingtype ) );
        $root->appendChild( $shippingTypeNode );
        
        if ( $coupon_code )
        {
            $coupon_codeNode = $doc->createElementNode( "coupon_code" );
            $coupon_codeNode->appendChild( $doc->createTextNode( $coupon_code ) );
            $root->appendChild( $coupon_codeNode );
        }
        else
        {
            $coupon_codeNode = $doc->createElementNode( "coupon_code" );
            $coupon_codeNode->appendChild( $doc->createTextNode( '' ) );
            $root->appendChild( $coupon_codeNode );
        }
        if ($shipping != "1")
        {
            /* Shipping address*/

            $s_firstNameNode = $doc->createElementNode( "s_first-name" );
            $s_firstNameNode->appendChild( $doc->createTextNode( $s_firstName ) );
            $root->appendChild( $s_firstNameNode );

            $s_miNode = $doc->createElementNode( "s_mi" );
            $s_miNode->appendChild( $doc->createTextNode( $s_mi ) );
            $root->appendChild( $s_miNode );

            $s_lastNameNode = $doc->createElementNode( "s_last-name" );
            $s_lastNameNode->appendChild( $doc->createTextNode( $s_lastName ) );
            $root->appendChild( $s_lastNameNode );

            $s_address1Node = $doc->createElementNode( "s_address1" );
            $s_address1Node->appendChild( $doc->createTextNode( $s_address1 ) );
            $root->appendChild( $s_address1Node );

            $s_address2Node = $doc->createElementNode( "s_address2" );
            $s_address2Node->appendChild( $doc->createTextNode( $s_address2 ) );
            $root->appendChild( $s_address2Node );

            $s_cityNode = $doc->createElementNode( "s_city" );
            $s_cityNode->appendChild( $doc->createTextNode( $s_city ) );
            $root->appendChild( $s_cityNode );

            $s_stateNode = $doc->createElementNode( "s_state" );
            $s_stateNode->appendChild( $doc->createTextNode( $s_state ) );
            $root->appendChild( $s_stateNode );

            $s_zipNode = $doc->createElementNode( "s_zip" );
            $s_zipNode->appendChild( $doc->createTextNode( $s_zip ) );
            $root->appendChild( $s_zipNode );

            $s_countryNode = $doc->createElementNode( "s_country" );
            $s_countryNode->appendChild( $doc->createTextNode( $s_country ) );
            $root->appendChild( $s_countryNode );

            $s_phoneNode = $doc->createElementNode( "s_phone" );
            $s_phoneNode->appendChild( $doc->createTextNode( $s_phone ) );
            $root->appendChild( $s_phoneNode );

            $s_emailNode = $doc->createElementNode( "s_email" );
            $s_emailNode->appendChild( $doc->createTextNode( $s_email ) );
            $root->appendChild( $s_emailNode );

            /* Shipping address*/
        } /* Shippingaddress is equal or not */

        $order->setAttribute( 'data_text_1', $doc->toString() );
        $shopAccountINI =& eZINI::instance( 'shopaccount.ini' );
        
        $order->setAttribute( 'account_identifier', $shopAccountINI->variable( 'AccountSettings', 'Handler' ) );
 
        $order->setAttribute( 'ignore_vat', 0 );

        $order->store();
        $db->commit();

        eZHTTPTool::setSessionVariable( 'MyTemporaryOrderID', $order->attribute( 'id' ) );

        $module->redirectTo( '/shop/confirmorder/' );
        return;
    }
    else
    {
        $tpl->setVariable( "input_error", true );
    }
}

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
$tpl->setVariable( "shipping", $shipping );
$tpl->setVariable( "shippingtype", $shippingtype );


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

$tpl->setVariable( "coupon_code", $coupon_code );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/userregister.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/shop', 'Enter account information' ) ) );
?>