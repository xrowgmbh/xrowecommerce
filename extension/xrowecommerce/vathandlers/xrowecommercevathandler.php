<?php

class xrowECommerceVATHandler
{

    function taxMapping()
    {
        return array( 
            'DEU' => 19 , 
            'AUT' => 20 , 
            'IRL' => 21 , 
            'USA' => array( 
                'NY' => 8.375 , 
                'CT' => 6.00 
            ) 
        );
    }

    function getVatPercent( $object, $country )
    {
        $percentage = 0;
        $http = eZHTTPTool::instance();
        $orderID = $http->sessionVariable( 'MyTemporaryOrderID' );
        
        if ( $orderID > 0 )
            $order = eZOrder::fetch( $orderID );
        else
            $order = false;
        if ( get_class( $order ) == 'eZOrder' )
        {
            $xml = new eZXML( );
            $xmlDoc = $order->attribute( 'data_text_1' );
            eZDebug::writeDebug( $xmlDoc );
            if ( $xmlDoc != null )
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
                {
                    $taxid = $taxiddom[0]->textContent();
                }
                else
                {
                	$taxid = false;
                }
                if ( array_key_exists( 0, $statedom ) and is_object( $statedom[0] ) )
                    $state = $statedom[0]->textContent();
                if ( array_key_exists( 0, $countrydom ) and is_object( $countrydom[0] ) )
                    $country = $countrydom[0]->textContent();
                $percentage = xrowECommerceVATHandler::getTAX( $country, $state, $taxid );
            }
            else
            {
            	eZDebug::writeError( 'XML is broken', 'xrowECommerceVATHandler' );
            }
        }
        $user = eZUser::currentUser();
        if ( $order === false and ! $user->isAnonymous() )
        {
            $object = $user->attribute( 'contentobject' );
            $country = eZVATManager::getUserCountry( $user, false );
            $percentage = xrowECommerceVATHandler::getTAX( $country, false, false );
            if ( is_object( $object ) )
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
            $state = strtoupper( $state );
        }
        $country = strtoupper( $country );
        $taxmap = xrowECommerceVATHandler::taxMapping();
        $account = eZShopAccountHandler::instance();
        $merchant = $account->merchantsLocations();
        
        if ( ! is_array( $merchant ) and count( $merchant ) > 0 )
        {
            throw new Exception( "Merchant locations not setup in xrowecommerce.ini." );
        }
        $matched = FALSE;
        foreach ( $merchant as $merchantitem )
        {
            if ( ! $matched )
            {
                if ( ! is_array( $merchantitem ) and $merchantitem == $country )
                {
                    $percentage = $taxmap[$merchantitem];
                    $matched = TRUE;
                }
                elseif ( ( array_key_exists( $merchantitem[0], $taxmap ) and $merchantitem[0] == $country ) or ( $merchantitem[0] != $country and ! $taxid ) )
                {
                    if ( is_numeric( $taxmap[$merchantitem[0]] ) )
                    {
                        $percentage = $taxmap[$merchantitem[0]];
                        $matched = TRUE;
                    }
                    elseif ( ! is_array( $merchantitem[1] ) and is_array( $taxmap[$merchantitem[0]] ) and array_key_exists( $merchantitem[1], $taxmap[$merchantitem[0]] ) and $merchantitem[1] == $state )
                    {
                        $percentage = $taxmap[$merchantitem[0]][$merchantitem[1]];
                        $matched = TRUE;
                    }
                    elseif ( is_array( $merchantitem[1] ) and is_array( $taxmap[$merchantitem[0]] ) and array_key_exists( $state, $taxmap[$merchantitem[0]] ) and in_array( $state, $merchantitem[1] ) )
                    {
                        $percentage = $taxmap[$merchantitem[0]][$state];
                        $matched = TRUE;
                    }
                }
            }
        }
        
        /*        
        if ( ( array_key_exists( $merchant[0], $taxmap ) and $merchant[0] == $country ) or ( $merchant[0] != $country and !$taxid  ) )
        {
            
            if ( is_numeric( $taxmap[$merchant[0]] ) )
            {
                $percentage = $taxmap[$merchant[0]];
            }
            elseif ( !is_array( $merchant[1] ) and is_array( $taxmap[$merchant[0]] ) and array_key_exists( $merchant[1], $taxmap[$merchant[0]] ) and $merchant[1] == $state )
            {
                $percentage = $taxmap[$merchant[0]][$merchant[1]];
            }
            elseif ( is_array( $merchant[1] ) and is_array( $taxmap[$merchant[0]] ) and array_key_exists( $state, $taxmap[$merchant[0]] ) and in_array( $state, $merchant[1] ) )
            {
                $percentage = $taxmap[$merchant[0]][$state];
            }
            else
            {
                $percentage = 0;
            }
        }
*/
        return $percentage;
    }
}
?>
