<?php

class xrowOrderStatusCancel extends xrowOrderStatusDefault
{

    function run( eZOrder $order, xrowOrderStatusDefault $old_status )
    {
        
        $data = $order->attribute( 'account_information' );
        $gateway = xrowEPayment::instanceGateway( $data[xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD] );
        
        try
        {
            if ( $gateway->cancel( $order ) )
            {
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
        return false;
    }

}
?>