<?php

//
// Definition of eZAuthorizeGateway class
//
// eZ publish payment gateway for Authorize.net
// implementing transparent credit card payment
// transactions in eZ publish using cURL.
//
// Created on: <01-Dec-2005 7:50:00 Dylan McDiarmid>
// Last Updated: <11-Dec-2005 01:49:35 Graham Brookins>
// Version: 1.0.0
//
// Copyright (C) 2001-2005 Brookins Consulting. All rights reserved.
//
// This source file is part of an extension for the eZ publish (tm)
// Open Source Content Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 (or greater) as published by
// the Free Software Foundation and appearing in the file LICENSE
// included in the packaging of this file.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html
//
// Contact licence@brookinsconsulting.com if any conditions
// of this licencing isn't clear to you.
//


/*!
  \class eZAuthorizeGateway ezauthorizegateway.php
  \brief eZAuthorizeGateway extends eZCurlGateway to provide a transparent
  Payment system through Authorize.Net using cURL.
*/

class eZAuthorizeGateway extends xrowEPaymentGateway
{
    const SHOW_FORM = 1;
    const DO_CURL = 2;
    
    const AUTOMATIC_STATUS = false;
    const GATEWAY_STRING = "eZAuthorize";

    /*!
     Constructor
    */
    function eZAuthorizeGateway()
    {
    }

    function _format_number( $str, $decimal_places = '2', $decimal_padding = "0" )
    {
        /* firstly format number and shorten any extra decimal places */
        /* Note this will round off the number pre-format $str if you dont want this fucntionality */
        $str = number_format( $str, $decimal_places, '.', '' ); // will return 12345.67
        $number = explode( '.', $str );
        $number[1] = ( isset( $number[1] ) ) ? $number[1] : ''; // to fix the PHP Notice error if str does not contain a decimal placing.
        $decimal = str_pad( $number[1], $decimal_places, $decimal_padding );
        return (float) $number[0] . '.' . $decimal;
    }

    function _loadAccountHandlerData( $order )
    {
        $ini = eZINI::instance( 'ezauthorize.ini' );
        
        $xmlDoc = $order->attribute( 'data_text_1' );
        eZDebug::writeDebug( $xmlDoc );
        $this->data = simplexml_load_string( $xmlDoc );
        if ( $ini->variable( 'eZAuthorizeSettings', 'StoreTransactionInformation' ) == true )
        {
            if ( isset( $this->data->{'ezauthorize-card-number'} ) )
            {
                $this->data->{'ezauthorize-card-number'} = $this->decryptData( $this->data->{'ezauthorize-card-number'} );
            }
            if ( isset( $this->data->{'ezauthorize-card-name'} ) )
            {
                $this->data->{'ezauthorize-card-name'} = $this->decryptData( $this->data->{'ezauthorize-card-name'} );
            }
            if ( isset( $this->data->{'ezauthorize-security-number'} ) )
            {
                $this->data->{'ezauthorize-security-number'} = $this->decryptData( $this->data->{'ezauthorize-security-number'} );
            }
        }
    }

    function _storeAccountHandlerData( $order )
    {
        $ini = eZINI::instance( 'ezauthorize.ini' );
        
        $data = $this->data;
        
        // If transaction storage is enabled
        if ( $ini->variable( 'eZAuthorizeSettings', 'StoreTransactionInformation' ) == true )
        {
            if ( isset( $this->data->{'ezauthorize-card-number'} ) )
            {
                $data->{'ezauthorize-card-number'} = $this->encryptData( $this->data->{'ezauthorize-card-number'} );
            }
            if ( isset( $this->data->{'ezauthorize-card-name'} ) )
            {
                $data->{'ezauthorize-card-name'} = $this->encryptData( $this->data->{'ezauthorize-card-name'} );
            }
            if ( isset( $this->data->{'ezauthorize-security-number'} ) )
            {
                $data->{'ezauthorize-security-number'} = $this->encryptData( $this->data->{'ezauthorize-security-number'} );
            }
        }

        eZDebug::writeDebug( $data->asXML(), 'asXML()' );
        $order->setAttribute( 'data_text_1', $data->asXML() );
        $order->store();
    }

    function execute( $process, $event )
    {
        $http = eZHTTPTool::instance();
        
        $processParameters = $process->attribute( 'parameter_list' );
        $processID = $process->attribute( 'id' );
        $orderID = $processParameters['order_id'];
        $order = eZOrder::fetch( $orderID );
        
        $xmlstring = $order->attribute( 'data_text_1' );
        if ( $xmlstring != null )
        {
            $doc = new DOMDocument( );
            $doc->loadXML( $xmlstring );
            $paymentmethod = $doc->getElementsByTagName( xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD )->item( 0 )->nodeValue;
            if ( ! isset( $paymentmethod ) )
            {
                $root = $doc->documentElement;
                $invoice = $doc->createElement( xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD, eZAuthorizeGateway::GATEWAY_STRING );
                $root->appendChild( $invoice );
                $order->setAttribute( 'data_text_1', $doc->saveXML() );
                $order->store();
            }
        }
        
        // if a form has been posted, we try and validate it.
        if ( $http->hasPostVariable( 'validate' ) )
        {
            $errors = $this->validateForm( $process );
            
            if ( ! $errors )
            {
                $process->setAttribute( 'event_state', eZAuthorizeGateway::DO_CURL );
                $this->storeHTTPInput( $order );
            }
            else
            {
                return $this->loadForm( $process, $errors );
            }
        }
        
        if ( $process->attribute( 'event_state' ) == eZAuthorizeGateway::SHOW_FORM )
        {
            // set the event state to do curl if we are not using a form
            if ( ! $this->useForm() )
            {
                $process->setAttribute( 'event_state', eZAuthorizeGateway::DO_CURL );
            }
        }
        
        switch ( $process->attribute( 'event_state' ) )
        {
            case eZAuthorizeGateway::SHOW_FORM:
                {
                    return $this->loadForm( $process );
                }
                break;
            case eZAuthorizeGateway::DO_CURL:
                {
                    return $this->doCURL( $process );
                }
                break;
        }
    }

    function storeHTTPInput( $order )
    {
        $xmlDoc = $order->attribute( 'data_text_1' );
        eZDebug::writeDebug( $xmlDoc );
        $this->data = simplexml_load_string( $xmlDoc );
        
        eZDebug::writeDebug( $_POST );
        $http = eZHTTPTool::instance();
        // assign shop account handeler payment values
        if ( is_object( $this->data ) )
        {
            $this->data->{'ezauthorize-card-name'} = trim( $http->postVariable( 'CardName' ) );
            $this->data->{'ezauthorize-card-number'} = trim( $http->postVariable( 'CardNumber' ) );
            $this->data->{'ezauthorize-card-date'} = trim( $http->postVariable( 'ExpirationMonth' ) ) . trim( $http->postVariable( 'ExpirationYear' ) );
            $this->data->{'ezauthorize-card-type'} = strtolower( $http->postVariable( 'CardType' ) );
            $this->data->{'ezauthorize-security-number'} = trim( $http->postVariable( 'SecurityNumber' ) );
            $this->_storeAccountHandlerData( $order );
        }
    }

    // override useForm() as false if you do not need to gather additional
    // user information before you send the curl information (although I can't
    // think of many situations in which this would happen, let me know if you
    // actually use it, otherwise I might just take it out.
    function useForm()
    {
        if ( eZSys::isShellExecution() )
            return false;
        else
            return true;
    }

    function loadForm( &$process, $errors = 0 )
    {
        $http = eZHTTPTool::instance();
        
        // get parameters
        $processParams = $process->attribute( 'parameter_list' );
        
        // load ini
        $ini = eZINI::instance( 'ezauthorize.ini' );
        
        // regen posted form values
        if ( $http->hasPostVariable( 'validate' ) and $ini->variable( 'eZAuthorizeSettings', 'RepostVariablesOnError' ) )
        {
            $tplVars['cardname'] = trim( $http->postVariable( 'CardName' ) );
            $tplVars['cardtype'] = strtolower( $http->postVariable( 'CardType' ) );
            $tplVars['cardnumber'] = $http->postVariable( 'CardNumber' );
            $tplVars['expirationmonth'] = $http->postVariable( 'ExpirationMonth' );
            $tplVars['expirationyear'] = $http->postVariable( 'ExpirationYear' );
            $tplVars['securitynumber'] = $http->postVariable( 'SecurityNumber' );
            $tplVars['amount'] = '';
        }
        else
        {
            // set form values to blank
            $tplVars['cardname'] = '';
            $tplVars['cardtype'] = '';
            $tplVars['cardnumber'] = '';
            $tplVars['expirationmonth'] = '';
            $tplVars['expirationyear'] = '';
            $tplVars['securitynumber'] = '';
            $tplVars['amount'] = '';
        }
        
        $tplVars['s_display_help'] = $ini->variable( 'eZAuthorizeSettings', 'DisplayHelp' );
        $tplVars['errors'] = $errors;
        $tplVars['order_id'] = $processParams['order_id'];
        
        $process->Template = array( 
            'templateName' => 'design:workflow/eventtype/result/' . 'ezauthorize_form.tpl' , 
            'templateVars' => $tplVars , 
            'path' => array( 
                array( 
                    'url' => false , 
                    'text' => 'Payment Information' 
                ) 
            ) 
        )
        ;
        return eZWorkflowEventType::STATUS_FETCH_TEMPLATE_REPEAT;
    }

    function validateForm( &$process )
    {
        $http = eZHTTPTool::instance();
        $errors = array();
        
        if ( trim( $http->postVariable( 'CardNumber' ) ) == '' )
        {
            $errors[] = 'You must enter a card number.';
        }
        elseif ( strlen( trim( $http->postVariable( 'CardNumber' ) ) ) > 49 )
        {
            $errors[] = 'Your card number should be under 50 characters.';
        }
        
        if ( trim( $http->postVariable( 'CardName' ) ) == '' )
        {
            $errors[] = 'You must enter a card name.';
        }
        elseif ( strlen( trim( $http->postVariable( 'CardName' ) ) ) > 79 )
        {
            $errors[] = 'Your card name should be under 80 characters.';
        }
        
        if ( trim( $http->postVariable( 'ExpirationMonth' ) ) == '' )
        {
            $errors[] = 'You must select an expiration month.';
        }
        
        if ( trim( $http->postVariable( 'ExpirationYear' ) ) == '' )
        {
            $errors[] = 'You must select an expiration year.';
        }
        
        return $errors;
    }

    /*
     * Builds URI and executes the Authorize.Net curl functions.
    */
    function furtherCharge( eZOrder $order, $amount = 0.00 )
    {
        // get totals in number format
        $order_total_amount = $this->_format_number( $amount );
        
        $response = $this->createDataForSendPayment( $order, $order_total_amount );
        
        if ( $response['aim']->hasError() or ! $response['md5pass'] )
        {
            return false;
        }
        else
        {
            $xmlstring = $order->attribute( 'data_text_1' );
            if ( $xmlstring != null )
            {
                $doc = new DOMDocument( );
                $doc->loadXML( $xmlstring );
                $transactions = $doc->getElementsByTagName( 'ezauthorize-transactions' );
                
                if ( $transactions->length > 0 )
                {
                    foreach ( $transactions as $domElement )
                    {
                        $transaction_child = $doc->createElement( 'transaction' );
                        $domElement->appendChild( $transaction_child );
                        
                        $transaction_attr1 = $doc->createAttribute( 'id' );
                        $transaction_child->appendChild( $transaction_attr1 );
                        
                        $id_text = $doc->createTextNode( $response['Transaction ID'] );
                        $transaction_attr1->appendChild( $id_text );
                        
                        $transaction_attr2 = $doc->createAttribute( 'amount' );
                        $transaction_child->appendChild( $transaction_attr2 );
                        
                        $amount_text = $doc->createTextNode( $order_total_amount );
                        $transaction_attr2->appendChild( $amount_text );
                    }
                    
                    $order->setAttribute( 'data_text_1', $doc->saveXML() );
                    $order->store();
                }
            }
            return true;
        }
    }

    /*
     * Builds URI and executes the Authorize.Net curl functions.
    */
    function doCURL( $process )
    {
        // make the order object
        $processParams = $process->attribute( 'parameter_list' );
        
        // get order id
        $order_id = $processParams['order_id'];
        
        // get order
        $order = eZOrder::fetch( $processParams['order_id'] );
        
        $order_total_amount_inc_vat = $order->attribute( 'total_inc_vat' );
        
        // get totals in number format
        $order_total_amount = $this->_format_number( $order_total_amount_inc_vat );
        
        $response = $this->createDataForSendPayment( $order, $order_total_amount );
        
        if ( $response['aim']->hasError() or ! $response['md5pass'] )
        {
            if ( ! $response['md5pass'] )
            {
                $errors[] = 'This transaction has failed to
                verify that the use of a secure transaction (MD5 Hash Failed).
                Please contact the site administrator and inform them of
                this error. Please do not try to resubmit payment.';
            }
            $errors[] = $response['Response Reason Text'];
            
            return $this->loadForm( $process, $errors );
        }
        else
        {
            ////////////////////////////////////////////////////
            // Original Authorize.net Payment Transaction Values
            

            // assign authorize.net transaction id from transaction responce array
            $this->data->{'ezauthorize-transactions'}->{'transaction'}['id'] = $response['Transaction ID'];
            $this->data->{'ezauthorize-transactions'}->{'transaction'}['amount'] = $order_total_amount;
            
            $this->_storeAccountHandlerData( $order );
            
            return eZWorkflowEventType::STATUS_ACCEPTED;
        }
    }

    function createDataForSendPayment( $order, $order_total_amount )
    {
        $this->_loadAccountHandlerData( $order );
        include_once ( 'kernel/classes/datatypes/ezuser/ezuser.php' );
        
        // load ini
        $ini = eZINI::instance( 'ezauthorize.ini' );
        
        // retrieve Status Codes
        $startStatusCode = $ini->variable( 'eZAuthorizeSettings', 'StartStatusCode' );
        $successStatusCode = $ini->variable( 'eZAuthorizeSettings', 'SuccessStatusCode' );
        $failStatusCode = $ini->variable( 'eZAuthorizeSettings', 'FailStatusCode' );
        
        // get user id
        $user_id = $order->UserID;
        
        // assign variables to Authorize.Net class from post
        $aim = new eZAuthorizeAIM( );
        
        // assign card name
        $aim->addField( 'x_card_name', (string)$this->data->{'ezauthorize-card-name'} );
        
        // assign card expiration date
        $aim->addField( 'x_exp_date', (string)$this->data->{'ezauthorize-card-date'} );
        
        // assign card number
        $aim->addField( 'x_card_num', (string)$this->data->{'ezauthorize-card-number'} );
        
        // check cvv2 code
        if ( $ini->variable( 'eZAuthorizeSettings', 'CustomerCVV2Check' ) == 'true' and $this->data['ezauthorize-security-number'] )
        {
            // assign card security number, cvv2 code
            $aim->addField( 'x_card_code', (string)$this->data->{'ezauthorize-security-number'} );
        }
        
        // get order customer information
        if ( $ini->variable( 'eZAuthorizeSettings', 'GetOrderCustomerInformation' ) == 'true' )
        {
            if ( $this->getOrderInfo( $order ) )
            {
                // Send customer billing address to authorize.net
                if ( $ini->variable( 'eZAuthorizeSettings', 'CustomerAddressVerification' ) == 'true' )
                {
                    $this->addAVS( $aim );
                }

                // Send customer shipping address to authorize.net
                if ( $ini->variable( 'eZAuthorizeSettings', 'SendCustomerShippingAddress' ) == 'true' )
                {
                    $this->addShipping( $aim );
                }
                
                // Send customer phone number (optional)
                $aim->addField( 'x_phone', $this->order_phone );
                
                // Send customer fax phone number (optional)
                $aim->addField( 'x_fax', $this->order_phone );
            }
        }
        // assign authorize.net invoice number
        

        // Provide authorize.net transaction with eZ publish order 'number'.
        // ps real order numbers do not exist in eZ publish until after payment
        // processing has been completed successfully so this is not possible by default.
        

        // or get actual order id (different number used in order view urls)
        $aim->addField( 'x_invoice_num', $order->ID );
        
        // assign authorize.net transaction description
        $aim->addField( 'x_description', 'Order ID #' . $order->ID );
        
        // assign customer IP
        if ( ! eZSys::isShellExecution() )
            $aim->addField( 'x_customer_ip', $_SERVER['REMOTE_ADDR'] );
            
        // assign customer id
        $aim->addField( 'x_cust_id', $user_id );
        
        // Send customer email address (default to true)
        $aim->addField( 'x_email', $this->order_email );
        
        // check send customer confirmation email
        if ( $ini->variable( 'eZAuthorizeSettings', 'CustomerConfirmationEmail' ) == 'true' )
        {
            // assign and send customer confirmation email
            $aim->addField( 'x_email_customer', 'TRUE' );
            
            $aim->addField( 'x_merchant_email', trim( $ini->variable( 'eZAuthorizeSettings', 'ShopAdminEmailAddress' ) ) );
        }
        
        // get currency code
        $currency_code = $ini->variable( 'eZAuthorizeSettings', 'CurrencyCode' );
        
        // assign currency code
        if ( $currency_code != '' )
        {
            $aim->addField( 'x_currency_code', $currency_code );
        }
        
        // assign total variables from order
        $aim->addField( 'x_amount', $order_total_amount );
        //$aim->addField( 'x_tax', $order_total_tax_amount );
        

        // assign merchant account information
        $aim->addField( 'x_login', $ini->variable( 'eZAuthorizeSettings', 'MerchantLogin' ) );
        $aim->addField( 'x_tran_key', $ini->variable( 'eZAuthorizeSettings', 'TransactionKey' ) );
        
        // send payment information to authorize.net
        $aim->sendPayment();
        $response = $aim->getResponse();
        
        ezDebug::writeDebug( $response, 'eZAuthorizeGateway response' );
        
        // Enable MD5Hash Verification
        if ( $ini->variable( 'eZAuthorizeSettings', 'MD5HashVerification' ) == 'true' )
        {
            $md5_hash_secret = $ini->variable( 'eZAuthorizeSettings', 'MD5HashSecretWord' );
            $aim->setMD5String( $md5_hash_secret, $ini->variable( 'eZAuthorizeSettings', 'MerchantLogin' ), $response['Transaction ID'], $order_total_amount );
            
            // Enable Optional Debug Output | MD5Hash Compare
            if ( $ini->variable( 'eZAuthorizeSettings', 'Debug' ) == 'true' )
            {
                ezDebug::writeDebug( 'Server md5 hash is ' . $response["MD5 Hash"] . ' and client hash is ' . strtoupper( md5( $aim->getMD5String ) ) . ' from string' . $aim->getMD5String );
            }
            $md5pass = $aim->verifyMD5Hash();
        }
        else
        {
            $md5pass = true;
        }
        $response['md5pass'] = $md5pass;
        $response['aim'] = $aim;
        
        return $response;
    }

    /*
    TODO:
    This function need fixes it uses hardcoded values from a shop account handler

    Workaround:
    set INI value eZAuthorizeSettings->GetOrderCustomerInformation = false
    */
    function getOrderInfo( $order )
    {
        // get order information out of XML
        $xml = simplexml_load_string( $order->attribute( 'data_text_1' ) );
        if ( $xml )
        {
            // assign shop account handeler values (now staticly)
	        $this->order_first_name = (string)$xml->{'first_name'};
	        $this->order_last_name = (string)$xml->{'last_name'};
	        $this->order_company = (string)$xml->{'company'};
	       	$this->order_email = (string)$xml->{'email'};
	        $this->order_street1 = (string)$xml->{'address1'};
	            
	        $this->order_fax = '';
	        $this->order_street2 = (string)$xml->{'address2'};
	        $this->order_zip = (string)$xml->{'zip'};
	        $this->order_place = (string)$xml->{'city'};
	        $this->order_state = (string)$xml->{'state'};
	        $this->order_country = (string)$xml->{'country'};
	        $this->order_comment = (string)$xml->{'comment'};
	        $this->order_phone = (string)$xml->{'phone'};
            
            return true;
        }
        return false;
    }

    function addAVS( &$aim )
    {
        // customer billing address
        $aim->addField( 'x_first_name', $this->order_first_name );
        $aim->addField( 'x_last_name', $this->order_last_name );
        $aim->addField( 'x_company', $this->order_company );
        $aim->addField( 'x_address', $this->order_street1 . ' ' . $this->order_street2 );
        $aim->addField( 'x_city', $this->order_place );
        $aim->addField( 'x_state', $this->order_state );
        $aim->addField( 'x_zip', $this->order_zip );
        $aim->addField( 'x_country', str_replace( " ", "%20", $this->order_country ) );
    
    }

    function addShipping( &$aim )
    {
        if ( (string)$this->data{'s_address1'} )
        {
            // customer shipping address
            $aim->addField( 'x_ship_to_first_name', (string)$this->data{'s_first-name'} );
            $aim->addField( 'x_ship_to_last_name', (string)$this->data{'s_last-name'} );
            $aim->addField( 'x_ship_to_company', (string)$this->data{'s_city'} );
            $aim->addField( 'x_ship_to_address', (string)$this->data{'s_address1'} . ' ' . (string)$this->data{'s_address2'} );
            $aim->addField( 'x_ship_to_city', (string)$this->data{'s_city'} );
            $aim->addField( 'x_ship_to_state', (string)$this->data{'s_state'} );
            $aim->addField( 'x_ship_to_zip', (string)$this->data{'s_zip'} );
            $aim->addField( 'x_ship_to_country', str_replace( " ", "%20", (string)$this->data{'s_country'} ) );
        }
        else
        {
            // customer billing address
            $aim->addField( 'x_ship_to_first_name', $this->order_first_name );
            $aim->addField( 'x_ship_to_last_name', $this->order_last_name );
            $aim->addField( 'x_ship_to_company', $this->order_company );
            $aim->addField( 'x_ship_to_address', $this->order_street1 . ' ' . $this->order_street2 );
            $aim->addField( 'x_ship_to_city', $this->order_place );
            $aim->addField( 'x_ship_to_state', $this->order_state );
            $aim->addField( 'x_ship_to_zip', $this->order_zip );
            $aim->addField( 'x_ship_to_country', str_replace( " ", "%20", $this->order_country ) );
        }
    }
}

xrowEPayment::registerGateway( eZAuthorizeGateway::GATEWAY_STRING, "ezauthorizegateway", "Authorize.Net" );

?>
