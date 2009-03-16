<?php

$FunctionList = array();
$FunctionList['get_country_list'] = array( 'name' => 'get_country_list',
                                           'operation_types' => array( 'read' ),
                                           'call_method' => array( 'class' => 'xrowECommerceFunctionCollection',
                                                                   'method' => 'getCountryList' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( ) );
$FunctionList['get_shopaccount_value'] = array( 'name' => 'get_shopaccount_value',
                                           'operation_types' => array( 'read' ),
                                           'call_method' => array( 'class' => 'xrowECommerceFunctionCollection',
                                                                   'method' => 'getShopAccountValue' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( array( 'name' => 'name',
                                                              		     'type' => 'string',
                                                                         'required' => true ),
														   array( 'name' => 'order',
                                                              		     'type' => 'object',
                                                                         'required' => false ) ) );
$FunctionList['list_all_gateways'] = array( 'name' => 'list_all_gateways',
                                            'operation_types' => array( 'read' ),
                                           'call_method' => array( 'class' => 'xrowECommerceFunctionCollection',
                                                                   'method' => 'listAllGateways' ),
                                           'parameter_type' => 'standard',
                                           'parameters' => array( ) );
?>