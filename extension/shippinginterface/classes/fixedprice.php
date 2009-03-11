<?php

class fixedprice extends ShippingInterface 
{
    function methods() {
    	return array( 
    	array(
    		'identifier' => 'fixedprice',
    		'name' => 'Standard'
    	)
    	 );
    }
    function getPrice() {
    	return (float)eZINI::instace('shipping.ini')->variable( 'FixedPrice', 'Amount' );
    }
    
}
?>