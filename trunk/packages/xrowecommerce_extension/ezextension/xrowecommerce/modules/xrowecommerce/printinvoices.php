<?php

$http = eZHTTPTool::instance();
$user = eZUser::currentUser();
$module = $Params['Module'];
if (  $http->hasPostVariable('IDArray') )
{
    $orderIDarray = $http->postVariable('IDArray');
    $orders = array();
    
    foreach ($orderIDarray as $key => $orderID )
    {
        $orders[$key] = eZOrder::fetch( $orderID );
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
    $tpl = eZTemplate::factory();
    $tpl->setVariable( "orders", $orders );
    
    $Result = array();
    $Result['content'] = $tpl->fetch( "design:shop/invoices.tpl" );
    $Result['pagelayout'] = 'print_pagelayout.tpl';
    $Result['path'] = array( array( 'url' => 'shop/orderlist',
                                    'text' => ezpI18n::tr( 'kernel/shop', 'Order list' ) ),
                             array( 'url' => false,
                                    'text' => ezpI18n::tr( 'kernel/shop', 'Print Invoices', null, null ) ) );

}
else
{
    eZDebug::writeError( 'No post variables provided', __FILE__ );
    return $module->handleError( eZError::KERNEL_ACCESS_DENIED, 'kernel' );
}
?>