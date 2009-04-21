<?php

class xrowComdirectECGateway extends xrowComdirectBaseGateway
{
    const GATEWAY_STRING = 'xrowComdirectEC';
    const TEMPLATE = 'design:workflow/comdirectecgateway.tpl';

    function execute( $process, $event )
    {
        return parent::execute( $process, $event );
    }

}

xrowEPayment::registerGateway( xrowComdirectECGateway::GATEWAY_STRING, "xrowcomdirectecgateway", ezi18n( 'kernel/classes/datatypes', 'Direct debit' ) );

?>