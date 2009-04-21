<?php

/*!
    \class xrowComDirectInfo xrowcomdirectinfo.php
    \brief xrowComDirectInfo provides validation methods for the ezcreditcardpayment datatype.

    used by the extension recurringorders
*/
class xrowComDirectInfo
{

    function xrowComDirectInfo()
    {
    }

    /*!
     \static
     returns true if a card can be used by this gateway
     This can be called like xrowComDirectInfo::isCardAvailable( $type )
    */
    function isCardAvailable( $type )
    {
        switch ( $type )
        {
            case XROWCREDITCARD_TYPE_MASTERCARD:
            case XROWCREDITCARD_TYPE_VISA:
            case XROWCREDITCARD_TYPE_DISCOVER:
            case XROWCREDITCARD_TYPE_AMERICANEXPRESS:
            case XROWCREDITCARD_TYPE_EUROCARD:
                {
                    return true;
                }
                break;
            default:
                return false;
        }
    }

    /*!
     \static
     returns true if a card is valid and can be billed
     This can be called like xrowComDirectInfo::validateCardData( $contentObjectAttribute, $classAttribute, $data )
    */
    function validateCardData( &$contentObjectAttribute, &$classAttribute, $data )
    {
        switch ( $data['type'] )
        {
            case XROWCREDITCARD_TYPE_MASTERCARD:
            case XROWCREDITCARD_TYPE_VISA:
            case XROWCREDITCARD_TYPE_DISCOVER:
            case XROWCREDITCARD_TYPE_AMERICANEXPRESS:
                {
                    return xrowComDirectInfo::validateCCData( $contentObjectAttribute, $classAttribute, $data );
                }
                break;
            
            case XROWCREDITCARD_TYPE_EUROCARD:
                {
                    return xrowComDirectInfo::validateECData( $contentObjectAttribute, $classAttribute, $data );
                }
                break;
            
            default:
                return false;
        }
    }

    function validateECData( &$contentObjectAttribute, &$classAttribute, $data )
    {
        // validate eurocard
        if ( strlen( $data['ecname'] ) == 0 )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'Please enter the name of the bank account.' ) );
            return false;
        }
        if ( ! preg_match( "/^[0-9]{1,10}$/", $data['accountnumber'] ) )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'Please enter your correct account number (max. 10 numbers)' ) );
            return false;
        }
        if ( ! preg_match( "/^[0-9]{8}$/", $data['bankcode'] ) )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'Please enter your correct bank code (8 numbers)' ) );
            return false;
        }
        
        $ecINI = & eZINI::instance( 'xrowcomdirect.ini' );
        $testMode = $ecINI->variable( 'ServerSettings', 'TestMode' ) == 'enabled' ? true : false;
        
        if ( ! $testMode )
        {
            if ( xrowComDirectInfo::validateECCard( $contentObjectAttribute, $classAttribute, $data ) == false )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'The account number or the bank code was not accepted.
                                                                  Please check the eurocard data.' ) );
                return false;
            }
        }
        else
        {
            eZDebug::writeDebug( 'Test mode enabled.', 'xrowComDirectInfo::validateCardData()' );
        }
        return true;
    }

    function validateCCData( &$contentObjectAttribute, &$classAttribute, $data )
    {
        if ( $data['name'] == '' )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'Please enter a name' ) );
            return false;
        }
        if ( ! preg_match( "/^[0-9]{1,19}$/", $data['number'] ) )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'Creditcard number is not a number' ) );
            return false;
        }
        
        if ( $data['type'] == XROWCREDITCARD_TYPE_AMERICANEXPRESS )
            $maxDigits = 4;
        else
            $maxDigits = 3;
        if ( ! preg_match( "/^[0-9]{3,$maxDigits}$/", $data['securitycode'] ) )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'Please enter the correct CVV2 code.' ) );
            return false;
        }
        
        include_once ( 'lib/ezlocale/classes/ezdatetime.php' );
        $time = eZDateTime::create( - 1, - 1, - 1, $data['month'], - 1, $data['year'] );
        $now = new eZDateTime( false );
        if ( $now->isGreaterThan( $time ) )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'Your creditcard is expired.' ) );
            return false;
        }
        
        $ecINI = & eZINI::instance( 'xrowcomdirect.ini' );
        $testMode = $ecINI->variable( 'ServerSettings', 'TestMode' ) == 'enabled' ? true : false;
        
        if ( ! $testMode )
        {
            // validate credit card using xrowcomdirect gateway
            if ( xrowComDirectInfo::validateCreditCard( $contentObjectAttribute, $classAttribute, $data ) == false )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'The creditcard was not accepted.
                                                                  Please check the creditcard data.' ) );
                return false;
            }
        }
        else
            eZDebug::writeDebug( 'Test mode enabled.', 'xrowComDirectInfo::validateCCData()' );
        
        return true;
    }

    /*!
     \static
     Tries to reserve 1 EUR from the provided card.
     If successfull it returns true, otherwise false.

     This can be called like xrowComDirectInfo::validateCard( $contentObjectAttribute, $classAttribute, $data )
    */
    function validateECCard( &$contentObjectAttribute, &$classAttribute, $data )
    {
        require_once ( 'Net/Curl.php' );
        
        $shopINI = eZINI::instance( 'shop.ini' );
        $xrowcomdirectINI = eZINI::instance( 'xrowcomdirect.ini' );
        
        $user = $xrowcomdirectINI->variable( 'ServerSettings', 'Username' );
        $password = $xrowcomdirectINI->variable( 'ServerSettings', 'Password' );
        $serverLink = $xrowcomdirectINI->variable( 'ServerSettings', 'ServerRequestLink' );
        $requestTimeout = $xrowcomdirectINI->variable( 'ServerSettings', 'RequestTimeout' );
        
        // 1 EUR
        $amount = 100;
        
        //allowed charset is latin-1
        $codepage = eZCodePage::instance( "iso-8859-1" );
        
        $cname = $data['ecname'];
        $cname = $codepage->convertStringFromUTF8( $cname );
        //$cname = utf8_decode ( $cname );
        

        // only EUR is supported
        $currencyCode = 'EUR';
        
        $bankcode = $data['bankcode'];
        $account = $data['accountnumber'];
        
        $command = 'preauthorization';
        
        $id = $contentObjectAttribute->attribute( "contentobject_id" );
        $orderID = $id . 't' . time();
        
        $fields = array( 
            'amount' => $amount , 
            'cname' => $cname , 
            'bankcode' => $bankcode , 
            'account' => $account , 
            'orderid' => $orderID , 
            'command' => $command , 
            'currency' => $currencyCode 
        );
        
        $curl = & new Net_Curl( $serverLink );
        $curl->username = $user;
        $curl->password = $password;
        $curl->type = 'post';
        $curl->fields = $fields;
        $curl->mute = true;
        $curl->timeout = 30;
        
        $file = eZSys::rootDir() . '/' . eZExtension::baseDirectory() . '/xrowcomdirect/cacert.pem';
        if ( ! file_exists( $file ) )
            return false;
        $result = $curl->create();
        
        if ( ! PEAR::isError( $result ) )
        {
            // Set other options here with Net_Curl::setOption()
            $curl->caInfo = $file;
            $curl->verbose = true;
            $curl->verifyPeer = true;
            $this->verifyHost = 2;
            
            $result = $curl->execute();
            
            if ( ! PEAR::isError( $result ) )
            {
                //eZDebug::writeDebug ( $result, 'Payment server ' . $command . ' answer' );
                $resultArray = explode( '&', $result );
                $serverAnswer = array();
                foreach ( $resultArray as $key => $item )
                {
                    $dummy = explode( '=', $item, 2 );
                    if ( count( $dummy ) == 2 )
                        $serverAnswer[urldecode( $dummy[0] )] = urldecode( $dummy[1] );
                }
                
                $res = array();
                $res['servercode'] = '';
                if ( isset( $serverAnswer['rc'] ) )
                    $res['servercode'] = $serverAnswer['rc'];
                
                $res['transactionid'] = '';
                if ( isset( $serverAnswer['trefnum'] ) )
                    $res['transactionid'] = $serverAnswer['trefnum'];
                
                $res['servermsg'] = '';
                if ( isset( $serverAnswer['rmsg'] ) )
                    $res['servermsg'] = $codepage->convertString( $serverAnswer['rmsg'] );
                
                if ( $res['servercode'] === '000' )
                {
                    // card is approved
                    eZDebug::writeDebug( 'Card accepted: ' . $res['servermsg'], 'Card validation' );
                    $curl->close();
                    return true;
                }
                else
                {
                    // card isn't approved
                    eZDebug::writeDebug( 'Card rejected, reason: ' . $res['servermsg'], 'Card validation' );
                }
            }
            else
            {
                eZDebug::writeError( $result->getMessage(), 'curl error' );
            }
            
            $curl->close();
        }
        else
        {
            eZDebug::writeError( $result->getMessage(), 'curl error' );
        }
        return false;
    }

    /*!
     \static
     Tries to reserve 1 EUR from the provided card.
     If successfull it returns true, otherwise false.

     This can be called like xrowComDirectInfo::validateCreditCard( $contentObjectAttribute, $classAttribute, $data )
    */
    function validateCreditCard( &$contentObjectAttribute, &$classAttribute, $data )
    {
        include_once ( eZExtension::baseDirectory() . '/ezpowerlib/ezpowerlib.php' );
        require_once ( 'Net/Curl.php' );
        include_once ( 'lib/ezi18n/classes/ezutf8codec.php' );
        include_once ( 'lib/ezi18n/classes/ezcodepage.php' );
        
        $shopINI = eZINI::instance( 'shop.ini' );
        $xrowcomdirectINI = eZINI::instance( 'xrowcomdirect.ini' );
        
        $user = $xrowcomdirectINI->variable( 'ServerSettings', 'Username' );
        $password = $xrowcomdirectINI->variable( 'ServerSettings', 'Password' );
        $serverLink = $xrowcomdirectINI->variable( 'ServerSettings', 'ServerRequestLink' );
        $requestTimeout = $xrowcomdirectINI->variable( 'ServerSettings', 'RequestTimeout' );
        
        // allowed charset latin-1
        $codepage = eZCodePage::instance( "iso-8859-1" );
        
        // 1 EUR
        $amount = 100;
        
        $shopINI = & eZINI::instance( 'shop.ini' );
        $currencyCode = $shopINI->variable( 'CurrencySettings', 'PreferredCurrency' );
        
        $creditc = $data['number'];
        $cvcode = $data['securitycode'];
        
        $year = '';
        if ( strlen( $data['year'] ) == 4 )
            $year = substr( $data['year'], 2, 2 );
        else 
            if ( strlen( $data['year'] ) == 2 )
                $year = $data['year'];
        
        $expdat = $year . XROWRecurringordersCommonFunctions::addLeadingZero( $data['month'] );
        
        $command = 'preauthorization';
        
        $id = $contentObjectAttribute->attribute( "contentobject_id" );
        $orderID = $id . 't' . time();
        
        $fields = array( 
            'creditc' => $creditc , 
            'cvcode' => $cvcode , 
            'expdat' => $expdat , 
            'orderid' => $orderID , 
            'command' => $command , 
            'currency' => $currencyCode , 
            'amount' => $amount 
        );
        
        $curl = & new Net_Curl( $serverLink );
        $curl->username = $user;
        $curl->password = $password;
        $curl->type = 'post';
        $curl->fields = $fields;
        $curl->mute = true;
        $curl->timeout = 30;
        
        $file = eZSys::rootDir() . '/' . eZExtension::baseDirectory() . '/xrowcomdirect/cacert.pem';
        if ( ! file_exists( $file ) )
            return false;
        $result = $curl->create();
        
        if ( ! PEAR::isError( $result ) )
        {
            // Set other options here with Net_Curl::setOption()
            $curl->caInfo = $file;
            $curl->verbose = true;
            $curl->verifyPeer = true;
            $this->verifyHost = 2;
            
            $result = $curl->execute();
            
            if ( ! PEAR::isError( $result ) )
            {
                //eZDebug::writeDebug ( $result, 'Payment server ' . $command . ' answer' );
                $resultArray = explode( '&', $result );
                $serverAnswer = array();
                foreach ( $resultArray as $key => $item )
                {
                    $dummy = explode( '=', $item, 2 );
                    if ( count( $dummy ) == 2 )
                        $serverAnswer[urldecode( $dummy[0] )] = urldecode( $dummy[1] );
                }
                
                $res = array();
                $res['servercode'] = '';
                if ( isset( $serverAnswer['rc'] ) )
                    $res['servercode'] = $serverAnswer['rc'];
                
                $res['transactionid'] = '';
                if ( isset( $serverAnswer['trefnum'] ) )
                    $res['transactionid'] = $serverAnswer['trefnum'];
                
                $res['servermsg'] = '';
                if ( isset( $serverAnswer['rmsg'] ) )
                    $res['servermsg'] = $codepage->convertString( $serverAnswer['rmsg'] );
                
                if ( $res['servercode'] === '000' )
                {
                    // card is approved
                    eZDebug::writeDebug( 'Card accepted: ' . $res['servermsg'], 'Card validation' );
                    $curl->close();
                    return true;
                }
                else
                {
                    // card isn't approved
                    eZDebug::writeDebug( 'Card rejected, reason: ' . $res['servermsg'], 'Card validation' );
                }
            }
            else
            {
                eZDebug::writeError( $result->getMessage(), 'curl error' );
            }
            
            $curl->close();
        }
        else
        {
            eZDebug::writeError( $result->getMessage(), 'curl error' );
        }
        return false;
    }

}
?>