<?php
class xrowShippingFunctionCollection
{

	function listMethods()
	{

        return array( 'result' => xrowShippingInterface::fetchActive() );
	}
}

?>