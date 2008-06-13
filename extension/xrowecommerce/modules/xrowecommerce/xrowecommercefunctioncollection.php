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
}

?>