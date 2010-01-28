<?php

class xrowShippingInterface
{
    static function getShippingMethods( $gateway )
    {
        $list = self::fetchAll();
        foreach ( $list as $key => $method )
        {
            if ( $list[$key]['gateway'] == $gateway )
            {
                $name = $methods[$key]['gateway'];
                $return = new $name( );
                $return->loadConfiguration();
                return $return;
            }
        }
        return false;
    }

    static function instanceByIdentifier( $gateway )
    {
        $list = self::fetchAll();
        foreach ( $list as $key => $method )
        {
            if ( $list[$key]['gateway'] == $gateway )
            {
                $name = $methods[$key]['gateway'];
                $return = new $name( );
                $return->loadConfiguration();
                return $return;
            }
        }
        return false;
    }

    static function instanceByMethod( $methodname )
    {
        $list = self::fetchAll();
        foreach ( $list as $key => $method )
        {
            if ( $method['identifier'] == $methodname )
            {
                $name = $method['gateway'];
                $return = new $name( );
                $return->loadConfiguration();
                $return->method = $methodname;
                $return->name = $method['name'];
                return $return;
            }
        }
        return false;
    }

    static function fetchAll()
    {
        $result = array();
        $list = eZINI::instance( 'shipping.ini' )->variable( 'Settings', 'ShippingGateways' );
        
        foreach ( $list as $item )
        {
            if ( ! class_exists( $item ) )
            {
                eZDebug::writeError( "Shipping Gateway '" . $item . "' doesn't exist.", "xrowShippingInterface::fetchAll()" );
                continue;
            }
            $gateway = new $item( );
            $methods = $gateway->methods();
            foreach ( $methods as $key => $method )
            {
                $methods[$key]['gateway'] = $item;
            }
            $result = array_merge( $result, $methods );
        }
        return $result;
    }

    static function fetchActive( $asObject = false )
    {
        $result = array();
        $db = eZDB::instance();
        
        $list = $db->arrayQuery( "SELECT `data_text3` FROM ezworkflow_event WHERE `workflow_type_string` = 'event_ezshippinginterface' AND `version` = 0;" );
        if ( count( $list ) == 0 )
        {
            throw new Exception( "Shipping worflow is not properly setup. It exists not at all." );
        }
        if ( count( $list ) > 1 )
        {
            throw new Exception( "Shipping worflow is not properly setup. It exists more then one time." );
        }
        $active = unserialize( $list[0]['data_text3'] );
        foreach ( xrowShippingInterface::fetchAll() as $method )
        {
            if ( in_array( $method['identifier'], $active ) )
            {
                if ( $asObject )
                {
                    $result[] = self::instanceByMethod( $method['identifier'] );
                }
                else
                {
                    $result[] = $method;
                }
            }
        }
        return $result;
    }
}

?>