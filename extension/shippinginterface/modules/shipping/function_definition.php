<?php

$FunctionList = array();
$FunctionList['list_methods'] = array( 'name' => 'list_methods',
'operation_types' => array( 'read' ),
                                           'call_method' => array( 'class' => 'xrowShippingFunctionCollection',
                                                                   'method' => 'listMethods' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( ) );

?>