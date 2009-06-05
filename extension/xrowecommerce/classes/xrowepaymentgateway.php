<?php

class xrowEPaymentGateway extends eZPaymentGateway
{
	const AUTOMATIC_STATUS = true;
    function capture( eZOrder $order ) {
    	return true;
    }
    function cancel( eZOrder $order ) {
        return true;
    }
}

?>