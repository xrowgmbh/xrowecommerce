<?php

class fixedprice extends ShippingInterface 
{
    function methods() {
        return array( 
            array(
                'identifier' => 'fixedprice',
                'name' => ezpI18n::tr( 'extension/shippinginterface', 'Standard Shipping' )
            )
        );
    }
    
    function getService( $service_list, $service_name )
    {
        return false;
    }
    
    function getPrice( $shippingmethod ) {
        return (float)eZINI::instance('shipping.ini')->variable( 'FixedPrice', 'Amount' );
    }
    
    function getDescription( $shippingmethod )
    {
        return ezpI18n::tr( 'extension/shippinginterface', 'Standard Shipping' );
    }
    
    function getShippingDetails()
    {
        return false;
    }
}
?>