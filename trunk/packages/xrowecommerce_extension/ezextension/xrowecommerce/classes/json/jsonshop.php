<?php

class xrowECommerceJSON implements eZJSON
{
    function translate( $text )
    {
        switch ( $text )
        {
        	case "The shipping method '%old%' is not available for your country of destination and was changed to '%new%'.":
        	{
        		return ezpI18n::tr( 'extension/xrowecommerce', "The shipping method '%old%' is not available for your country of destination and was changed to '%new%'." ) ;
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