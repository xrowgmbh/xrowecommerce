<?php
//
// Definition of Customorderview class
//
// Created on: <01-Mar-2004 15:53:50 wy>
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

/*! \file customorderview.php
*/
$db =& eZDB::instance();
$email = $db->escapeString( $Params['email'] );
$module =& $Params['Module'];
include_once( "kernel/common/template.php" );
include_once( "kernel/classes/ezorder.php" );

$http =& eZHTTPTool::instance();
$tpl =& templateInit();



$userarray = $db->arrayQuery('SELECT distinct e.user_id, e.email FROM ezorder e
WHERE e.is_archived ="0" AND e.is_temporary ="0"
AND email = "'.$email.'"
ORDER BY e.email;');

if ( count($userarray) == 1)
{
    $user_id=$userarray[0]["user_id"];
    $Module->redirectTo( "/shop/customerorderview/$user_id/".$userarray[0]["email"] );
}
elseif ( count($userarray) >= 2 )
{
    $tpl->setVariable( "emails", "2" );
    $tpl->setVariable( "userid", $userarray );
    $tpl->setVariable( "email", $email );
}

elseif ( count($userarray) == 0 )
{
    $tpl->setVariable( "emails", "0" );
    $tpl->setVariable( "email", $email );
}

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/orderlist.tpl" );
$path = array();
$path[] = array( 'url' => '/shop/orderlist',
                 'text' => ezpI18n::tr( 'kernel/shop', 'Order list' ) );
$path[] = array( 'url' => false,
                 'text' => ezpI18n::tr( 'kernel/shop', 'Customer order view' ) );
$Result['path'] = $path;

?>
