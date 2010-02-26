<?php

interface xrowPaymentGatewayInterface
{

    function capture( eZOrder $order );

    function cancel( eZOrder $order );
}
?>