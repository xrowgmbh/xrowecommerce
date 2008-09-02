<?php

class xrowInventoryType extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = 'xrowinventory';

    function xrowInventoryType()
    {
        $this->eZWorkflowEventType( xrowInventoryType::WORKFLOW_TYPE_STRING, ezi18n( 'kernel/workflow/event', "Inventory Manager" ) );
        $this->setTriggerTypes( array( 'shop' => array( 'addtobasket' => array ( 'after' ) ) ) );
    }

    function execute( $process, $event )
    {
        $basket = eZBasket::currentBasket();
        
        // @TODO This event will reduce the inventory if implemented
        
        return eZWorkflowType::STATUS_ACCEPTED;
    }
}

eZWorkflowEventType::registerEventType( xrowInventoryType::WORKFLOW_TYPE_STRING, "xrowinventorytype" );

?>
