<?php
$Module = array( "name" => "Customersearch" );

$ViewList = array();
$ViewList["search"] = array(
    "functions" => array( "customersearch" ),
    "script" => "search.php");
$ViewList["multiple"] = array(
    "functions" => array( "multiple" ),
    "script" => "multiple.php",
    "params" => array( "email" ) );

$FunctionList["customersearch"] = array( );
?>
