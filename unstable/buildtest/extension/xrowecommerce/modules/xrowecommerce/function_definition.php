<?php

$FunctionList = array();
$FunctionList['payment_status'] = array( 'name' => 'payment_status',
                                    'operation_types' => array( 'read' ),
                                    'call_method' => array( 'class' => 'xrowECommerceFunctionCollection',
                                                            'method' => 'paymentStatus' ),
                                    'parameter_type' => 'standard',
                                    'parameters' => array( array( 'name' => 'id',
                                                                  'type' => 'integer',
                                                                  'required' => true ) ) );
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

$FunctionList['variation'] = array( 'name' => 'variation',
                                    'operation_types' => array( 'read' ),
                                    'call_method' => array( 'class' => 'xrowECommerceFunctionCollection',
                                                            'method' => 'fetchVariation' ),
                                    'parameter_type' => 'standard',
                                    'parameters' => array( array( 'name' => 'id',
                                                                  'type' => 'integer',
                                                                  'required' => true ) ) );

$FunctionList['sliding_price'] = array( 'name' => 'sliding_price',
                                    'operation_types' => array( 'read' ),
                                    'call_method' => array( 'class' => 'xrowECommerceFunctionCollection',
                                                            'method' => 'fetchSlidingPrice' ),
                                    'parameter_type' => 'standard',
                                    'parameters' => array( array( 'name' => 'price_id',
                                                                  'type' => 'integer',
                                                                  'required' => true ),
                                                           array( 'name' => 'amount',
                                                                  'type' => 'integer',
                                                                  'required' => false )
                                                            ) );

$FunctionList['has_sliding_price'] = array( 'name' => 'sliding_price',
                                    'operation_types' => array( 'read' ),
                                    'call_method' => array( 'class' => 'xrowECommerceFunctionCollection',
                                                            'method' => 'hasSlidingPrice' ),
                                    'parameter_type' => 'standard',
                                    'parameters' => array( array( 'name' => 'attribute_id',
                                                                  'type' => 'integer',
                                                                  'required' => true ),
                                                           array( 'name' => 'version',
                                                                  'type' => 'integer',
                                                                  'required' => true ),
                                                           array( 'name' => 'language',
                                                                  'type' => 'string',
                                                                  'required' => true ),
                                                            ) );

?>