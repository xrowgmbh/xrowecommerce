<?php

class attrtibute1 implements ProductAttribute
{
    function validate( $struc )
    {
    	if ( empty( $struc ) )
    	{
    	    throw new ValiationException( "No Input" );
    	}
    	else
    	{
            return true;
    	}
    }
}
?>