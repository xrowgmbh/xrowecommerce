<?php

include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/eznodeviewfunctions.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'kernel/classes/ezorder.php' );
include_once( 'kernel/classes/ezorderitem.php' );
include_once( 'kernel/classes/ezorderstatus.php' );
include_once( 'kernel/classes/ezpreferences.php' );
$Module  =& $Params['Module'];
$module =& $Params['Module'];
$http =& eZHTTPTool::instance();
$tpl =& templateInit();
$orderNR = $Params['orderid'];
if ( empty( $orderNR ) )
{
    if ( $http->hasPostVariable( 'Edit' ) )
    {
        if ( $http->hasPostVariable( 'order_id' ) )
        {
            $orderNR = $http->postVariable( 'order_id' );
        }
    }
}
if ( $http->hasPostVariable( 'Cancel' ) )
{
    $Module->redirectTo( '/shop/orderlist' );
}
if ( $http->hasPostVariable( 'Store' ) )
{
    if ( $http->hasPostVariable( 'orderitemid' ) )
    {
        $orderitemids = $http->postVariable( 'orderitemid' );
        if ( is_array( $orderitemids ) )
        {
            foreach ( $orderitemids as $item )
            {
                $objects = eZPersistentObject::fetchObjectList( eZOrderItem::definition(),
                                                    null,
                                                    array( "id" => $item ),
                                                    null,
                                                    null,
                                                    true );
                $object = $objects[0];
                if ( $http->hasPostVariable( 'price_'.$item ) )
                {
                    $price = $http->PostVariable( 'price_'.$item );
                    $object->setAttribute('price', $price);
                    unset ($price);
                }
                if ( $http->hasPostVariable( 'description_'.$item ) )
                {
                    $description = $http->PostVariable( 'description_'.$item );
                    $object->setAttribute('description', $description);
                    unset ($description);
                }
                    
                $object->store();
                $Module->redirectTo( '/shop/orderlist' );
            }
        }
        
    }
}

if ( is_numeric( $orderNR ) )
{
    
    $order = eZPersistentObject::fetchObject( eZOrder::definition(),
                                                null,
                                                array( "order_nr" => $orderNR ),
                                                true );
    if ( is_object( $order ) )
    {
        $orderitemarray = eZOrderItem::fetchList( $order->ID );
    }
    else 
        $Module->redirectTo( '/shop/orderlist' );
if ( count( $orderitemarray ) >= 1 )
{
    $tpl->setVariable( 'orderitems', $orderitemarray );
    $tpl->setVariable( 'ordernr', $orderNR );
    $tpl->setVariable( 'order', $order );
    
}
else 
        $Module->redirectTo( '/shop/orderlist' );

$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/ordereditedit.tpl" );
$path = array();
$path[] = array( 'url' => '/orderedit/edit',
                 'text' => ezi18n( 'kernel/shop', 'Orderedit' ) );
$path[] = array( 'url' => false,
                 'text' => ezi18n( 'kernel/shop', 'Orderitem' ) );
$Result['path'] = $path;
}
else 
    $Module->redirectTo( '/shop/orderlist' );
?>