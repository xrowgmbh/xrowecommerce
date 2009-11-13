<?php

$FunctionList = array();

$FunctionList['fetch_text'] = array( 'name' => 'fetch_text',
                                 'call_method' => array( 'include_file' => 'extension/recurringorders/modules/recurringorders/recurringordersfunctioncollection.php',
                                                         'class' => 'recurringordersFunctionCollection',
                                                         'method' => 'fetchTextAdjectiveArray' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array( ) );
$FunctionList['now'] = array( 'name' => 'now',
                                 'call_method' => array( 'include_file' => 'extension/recurringorders/modules/recurringorders/recurringordersfunctioncollection.php',
                                                         'class' => 'recurringordersFunctionCollection',
                                                         'method' => 'fetchGMNow' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array( ) );
$FunctionList['has_subscription'] = array( 'name' => 'has_subscription',
                                 'call_method' => array( 'include_file' => 'extension/recurringorders/modules/recurringorders/recurringordersfunctioncollection.php',
                                                         'class' => 'recurringordersFunctionCollection',
                                                         'method' => 'hasSubscription' ),
                                 'parameter_type' => 'standard',
                                 'parameters' => array( array( 'name' => 'object_id',
                                                                'type' => 'integer',
                                                                'required' => true ) ) );
                                 
?>
