<?php

class xrowECommerceFunctionCollection
{
    public function getCountryList()
    {
        return array(
            'result' => eZCountryType::fetchCountryList()
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

}

?>