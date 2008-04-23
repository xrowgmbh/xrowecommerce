<?php
//
// Created on: <04-Jul-2002 13:19:43 bf>
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

include_once( "kernel/classes/ezcontentobject.php" );
include_once( "kernel/classes/ezbasket.php" );
include_once( "kernel/classes/ezvattype.php" );
include_once( "kernel/classes/ezorder.php" );
include_once( "kernel/classes/datatypes/ezuser/ezuser.php" );
include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
include_once( "kernel/classes/ezproductcollection.php" );
include_once( "kernel/classes/ezproductcollectionitem.php" );
include_once( "kernel/classes/ezproductcollectionitemoption.php" );
include_once( "kernel/common/template.php" );
include_once( 'lib/ezutils/classes/ezhttptool.php' );

$basket =& eZBasket::currentBasket();
$basket->updatePrices(); // Update the prices. Transaction not necessary.

if ( $http->hasPostVariable( "ActionAddToBasket" ) )
{
    if ( $http->hasPostVariable( "AddToBasketList" ) and is_array( $http->postVariable( "AddToBasketList" ) ) )
    {
        $list = array();
        foreach ( $http->postVariable( "AddToBasketList" ) as $position )
        {
            if ( $position['quantity'] > 0 and $position['object_id'] )
            {
                if ( !array_key_exists( 'variations', $position ) or
                     ( array_key_exists( 'variations', $position ) and !is_array( $position['variations'] ) ) )
                    $position['variations'] = array();
                $list[] = $position;
            }
        }
        if( $http->hasPostVariable( "RedirectURI" ) )
            $http->setSessionVariable( "FromPage", $http->postVariable( "RedirectURI" ) );
        else
            $http->setSessionVariable( "FromPage", 'shop/basket' );
        if ( count( $list ) == 0 )
        {
        	//error no product sumbitted
             $module->redirectTo( $_SERVER['HTTP_REFERER'] );
             return;
        }
    }
}
if ( count( $list ) > 0  )
{
	foreach ( $list as $position )
	{
        $operationResult = eZOperationHandler::execute( 'shop', 'addtobasket', array( 'basket_id' => $basket->attribute( 'id' ),
                                                                              'object_id' => $position['object_id'],
                                                                              'option_list' => $position['variations'],
                                                                              'quantity' => $position['quantity'] ) );
	}
    return $module->redirectTo( $http->sessionVariable( "FromPage" ) );
}

eZDebug::writeError("How did you get here?", "multiadd");
$module->redirectTo( 'shop/basket');
return;
?>