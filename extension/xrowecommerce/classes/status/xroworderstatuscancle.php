<?php

class xrowOrderStatusCancel extends xrowOrderStatusDefault
{

    function run( eZOrder $order, xrowOrderStatusDefault $old_status )
    {
        
        $data = $order->attribute( 'account_information' );
        $gateway = xrowEPayment::instanceGateway( $data[xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD] );
        if ( $gateway->cancel( $order ) )
        {
            return true;
        }
        
        return false;
    }

}
?>