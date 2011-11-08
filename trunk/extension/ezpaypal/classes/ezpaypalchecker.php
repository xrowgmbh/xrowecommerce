<?php

class eZPaypalChecker extends xrowPaymentCallbackChecker
{

    function __construct( $iniFile )
    {
        parent::__construct( $iniFile );
        $this->logger = eZPaymentLogger::CreateForAdd( 'var/log/payment.log' );
    }

    /* Asks paypal's server to validate callback.
     * 
     */
    function requestValidation()
    {
        $server     = $this->ini->variable( 'ServerSettings', 'ServerName');
        $serverPort = 443;
        $requestURI = $this->ini->variable( 'ServerSettings', 'RequestURI');
        $request    = $this->buildRequestString();
        eZDebug::writeDebug( $request, __CLASS__ . '::' . __FUNCTION__ );
        $response   = $this->sendPOSTRequest( $server, $serverPort, $requestURI, $request);
        eZDebug::writeDebug( $response, __CLASS__ . '::' . __FUNCTION__ );

        if( $response && strpos( $response, 'VERIFIED' ) !== false )
        {
            return true;
        }
      
        eZDebug::writeError( 'Invalid response' , __CLASS__ . '::' . __FUNCTION__ );
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

    /* Creates resquest string which is used to 
     * confirm paypal's callback.
     */
    function buildRequestString()
    {
        $request = "cmd=_notify-validate";
        foreach( $this->callbackData as $key => $value )
        {
            $request .= "&$key=".urlencode( $value );
        }
        return $request;
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
