<?php

class rangedfixedprice extends ShippingInterface
{
    const CONFIG_FILE = 'settings/override/shipping.xml';

    function methods()
    {
        return array( 
            array( 
                'identifier' => 'standard' , 
                'name' => ezpI18n::tr( 'extension/shippinginterface', 'Standard Shipping' ) 
            ) , 
            array( 
                'identifier' => 'express' , 
                'name' => ezpI18n::tr( 'extension/shippinginterface', 'Express Shipping' ) 
            ) 
        );
    }

    function getService( $service_list, $service_name )
    {
        return false;
    }

    function getPrice( $shippingmethod )
    {
        $config = new SimpleXMLElement( file_get_contents( self::CONFIG_FILE ) );
        $list = $config->xpath( "/shipping/option[ @id = '" . $this->method . "' ]/policy" );

        foreach ( $list as $policy )
        {
        	
            if ( isset( $policy['match_country'] ) and isset( $policy['match_zip'] ) and preg_match( $policy['match_country'], $this->address_to["country"] ) and preg_match( $policy['match_zip'], $this->address_to["zip"] ) )
            {
               break;
            }
        }

        $list = $policy->xpath( 'rate' );
        foreach ( $list as $rate )
        {
        	if($rate->min_weight <= $this->weight and ($rate->max_weight > $this->weight or !isset($rate->max_weight)))
        	{
        		return (float) $rate->fee;
        	}
        }
        return (float) 0.00;
    }

    function getDescription( $shippingmethod )
    {
        $list = $this->methods();
        foreach ( $list as $item )
        {
            if ( $item['identifier'] == $this->method )
            {
                return $item['name'];
            }
        }
    }

    function getShippingDetails()
    {
        return false;
    }
}
?>