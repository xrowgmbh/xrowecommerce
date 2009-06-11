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
            try
            {
                if ( $gateway->capture( $order ) )
                {
                    
                    $payment = xrowPaymentObject::fetchByOrderID( $order->ID );
                    $payment->modifyStatus( xrowPaymentObject::STATUS_APPROVED );
                    return true;
                }
            }
            catch ( xrowPaymentErrorException $e )
            {
                $po = xrowPaymentObject::fetchByOrderID( $order->ID );
                $array = $po->dataArray();
                $array['errors'][] = $e->getMessage();
                $po->setDataArray( $array );
                $po->store();
                return false;
            }
        
        }
        return false;
    }

}
?>