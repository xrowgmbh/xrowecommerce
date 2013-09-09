<?php

$http = eZHTTPTool::instance();
$Module = $Params['Module'];

$export = true;
$exportArray = array();
$errorArray = array();

$tpl = eZTemplate::factory();
$result = false;

$email = $country = "";

if ( $http->hasPostVariable( 'ExportButton' ) )
{
    $export = true;
    $country = $http->postVariable( 'Country' );
    $email = $http->postVariable( 'Email' );
    
    $db = eZDB::instance();
    
    $action = "xrowpriceexport";
    $params = array( 'email' => $email, 
                     'country' => $country );
    $params = $db->escapeString( serialize( $params ) );
        
    $sql = "INSERT INTO ezpending_actions ( action, param, created ) VALUES ( '$action', '$params', '" . time() . "' )";
    $db->query( $sql );
    
    $result = true;
    
}

$tpl->setVariable( "result", $result );
$tpl->setVariable( "email", $email );

$tpl->setVariable( "export", $export );
$tpl->setVariable( "error_array", $errorArray );


$Result = array();
$Result['content'] = $tpl->fetch( "design:xrowecommerce/price_export.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'extension/xrowecommerce', 'Price export' ) ) );



?>