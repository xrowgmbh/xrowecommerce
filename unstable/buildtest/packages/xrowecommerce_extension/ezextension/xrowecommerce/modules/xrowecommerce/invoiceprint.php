<?php

$OrderID = $Params['OrderID'];
$module = $Params['Module'];
require_once( "kernel/common/template.php" );

$ini = eZINI::instance();
$http = eZHTTPTool::instance();
$user = eZUser::currentUser();
$access = false;
$order = eZOrder::fetch( $OrderID );
if ( !$order )
{
    return $module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

$accessToAdministrate = $user->hasAccessTo( 'xrowecommerce', 'administrate' );
$accessToAdministrateWord = $accessToAdministrate['accessWord'];

$accessToBuy = $user->hasAccessTo( 'shop', 'buy' );
$accessToBuyWord = $accessToBuy['accessWord'];

if ( $accessToAdministrateWord != 'no' )
{
    $access = true;
}
elseif ( $accessToBuyWord != 'no' )
{
    if ( $user->id() == $ini->variable( 'UserSettings', 'AnonymousUserID' ) )
    {
        if( $OrderID != $http->sessionVariable( 'UserOrderID' ) )
        {
            $access = false;
        }
        else
        {
            $access = true;
        }
    }
    else
    {
        if ( $order->attribute( 'user_id' ) == $user->id() )
        {
            $access = true;
        }
        else
        {
            $access = false;
        }
    }
}
if ( !$access )
{
     return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}
$tpl = templateInit();


$tpl->setVariable( "order", $order );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/invoice.tpl" );
$Result['pagelayout'] = 'print_pagelayout.tpl';
$Result['path'] = array( array( 'url' => 'shop/orderlist',
                                'text' => ezi18n( 'kernel/shop', 'Order list' ) ),
                         array( 'url' => false,
                                'text' => ezi18n( 'kernel/shop', 'Order #%order_id', null, array( '%order_id' => $order->attribute( 'order_nr' ) ) ) ) );

?>
