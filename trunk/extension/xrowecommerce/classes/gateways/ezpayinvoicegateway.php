<?php

define( "EZ_PAYMENT_GATEWAY_TYPE_PAYINVOICE", "eZPayinvoice" );
include_once( 'kernel/shop/classes/ezpaymentobject.php' );

class eZPayinvoiceGateway extends eZPaymentGateway
{
    function eZPayinvoiceGateway()
    {
    }
    function execute( &$process, &$event )
    {
        $processParams  =& $process->attribute( 'parameter_list' );
        $processID         =  $process->attribute( 'id' );
        $orderID        = $processParams['order_id'];
        $order          =& eZOrder::fetch( $orderID );
        $amount         = $order->attribute( 'total_inc_vat' );
        $accountInfo    = $order->attribute( 'account_information' );
        $first_name     = $accountInfo['first_name'] ;
        $last_name      = $accountInfo['last_name'];
        $street         = $accountInfo['street2'];
        $zip            = $accountInfo['zip'];
        $state          = $accountInfo['state'];
        $place          = $accountInfo['place'];
        $country        = $accountInfo['country'];
        if ($country === "Germany")
        {
            include_once( 'lib/ezxml/classes/ezxml.php' );
            $xml = new eZXML();
            $xmlDoc = $order->attribute( 'data_text_1' );
            if( $xmlDoc != null )
            {
                $dom = $xml->domTree( $xmlDoc );
                $root = $dom->get_root();
                $invoice = $dom->createElementNode( "invoice" );
                $invoice->appendChild( $dom->createTextNode( "1" ) );
                $root->appendChild( $invoice );
                $order->setAttribute( 'data_text_1', $root->toString() );
                $order->store();
            }
            return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
        }
        else
        {
            include_once( "kernel/common/template.php" );
            $tpl =& templateInit();
            $tpl->setVariable ("ErrorCode", "WRONG_COUNTRY");
            return EZ_WORKFLOW_TYPE_STATUS_REJECTED;
        }
    }
}
eZPaymentGatewayType::registerGateway( EZ_PAYMENT_GATEWAY_TYPE_PAYINVOICE, "ezpayinvoicegateway", "Pay by invoice" );
?>
