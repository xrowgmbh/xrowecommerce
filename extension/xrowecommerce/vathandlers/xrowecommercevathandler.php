<?php
include_once( "kernel/classes/ezorder.php" );
include_once( 'lib/ezutils/classes/ezoperationhandler.php' );
include_once( 'lib/ezxml/classes/ezxml.php' );
include_once( 'kernel/classes/ezshopaccounthandler.php' );
class xrowECommerceVATHandler
{
    function taxMapping()
    {
        return array(
        'DEU' => 19,
        'IRL' => 21,
        'USA' => array( 'NY' => 8.375, 'CT' => 6.00 )
        );
    }
    function getVatPercent( $object, $country )
    {
    	$http = eZHTTPTool::instance();
        $orderID = $http->sessionVariable( 'MyTemporaryOrderID' );

        if ($orderID > 0)
            $order = eZOrder::fetch( $orderID );
        else
            $order = false;

        if ( get_class( $order ) == 'ezorder' )
        {
            $xml = new eZXML();
            $xmlDoc = $order->attribute( 'data_text_1' );
            eZDebug::writeDebug($xmlDoc);
            if( $xmlDoc != null )
            {   
                $dom = $xml->domTree( $xmlDoc );
                
                $element = $dom->elementsByName( "shipping" );
                if ( array_key_exists( 0, $element ) and is_object( $element[0] ) )                
                    $use_shipping_address = $element[0]->textContent();
                else
                    $use_shipping_address = false;
                if ( $use_shipping_address == "1" )
                {
                      $statedom = $dom->elementsByName( "state" );
                      $countrydom = $dom->elementsByName( "country" );
                }
                else
                {
                   $statedom = $dom->elementsByName( "s_state" );
                   $countrydom = $dom->elementsByName( "s_country" );
                }
                $taxiddom = $dom->elementsByName( "taxid" );
                if ( array_key_exists( 0, $taxiddom ) and is_object( $taxiddom[0] ) ) 
                    $taxid = $taxiddom[0]->textContent();
                if ( array_key_exists( 0, $statedom ) and is_object( $statedom[0] ) ) 
                    $state = $statedom[0]->textContent();
                if ( array_key_exists( 0, $countrydom ) and is_object( $countrydom[0] ) ) 
                    $country = $countrydom[0]->textContent();
                $percentage = xrowECommerceVATHandler::getTAX( $country, $state = false, $taxid = false );
            }
            eZDebug::writeError( 'XML is broken', 'xrowECommerceVATHandler' );
        }
        $user = eZUser::currentUser();
        if ( $order === false and !$user->isAnonymous() )
        {
            $object = $user->attribute( 'contentobject' );
            $country = eZVATManager::getUserCountry( $user, false );
            $percentage = xrowECommerceVATHandler::getTAX( $country, false, false );
            if( is_object( $object ) )
            {
                $dm = $object->dataMap();
                if ( is_object( $dm['taxid'] ) and $dm['taxid']->attribute( 'has_content' ) )
                {
                    $percentage = xrowECommerceVATHandler::getTAX( $country, false, true );
                }
            }
        }
        return $percentage;
    }
    function getTAX( $country, $state = false, $taxid = false )
    {
        $percentage = 0;
        if ( $state !== false )
        {
            $state = strtolower( $state );
        }
        $country = strtolower( $country );
        $taxmap = xrowECommerceVATHandler::taxMapping();
        $account = eZShopAccountHandler::instance();
        $merchant = $account->merchantsLocations();
        
        if ( ( array_key_exists( $merchant[0], $taxmap ) and $merchant[0] == $country ) or ( $merchant[0] != $country and !$taxid  ) )
        {
            
            if ( is_numeric( $taxmap[$merchant[0]] ) )
            {
                $percentage = $taxmap[$merchant[0]];
            }
            elseif ( is_array( $taxmap[$merchant[0]] ) and array_key_exists( $merchant[1], $taxmap[$merchant[0]] ) and $merchant[1] == $state )
            {
                $percentage = $taxmap[$merchant[0]][$merchant[1]];
            }
            else
            {
                $percentage = 0;
            }
        }
        return $percentage;
    }
}
?>
