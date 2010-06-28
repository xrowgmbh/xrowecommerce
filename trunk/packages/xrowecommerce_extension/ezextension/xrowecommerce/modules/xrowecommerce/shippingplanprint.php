<?php

$OrderID = $Params['OrderID'];
$module = $Params['Module'];


$ini = eZINI::instance();
$http = eZHTTPTool::instance();
$user = eZUser::currentUser();
$access = false;
$order = eZOrder::fetch( $OrderID );
if ( !$order )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$tpl = eZTemplate::factory();

$tpl->setVariable( "order", $order );
$tpl->setVariable( "package_list", xrowECommerce::getPackageListArray( $order ) );


$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/shippingplan.tpl" );
$Result['pagelayout'] = 'print_pagelayout.tpl';
$Result['path'] = array( array( 'url' => 'shop/orderlist',
                                'text' => ezpI18n::tr( 'kernel/shop', 'Shipping plan' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/shop', 'Order #%order_id', null, array( '%order_id' => $order->attribute( 'order_nr' ) ) ) ) );

?>
