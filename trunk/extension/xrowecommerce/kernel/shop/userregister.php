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
if ( $user->isLoggedIn() )
{
    $userObject = $user->attribute( 'contentobject' );
    $userMap = $userObject->dataMap();
    $firstName = $userMap['first_name']->content();
    $lastName = $userMap['last_name']->content();
    $email = $user->attribute( 'email' );
}

// Initialize variables
$address1 = $address2 = $zip = $city = $state = $phone = $mi = '';


// Check if user has an earlier order, copy order info from that one
$orderList = eZOrder::activeByUserID( $user->attribute( 'contentobject_id' ) );
if ( count( $orderList ) > 0 and  $user->isLoggedIn() )
{
    $accountInfo = $orderList[0]->accountInformation();
    $address1 = $accountInfo['address1'];
    $address2 = $accountInfo['address2'];
    $zip = $accountInfo['zip'];
    $city = $accountInfo['city'];
    $state = $accountInfo['state'];
    $phone = $accountInfo['phone'];
    $mi = $accountInfo['mi'];
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
    if ( trim( $state ) == "" )
        $inputIsValid = false;
        
	$city = $http->postVariable( "City" );
    if ( trim( $city ) == "" )
        $inputIsValid = false;
        
    $zip = $http->postVariable( "Zip" );
    if ( trim( $zip ) == "" )
        $inputIsValid = false;
        
    $phone = $http->postVariable( "Phone" );
    if ( trim( $phone ) == "" )
        $inputIsValid = false;

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
        
        $zipNode = $doc->createElementNode( "zip" );
        $zipNode->appendChild( $doc->createTextNode( $zip ) );
        $root->appendChild( $zipNode );

        $stateNode = $doc->createElementNode( "state" );
        $stateNode->appendChild( $doc->createTextNode( $state ) );
        $root->appendChild( $stateNode );
        
        $phoneNode = $doc->createElementNode( "phone" );
        $phoneNode->appendChild( $doc->createTextNode( $phone ) );
        $root->appendChild( $phoneNode );
        
		$emailNode = $doc->createElementNode( "email" );
        $emailNode->appendChild( $doc->createTextNode( $email ) );
        $root->appendChild( $emailNode );



        $order->setAttribute( 'data_text_1', $doc->toString() );
        $order->setAttribute( 'account_identifier', "ez" );

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
$tpl->setVariable( "zip", $zip );
$tpl->setVariable( "city", $city );
$tpl->setVariable( "state", $state );
$tpl->setVariable( "phone", $phone );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/userregister.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/shop', 'Enter account information' ) ) );
?>