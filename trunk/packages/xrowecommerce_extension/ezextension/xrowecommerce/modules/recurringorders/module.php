<?php
$Module = array( "name" => "Recurring orders" );

$ViewList = array();
$ViewList["add"] = array( 
    "functions" => array( 'use' ),
    "script" => "add.php"
);
$ViewList["list"] = array( 
            "default_navigation_part" => 'xrowrecurringorders',
            "functions" => array( 'use' ),
            'single_post_actions' => array( 'Remove' => 'Remove',
                                            'Cancel' => 'Cancel',
                                            'Update' => 'Update' ),
            'post_action_parameters' => array( 
                                            'Remove' => array( 'RemoveArray' => 'RemoveArray' ),
                                            'Cancel' => array( ),
                                            'Update' => array( 
                                                                'ItemArray' => 'ItemArray',
                                                                'SendDay' => 'SendDay',
                                                                'Pause' => 'Pause'
                                                             )
                                        ),
            'params' => array( 'CollectionID' ),
            'script' => 'list.php' );
$ViewList['listitems'] = array(
    "default_navigation_part" => 'xrowrecurringorders',
            'single_post_actions' => array( 'Cancel' => 'Cancel',
                                            'Update' => 'Update' ),
            'post_action_parameters' => array( 
                                            'Cancel' => array( ),
                                            'Update' => array( 
                                                                'ItemArray' => 'ItemArray'
                                                             )
                                                                                                     ),
    'functions' => array( 'administrate' ),
    'script' => 'listitems.php' );
$ViewList['history'] = array(
    "default_navigation_part" => 'xrowrecurringorders',
    'functions' => array( 'administrate' ),
    'params' => array( 'Offset' ),
    'script' => 'history.php' );
$ViewList['forecast'] = array(
    "default_navigation_part" => 'xrowrecurringorders',
    'functions' => array( 'administrate' ),
    'script' => 'forecast.php' );
$FunctionList['administrate'] = array( );
$FunctionList['use'] = array( );
?>