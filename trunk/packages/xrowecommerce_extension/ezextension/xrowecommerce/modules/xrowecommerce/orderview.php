<?php
//
// Created on: <31-Jul-2002 16:49:13 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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

$OrderID = $Params['OrderID'];
$module = $Params['Module'];


$ini = eZINI::instance();
$http = eZHTTPTool::instance();
$user = eZUser::currentUser();
$access = false;
$order = eZOrder::fetch( $OrderID );
if ( !$order )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$accessToAdministrate = $user->hasAccessTo( 'shop', 'administrate' );
$accessToAdministrateWord = $accessToAdministrate['accessWord'];

$accessToBuy = $user->hasAccessTo( 'shop', 'buy' );
$accessToBuyWord = $accessToBuy['accessWord'];


$tpl = eZTemplate::factory();

$access = xrowECommerce::checkOrderAccess($module, $OrderID);
if ( !$access )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$tpl->setVariable( "order", $order );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/orderview.tpl" );
$Result['path'] = array( array( 'url' => 'shop/orderlist',
                                'text' => ezpI18n::tr( 'kernel/shop', 'Order list' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/shop', 'Order #%order_id', null, array( '%order_id' => $order->attribute( 'order_nr' ) ) ) ) );

?>
