<?php

class xrowOrderStatusCancel extends xrowOrderStatusDefault
{

    function run( eZOrder $order, xrowOrderStatusDefault $old_status )
    {
        
        $data = $order->attribute( 'account_information' );
        $classname = $data[xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD] . 'Gateway';
        if ( class_exists( $classname ) )
        {
            $gateway = new $classname( );
        }
        else
        {
            throw new Exception( "Gateway $classname does no longer exist." );
        }
        if ( $gateway->cancel( $order ) )
        {
            return true;
        }
        
        return false;
    }

}
?>