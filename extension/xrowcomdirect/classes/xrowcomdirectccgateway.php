<?php

class xrowComdirectCCGateway extends xrowComdirectBaseGateway
{
    const GATEWAY_STRING = 'xrowComdirectCC';
    const TEMPLATE = 'design:workflow/comdirectccgateway.tpl';

    function execute( $process, $event )
    {
        return parent::execute( $process, $event );
    }

}

xrowEPayment::registerGateway( xrowComdirectCCGateway::GATEWAY_STRING, "xrowcomdirectccgateway", ezi18n( 'extension/xrowcomdirect', 'Credit card' ) );

?>