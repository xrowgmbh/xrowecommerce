<?php
/*!
    \class eZAuthorizeInfo ezauthorizeinfo.php
    \brief eZAuthorizeInfo provides validation methods for the ezcreditcardpayment datatype.

    used by the extension recurringorders
*/

class eZAuthorizeInfo
{
    function eZAuthorizeInfo()
    {
    }

    /*!
     \static
     returns true if a card can be used by this gateway
     This can be called like eZAuthorizeInfo::isCardAvailable( $type )
    */
    function isCardAvailable ( $type )
    {
        switch ( $type )
        {
            case ezcreditcardType::MASTERCARD:
            case ezcreditcardType::VISA:
            case ezcreditcardType::DISCOVER:
            case ezcreditcardType::AMERICANEXPRESS:
            {
                return true;
            }break;
            default: return false;
        }
    }

    /*!
     \static
     returns true if a card is valid and can be billed
     This can be called like eZAuthorizeInfo::validateCardData( $contentObjectAttribute, $classAttribute, $data )
    */
    function validateCardData( $contentObjectAttribute, $classAttribute, $data )
    {
        $error = false;
        if ( $data['name'] == '' )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Name on creditcard not given' ) );
            $error = true;
        }
        if ( !preg_match( "/^[0-9]+$/", $data['number'] )  )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Creditcard number is not a number' ) );
            $error = true;
        }

        if ( $data['type'] == ezcreditcardType::AMERICANEXPRESS )
            $maxDigits = 4;
        else
            $maxDigits = 3;
        if ( !preg_match( "/^[0-9]{3,$maxDigits}$/", $data['securitycode'] )  )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Please enter the correct CVV2 code.' ) );
            $error = true;
        }

        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $time = eZDateTime::create( -1, -1, -1, $data['month'], -1, $data['year'] );
        $now = new eZDateTime( false );
        if ( $now->isGreaterThan( $time ) )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Your creditcard is expired.' ) );
            $error = true;
        }

        if ( !$error )
        {
            // validate credit card using gateway
            $result = eZAuthorizeInfo::validateCard( $contentObjectAttribute, $classAttribute, $data );

            if ( $result == false )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'The creditcard was not accepted.
                                                                  Please check the creditcard data.' ) );
                $error = true;
            }
        }

        if ( $error )
            return false;
        else
            return true;
    }

    /*!
     \static
     Tries to reserve 1 EUR from the provided creditcard.
     If successfull it returns true, otherwise false.

     This can be called like eZAuthorizeInfo::validateCard( $data )
    */
    function validateCard( $contentObjectAttribute, $classAttribute, $data )
    {
        $ini = eZINI::instance( 'ezauthorize.ini' );
        include_once ( eZExtension::baseDirectory() . '/ezauthorize/classes/ezauthorizeaim.php' );

        include_once( 'lib/ezlocale/classes/ezdatetime.php' );
        $time = eZDateTime::create( -1, -1, -1, $data['month'], -1, $data['year'] );

        $error = false;

        // assign variables to Authorize.Net class from post
        $aim = new eZAuthorizeAIM();
        // assign transaction type
        $aim->addField( 'x_type', 'AUTH_ONLY' );
        // assign card name
        $aim->addField( 'x_card_name', $data['name'] );
        
        // assign card expiration date
        $aim->addField( 'x_exp_date', $time->month() . substr( $time->year(), 2, 2 ) );

        // assign card number
        $aim->addField( 'x_card_num', $data['number']  );

        // assign customer IP
        if ( !eZSys::isShellExecution() )
            $aim->addField( 'x_customer_ip', $_SERVER['REMOTE_ADDR'] );

        // check cvv2 code
        if ( $ini->variable( 'eZAuthorizeSettings', 'CustomerCVV2Check' ) == 'true' )
        {
            // assign card security number, cvv2 code
            // This may not work with every card if the if they issuing bank does not support verification
            $aim->addField( 'x_card_code', $data['securitycode'] );
        }
        $aim->addField( 'x_description', 'Authorization Check' );
        #$aim->addField( 'x_cust_id', eZUser::currentUserID() );

        $aim->addField( 'x_email_customer', 'false' );
        $aim->addField( 'x_email_merchant', 'false' );
        // get currency code
        $currency_code =  $ini->variable( 'eZAuthorizeSettings', 'CurrencyCode' );

        // assign currency code
        if ( $currency_code != '' )
        {
            $aim->addField( 'x_currency_code', $currency_code );
        }
        
        #$aim->addField( 'x_authentication_indicator', '7' );
        $aim->addField( 'x_invoice_num', uniqid( "validate_" ) );
        // assign total variables from order $ 1 transaction
       
        $aim->addField( 'x_amount', '1.00' );
        // assign merchant account information
        $aim->addField( 'x_login', $ini->variable( 'eZAuthorizeSettings', 'MerchantLogin' ) );
        $aim->addField( 'x_tran_key', $ini->variable( 'eZAuthorizeSettings', 'TransactionKey' ) );

        // send payment information to authorize.net
        $aim->sendPayment();
        $response = $aim->getResponse();

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

        eZDebug::writeDebug( $response, 'eZAuthorizeGateway response'  );
        if ( $aim->hasError() or !$md5pass )
        {
            if ( !$md5pass )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'This transaction has failed to
                verify that the use of a secure transaction (MD5 Hash Failed).
                Please contact the site administrator and inform them of
                this error. Please do not try to resubmit payment.' ) );
            }
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', $response['Response Reason Text'] ) );
            $error = true;
        }

        if ( $error )
            return false;
        else
            return true;
    }

}

?>