<?php
$Module = array( "name" => "Product variation" );

$ViewList = array();

$ViewList['attributelist'] = array(
    'functions' => array( 'viewattribute' ),
    'script' => 'attributelist.php',
    'params' => array( 'Language' ),
    'unordered_params' => array( 'offset' => 'Offset' ),
    'default_navigation_part' => 'ezshopnavigationpart',
    );

$ViewList['attributeedit'] = array(
    'functions' => array( 'editattribute' ),
    'script' => 'attributeedit.php',
    'params' => array( 'ID', 'Language' ),
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezshopnavigationpart'
    );

$ViewList['attributedelete'] = array(
    'functions' => array( 'editattribute' ),
    'script' => 'attributedelete.php',
    'default_navigation_part' => 'ezshopnavigationpart'
    );

$ViewList['templatelist'] = array(
    'functions' => array( 'viewtemplate' ),
    'script' => 'templatelist.php',
    'params' => array( 'Language' ),
    'unordered_params' => array( 'offset' => 'Offset' ),
    'default_navigation_part' => 'ezshopnavigationpart'
    );

$ViewList['templateedit'] = array(
    'functions' => array( 'edittemplate' ),
    'script' => 'templateedit.php',
    'params' => array( 'ID', 'Language' ),
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezshopnavigationpart'
    );

$ViewList['templateremove'] = array(
    'functions' => array( 'removetemplate' ),
    'script' => 'templateremove.php',
    'ui_context' => 'edit',
    'default_navigation_part' => 'ezshopnavigationpart'
    );

$FunctionList = array();
$FunctionList['viewattribute'] = array();
$FunctionList['editattribute'] = array();
$FunctionList['removeattribute'] = array();
$FunctionList['viewtemplate'] = array();
$FunctionList['edittemplate'] = array();
$FunctionList['removetemplate'] = array();
?>