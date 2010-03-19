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

class eZAuthorizeGateway extends eZCurlGateway
{
    const GATEWAY_TYPE = "eZAuthorize";
    /*!
     Constructor
    */
    function eZAuthorizeGateway()
    {
    }
    function _format_number( $str, $decimal_places='2', $decimal_padding="0" )
    {
        /* firstly format number and shorten any extra decimal places */
        /* Note this will round off the number pre-format $str if you dont want this fucntionality */
        $str =  number_format( $str, $decimal_places, '.', '');    // will return 12345.67
        $number = explode( '.', $str );
        $number[1] = ( isset( $number[1] ) )?$number[1]:''; // to fix the PHP Notice error if str does not contain a decimal placing.
        $decimal = str_pad( $number[1], $decimal_places, $decimal_padding );
        return (float) $number[0].'.'.$decimal;
    }
    function _loadAccountHandlerData( &$process )
    {
        $ini = eZINI::instance( 'ezauthorize.ini' );
        $processParams = $process->attribute( 'parameter_list' );
        $order = eZOrder::fetch( $processParams['order_id'] );
        $xmlDoc = $order->attribute( 'data_text_1' );
		eZDebug::writeDebug($xmlDoc);
        $this->data = simplexml_load_string( $order->attribute( 'data_text_1' ) );
        if ( $ini->variable( 'eZAuthorizeSettings', 'StoreTransactionInformation', 'ezauthorize.ini') == true  )
        {
            if ( isset( $this->data->{'ezauthorize-card-number'} ) )
                $this->data->{'ezauthorize-card-number'} = $this->gpgDecode( $this->data->{'ezauthorize-card-number'} );
            if ( isset( $this->data->{'ezauthorize-card-name'} ) )
                $this->data->{'ezauthorize-card-name'} = $this->gpgDecode( $this->data->{'ezauthorize-card-name'} );
            if ( isset( $this->data->{'ezauthorize-security-number'} ) )
                $this->data->{'ezauthorize-security-number'} = $this->gpgDecode( $this->data->{'ezauthorize-security-number'} );
        }
    }
    function _storeAccountHandlerData( &$process )
    {
        $ini = eZINI::instance( 'ezauthorize.ini' );
        $processParams = $process->attribute( 'parameter_list' );
        $order = eZOrder::fetch( $processParams['order_id'] );
        
        $data = $this->data;
        
        // If transaction storage is enabled
        if ( $ini->variable( 'eZAuthorizeSettings', 'StoreTransactionInformation', 'ezauthorize.ini') == true  )
        {
            if ( isset( $this->data->{'ezauthorize-card-number'} ) )
                $data->{'ezauthorize-card-number'} = $this->gpgEncode( $this->data->{'ezauthorize-card-number'} );
            if ( isset( $this->data->{'ezauthorize-card-name'} ) )
                $data->{'ezauthorize-card-name'} = $this->gpgEncode( $this->data->{'ezauthorize-card-name'} );
            if ( isset( $this->data->{'ezauthorize-security-number'} ) )
                $data->{'ezauthorize-security-number'} = $this->gpgEncode( $this->data->{'ezauthorize-security-number'} );
        }

        eZDebug::writeDebug($data->asXML(),'asXML()');
        $order->setAttribute( 'data_text_1', $data->asXML() );
        $order->store();        
    }

    function storeHTTPInput( &$process )
    {
        $this->_loadAccountHandlerData( $process );
        eZDebug::writeDebug($_POST);
        $http = eZHTTPTool::instance();
        // assign shop account handeler payment values
        if( is_object( $this->data ) )
        {
            $this->data->{'ezauthorize-card-name'} = trim( $http->postVariable( 'CardName' ) );
            $this->data->{'ezauthorize-card-number'} = trim( $http->postVariable( 'CardNumber' ) );
            $this->data->{'ezauthorize-card-date'} = trim( $http->postVariable( 'ExpirationMonth' ) ) . trim( $http->postVariable( 'ExpirationYear' ) );
            $this->data->{'ezauthorize-card-type'} = strtolower( $http->postVariable( 'CardType' ) );
            $this->data->{'ezauthorize-security-number'} = trim( $http->postVariable( 'SecurityNumber' ) );
            $this->_storeAccountHandlerData( $process );
        }
    }
    function loadForm( &$process, $errors = 0 )
    {
        $http = eZHTTPTool::instance();

        // get parameters
        $processParams = $process->attribute( 'parameter_list' );

        // load ini
        $ini = eZINI::instance( 'ezauthorize.ini' );

        // regen posted form values
        if ( $http->hasPostVariable( 'validate' ) and
             $ini->variable( 'eZAuthorizeSettings', 'RepostVariablesOnError' ) )
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

        $process->Template=array
        (
            'templateName' => 'design:workflow/eventtype/result/' . 'ezauthorize_form.tpl',
            'templateVars' => $tplVars,
            'path' => array( array( 'url' => false,
                                    'text' =>  'Payment Information') ) 

        );
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
        elseif( strlen( trim( $http->postVariable( 'CardNumber' ) ) ) > 49 )
        {
            $errors[] = 'Your card number should be under 50 characters.';
        }

        if ( trim( $http->postVariable( 'CardName' ) ) == '' )
        {
            $errors[] = 'You must enter a card name.';
        }
        elseif( strlen( trim( $http->postVariable( 'CardName' ) ) ) > 79 )
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
    function doCURL( &$process )
    {
        $this->_loadAccountHandlerData( $process );
        include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );

        // load ini
        $ini = eZINI::instance( 'ezauthorize.ini' );

        // retrieve Status Codes
        $startStatusCode =  $ini->variable( 'eZAuthorizeSettings', 'StartStatusCode' );
        $successStatusCode =  $ini->variable( 'eZAuthorizeSettings', 'SuccessStatusCode' );
        $failStatusCode =  $ini->variable( 'eZAuthorizeSettings', 'FailStatusCode' );

        // make the order object
        $processParams = $process->attribute( 'parameter_list' );

        // get order id
        $order_id = $processParams['order_id'];

        // get order
        $order = eZOrder::fetch( $processParams['order_id'] );

        // get total order amount, including tax
        $order_total_amount = $order->attribute( 'total_inc_vat' );

        $order_total_tax_amount = $order->attribute( 'total_inc_vat' ) - $order->attribute( 'total_ex_vat' );

        // get totals in number format
        $order_total_amount = $this->_format_number( $order_total_amount );
        $order_total_tax_amount = $this->_format_number( $order_total_tax_amount );

        // get user id
        $user_id = $processParams['user_id'];

        // assign variables to Authorize.Net class from post
        $aim = new eZAuthorizeAIM();

        // assign card name
        $aim->addField( 'x_card_name', $this->data->{'ezauthorize-card-name'} );

        // assign card expiration date
        $aim->addField( 'x_exp_date', $this->data->{'ezauthorize-card-date'} );

        // assign card number
        $aim->addField( 'x_card_num', $this->data->{'ezauthorize-card-number'} );

        // check cvv2 code
        if ( $ini->variable( 'eZAuthorizeSettings', 'CustomerCVV2Check' ) == 'true' 
             and $this->data['ezauthorize-security-number'] )
        {
            // assign card security number, cvv2 code
            $aim->addField( 'x_card_code', $this->data->{'ezauthorize-security-number'} );
        }

        // get order customer information
        if ( $ini->variable( 'eZAuthorizeSettings', 'GetOrderCustomerInformation' ) == 'true' )
        {
            if ( $this->getOrderInfo( $order ) ) {

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
        if ( !eZSys::isShellExecution() )
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
        $currency_code =  $ini->variable( 'eZAuthorizeSettings', 'CurrencyCode' );

        // assign currency code
        if ( $currency_code != '' )
        {
            $aim->addField( 'x_currency_code', $currency_code );
        }

        // assign total variables from order
        $aim->addField( 'x_amount', $order_total_amount );
        $aim->addField( 'x_tax', $order_total_tax_amount );

        // assign merchant account information
        $aim->addField( 'x_login', $ini->variable( 'eZAuthorizeSettings', 'MerchantLogin' ) );
        $aim->addField( 'x_tran_key', $ini->variable( 'eZAuthorizeSettings', 'TransactionKey' ) );

        // set authorize.net mode
        $aim->setTestMode( $ini->variable( 'eZAuthorizeSettings', 'TestMode' ) == 'true' );

        // send payment information to authorize.net
        $aim->sendPayment();
        $response = $aim->getResponse();

        ezDebug::writeDebug( $response, 'eZAuthorizeGateway response'  );

        // Enable MD5Hash Verification
        if ( $ini->variable( 'eZAuthorizeSettings', 'MD5HashVerification' ) == 'true' )
        {
            $md5_hash_secret = $ini->variable( 'eZAuthorizeSettings', 'MD5HashSecretWord' );
            $aim->setMD5String ( $md5_hash_secret, $ini->variable( 'eZAuthorizeSettings', 'MerchantLogin' ), $response['Transaction ID'], $order_total_amount );

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

        if ( $aim->hasError() or !$md5pass)
        {
            if ( !$md5pass )
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
            $this->data['ezauthorize-transaction-id'] = $response['Transaction ID'];
            $this->_storeAccountHandlerData( $process );

            return eZWorkflowEventType::STATUS_ACCEPTED;
        }
    }

    /*
    TODO:
    This function need fixes it uses hardcoded values from a shop account handler

    Workaround:
    set INI value eZAuthorizeSettings->GetOrderCustomerInformation = false
    */
    function getOrderInfo( $order )
    {
        include_once( 'lib/ezxml/classes/ezxml.php' );

        // get order information out of XML
        $xml = simplexml_load_string( $order->attribute( 'data_text_1' ) );
        if( $xml )
        {
            // get shop account handeler map settings
            $ini = eZINI::instance( 'ezauthorize.ini' );

            // check for custom shop handeler settings
            if( $ini->variable( 'eZAuthorizeSettings', 'CustomShopAccountHandeler' ) )
            {
              // set shop account handeler values (dynamicaly)
              // add support for custom values supported like phone and email ...

              $handeler_name_first_name = $ini->variable( 'eZAuthorizeSettings', 'ShopAccountHandelerFirstName' );
              $handeler_name_last_name = $ini->variable( 'eZAuthorizeSettings', 'ShopAccountHandelerLastName' );
              $handeler_name_email = $ini->variable( 'eZAuthorizeSettings', 'ShopAccountHandelerEmail' );
              $handeler_name_street1 = $ini->variable( 'eZAuthorizeSettings', 'ShopAccountHandelerStreet1' );
              $handeler_name_street2 = $ini->variable( 'eZAuthorizeSettings', 'ShopAccountHandelerStreet2' );
              $handeler_name_zip = $ini->variable( 'eZAuthorizeSettings', 'ShopAccountHandelerZip' );
              $handeler_name_place = $ini->variable( 'eZAuthorizeSettings', 'ShopAccountHandelerFirstPlace' );
              $handeler_name_state = $ini->variable( 'eZAuthorizeSettings', 'ShopAccountHandelerFirstState' );
              $handeler_name_country = $ini->variable( 'eZAuthorizeSettings', 'ShopAccountHandelerCountry' );

              $handeler_name_comment = $ini->variable( 'eZAuthorizeSettings', 'ShopAccountHandelerComment' );
              $handeler_name_phone = $ini->variable( 'eZAuthorizeSettings', 'ShopAccountHandelerAddressPhone' );
              $handeler_name_fax = $ini->variable( 'eZAuthorizeSettings', 'ShopAccountHandelerAddressFax' );

            } else {
              $handeler_name_first_name = 'first-name';
              $handeler_name_last_name = 'last-name';
              $handeler_name_email = 'email';
              $handeler_name_street1 = 'street1';
              $handeler_name_street2 = 'street2';
              $handeler_name_zip = 'zip';
              $handeler_name_place = 'place';
              $handeler_name_state = 'state';
              $handeler_name_country = 'country';
              $handeler_name_comment = 'comment';
            }


            // assign shop account handeler values (now staticly)
            $this->order_first_name = $xml->{$handeler_name_first_name};

            $this->order_last_name = $xml->{ $handeler_name_last_name };

            $this->order_email = $xml->{ $handeler_name_email };

            $this->order_street1 = $xml->{ $handeler_name_street1 };

            $this->order_company = '';

            $this->order_phone = $xml->{ $handeler_name_phone };

            // $order_fax = $dom->elementsByName( $handeler_name_fax );
            // $this->order_fax = $order_fax[0]->textContent();
            $this->order_fax = '';

            $this->order_street2 = $xml->{ $handeler_name_street2 };

            $this->order_zip = $xml->{ $handeler_name_zip };

		    $this->order_place = $xml->{ $handeler_name_place };

            $this->order_state = $xml->{ $handeler_name_state };

	        $this->order_country = $xml->{ $handeler_name_country };

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

        // does this match the default?? cause it is wrong with shop account handeler usage !
        // $aim->addField( 'x_address', $this->order_street2 );
        //
        $aim->addField( 'x_address', $this->order_street1 .' '. $this->order_street2 );

        $aim->addField( 'x_city', $this->order_place );
        $aim->addField( 'x_state', $this->order_state );
        $aim->addField( 'x_zip', $this->order_zip );
        $aim->addField( 'x_country', str_replace( " ", "%20", $this->order_country ) );

    }

    function addShipping( &$aim )
    {
        if ( $this->data['s_address1'] )
        {
            // customer shipping address
            $aim->addField( 'x_ship_to_first_name', $this->data['s_first-name'] );
            $aim->addField( 'x_ship_to_last_name', $this->data['s_last-name'] );
            $aim->addField( 'x_ship_to_company', $this->data['s_city'] );
            $aim->addField( 'x_ship_to_address', $this->data['s_address1'] .' '. $this->data['s_address2'] );
            $aim->addField( 'x_ship_to_city', $this->data['s_city'] );
            $aim->addField( 'x_ship_to_state', $this->data['s_state'] );
            $aim->addField( 'x_ship_to_zip', $this->data['s_zip'] );
            $aim->addField( 'x_ship_to_country', str_replace( " ", "%20", $this->data['s_country'] ) );       
        }
        else
        {
            // customer billing address
            $aim->addField( 'x_ship_to_first_name', $this->order_first_name );
            $aim->addField( 'x_ship_to_last_name', $this->order_last_name );
            $aim->addField( 'x_ship_to_company', $this->order_company );

            // does this match the default?? cause it is wrong with shop account handeler usage !
            // $aim->addField( 'x_address', $this->order_street2 );
            //
            $aim->addField( 'x_ship_to_address', $this->order_street1 .' '. $this->order_street2 );

            $aim->addField( 'x_ship_to_city', $this->order_place );
            $aim->addField( 'x_ship_to_state', $this->order_state );
            $aim->addField( 'x_ship_to_zip', $this->order_zip );
            $aim->addField( 'x_ship_to_country', str_replace( " ", "%20", $this->order_country ) );
        }
     }
}

xrowEPayment::registerGateway( eZAuthorizeGateway::GATEWAY_TYPE, "ezauthorizegateway", "Authorize.Net" );

?>
