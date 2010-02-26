<?php

class xrowEPaymentGateway extends eZPaymentGateway
{
    const AUTOMATIC_STATUS = true;
    /* Capture a payment
     * 
     * @returns boolean true on success
     * @throws xrowPaymentErrorException
     */
    function capture( eZOrder $order )
    {
        return true;
    }
    /* Cancel a payment
     * 
     * @returns boolean true on success
     * @throws xrowPaymentErrorException
     */
    function cancel( eZOrder $order )
    {
        return true;
    }
}

?>