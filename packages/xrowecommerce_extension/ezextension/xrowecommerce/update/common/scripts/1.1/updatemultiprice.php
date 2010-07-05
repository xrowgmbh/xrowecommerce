#!/usr/bin/env php
<?php

require 'autoload.php';

$cli = eZCLI::instance();

$script = eZScript::instance( array( 
    'description' => ( "" ) , 
    'use-session' => false , 
    'use-modules' => false , 
    'use-extensions' => false 
) );

$script->startup();

$options = $script->getOptions( '', '', array() );
$script->initialize();

$limit = 20;
$offset = 0;

$db = eZDB::instance();

$script->setIterationData( '.', '~' );

while ( $attributes = eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(), null, array( 
    'data_type_string' => eZOption2Type::OPTION2 
), null, array( 
    'offset' => $offset , 
    'limit' => $limit 
) ) )
{
    foreach ( $attributes as $attribute )
    {
        $xmlString = $attribute->attribute( "data_text" );
        $doc = new DOMDocument( '1.0', 'utf-8' );
        $success = $doc->loadXML( $xmlString );
        $optionNodes = $doc->getElementsByTagName( "option" );
        foreach ( $optionNodes as $optionNode )
        {
            if ( $optionNode->hasAttribute( 'additional_price' ) )
            {
                $price = $optionNode->getAttribute( 'additional_price' );
                if ( empty( $price ) )
                {
                	$price = '0.00';
                }
                $optionNode->removeAttribute( 'additional_price' );
                
                $multi_price = $doc->createElement( "multi_price" );
                
                $priceNode = $doc->createElement( 'price' );
                
                $priceNode->setAttribute( 'currency_code', eZShopFunctions::preferredCurrencyCode() ); # if preferredCurrencyCode not set, write you currency code like 'EUR' or 'USD'
                $priceNode->setAttribute( 'value', $price );
                $priceNode->setAttribute( 'type', eZMultiPriceData::VALUE_TYPE_CUSTOM );
                
                $multi_price->appendChild( $priceNode );
                unset( $priceNode );
                
                $optionNode->appendChild( $multi_price );
                unset( $multi_price );
            }
            unset( $optionNode );
        
        }
        $text = "Changing data_text content";
        $script->iterate( $cli, true, $text );
        $xml = $doc->saveXML();
        $attribute->setAttribute( "data_text", $xml );
        eZPersistentObject::storeObject( $attribute );
        unset( $doc );
    }
    
    $offset += $limit;
}

$script->shutdown();

?>
