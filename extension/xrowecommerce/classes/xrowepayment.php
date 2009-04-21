<?php

class xrowEPayment
{
    const MASTERCARD = 1;
    const VISA = 2;
    const DISCOVER = 3;
    const AMERICANEXPRESS = 4;
    const EUROCARD = 5;

    static function allowedGatewayListByUser( eZUser $user = null )
    {
        
        $return = array();
        foreach ( self::allowedGatewaysByUser( $user ) as $gateway )
        {
            $return[] = $gateway['value'];
        }
        return $return;
    }

    static function allowedGatewaysByUser( eZUser $user = null )
    {
        if ( $user === null )
        {
            $user = eZUser::currentUser();
        }
        $gateways = self::getGateways( array( 
            - 1 
        ) );
        $access = $user->hasAccessTo( 'xrowecommerce', 'payment' );
        if ( $access['accessWord'] == 'yes' )
        {
            return $gateways;
        }
        elseif ( $access['accessWord'] == 'limited' )
        {
            $allowed = array();
            foreach ( $access['policies'] as $policy )
            {
                if ( isset( $policy['Payment'] ) )
                {
                    
                    $allowed = array_merge( $allowed, $policy['Payment'] );
                }
            }
            $allowed = array_unique( $allowed );
            $allowed_gateways = array();
            foreach ( $gateways as $gateway )
            {
                if ( in_array( $gateway['value'], $allowed ) )
                {
                    $allowed_gateways[] = $gateway;
                }
            }
            return $allowed_gateways;
        }
        else
        {
            return array();
        }
    
    }

    /*!
    Each gateway must call this function to become 'available'.
    */
    
    static function registerGateway( $gateway, $class_name, $description )
    {
        $gateways = & $GLOBALS["eZPaymentGateways"];
        if ( ! is_array( $gateways ) )
        {
            $gateways = array();
        }
        
        if ( isset( $gateways[$gateway] ) )
        {
            eZDebug::writeError( "Gateway already registered: $gateway", "eZPaymentGatewayType::registerGateway" );
        }
        else
        {
            $gateways[$gateway] = array( 
                "class_name" => $class_name , 
                "description" => $description 
            );
        }
    }

    /*!
    Returns an array of gateways definitions( class_name, description ) by
    'gatewaysTypes'( array of 'gateway' values that were passed to
    'registerGateway' function).
    */
    static function getGateways( $gatewaysTypes )
    {
        $gateways = array();
        if ( ! array_key_exists( 'eZPaymentGateways', $GLOBALS ) )
        {
            self::loadAndRegisterGateways();
        }
        $availableGateways = $GLOBALS['eZPaymentGateways'];
        if ( ! is_array( $availableGateways ) )
        {
            return $gateways;
        }
        
        if ( in_array( '-1', $gatewaysTypes ) )
        {
            $gatewaysTypes = array_keys( $availableGateways );
        }
        
        foreach ( $gatewaysTypes as $key )
        {
            $gateway = $availableGateways[$key];
            $gateway['Name'] = $gateway['description'];
            $gateway['value'] = $key;
            $gateways[] = $gateway;
        }
        
        return $gateways;
    }

    static function validateCardData( $data, &$errors )
    {
        switch ( $data[xrowECommerce::ACCOUNT_KEY_TYPE] )
        {
            case xrowEPayment::MASTERCARD:
            case xrowEPayment::VISA:
            case xrowEPayment::DISCOVER:
            case xrowEPayment::AMERICANEXPRESS:
                {
                    return self::validateCCData( $data, $errors );
                }
                break;
            
            case xrowEPayment::EUROCARD:
                {
                    return self::validateECData( $data, $errors );
                }
                break;
            
            default:
                throw new Exception( "Type not given." );
        }
    }

    static function validateECData( $data, &$errors )
    {
        $valid = true;
        // validate eurocard
        if ( strlen( $data['ecname'] ) == 0 )
        {
            $errors[] = ezi18n( 'extension/xrowecommerce/epayment', 'Please enter the name of the bank account.' );
            $valid = false;
        }
        if ( ! preg_match( "/^[0-9]{1,10}$/", $data['accountnumber'] ) )
        {
            $errors[] = ezi18n( 'extension/xrowecommerce/epayment', 'Please enter your correct account number (max. 10 numbers)' );
            $valid = false;
        }
        if ( ! preg_match( "/^[0-9]{8}$/", $data['bankcode'] ) )
        {
            $errors[] = ezi18n( 'extension/xrowecommerce/epayment', 'Please enter your correct bank code (8 numbers)' );
            $valid = false;
        }
        if ( $valid )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    static function validateCCData( $data, &$errors )
    {
        $valid = true;
        if ( $data['name'] == '' )
        {
            $errors[] = ezi18n( 'extension/xrowecommerce/epayment', 'Please enter a name' );
            $valid = false;
        }
        if ( ! preg_match( "/^[0-9]{1,19}$/", $data['number'] ) )
        {
            $errors[] = ezi18n( 'extension/xrowecommerce/epayment', 'Credit card number is not a number' );
            $valid = false;
        }
        
        if ( $data['type'] == xrowEPayment::AMERICANEXPRESS )
        {
            $maxDigits = 4;
        }
        else
        {
            $maxDigits = 3;
        }
        if ( ! preg_match( "/^[0-9]{3,$maxDigits}$/", $data['securitycode'] ) )
        {
            $errors[] = ezi18n( 'extension/xrowecommerce/epayment', 'Please enter the correct security code.' );
            $valid = false;
        }
        $time = eZDateTime::create( - 1, - 1, - 1, $data['month'], - 1, $data['year'] );
        $now = new eZDateTime( false );
        if ( $now->isGreaterThan( $time ) )
        {
            $errors[] = ezi18n( 'extension/xrowecommerce/epayment', 'Your credit card is expired.' );
            $valid = false;
        }
        if ( $valid )
        {
            return true;
        }
        else
        {
            return false;
        }
    
    }

    /*!
    Searches 'available' gateways( built-in or as extensions ).
    */
    
    static function loadAndRegisterGateways()
    {
        $gatewaysINI = eZINI::instance( 'paymentgateways.ini' );
        foreach ( $gatewaysINI->variable( 'GatewaysSettings', 'AvailableGateways' ) as $name )
        {
            $classname = $name . 'Gateway';
            $test = new $classname( );
            if ( ! ( $test instanceof eZPaymentGateway ) )
            {
                throw new Exception( "'$name' isn't a valid payment gateway." );
            }
        }
    }
}

?>