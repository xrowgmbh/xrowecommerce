<?php

$Path       = array();
$Result     = array();
$User       = eZUser::currentUser();
$Email      = $User->Email;
$CustomerID = $User->ContentObjectID;
$Email      = urldecode( $Email );
$OrderList  = eZOrder::orderList( $CustomerID, $Email );
$tpl = eZTemplate::factory();

rsort($OrderList);
$tpl->setVariable( "order_list", $OrderList );

$Path[]             = array( 'url' => false,
                             'text' => ezpI18n::tr( 'kernel/shop', 'Order history' ) );

$Result['content']  = $tpl->fetch( "design:shop/orderhistory.tpl" );
$Result['path']     = $Path;
?>