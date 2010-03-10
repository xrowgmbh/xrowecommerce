<?php
//
// Created on: <12-Jan-2007 12:57:54 gb>
//
// Copyright (C) 2001-2006 Brookins Consulting. All rights reserved.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 or greater as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@brookinsconsulting.com if any conditions of
// this licencing isn't clear to you.
//

$Module = array( 'name' => 'refund',
                 'variable_params' => true );

$ViewList = array();
$ViewList['refund'] = array(
                          'script' => 'order.php',
                          "default_navigation_part" => 'ezshopnavigationpart',
                          "unordered_params" => array( 'order' => 'Order' ) );

/*
$Module = array( 'name' => 'refund' );

$ViewList["refund"] = array(
                               "functions" => array( 'refund' ),
                               "script" => "order.php",
                               "default_navigation_part" => 'ezshopnavigationpart',
                               "params" => array( "OrderID" ) );
*/

?>
