<?php

class xrowEPaymentGateway extends eZPaymentGateway
{
    function capture( eZOrder $order ) {
    	return true;
    }
    function cancel( eZOrder $order ) {
        return true;
    }
}

?>