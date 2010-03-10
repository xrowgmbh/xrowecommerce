<?php
//
// Definition of eZAuthorizeAIM class
//
// Authorize.Net AIM cURL class
//
// Created on: <01-Dec-2005 8:30:00 Dylan McDiarmid>
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

class eZAuthorizeAIM
{
    /*!
    * Constructor
    * Sets a few fields statically (x_delim_char, x_version, x_delim_date
    * x_relay_response, x_method, x_type). These can all be overridden
    */
    function eZAuthorizeAIM( $aSetup = false )
    {
        $this->addField( 'x_delim_char', $this->delim_char );
        $this->addField( 'x_version', '3.1' );
        $this->addField( 'x_delim_data', 'true' );
        $this->addField( 'x_relay_response', 'false' );

        // assign payment method
        $this->addField( 'x_method', 'CC' );

        // assign transaction type
        $this->addField( 'x_type', 'AUTH_CAPTURE' );

        if ( $aSetup )
        {
          foreach( $aSetup as $sKey => $sVal )
          {
            $this->addField( $sKey, $sVal );
          }
        }
    }

    /*!
    * Adds a field.
    */
    function addField( $sField, $sVal )
    {
        $this->fields[$sField] = $sVal;
    }

    /*!
    * Sets the character used to delimitate the response.
    */
    function setDelim( $char )
    {
        $this->delim_char = $char;
    }

    /*!
    * Returns the fields array.
    */
    function getFields()
    {
        return $this->fields;
    }

    /*!
    * Returns url string with all fields.
    */
    function getFieldString()
    {
        $url = '';

        foreach( $this->fields as $sField => $sVal )
        {
          $url .= $sField . '=' . $sVal . "&";
        }

        return chop( $url, "&" );
    }

    /*!
    * Turns test mode on or off.
    */
    function setTestMode( $bool )
    {
        $this->test_mode = $bool;
        if ($bool) {
            $this->addField('x_test_request', 'TRUE');
        } else {
            $this->addField('x_test_request', 'FALSE');
        }

    }

    /*!
    * Returns the response array
    */
    function getResponse()
    {
        return $this->response;
    }

    /*!
    * @private
    * Used from the sendPayment method to explode the response string into
    * a labelled array. From the AIM 3.1 documentation.
    * Most of the code in this method was taken directly from Micah Carrick's class
    */
    function _responseToArray()
    {
        $temp_values = explode( $this->delim_char, $this->response_string );

        $temp_keys = array
        (
         'Response Code',
         'Response Subcode',
         'Response Reason Code',
         'Response Reason Text',
         'Approval Code',
         'AVS Result Code',
         'Transaction ID',
         'Invoice Number',
         'Description',
         'Amount',
         'Method',
         'Transaction Type',
         'Customer ID',
         'Cardholder First Name',
         'Cardholder Last Name',
         'Company',
         'Billing Address',
         'City',
         'State',
         'Zip',
         'Country',
         'Phone',
         'Fax',
         'Email',
         'Ship to First Name',
         'Ship to Last Name',
         'Ship to Company',
         'Ship to Address',
         'Ship to City',
         'Ship to State',
         'Ship to Zip',
         'Ship to Country',
         'Tax Amount',
         'Duty Amount',
         'Freight Amount',
         'Tax Exempt Flag',
         'PO Number',
         'MD5 Hash',
         'Card Code (CVV2/CVC2/CID) Response Code',
         'Cardholder Authentication Verification Value (CAVV) Response Code'
         );

        // add additional keys for reserved fields and merchant defined fields
        for( $i = 0; $i <= 27; $i++ )
        {
            array_push( $temp_keys, 'Reserved Field ' . $i );
        }

        $i=0;

        while( sizeof( $temp_keys ) < sizeof( $temp_values ) )
        {
            array_push( $temp_keys, 'Merchant Defined Field ' . $i );
            $i++;
         }

        // combine the keys and values arrays into the $response array.
        for( $i = 0; $i < sizeof( $temp_values ); $i++ )
        {
            $this->response["$temp_keys[$i]"] = $temp_values[$i];
        }

        return $this->response;
    }

    /*!
    * sendPayment will attempt to send the fields to Authorize.net.
    * A response code will be returned.
    */
    function sendPayment()
    {
        $ini = eZINI::instance( 'ezauthorize.ini' );
        $ch=curl_init();

        if ( $this->test_mode )
        {
          curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        }

        curl_setopt( $ch, CURLOPT_URL, $this->transact_url );

        curl_setopt( $ch, CURLOPT_HEADER, 0 );
        curl_setopt( $ch, CURLOPT_VERBOSE, 0 );
        curl_setopt( $ch, CURLOPT_POST, 1 );

        // Fix for curl version > 7.1 with require CA cert by default.
        // For better security implement a cacert bundle
        $file = eZSys::rootDir() . '/'  . eZExtension::baseDirectory() . '/ezauthorize/cacert.pem';
        if ( $ini->variable( 'eZAuthorizeSettings', 'SSLVerify' ) == 'true'
             and file_exists( $file ) )
        {
            curl_setopt ($ch, CURLOPT_CAINFO, $file );
        }
        else
        {
            curl_setopt ($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        }
        $fields = $this->getFieldString();
        eZDebug::writeDebug( $fields , 'Request' );
        curl_setopt( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $fields );

        $this->response_string=urldecode( curl_exec( $ch ) );
        eZDebug::writeDebug( $this->response_string , 'Response' );
        if ( curl_errno( $ch ) )
        {
            $this->response['Response Reason Text'] = "CURL Error: " . curl_error( $ch );
            $this->response['Response Code'] = 3;
            return 3;
        }
        else
        {
            curl_close( $ch );
        }

        $this->_responseToArray();
        return $this->response['Response Code'];
    }

    /*!
    * Returns false only if the response code comes back
    * as a valid transaction, or is being held for review.
    */
    function hasError()
    {
        // response code
        $response_code = $this->response['Response Code'];
        if( in_array( $this->response['Response Reason Code'], array( 10, 2, 0 ) ) and $this->test_mode )
        {
            return false;
        }
        if ( $response_code != 1 and $response_code != 4 )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /*!
       Set MD5 String variable
    */
    function setMD5String( $hash_value, $user_id, $transaction_id, $amount )
    {
        $this->md5String = $hash_value . $user_id . $transaction_id . $amount;
    }

    /*!
        Get MD5 String (client). Use for debug.
    */
    function getMD5String()
    {
        return $this->md5String;
    }

    /*!
       AuthorizeNet Validate MD5 Hash
    */
    function verifyMD5Hash() {
        if ( !isset( $this->md5String ) )
            return false;

        if ( $this->response['MD5 Hash'] == '' )
            return false;

        if ( strtoupper(md5( $this->md5String )) == $this->response['MD5 Hash'] )
            return true;

        return false;
    }

    /*!
       AuthorizeNet CVS Response Text
    */
    function getCardCodeResponseText ( $cvs_code ) {
        // if ($this->debug) echo "CVS Code: $cvs_code\n";

        switch( $cvs_code ) {
        case "M":
            return "Match";
            break;
        case "N":
            return "No Match";
            break;
        case "P":
            return "Not Processed";
            break;
        case "S":
            return "Should Have Been Present";
            break;
        case "U":
            return "Issuer Unable To Process Request";
            break;
        }
    }

    /*!
     AuthorizeNet AVS Response Text
    */
    function getAVSResponseText ( $avs_code ) {
        // if ( $this->debug ) echo "AVS Code: $avs_code\n";

        switch( $avs_code ) {
        case "A":
            return "Address (Street) Matches, ZIP does not.";
            break;
        case "B":
            return "Address information not provided for AVS check.";
            break;
        case "E":
            return "AVS Error.";
            break;
        case "G":
            return "Non-US Card Issuing Bank.";
            break;
        case "N":
            return "No Match on Address (Street) or ZIP.";
            break;
        case "P":
            return "AVS not applicable for this transaction.";
            break;
        case "R":
            return "Retry - System unavailable or timed out.";
            break;
        case "S":
            return "Service not supported by issuer.";
            break;
        case "U":
            return "Address information is unavailable.";
            break;
        case "W":
            return "9 digit ZIP Matches, Address (Street) does not.";
            break;
        case "X":
            return "Address (Street) and 9 digit ZIP match.";
            break;
        case "Y":
            return "Address (Street) and 5 digit ZIP match.";
            break;
        case "Z":
            return "5 digit ZIP matches, Address (Street) does not.";
            break;
        }
    }

    var $transact_url = 'https://secure.authorize.net/gateway/transact.dll';
    var $delim_char = '|';
    var $test_mode = 0;

    var $debug = false;
    var $email_header = false;
    var $email_footer = false;

    var $hash = false;
    var $user_id = false;
    var $transaction_id = false;
    var $amount = false;

}

?>
