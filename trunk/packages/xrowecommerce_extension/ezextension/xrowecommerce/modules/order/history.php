<?php

$Path       = array();
$Result     = array();

if ( !eZUser::isAnonymous() )
{
    $OrderList  = eZOrder::orderList( eZUser::currentUser()->ContentObjectID, false );
}
else 
{
    $OrderList = array();
}

$tpl = eZTemplate::factory();

rsort($OrderList);
$tpl->setVariable( "order_list", $OrderList );

$Path[]             = array( 'url' => false,
                             'text' => ezpI18n::tr( 'kernel/shop', 'Order history' ) );

$Result['content']  = $tpl->fetch( "design:shop/orderhistory.tpl" );
$Result['path']     = $Path;
?>