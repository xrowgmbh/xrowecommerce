<?php
class xrowInvoiceGateway extends eZPaymentGateway
{
    const GATEWAY_STRING = "xrowInvoice";

    function xrowInvoiceGateway ()
    {
    }

    function execute ( 
                                    $process, 
                                    $event )
    {
        $processParams = $process->attribute( 'parameter_list' );
        $processID = $process->attribute( 'id' );
        $orderID = $processParams['order_id'];
        $order = eZOrder::fetch( $orderID );
        include_once ('lib/ezxml/classes/ezxml.php');
        $xml = new eZXML( );
        $xmlDoc = $order->attribute( 'data_text_1' );
        if ( $xmlDoc != null )
        {
            $dom = $xml->domTree( $xmlDoc );
            $root = $dom->get_root();
            $invoice = $dom->createElementNode( xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD );
            $invoice->appendChild( $dom->createTextNode( xrowInvoiceGateway::GATEWAY_STRING ) );
            $root->appendChild( $invoice );
            $order->setAttribute( 'data_text_1', $root->toString() );
            $order->store();
        }
        return eZWorkflowType::STATUS_ACCEPTED;
    }
}
eZPaymentGatewayType::registerGateway( xrowInvoiceGateway::GATEWAY_STRING, "xrowinvoicegateway", "Pay by invoice" );
?>
