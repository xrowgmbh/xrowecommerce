<?php

class xrowEPayment
{

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

    /*!
    Searches 'available' gateways( built-in or as extensions ).
    */
    
    static function loadAndRegisterGateways()
    {
        self::loadAndRegisterBuiltInGateways();
        self::loadAndRegisterExtensionGateways();
    }

    static function loadAndRegisterBuiltInGateways()
    {
        $gatewaysINI = eZINI::instance( 'paymentgateways.ini' );
        $gatewaysTypes = $gatewaysINI->variable( 'GatewaysSettings', 'AvailableGateways' );
        $gatewaysDir = $gatewaysINI->variable( 'GatewaysSettings', 'GatewaysDirectories' );
        
        if ( is_array( $gatewaysDir ) && is_array( $gatewaysTypes ) )
        {
            foreach ( $gatewaysDir as $dir )
            {
                foreach ( $gatewaysTypes as $gateway )
                {
                    $gatewayPath = "$dir/$gateway/classes/" . $gateway . 'gateway.php';
                    if ( file_exists( $gatewayPath ) )
                    {
                        include_once ( $gatewayPath );
                    }
                }
            }
        }
    }

    static function loadAndRegisterExtensionGateways()
    {
        $gatewaysINI = eZINI::instance( 'paymentgateways.ini' );
        $siteINI = eZINI::instance( 'site.ini' );
        $extensionDirectory = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' );
        $activeExtensions = eZExtension::activeExtensions();
        
        foreach ( $activeExtensions as $extension )
        {
            $gatewayPath = "$extensionDirectory/$extension/classes/" . $extension . 'gateway.php';
            if ( file_exists( $gatewayPath ) )
            {
                include_once ( $gatewayPath );
            }
        }
        foreach ( $gatewaysINI->variable( 'GatewaysSettings', 'GatewaysDirectories' ) as $dir )
        {
            foreach ( $gatewaysINI->variable( 'GatewaysSettings', 'AvailableGateways' ) as $name )
            {
                $gatewayPath = $dir . '/' . $name . 'gateway.php';
                if ( file_exists( $gatewayPath ) )
                {
                    include_once ( $gatewayPath );
                }
            }
        }
    }

}

?>