<?
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

include_once( 'lib/ezutils/classes/ezini.php' );
include_once( 'kernel/classes/ezorder.php' );
include_once( 'lib/ezxml/classes/ezxml.php' );

include_once( 'extension/ezauthorize/classes/ezauthorizeaim.php' );

class ezAuthorizeRefund
{
    var $ini;
    var $order; // ezOrder object
    var $order_account_info;
    var $OrderStatusRefundCode;
    var $OrderStatusAssignment;
    var $gpg_ini;
    var $KeyID;
    var $error;

    function ezAuthorizeRefund( $order_id )
    {
        $this->ini = &eZINI::instance( 'ezauthorize.ini' );
        $this->order = &eZOrder::fetch( $order_id );
        $this->order_account_info = $this->order->accountInformation();
        $this->order_total_amount = $this->order->productTotalIncVAT();

        $this->OrderStatusAssignment =  $this->ini->variable( 'eZAuthorizeSettings', 'OrderStatusAssignment' ) ? $this->ini->variable( 'eZAuthorizeSettings', 'OrderStatusAssignment' ) : false;
        $this->OrderStatusRefundCode = $this->ini->variable( 'eZAuthorizeSettings', 'OrderStatusRefundCode' );

        $this->gpg_ini = &eZINI::instance( 'ezgpg.ini' );
        $this->KeyID = $this->gpg_ini->variable( 'eZGPGSettings', 'KeyID' );
    }

    function SendRefund()
    {
        if( $this->order_account_info == null )
        {
            $this->error = "No order account information.";
            return false;
        }

        include_once( 'extension/ezgpg/autoloads/ezgpg_operators.php' );

        // get authorize.net transaction id
        $transaction_id = $this->order_account_info['ezauthorize_transaction_id'];

        // get last 4 digits of card number
        $card_number_encrypted = $this->order_account_info['ezauthorize_card_number'];

        $ez_gpg = new eZGPGOperators();
        $card_number_limited = $ez_gpg->gpgDecodeLimited( $card_number_encrypted, $this->KeyID );

        $this->aim = new eZAuthorizeAIM();
        $this->aim->addField( 'x_type', 'CREDIT' );

        // set authorize.net transaction id
        $this->aim->addField( 'x_trans_id', $transaction_id );

        // assign card number
        $this->aim->addField( 'x_card_num', $card_number_limited );

        // assign variables from order
        $this->aim->addField( 'x_amount', $this->order_total_amount );
        $this->aim->addField( 'x_login', $this->ini->variable( 'eZAuthorizeSettings', 'MerchantLogin' ) );
        $this->aim->addField( 'x_tran_key', $this->ini->variable( 'eZAuthorizeSettings', 'TransactionKey' ) );

        // send payment information to authorize.net
        $this->payment_response = $this->aim->sendPayment();
        $this->response = $this->aim->getResponse();

        if ( $this->aim->hasError() )
        {
            $this->error = $this->response["Response Reason Text"];
            return false;
        }

        // no error, switch assignment
        if( $this->OrderStatusAssignment )
        {
            $this->order->modifyStatus( $this->OrderStatusRefundCode );
        }
        return true;
    }

    function hasError()
    {
        if ( $this->error )
        {
            return true;
        }
        return false;
    }
}

?>