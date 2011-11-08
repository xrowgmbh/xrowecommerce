<?php

$checker = new eZPaypalChecker( 'paypal.ini' );

if ( $checker->createDataFromPOST() )
{
    
    unset( $_POST );
    if ( $checker->requestValidation() && $checker->checkPaymentStatus() )
    {
        eZDebug::writeDebug( "Validation success" );
        $data = unserialize( $checker->getFieldValue( 'custom' ) );
        $orderID = (int) $data['order_id'];
        if ( $checker->setupOrderAndPaymentObject( $orderID ) )
        {
            eZDebug::writeDebug( "setupOrderAndPaymentObject success" );
            $amount = $checker->getFieldValue( 'mc_gross' );
            $currency = $checker->getFieldValue( 'mc_currency' );
            if ( $checker->checkAmount( $amount ) && $checker->checkCurrency( $currency ) )
            {
                $checker->approvePayment();
                eZDebug::writeDebug( "approvePayment success" );
            }
            else
            {
                eZDebug::writeDebug( "approvePayment failed" );
            }
        }
        else
        {
            eZDebug::writeError( "setupOrderAndPaymentObject failed" );
        }
    }
    else
    {
        eZDebug::writeError( "Validation failed" );
    }
}
else
{
    eZDebug::writeError( "No post Data" );
}

eZDebug::writeDebug( 'notify_url.php END' );

?>