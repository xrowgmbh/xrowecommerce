<?php
$Module = array( "name" => "Order",
                 "variable_params" => true );

$ViewList = array();

$ViewList["history"] = array(
    "functions" => array( 'invoice' ),
    "script" => "history.php",
    "default_navigation_part" => 'ezcontentnavigationpart',
    "unordered_params" => array( 'offset' => 'Offset' ),
    "params" => array( ) );
    
$ViewList["invoice"] = array(
    "functions" => array( 'invoice' ),
    "script" => "invoice.php",
    "default_navigation_part" => 'ezcontentnavigationpart',
    "params" => array( "OrderID", "includePackingSlip" ) );

$FunctionList['invoice'] = array( );
?>