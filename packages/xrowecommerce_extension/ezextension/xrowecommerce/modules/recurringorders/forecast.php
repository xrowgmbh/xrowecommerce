<?php

$Module =& $Params['Module'];
include_once( 'kernel/common/template.php' );
$tpl = eZTemplate::factory();

if ( isset( $Params['UserParameters'] ) )
{
    $viewParameters = $Params['UserParameters'];
}
else
{
    $viewParameters = array();
}
$offset = (int)$viewParameters['offset'];

$http = eZHTTPTool::instance();

$limit = 10;

$http = eZHTTPTool::instance();

$now = gmmktime( );
$time = gmmktime( 0,0,0, 12, 1, 2007 );
$list = XROWRecurringOrderItem::fetchObjectList( XROWRecurringOrderItem::definition(), null, null, array( 'contentobject_id' => false ) );
$list_result = array();
foreach ( $list as $item )
{
    $options = $item->options();
    $key ='';
    foreach ( $options as $option )
    {
        $key .= $option['id'] . '_';
    }
    eZDebug::writeDebug($key,'key');
    if ( !isset( $list_result[$item->contentobject_id]['object'] ) )
    {
        $list_result[$item->contentobject_id]['object'] = $item->object();
    }
    if ( !isset( $list_result[$item->contentobject_id]['variations'][$key] ) )
    {
        $list_result[$item->contentobject_id]['variations'][$key] = array();
        $list_result[$item->contentobject_id]['variations'][$key]['options'] = $options;

    }
    if ( !isset( $list_result[$item->contentobject_id]['variations'][$key]['cycles'][$item->cycle_unit] ) )
    {
        $list_result[$item->contentobject_id]['variations'][$key]['cycles'][$item->cycle_unit] = array();
        $list_result[$item->contentobject_id]['variations'][$key]['cycles'][$item->cycle_unit]['amount'] = $item->amount / $item->cycle;
        $list_result[$item->contentobject_id]['variations'][$key]['cycles'][$item->cycle_unit]['unit_price'] = $item->pricePerItem();
        $list_result[$item->contentobject_id]['variations'][$key]['cycles'][$item->cycle_unit]['total_price'] = $item->pricePerItem() * $item->amount / $item->cycle;

    }
    else
    {
        $list_result[$item->contentobject_id]['variations'][$key]['cycles'][$item->cycle_unit]['amount'] += $item->amount / $item->cycle;
        $list_result[$item->contentobject_id]['variations'][$key]['cycles'][$item->cycle_unit]['total_price'] += $item->pricePerItem() * $item->amount / $item->cycle;
    }
    if ( !isset( $totals[$item->cycle_unit] ) )
    {
        $totals[$item->cycle_unit] = $item->pricePerItem() * $item->amount / $item->cycle;
    }
    else
    {
        $totals[$item->cycle_unit] += $item->pricePerItem() * $item->amount / $item->cycle;
    }
        
}
$tpl->setVariable( "limit", $limit );
$tpl->setVariable( 'list', $list_result );
$tpl->setVariable( 'totals', $totals );
$tpl->setVariable( 'type_text_array', XROWRecurringOrderCollection::getBillingCycleTextAdjectiveArray() );
$tpl->setVariable( 'list_count', count( $list_result ) );
$viewParameters = array( 'offset' => $offset );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'cycles', XROWRecurringOrderCollection::getAllCycleTypes() );
$Result = array();

$Result['left_menu'] = "design:parts/xrowrecurringorders/menu.tpl";
$Result['content'] = $tpl->fetch( "design:recurringorders/forecast.tpl" );
$Result['path'] = array( array( 'url' => false,
                        'text' => 'Recurring orders' ),
                        array( 'url' => false,
                        'text' => 'Forecast'
                         ) );
?>
