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

require_once( 'extension/ezauthorize/classes/ezauthorizerefund.php' );

$module =& $Params['Module'];
$order_id = $Params['Order'];

if ( !is_numeric( $order_id ) )
{
    die('Invalid order id.');
}

$refund = new ezAuthorizeRefund( $Params['Order'] );

if ( !$refund->SendRefund() )
{
    // error case

    /*
    echo 'We have encountered an error while trying to process this refund.<br /><br />';
    echo 'Error Message: ' . $refund->error . '<br /><br />';
    */

    //for what got sent, uncomment next line
    //var_dump($refund->aim);

    // for what got returned, uncomment next line
    // print_r($refund->response);

    // Question, redirect to order view, order list or completed page?
    // $viewParameters = array( 'order_id' => $order_id );
    // $tpl->setVariable( 'view_parameters', $viewParameters );

    include_once( 'kernel/common/template.php' );
    $tpl =& templateInit();

    $r_response_code = $refund->response['Response Code'];
    $r_response_subcode = $refund->response['Response Subcode'];
    $r_response_reason_code = $refund->response['Response Reason Code'];

    $error_code = 'Transaction Error Code: '. $r_response_code .' / '. $r_response_subcode .' / '. $r_response_reason_code;

    $actual_error_message = trim( $refund->error);

    if( $r_response_reason_code == '54' ){
        $friendly_error_message = 'This is most often because the order payment has not yet been settled by the authorize.net payment gateway.<br />You may alternatly void the transaction via authorize.net pannel and change the status of your order manualy.';
    }
    else{
        $friendly_error_message = '';
    }

    $tpl->setVariable( 'friendly_error_message', $friendly_error_message );
    $tpl->setVariable( 'actual_error_message', $actual_error_message );
    $tpl->setVariable( 'error_code', $error_code );
    $tpl->setVariable( 'order_id', $order_id );

    // debug
    /*
    include_once( 'extension/ezdbug/autoloads/ezdbug.php' );
    $d = new eZDBugOperators();
    $d->ezdbugDump( $refund );
    // echo $actual_error_mesage;
    die();
    */

    $Result = array();

    $Result['path'] = array( array( 'url' => false,
                                    'text' => 'Order ' ),
                             array( 'url' => false,
                                    'text' => 'Refund Error' ) );

    $Result['content'] = $tpl->fetch( "design:refund/failure.tpl" );
}
else
{
    include_once( 'kernel/common/template.php' );
    $tpl =& templateInit();

    // Question, redirect to order view, order list or completed page?
    /*
    $viewParameters = array( 'order_id' => $order_id );
    $tpl->setVariable( 'view_parameters', $viewParameters );
    */

    $Result = array();
    $Result['path'] = array( array( 'url' => false,
                                    'text' => 'Order ' ),
                             array( 'url' => false,
                                    'text' => 'Refund Completed' ) );

    $Result['content'] = $tpl->fetch( "design:refund/completed.tpl" );

}

?>
