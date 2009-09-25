<?php

class xrowECommerceJSON implements eZJSON
{
    function translate( $text )
    {
        switch ( $text )
        {
        	case "Your previously selected shipping method is not avialable for your current shipping destination.":
        	{
        		return ezi18n( 'extension/xrowecommerce', "Your previously selected shipping method is not avialable for your current shipping destination." ) ;
        	} break;
        }
        throw new Exception( 'Tranlation not found.' );
    }
    function getShipping( $country )
    {
        $list = xrowShippingInterface::fetchActive( true );
        $return = array();
        foreach ( $list as $item )
        {
        	try
        	{
        		$result = $item->methodCheck( $country );
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