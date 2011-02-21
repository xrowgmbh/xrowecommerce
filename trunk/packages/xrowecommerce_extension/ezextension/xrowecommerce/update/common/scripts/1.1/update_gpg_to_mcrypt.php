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

/******************************************
 * Set here your parameters
 ******************************************/
// Initialize the gpg parameters
$keyGPG = 'SetYourOldKeyHere0123456789';
// Initialize the mcrypt parameters
$key = 'SetYourNewKeyHere0123456789';
$algorithm = 'tripledes';
$mode = 'cfb';

$db = eZDB::instance();
$script->setIterationData( '.', '~' );

$encodedGPGUser = array();
$decodedUser = array();
$encodedMcryptUser = array();

$cli->output('Get the creditcard data from user account');

// decode and encode the creditcard data from user account
while ( $attributes = eZPersistentObject::fetchObjectList( eZContentObjectAttribute::definition(), null, array( 
    'data_type_string' => ezcreditcardType::DATA_TYPE_STRING 
), null, array( 
    'offset' => $offset , 
    'limit' => $limit 
) ) )
{
    foreach ( $attributes as $attribute )
    {
        if ( $attribute->attribute( 'data_text' ) != '' )
        {
            // decode data
            $content = XROWRecurringordersCommonFunctions::createArrayfromXML( $attribute->attribute( 'data_text' ) );
            $encodedGPGUser[] = $content;
            #print_r($content);
            if ( $content['type'] != '' && $content['has_stored_card'] == 1 )
            {
                if ( $content['type'] == ezcreditcardType::EUROCARD )
                {
                    if ( isset( $content['accountnumber'] ) and strlen( $content['accountnumber'] ) > 0 )
                    {
                        $content['accountnumber'] = decryptDataGPG( $content['accountnumber'], $keyGPG );
                    }
                    if ( isset( $content['ecname'] ) and strlen( $content['ecname'] ) > 0 )
                    {
                        $content['ecname'] = decryptDataGPG( $content['ecname'], $keyGPG );
                    }
                    if ( isset( $content['bankcode'] ) and strlen( $content['bankcode'] ) > 0 )
                    {
                        $content['bankcode'] = decryptDataGPG( $content['bankcode'], $keyGPG );
                    }
                } 
                else
                {
                    if ( isset( $content['number'] ) and strlen( $content['number'] ) > 0 )
                    {
                        $content['number'] = decryptDataGPG( $content['number'], $keyGPG );
                    }
                    if ( isset( $content['name'] ) and strlen( $content['name'] ) > 0 )
                    {
                        $content['name'] = decryptDataGPG( $content['name'], $keyGPG );
                    }
                }

                $decodedUser[] = $content;
                if ( isset( $content['accountnumber'] ) )
                {
                    // encode data to save the new encode value
                    $content['accountnumber'] = encryptData( $content['accountnumber'], $key, $algorithm, $mode );
                    $content['ecname'] = encryptData( $content['ecname'], $key, $algorithm, $mode );
                    $content['bankcode'] = encryptData( $content['bankcode'], $key, $algorithm, $mode );
                }
                
                if ( isset( $content['number'] ) )
                {
                    // encode data to save the new encode value
                    $content['name'] = encryptData( $content['name'], $key, $algorithm, $mode );
                    $content['number'] = encryptData( $content['number'], $key, $algorithm, $mode );
                    $content['securitycode'] = encryptData( $content['securitycode'], $key, $algorithm, $mode );
                }

                $encodedMcryptUser[] = $content;
                // set xml node attributes for the root creditcard -> <creditcard encryption='mcrypt' mode='xxx' algorithm='xxx'>...</creditcard>
                // maybe we need it later
                $root_attribute = array( 'encryption' => 'mcrypt', 'mode' => $mode, 'algorithm' => $algorithm );
                $doc = XROWRecurringordersCommonFunctions::createDOMTreefromArray( 'creditcard', $content, false, $root_attribute );
                $xml = $doc->saveXML();
                $script->iterate( $cli, true, '' );
                $attribute->setAttribute( "data_text", $xml );
                eZPersistentObject::storeObject( $attribute );
            }
        }
    }
    
    $offset += $limit;
}

$cli->output('Get the creditcard data from order');
         
$limit = 20;
$offset = 0;

$encodedGPGOrder = array();
$decodedOrder = array();
$encodedMcryptOrder = array();

// decode and encode the creditcard data from order
while ( $attributesOrder = eZPersistentObject::fetchObjectList( eZOrder::definition(), null, null, null, array( 
    'offset' => $offset , 
    'limit' => $limit 
) ) )
{
    foreach ( $attributesOrder as $attributeOrder )
    {
        if ( $attributeOrder->attribute( 'data_text_1' ) != '' )
        {
            // decode data
            $contentOrder = XROWRecurringordersCommonFunctions::createArrayfromXML( $attributeOrder->attribute( 'data_text_1' ) );

            if ( array_key_exists( 'ezauthorize-card-number', $contentOrder) )
            {
                $encodedGPGOrder[] = $contentOrder;
                $creditCardData = array();
                $Type = strtoupper($contentOrder['ezauthorize-card-type']);
                switch ( $Type )
                {
                    case 'MASTERCARD':
                        $creditCardData['type'] = 1;
                        break;
                    case 'VISA':
                        $creditCardData['type'] = 2;
                        break;
                    case 'DISCOVER':
                        $creditCardData['type'] = 3;
                        break;
                    case 'AMERICANEXPRESS':
                        $creditCardData['type'] = 4;
                        break;
                    case 'EUROCARD':
                        $creditCardData['type'] = 5;
                        break;
                    default:
                        $creditCardData['type'] = 0;
                        break;
                }
                if ( isset( $contentOrder['ezauthorize-card-name'] ) and strlen( $contentOrder['ezauthorize-card-name'] ) > 0 )
                {
                    $contentOrder['ezauthorize-card-name'] = decryptDataGPG( $contentOrder['ezauthorize-card-name'], $keyGPG );
                }
                if ( isset( $contentOrder['ezauthorize-card-number'] ) and strlen( $contentOrder['ezauthorize-card-number'] ) > 0 )
                {
                    $contentOrder['ezauthorize-card-number'] = decryptDataGPG( $contentOrder['ezauthorize-card-number'], $keyGPG );
                }
                // if your secure code is encode activate this if clause
                #if ( isset( $contentOrder['ezauthorize-security-number'] ) and strlen( $contentOrder['ezauthorize-security-number'] ) > 0 )
                #{
                #    $contentOrder['ezauthorize-security-number'] = decryptDataGPG( $contentOrder['ezauthorize-security-number'], $keyGPG );
                #}
                $decodedOrder[] = $contentOrder;
                // encode data to save the new encode value
                $contentOrder['ezauthorize-card-name'] = encryptData( $contentOrder['ezauthorize-card-name'], $key, $algorithm, $mode );
                $contentOrder['ezauthorize-card-number'] = encryptData( $contentOrder['ezauthorize-card-number'], $key, $algorithm, $mode );
                if ( isset( $contentOrder['ezauthorize-security-number'] ) and strlen( $contentOrder['ezauthorize-security-number'] ) > 0 )
                {
                    $contentOrder['ezauthorize-security-number'] = encryptData( $contentOrder['ezauthorize-security-number'], $key, $algorithm, $mode );
                }
                $encodedMcryptOrder[] = $contentOrder;
                $docOrder = XROWRecurringordersCommonFunctions::createDOMTreefromArray( 'shop_account', $contentOrder );
                $xmlOrder = $docOrder->saveXML();
                $script->iterate( $cli, true, '' );
                $attributeOrder->setAttribute( "data_text_1", $xmlOrder );
                eZPersistentObject::storeObject( $attributeOrder );
            }
        }
    }
    $offset += $limit;
}

if ( count( $encodedGPGUser ) > 0 )
{
      $F = fopen( "user_encodedGPG.csv", "w" );
      foreach( $encodedGPGUser as $object )
      {
      $line = "\"".implode("\";\"", $object)."\"\n";
        fputs($F, $line);
    }
    fclose($F);
}
if ( count( $decodedUser ) > 0 )
{
      $F = fopen( "user_decoded.csv", "w" );
      foreach( $decodedUser as $object )
      {
      $line = "\"".implode("\";\"", $object)."\"\n";
        fputs($F, $line);
    }
    fclose($F);
}
if ( count( $encodedMcryptUser ) > 0 )
{
      $F = fopen( "user_encodedMcrypt.csv", "w" );
      foreach( $encodedMcryptUser as $object )
      {
      $line = "\"".implode("\";\"", $object)."\"\n";
        fputs($F, $line);
    }
    fclose($F);
}

if ( count( $encodedGPGOrder ) > 0 )
{
      $F = fopen( "order_encodedGPG.csv", "w" );
      foreach( $encodedGPGOrder as $object )
      {
      $line = "\"".implode("\";\"", $object)."\"\n";
        fputs($F, $line);
    }
    fclose($F);
}
if ( count( $decodedOrder ) > 0 )
{
      $F = fopen( "order_decoded.csv", "w" );
      foreach( $decodedOrder as $object )
      {
      $line = "\"".implode("\";\"", $object)."\"\n";
        fputs($F, $line);
    }
    fclose($F);
}
if ( count( $encodedMcryptOrder ) > 0 )
{
      $F = fopen( "order_encodedMcrypt.csv", "w" );
      foreach( $encodedMcryptOrder as $object )
      {
      $line = "\"".implode("\";\"", $object)."\"\n";
        fputs($F, $line);
    }
    fclose($F);
}


$script->shutdown();


function encryptData( $planeText, $key, $algorithm, $mode )
{
    $td = mcrypt_module_open( $algorithm, '', $mode, '' );
    $iv = mcrypt_create_iv( mcrypt_enc_get_iv_size( $td ), MCRYPT_RAND );
    $okey = substr( md5( $key . rand( 0, 9 ) ), 0, mcrypt_enc_get_key_size( $td ) );
    mcrypt_generic_init( $td, $okey, $iv );
    $encrypted = mcrypt_generic( $td, $planeText . chr( 194 ) );
    $encryptedString = $encrypted . $iv;
    return base64_encode( $encryptedString );
}

function decryptDataGPG( $value, $key )
{
    $return = $value;
    if ( include_once( 'extension/ezgpg/autoloads/ezgpg_operators.php' ) )
    {
        $return = eZGPGOperators::gpgDecode( $value, $key );
        if ( $return !== false )
        {
            $value = $return;
        }
    }
    return $value;
}

?>