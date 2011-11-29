<?php

class eZPaypalChecker extends xrowPaymentCallbackChecker
{
    /* Asks paypal's server to validate callback.
     * 
     */
    function requestValidation()
    {
        $server     = eZINI::instance('paypal.ini')->variable( 'ServerSettings', 'ServerName');
        $requestURI = eZINI::instance('paypal.ini')->variable( 'ServerSettings', 'RequestURI');
        
        $data   = $this->callbackData;
        $data['cmd'] = "_notify-validate";
        
        eZDebug::writeDebug( $data, __METHOD__ );
        $response = $this->sendPOSTRequest( $server, $requestURI, $data );

        if( $response && strpos( $response, 'VERIFIED' ) !== false )
        {
            return true;
        }
      
        eZDebug::writeError( 'Invalid response ' . $response, __METHOD__ );
        return false;
    }

    /* Convinces of completion of the payment.
     */
    function checkPaymentStatus()
    {
        if( $this->checkDataField( 'payment_status', 'Completed' ) )
        {
            return true;
        }

        if( ( $this->checkDataField( 'protection_eligibility', 'Ineligible' ) or $this->checkDataField( 'protection_eligibility', 'PartiallyEligible' ) ) and $this->checkDataField( 'payment_status', 'Pending' ) and $this->checkDataField( 'pending_reason', 'paymentreview' ) )
        {
            return true;
        }
        return false;
    }
    
    function handleResponse( $socket )
    {
        if( $socket )
        {
            while ( !feof( $socket ) )
            {
                $response .= fgets ( $socket, 1024 );
            }
      
            fclose( $socket );
            return $response;
        }
        eZDebug::writeError( "socket = $socket is invalid." , __CLASS__ . '::' . __FUNCTION__ );
        return null;
    }
}

?>
