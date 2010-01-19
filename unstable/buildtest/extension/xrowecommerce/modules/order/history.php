<?php
//
// Definition of Order history class
//

/*! \file history.php
*/

include_once( "kernel/common/template.php" );

$Path       = array();
$Result     = array();
$User       = eZUser::currentUser();
$Email      = $User->Email;
$CustomerID = $User->ContentObjectID;
$Email      = urldecode( $Email );
$OrderList  = eZOrder::orderList( $CustomerID, $Email );
$tpl        = templateInit();

rsort($OrderList);
$tpl->setVariable( "order_list", $OrderList );

$Path[]             = array( 'url' => false,
                             'text' => ezi18n( 'kernel/shop', 'Order history' ) );

$Result['content']  = $tpl->fetch( "design:shop/orderhistory.tpl" );
$Result['path']     = $Path;
?>