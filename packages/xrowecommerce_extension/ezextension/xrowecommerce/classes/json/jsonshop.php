<?php

class xrowECommerceJSON extends ezjscServerFunctions
{
	public static function test( $args )
    {
        if ( isset( $args[0] ) )
        {
            return 'Hello World, you sent me 
                    parameter : ' . $args[0];
        }
        else
        {
            $http = eZHTTPTool::instance();
            if ( $http->hasPostVariable( 'arg1' ) )
            {
                return 'Hello World, you sent 
                        me post : ' . $http->postVariable( 'arg1' );
            }
        }
 
        return "Request to server completed, 
                but you did not send any 
                post / function parameters!";
    }
	public static function test_exception( $args )
    {
        if ( isset( $args[0] ) )
        {
            throw new Exception ( 'Hello World, you sent me 
                    parameter : ' . $args[0] );
        }
        else
        {
            $http = eZHTTPTool::instance();
            if ( $http->hasPostVariable( 'arg1' ) )
            {
                throw new Exception ( 'Hello World, you sent 
                        me post : ' . $http->postVariable( 'arg1' ) );
            }
        }
 
        throw new Exception ( "Request to server completed, 
                but you did not send any 
                post / function parameters!" );
    }
    static function translate( $args )
    {
        switch ( $_POST['text'] )
        {
        	case "The shipping method '%old%' is not available for your country of destination and was changed to '%new%'.":
        	{
        		return ezpI18n::tr( 'extension/xrowecommerce', "The shipping method '%old%' is not available for your country of destination and was changed to '%new%'." ) ;
        	} break;
        }
        throw new Exception( 'Tranlation not found.' );
    }
    static function getSubdivisions( $args )
    {
        return xrowGeonames::getSubdivisions( $args[0] );
    }
    static function getShipping( $args )
    {
        $list = xrowShippingInterface::fetchActive( true );
        $return = array();
        foreach ( $list as $item )
        {
        	try
        	{
        		$result = $item->methodCheck( $args[0] );
        	}
        	catch( xrowShippingException $e )
        	{
        		$result = false;
        	}
            if ( $result )
            {
                $return[] = array( 
                    $item->description() , 
                    $item->method , 
                    true 
                );
            }
            else
            {
                $return[] = array( 
                    $item->description() , 
                    $item->method , 
                    false 
                );
            }

        }
        return $return;
    }
}

?>