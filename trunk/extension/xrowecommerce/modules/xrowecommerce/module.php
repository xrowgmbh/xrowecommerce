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

$FunctionList['buy'] = array( );
$FunctionList['administrate'] = array( );
$FunctionList['bypass_captcha'] = array( );
$FunctionList['payment'] = array( 'Payment' => $Payment );
?>
