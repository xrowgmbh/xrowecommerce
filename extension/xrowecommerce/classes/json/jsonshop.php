<?php

class xrowECommerceJSON implements eZJSON
{

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