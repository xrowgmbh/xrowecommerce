#!/usr/bin/env php
<?php

require 'autoload.php';

/*!
 update content classes
*/
function attributeIdentifierExists( eZContentClass $class, $identifier )
{
    if ( $class->fetchAttributeByIdentifier( $identifier ) instanceof eZContentClassAttribute )
    {
        return true;
    }
    else
    {
        return false;
    }
}

function updateClasses( $ident )
{
    $installer = new eZSiteInstaller( );
    $class = eZContentClass::fetchByIdentifier( $ident );
    $attributes = array();
    if ( ! attributeIdentifierExists( $class, 'company_name' ) )
    {
        $attributes[] = array( 
            'identifier' => 'company_name' , 
            'can_translate' => false , 
            'name' => 'Company name' , 
            'data_type_string' => 'ezstring' 
        );
    
    }
    if ( ! attributeIdentifierExists( $class, 'company_additional' ) )
    {
        $attributes[] = array( 
            'identifier' => 'company_additional' , 
            'can_translate' => false , 
            'name' => 'Company additional' , 
            'data_type_string' => 'ezstring' 
        );
    
    }
    if ( ! attributeIdentifierExists( $class, 'tax_id' ) )
    {
        $attributes[] = array( 
            'identifier' => 'tax_id' , 
            'can_translate' => false , 
            'name' => 'Tax ID' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 'first_name' ) )
    {
        $attributes[] = array( 
            'identifier' => 'first_name' , 
            'can_translate' => false , 
            'name' => 'First Name' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 'last_name' ) )
    {
        $attributes[] = array( 
            'identifier' => 'last_name' , 
            'can_translate' => false , 
            'name' => 'Last Name' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 'address1' ) )
    {
        $attributes[] = array( 
            'identifier' => 'address1' , 
            'can_translate' => false , 
            'name' => 'Address' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 'address2' ) )
    {
        $attributes[] = array( 
            'identifier' => 'address2' , 
            'can_translate' => false , 
            'name' => 'Additional address' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 'state' ) )
    {
        $attributes[] = array( 
            'identifier' => 'state' , 
            'can_translate' => false , 
            'name' => 'State' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 'zip' ) )
    {
        $attributes[] = array( 
            'identifier' => 'zip' , 
            'can_translate' => false , 
            'name' => 'ZIP' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 'city' ) )
    {
        $attributes[] = array( 
            'identifier' => 'city' , 
            'can_translate' => false , 
            'name' => 'City' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 'country' ) )
    {
        $attributes[] = array( 
            'identifier' => 'country' , 
            'can_translate' => false , 
            'name' => 'Country' , 
            'data_type_string' => 'ezcountry' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 'phone' ) )
    {
        $attributes[] = array( 
            'identifier' => 'phone' , 
            'can_translate' => false , 
            'name' => 'Phone' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 'fax' ) )
    {
        $attributes[] = array( 
            'identifier' => 'fax' , 
            'name' => 'Fax' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 'shippingaddress' ) )
    {
        $attributes[] = array( 
            'identifier' => 'shippingaddress' , 
            'can_translate' => false , 
            'name' => 'Billing and shipping addresses are identical' , 
            'data_type_string' => 'ezboolean' , 
            'default_value' => '1' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 's_company_name' ) )
    {
        $attributes[] = array( 
            'identifier' => 's_company_name' , 
            'can_translate' => false , 
            'name' => 'Shipping company name' , 
            'data_type_string' => 'ezstring' 
        );
    
    }
    if ( ! attributeIdentifierExists( $class, 's_company_additional' ) )
    {
        $attributes[] = array( 
            'identifier' => 's_company_additional' , 
            'can_translate' => false , 
            'name' => 'Shipping company additional' , 
            'data_type_string' => 'ezstring' 
        );
    
    }
    
    if ( ! attributeIdentifierExists( $class, 's_first_name' ) )
    {
        $attributes[] = array( 
            'identifier' => 's_first_name' , 
            'can_translate' => false , 
            'name' => 'Shipping first name' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 's_last_name' ) )
    {
        $attributes[] = array( 
            'identifier' => 's_last_name' , 
            'can_translate' => false , 
            'name' => 'Shipping last name' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 's_address1' ) )
    {
        $attributes[] = array( 
            'identifier' => 's_address1' , 
            'name' => 'Address' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 's_address2' ) )
    {
        $attributes[] = array( 
            'identifier' => 's_address2' , 
            'can_translate' => false , 
            'name' => 'Shipping additional address' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 's_state' ) )
    {
        $attributes[] = array( 
            'identifier' => 's_state' , 
            'can_translate' => false , 
            'name' => 'Shipping state' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 's_zip' ) )
    {
        $attributes[] = array( 
            'identifier' => 's_zip' , 
            'can_translate' => false , 
            'name' => 'Shippping ZIP' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 's_city' ) )
    {
        $attributes[] = array( 
            'identifier' => 's_city' , 
            'can_translate' => false , 
            'name' => 'Shipping City' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 's_country' ) )
    {
        $attributes[] = array( 
            'identifier' => 's_country' , 
            'can_translate' => false , 
            'name' => 'Shipping country' , 
            'data_type_string' => 'ezcountry' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 's_phone' ) )
    {
        $attributes[] = array( 
            'identifier' => 's_phone' , 
            'can_translate' => false , 
            'name' => 'Shipping Phone' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 's_fax' ) )
    {
        $attributes[] = array( 
            'identifier' => 's_fax' , 
            'can_translate' => false , 
            'name' => 'Shipping Fax' , 
            'data_type_string' => 'ezstring' 
        );
    }
    if ( ! attributeIdentifierExists( $class, 's_email' ) )
    {
        $attributes[] = array( 
            'identifier' => 's_email' , 
            'can_translate' => false , 
            'name' => 'Shipping email' , 
            'data_type_string' => 'ezemail' 
        );
    }
    $installer->addClassAttributes( array( 
        'class' => array( 
            'identifier' => $ident 
        ) , 
        'attributes' => $attributes 
    ) );

}

// script initializing
$cli = eZCLI::instance();
$script = eZScript::instance( array( 
    'description' => ( "\n" . "This script will upgrade ez." ) , 
    'use-session' => false , 
    'use-modules' => true , 
    'use-extensions' => true , 
    'user' => true 
) );
$script->startup();

$scriptOptions = $script->getOptions( "[class:]", "", array( 
    'class' => 'Class to update' 
), false, array( 
    'user' => true 
) );

$script->initialize();
if ( ! isset( $scriptOptions['class'] ) )
{
    $cli->error( 'Please supply the class parameter' );
    $script->shutdown( 1 );
    return;
}
updateClasses( $scriptOptions['class'] );
$cli->output( "Upgrade complete" );
$script->shutdown( 0 );

?>
