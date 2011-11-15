<?php

class xrowInvoiceGateway extends xrowEPaymentGateway
{
    const GATEWAY_STRING = "xrowInvoice";
    const AUTOMATIC_STATUS = false;

    function name()
    {
        return ezpI18n::tr( 'kernel/classes/datatypes', 'Pay by invoice' );
    }

    function execute( $process, $event )
    {
        $processParams = $process->attribute( 'parameter_list' );
        $processID = $process->attribute( 'id' );
        $orderID = $processParams['order_id'];
        $order = eZOrder::fetch( $orderID );
        
        $xmlstring = $order->attribute( 'data_text_1' );
        if ( $xmlstring != null )
        {
            $doc = new DOMDocument();
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
xrowEPayment::registerGateway( xrowInvoiceGateway::GATEWAY_STRING, "xrowinvoicegateway" );

?>