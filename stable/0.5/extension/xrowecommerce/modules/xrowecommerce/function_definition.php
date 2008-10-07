<?php

$FunctionList = array();
$FunctionList['get_country_list'] = array( 'name' => 'get_country_list',
                                           'operation_types' => array( 'read' ),
                                           'call_method' => array( 'class' => 'xrowECommerceFunctionCollection',
                                                                   'method' => 'getCountryList' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( ) );
?>