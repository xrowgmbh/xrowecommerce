<?php

class xrowCashOnDeliveryGateway extends xrowEPaymentGateway
{
    const AUTOMATIC_STATUS = false;
    const GATEWAY_STRING = "xrowCashOnDelivery";

    function name()
    {
        return ezpI18n::tr( 'kernel/classes/datatypes', 'Cash on delivery' );
    }

    function costs()
    {
        $paymentgatewaysINI = eZINI::instance( 'paymentgateways.ini' );
        if ( $paymentgatewaysINI->hasVariable( 'CashOnDeliverySettings', 'Costs' ) )
        {
            return (float) $paymentgatewaysINI->variable( 'CashOnDeliverySettings', 'Costs' );
        }
    }

    function execute( $process, $event )
    {
        $processParams = $process->attribute( 'parameter_list' );
        $processID = $process->attribute( 'id' );
        $orderID = $processParams['order_id'];
        $order = eZOrder::fetch( $orderID );
        
        if ( $this->costs() > 0 )
        {
            // Remove any existing order shipping item before appendeding a new item
            $class = new xrowEPaymentGateway();
            $orderlist = eZOrderItem::fetchListByType( $orderID, $class::GATEWAY_COSTS );
            if ( count( $orderlist ) > 0 )
            {
                foreach ( $orderlist as $item )
                {
                    $item->remove();
                }
            }
            
            $orderItem = new eZOrderItem( array( 
                'order_id' => $orderID , 
                'description' => ezpI18n::tr( 'extension/xrowecommerce', 'Cash on delivery fee' ) , 
                'price' => $this->costs() , 
                'vat_value' => eZVATManager::getVAT( false, false ) , 
                'type' => $class::GATEWAY_COSTS 
            ) );
            $orderItem->store();
        }
        
        $xmlstring = $order->attribute( 'data_text_1' );
        if ( $xmlstring != null )
        {
            $doc = new DOMDocument();
            $doc->loadXML( $xmlstring );
            $root = $doc->documentElement;
            $invoice = $doc->createElement( xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD, xrowCashOnDeliveryGateway::GATEWAY_STRING );
            $root->appendChild( $invoice );
            $order->setAttribute( 'data_text_1', $doc->saveXML() );
            $order->store();
        }
        return eZWorkflowType::STATUS_ACCEPTED;
    }
}
xrowEPayment::registerGateway( xrowCashOnDeliveryGateway::GATEWAY_STRING, "xrowcashondeliverygateway" );
?>