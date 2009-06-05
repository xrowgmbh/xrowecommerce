<?php

class xrowOrderStatusPaid extends xrowOrderStatusDefault
{

    function run( eZOrder $order, xrowOrderStatusDefault $old_status )
    {
        if ( xrowEPayment::paymentRequestType() != xrowEPayment::PAYMENT_REQUEST_TYPE_AUTH_AND_CAPTURE )
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
            if ( $gateway->capture( $order ) )
            {
                
                $payment = xrowPaymentObject::fetchByOrderID( $order->ID );
                $payment->modifyStatus( xrowPaymentObject::STATUS_APPROVED );
                return true;
            }
        }
        return false;
    }

}
?>