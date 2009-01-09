<?php

$FunctionList = array();
$FunctionList['list_methods'] = array( 'name' => 'list_methods',
'operation_types' => array( 'read' ),
                                           'call_method' => array( 'class' => 'xrowShippingFunctionCollection',
                                                                   'method' => 'listMethods' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( ) );
$FunctionList['list_all_methods'] = array( 'name' => 'list_all_methods',
'operation_types' => array( 'read' ),
                                           'call_method' => array( 'class' => 'xrowShippingFunctionCollection',
                                                                   'method' => 'listAllMethods' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( ) );
?>