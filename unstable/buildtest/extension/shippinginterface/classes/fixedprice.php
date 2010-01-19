<?php

class fixedprice extends ShippingInterface 
{
    function methods() {
    	return array( 
    	array(
    		'identifier' => 'fixedprice',
    		'name' => ezi18n( 'extension/shippinginterface', 'Standard Shipping' )
    	)
    	 );
    }
    function getPrice() {
    	return (float)eZINI::instace('shipping.ini')->variable( 'FixedPrice', 'Amount' );
    }
    
}
?>
