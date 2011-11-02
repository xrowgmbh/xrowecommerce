<?php

class xrowECommerceVATHandler
{

    static function taxMapping()
    {
        return array( 
            'DEU' => 19 , 
            'AUT' => 20 , 
            'IRL' => 21 , 
            'USA' => array(
                'NY' => 8.875 ,
                'CT' => 6 ,
                'PA' => 8
            ) 
        );
    }

    function getVatPercent( $object, $country )
    {
        $percentage = 0;
        $http = eZHTTPTool::instance();
        $orderID = $http->sessionVariable( 'MyTemporaryOrderID' );
        $user = eZUser::currentUser();
        if ( $orderID > 0 )
        {
            $order = eZOrder::fetch( $orderID );
            if ( $order instanceof eZOrder and $order->attribute( 'data_text_1' ) === '' )
            {
            	$order = false;
            }
        }
        else
        {
            $order = false;
        }
        if ( $order instanceof eZOrder )
        {
            $xmlDoc = $order->attribute( 'data_text_1' );
            eZDebug::writeDebug( $xmlDoc );
            $xml = simplexml_load_string( $xmlDoc );
            if ( $xmlDoc === '' )
            {
            	
            }
            elseif ( $xml instanceof SimpleXMLElement )
            {
                if ( (int)$xml->{'shipping'} )
                    $use_shipping_address = (int)$xml->{'shipping'};
                else
                    $use_shipping_address = false;
                if ( $use_shipping_address )
                {
                    $state = (string)$xml->{'state'};
                    $country = (string)$xml->{'country'};
                }
                else
                {
                    $state = (string)$xml->{'s_state'};
                    $country = (string)$xml->{'s_country'};
                }
                $tmptaxid = (string)$xml->{'tax_id'};
                if ( !empty( $tmptaxid ) )
                {
                    $taxid = $tmptaxid;
                }
                else
                {
                	$taxid = false;
                }
                if ( empty( $country ) )
                {
                	$country = eZShopFunctions::getPreferredUserCountry();
                }
            	if ( strlen( $country ) == 2 )
		    	{
		    		$countries = eZCountryType::fetchCountryList();
		    		foreach( $countries as $countryItem )
		    		{
		    			
		    			if ($countryItem['Alpha2'] == $country )
		    			{
		    				$country = $countryItem['Alpha3'];
		    			}
		    		}
		    		
		    	}
                $percentage = xrowECommerceVATHandler::getTAX( $country, $state, $taxid );
            }
            else
            {
            	eZDebug::writeError( 'XML is broken', 'xrowECommerceVATHandler' );
            }
        }
        elseif ( $order === false and ! $user->isAnonymous() )
        {
            $object = $user->attribute( 'contentobject' );
            $country = eZVATManager::getUserCountry( $user, false );
        	if ( strlen( $country ) == 2 )
	    	{
	    		$countries = eZCountryType::fetchCountryList();
	    		foreach( $countries as $countryItem )
	    		{
	    			
	    			if ($countryItem['Alpha2'] == $country )
	    			{
	    				$country = $countryItem['Alpha3'];
	    			}
	    		}
	    		
	    	}
            $percentage = xrowECommerceVATHandler::getTAX( $country, false, false );
            if ( is_object( $object ) )
            {
                $dm = $object->dataMap();
                if ( isset( $dm['tax_id'] ) and $dm['tax_id']->attribute( 'has_content' ) )
                {
                    $percentage = xrowECommerceVATHandler::getTAX( $country, false, true );
                }
            }
        }
        elseif ( $order === false and $user->isAnonymous() )
        {
            $country = eZShopFunctions::getPreferredUserCountry();
            $percentage = xrowECommerceVATHandler::getTAX( $country, false, false );
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
        $merchant = xrowECommerce::merchantsLocations();
        
        if ( ! is_array( $merchant ) and count( $merchant ) > 0 )
        {
            throw new Exception( "Merchant locations not setup in xrowecommerce.ini." );
        }
        $matched = FALSE;
        foreach ( $merchant as $merchantitem )
        {
            if ( !$matched )
            {
                if ( !is_array( $merchantitem ) and $merchantitem == $country )
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
