<?php

class xrowAfterSaleType extends eZWorkflowEventType
{
	const WORKFLOW_TYPE_STRING = 'xrowaftersale';
	const STATE_CANCEL = 2;
	const STATE_INVALID_CODE = 3;
	const STATE_VALID_CODE = 1;
	const BASE = 'event_ezcoupon';

    /*!
     Constructor
    */
    function xrowAfterSaleType()
    {
        $this->eZWorkflowEventType( xrowAfterSaleType::WORKFLOW_TYPE_STRING, ezpI18n::tr( 'kernel/workflow/event', "Coupon" ) );
        $this->setTriggerTypes( array( 'shop' => array( 'confirmorder' => array ( 'after' ) ) ) );
    }

    function execute( $process, $event )
    {
        $http = eZHTTPTool::instance();
        $this->fetchInput( $http, eZCouponWorkflowType::BASE, $event, $process );
        if( $http->hasPostVariable( 'Cancel' ) and $http->postVariable( 'Cancel' ) )
        {
            return eZWorkflowEventType::STATUS_ACCEPTED;
        }

        $process->Template = array();
        $process->Template['templateName'] = 'design:workflow/aftersale.tpl';
        $process->Template['templateVars'] = array ( 'process' => $process, 'event' => $event );

        return eZWorkflowType::STATUS_FETCH_TEMPLATE_REPEAT;

    }
 
}

eZWorkflowEventType::registerEventType( xrowAfterSaleType::WORKFLOW_TYPE_STRING, "xrowaftersaletype" );

?>
