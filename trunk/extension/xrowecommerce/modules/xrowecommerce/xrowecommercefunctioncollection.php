<?php
class xrowECommerceFunctionCollection
{
    function xrowECommerceFunctionCollection()
    {

    }
	function getCountryList()
	{
        $countryType = new eZCountryType();
        return array( 'result' => $countryType->fetchCountryList() );
	}
}

?>