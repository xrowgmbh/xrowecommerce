<?php

class xrowShippingInterface
{
    /*static function getShippingMethods( $gateway )
    {
        $list = self::fetchAll();
        foreach ( $list as $key => $method )
        {
            if ( $method == $gateway )
            {
                $name = $method['gateway'];
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
            if ( $method['gateway'] == $gateway )
            {
                $name = $method['gateway'];
                $return = new $name( );
                $return->loadConfiguration();
                return $return;
            }
        }
        return false;
    }*/

    static function instanceByMethod( $methodtype )
    {
        $list = self::fetchAll();
        foreach ( $list as $key => $method )
        {
            if ( $method['identifier'] == $methodtype )
            {
                $name = $method['gateway'];
                $return = new $name( );
                $return->loadConfiguration();
                $return->method = $methodtype;
                $return->name = $method['name'];
                $return->is_air = $method['is_air'];
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
        
        $list = $db->arrayQuery( "SELECT `data_text3` FROM ezworkflow_event ewe, ezworkflow ezw  WHERE  ewe.workflow_id = ezw.id AND ewe.workflow_type_string = 'event_ezshippinginterface' AND ezw.version = 0 AND ewe.version = 0 AND ezw.is_enabled = 1;" );
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