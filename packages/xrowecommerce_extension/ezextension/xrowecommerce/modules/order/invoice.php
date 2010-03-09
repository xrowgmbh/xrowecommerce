<?php
//
// Definition of Order history class
//

/*! \file history.php
*/

$OrderID = $Params['OrderID'];
$includePackingSlip = $Params['includePackingSlip'];
if ( $includePackingSlip == 1 )
     $includePackingSlip = true;
else $includePackingSlip = false;
$module =& $Params['Module'];

$ini = eZINI::instance();
$http = eZHTTPTool::instance();
$user = eZUser::currentUser();
$access = false;
$order = eZOrder::fetch( $OrderID );
if ( !$order )
{
    return $module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

$accessToAdministrate = $user->hasAccessTo( 'shop', 'administrate' );
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
     return $module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
}
$tpl = eZTemplate::factory();
$tpl->setVariable( "order", $order );
$tpl->setVariable( "includepackingslip", $includePackingSlip );
$Result = array();
$Result['pagelayout'] = "invoice_pagelayout.tpl";
$Result['content'] = $tpl->fetch( "design:shop/invoice.tpl" );
$Result['path'] = array( array( 'url' => 'shop/orderlist',
                                'text' => ezpI18n::tr( 'kernel/shop', 'Order list' ) ),
                         array( 'url' => false,
                                'text' => ezpI18n::tr( 'kernel/shop', 'Order #%order_id', null, array( '%order_id' => $order->attribute( 'order_nr' ) ) ) ) );

?>