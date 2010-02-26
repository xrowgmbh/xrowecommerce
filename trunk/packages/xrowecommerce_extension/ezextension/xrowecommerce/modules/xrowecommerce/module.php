<?php
$Module = array( "name" => "xrow e-commerce" );

$ViewList = array();

$ViewList["userregister"] = array(
    "functions" => array( 'buy' ),
    "script" => "userregister.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    'single_post_actions' => array( 'StoreButton' => 'Store',
                                    'CancelButton' => 'Cancel'
                                    )
    );
$ViewList["register"] = array(
    "functions" => array( ),
    "script" => "register.php"
    );
$ViewList["basket"] = array(
    "functions" => array( 'buy' ),
    "script" => "basket.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    'unordered_params' => array( 'error' => 'Error' ),
    "params" => array(  ) );

$ViewList["multiadd"] = array(
    "functions" => array( 'buy' ),
    "script" => "multiadd.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array(  ) );

$ViewList["statistics"] = array(
    "functions" => array( 'administrate' ),
    "script" => "orderstatistics.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array( 'StartYear', 'StartMonth', 'StopMonth', 'StartDay', 'StopDay' ) );

$ViewList["invoiceprint"] = array(
    "functions" => array( 'buy' ),
    "script" => "invoiceprint.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array( "OrderID" ) );

$ViewList["shippingplanprint"] = array(
    "functions" => array( 'administrate' ),
    "script" => "shippingplanprint.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array( "OrderID" ) );

$ViewList["tin"] = array(
    "functions" => array( 'administrate' ),
    "script" => "tin.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array() );

$ViewList["priceimport"] = array(
    "functions" => array( 'administrate' ),
    "script" => "price_import.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array() );

$ViewList["priceexport"] = array(
    "functions" => array( 'administrate' ),
    "script" => "price_export.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array() );

$ViewList["directorder"] = array(
    "script" => "direct_order.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array() );

$Payment = array(
    'name' => 'Payment',
    'values' => array(),
    'extension' => 'xrowecommerce',
    'path' => 'classes/',
    'file' => 'xrowecommerce.php',
    'class' => 'xrowECommerce',
    'function' => 'paymentLimitationList',
    'parameter' => array( false )
    );
$ViewList["orderlist"] = array(
    "functions" => array( 'administrate' ),
    "script" => "orderlist.php",
    "default_navigation_part" => 'ezshopnavigationpart',
    "unordered_params" => array( "offset" => "Offset" ),
    "params" => array(  ) );

$ViewList["removeorder"] = array(
    "functions" => array( 'remove_order' ),
    "script" => "removeorder.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array() );

$ViewList["archiveorder"] = array(
    "functions" => array( 'administrate' ),
    "script" => "archiveorder.php",
    'ui_context' => 'edit',
    "default_navigation_part" => 'ezshopnavigationpart',
    "params" => array() );

$ViewList["json"] = array( "script" => "json.php", "params" => array ( 'object', 'method' ) ); 


$FunctionList['buy'] = array( );
$FunctionList['administrate'] = array( );
$FunctionList['remove_order'] = array( );
$FunctionList['bypass_captcha'] = array( );
$FunctionList['payment'] = array( 'Payment' => $Payment );
?>
