<?php

class xrowInvoiceGateway extends eZPaymentGateway
{
    const GATEWAY_STRING = "xrowInvoice";

    function execute( $process, $event )
    {
        $processParams = $process->attribute( 'parameter_list' );
        $processID = $process->attribute( 'id' );
        $orderID = $processParams['order_id'];
        $order = eZOrder::fetch( $orderID );
        
        $xmlstring = $order->attribute( 'data_text_1' );
        if ( $xmlstring != null )
        {
            $doc = new DOMDocument( );
            $doc->loadXML( $xmlstring );
            $root = $doc->documentElement;
            $invoice = $doc->createElement( xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD, xrowInvoiceGateway::GATEWAY_STRING );
            $root->appendChild( $invoice );
            $order->setAttribute( 'data_text_1', $doc->saveXML() );
            $order->store();
        }
        return eZWorkflowType::STATUS_ACCEPTED;
    }
}
xrowPaymentGatewayType::registerGateway( xrowInvoiceGateway::GATEWAY_STRING, "xrowinvoicegateway", ezi18n( 'kernel/classes/datatypes', 'Pay by invoice' ) );
?>