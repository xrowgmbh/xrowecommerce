<?php

class xrowECommerceFunctionCollection
{
    public function paymentStatus( $id )
    {
        return array(
            'result' => xrowPaymentObject::fetchByOrderID( $id )
        );
    }
    public function getCountryList()
    {
        return array(
            'result' => xrowCountryType::fetchCountryList()
        );
    }

    public function getShopAccountValue( $name, $order )
    {
        $xml = simplexml_load_string( $order->DataText1 );
        return array(
            'result' => (string) $xml->{$name}
        );
    }

    public function listAllGateways()
    {
        return array(
            'result' => xrowEPayment::getGateways( array(
                - 1
            ) )
        );
    }

    /**
     * Fetch a variation entry of the product variation datatype
     *
     * @param integer $id
     * @return mixed
     */
    public function fetchVariation( $id )
    {
        $variation = xrowProductData::fetch( $id );
        $result = array( 'result' => $variation );
        return $result;
    }

    /**
     * Returns a sliding price if amount is given or
     * an array of sliding prices
     *
     * @param int $priceID
     * @return mixed
     */
    public function fetchSlidingPrice( $price_id, $amount = false )
    {
        if ( $amount === false )
        {
        	$slidingArray = xrowProductPrice::fetchSlidingPriceArray( $price_id );
        	$result = array( 'result' => $slidingArray );
        }
        else
        {
        	$price = xrowProductPrice::fetchPriceByAmount( $price_id, $amount );
        	$result = array( 'result' => $price );
        }

        return $result;
    }

    public function hasSlidingPrice( $attribute_id, $version, $language )
    {
    	$result = array( 'result' => false );
    	if ( $attribute_id > 0 )
    	{
    		$result['result'] = xrowProductData::hasSlidingPrice( $attribute_id, $version, $language );
    	}
    	return $result;
    }

}

?>