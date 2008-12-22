<?php
class xrowECommerceFunctionCollection
{
    function xrowECommerceFunctionCollection()
    {

    }
	function getCountryList()
	{
        return array( 'result' => eZCountryType::fetchCountryList() );
	}
	function getShopAccountValue( $name, $order )
	{
		$xml = simplexml_load_string( $order->DataText1 );
		return array( 'result' => (string)$xml->{$name} );
	}
}

?>