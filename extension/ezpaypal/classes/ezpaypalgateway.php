<?php

class eZPaypalGateway extends xrowRedirectEPaymentGateway
{
    const GATEWAY_STRING = "eZPaypal";

    function __construct()
    {
            $this->logger   = eZPaymentLogger::CreateForAdd( "var/log/eZPaypalType.log" );
            $this->logger->writeTimedString( 'eZPaypalGateway::eZPaypalGateway()' );
    }
    function name()
    {
        return 'Paypal';
    }
    
    function costs()
    {
    }


    /*!
        Creates redirectional url to paypal server.
    */
    function createRedirectionUrl( $process )
    {
        //__DEBUG__
            $this->logger->writeTimedString("createRedirectionUrl");
        //___end____

        $paypalINI      = eZINI::instance( 'paypal.ini' );

        $paypalServer   = $paypalINI->variable( 'ServerSettings', 'ServerName');
        $requestURI     = $paypalINI->variable( 'ServerSettings', 'RequestURI');
        $business       = urlencode( $paypalINI->variable( 'PaypalSettings', 'Business' ) );

        $processParams  = $process->attribute( 'parameter_list' );
        $orderID        = $processParams['order_id'];

        $indexDir       = eZSys::indexDir();
        $localHost      = eZSys::serverURL();
        $localURI       = eZSys::serverVariable( 'REQUEST_URI' );

        $order          = eZOrder::fetch( $orderID );
        $amount         = urlencode( $order->attribute( 'total_inc_vat' ) );
        $currency       = urlencode( $order->currencyCode() );

        $locale         = eZLocale::instance();

        $countryCode    = urlencode( $locale->countryCode() );

        $maxDescLen     = $paypalINI->variable( 'PaypalSettings', 'MaxDescriptionLength');
        $itemName       = urlencode( $this->createShortDescription( $order, $maxDescLen ) );

        $accountInfo    = $order->attribute( 'account_information' );
		
        $first_name     = urlencode( $accountInfo['first_name'] );
        $last_name      = urlencode( $accountInfo['last_name'] );
        $street         = urlencode( $accountInfo['address1'] );
        $zip            = urlencode( $accountInfo['zip'] );
        $state          = urlencode( $accountInfo['state'] );
        $place          = urlencode( $accountInfo['city'] );
        $image_url      = "$localHost" . urlencode( $paypalINI->variable( 'PaypalSettings', 'LogoURI' ) );
        $background     = urlencode( $paypalINI->variable( 'PaypalSettings', 'BackgroundColor' ) );
        $pageStyle      = urlencode( $paypalINI->variable( 'PaypalSettings', 'PageStyle' ) );
        $noNote         = urlencode( $paypalINI->variable( 'PaypalSettings', 'NoNote' ) );
        $noteLabel      = ($noNote == 1) ? '' : urlencode( $paypalINI->variable( 'PaypalSettings', 'NoteLabel' ) );
        $noShipping     = 1;

        $custom = urlencode( serialize( array( 'order_id' => $orderID, 'process_id' => $process->ID ) ) );
        $url =  $paypalServer  . $requestURI    .
                "?cmd=_ext-enter"               .
                "&redirect_cmd=_xclick"         .
                "&business=$business"           .
                "&item_name=$itemName"          .
                "&custom=$custom"               .
                "&amount=$amount"               .
                "&currency_code=$currency"      .
                "&first_name=$first_name"       .
                "&last_name=$last_name"         .
                "&address1=$street"             .
                "&zip=$zip"                     .
                "&state=$state"                 .
                "&city=$place"                  .
                "&image_url=$image_url"         .
                "&cs=$background"               .
                "&page_style=$pageStyle"        .
                "&no_shipping=$noShipping"      .
                "&cn=$noteLabel"                .
                "&transaction_subject=1233"     .
                "&no_note=$noNote"              .
                "&lc=$countryCode"              .
                "&notify_url=". urlencode($localHost . $indexDir . "/paypal/notify_url/").
                "&return=$localHost"     . $indexDir . "/xrowecommerce/checkout/" .
                "&cancel_return=$localHost" . $indexDir . "/xrowecommerce/cart/";

        return $url;
    }
}

xrowEPayment::registerGateway( eZPaypalGateway::GATEWAY_STRING, "ezpaypalgateway" );

?>
