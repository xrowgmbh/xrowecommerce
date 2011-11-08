<?php

class xrowEPayment
{
    const MASTERCARD = 1;
    const VISA = 2;
    const DISCOVER = 3;
    const AMERICANEXPRESS = 4;
    const EUROCARD = 5;
    const PAYMENT_REQUEST_TYPE_AUTH_ONLY = 0;
    const PAYMENT_REQUEST_TYPE_AUTH_AND_CAPTURE = 1;

    /**
     * Instanciates the payment gateway
     *
     * @param string $id
     * @return xrowEPaymentGateway
     */
    static function instanceGateway( $id )
    {
        $classname = $id . 'Gateway';
        if ( class_exists( $classname ) )
        {
            return new $classname();
        }
        else
        {
            throw new Exception( "Gateway $classname does no longer exist." );
        }
    }

    /**
     * Wheater the gateways should store or not store payment information 
     *
     * @return bloolean
     */
    static function storePaymentInformation()
    {
        if ( eZINI::instance( 'xrowecommerce.ini' )->hasVariable( 'EPaymentSettings', 'StorePaymentInformation' ) and eZINI::instance( 'xrowecommerce.ini' )->variable( 'EPaymentSettings', 'StorePaymentInformation' ) == 'enabled' )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    static function obscureString( $string, $length = 4, $char = '*' )
    {
        $amount = strlen( $string ) - $length;
        $result = '';
        for ( $i = $amount; $i > 0; $i -- )
        {
            $result .= $char;
        }
        $offset = $length * - 1;
        return $result . substr( $string, $offset, $length );
    }

    static function paymentRequestType()
    {
        $type = eZINI::instance( 'xrowecommerce.ini' )->variable( 'EPaymentSettings', 'PaymentRequestType' );
        switch ( $type )
        {
            case 'AUTH_ONLY':
                return self::PAYMENT_REQUEST_TYPE_AUTH_ONLY;
                break;
            case 'AUTH_AND_CAPTURE':
                return self::PAYMENT_REQUEST_TYPE_AUTH_AND_CAPTURE;
                break;
            default:
                throw new Exception( "Invalid Payment Request Type" );
                break;
        }
    }

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
    
    static function registerGateway( $gateway, $class_name )
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
                "class_name" => $class_name 
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
            $object = new $availableGateways[$key]['class_name']();
            $gateway['Name'] = $object->name();
            $gateway['value'] = $key;
            $gateway['costs'] = $object->costs();
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
        if ( strlen( $data[xrowECommerce::ACCOUNT_KEY_ECNAME] ) == 0 )
        {
            $errors[] = ezpI18n::tr( 'extension/xrowecommerce/epayment', 'Please enter the name of the card holder.' );
            $valid = false;
        }
        if ( ! preg_match( "/^[0-9]{1,10}$/", $data[xrowECommerce::ACCOUNT_KEY_ACCOUNTNUMBER] ) )
        {
            $errors[] = ezpI18n::tr( 'extension/xrowecommerce/epayment', 'Please enter the correct account number' );
            $valid = false;
        }
        if ( ! preg_match( "/^[0-9]{8}$/", $data[xrowECommerce::ACCOUNT_KEY_BANKCODE] ) )
        {
            $errors[] = ezpI18n::tr( 'extension/xrowecommerce/epayment', 'Please enter the correct bank code' );
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
        if ( $data[xrowECommerce::ACCOUNT_KEY_NAME] == '' )
        {
            $errors[] = ezpI18n::tr( 'extension/xrowecommerce/epayment', 'Please enter a name' );
            $valid = false;
        }
        if ( ! preg_match( "/^[0-9]{1,19}$/", $data[xrowECommerce::ACCOUNT_KEY_NUMBER] ) )
        {
            $errors[] = ezpI18n::tr( 'extension/xrowecommerce/epayment', 'Credit card number is not a number' );
            $valid = false;
        }
        
        if ( $data[xrowECommerce::ACCOUNT_KEY_TYPE] == xrowEPayment::AMERICANEXPRESS )
        {
            $maxDigits = 4;
        }
        else
        {
            $maxDigits = 3;
        }
        if ( ! preg_match( "/^[0-9]{3,$maxDigits}$/", $data[xrowECommerce::ACCOUNT_KEY_SECURITYCODE] ) )
        {
            $errors[] = ezpI18n::tr( 'extension/xrowecommerce/epayment', 'Please enter the correct security code.' );
            $valid = false;
        }
        $now = new DateTime();
        $now->setTime( 0, 0, 0 );
        # Works from php5.3
        # $time = DateTime::createFromFormat( 'mY' . $data['month'] . $data['year'] );
        $time = new DateTime();
        $time->setDate( $data['year'], $data['month'], $now->format( 'd' ) );
        $time->setTime( 0, 0, 0 );
        
        if ( $now->format( 'U' ) > $time->format( 'U' ) )
        {
            $errors[] = ezpI18n::tr( 'extension/xrowecommerce/epayment', 'The validity of your credit card has expired.' );
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
            if ( class_exists( $classname ) )
            {
                $test = new $classname();
                if ( ! ( $test instanceof eZPaymentGateway ) )
                {
                    throw new Exception( "'$name' isn't a valid payment gateway." );
                }
            }
        }
    }
}

?>