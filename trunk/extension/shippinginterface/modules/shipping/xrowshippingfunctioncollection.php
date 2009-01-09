<?php
class xrowShippingFunctionCollection
{

	function listMethods()
	{

        return array( 'result' => xrowShippingInterface::fetchActive() );
	}
	function listAllMethods()
	{

        return array( 'result' => xrowShippingInterface::fetchAll() );
	}
}

?>