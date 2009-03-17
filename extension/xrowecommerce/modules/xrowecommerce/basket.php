<?php

$http = eZHTTPTool::instance();
$module = $Params['Module'];

require_once( "kernel/common/template.php" );

$basket = eZBasket::currentBasket();
$basket->updatePrices(); // Update the prices. Transaction not necessary.

if ( $http->hasPostVariable( "ContinueShoppingButton" ) )
{
    $itemCountList = $http->hasPostVariable( "ProductItemCountList" ) ? $http->postVariable( "ProductItemCountList" ) : false;
    $itemIDList = $http->hasPostVariable( "ProductItemIDList" ) ? $http->postVariable( "ProductItemIDList" ) : false;
    if ( is_array( $itemCountList ) && is_array( $itemIDList ) && count( $itemCountList ) == count( $itemIDList ) && is_object( $basket ) )
    {
        $productCollectionID = $basket->attribute( 'productcollection_id' );

        $i = 0;

        $db = eZDB::instance();
        $db->begin();
        $itemCountError = false;
        foreach ( $itemIDList as $id )
        {
            if ( !is_numeric( $itemCountList[$i] ) or $itemCountList[$i] <= 0 )
            {
                $itemCountError = true;
            }
            else
            {
                $item = eZProductCollectionItem::fetch( $id );
                if ( is_object( $item ) && $item->attribute( 'productcollection_id' ) == $productCollectionID )
                {
                    $item->setAttribute( "item_count", $itemCountList[$i] );
                    $item->store();
                }
            }
            $i++;
        }
        $db->commit();
        if ( $itemCountError )
        {
            // Redirect to basket
            $module->redirectTo( $module->functionURI( "basket" ) . "/(error)/invaliditemcount" );
            return;
        }
    }
    $fromURL = $http->sessionVariable( "FromPage" );
    $http->RemoveSessionVariable( "FromPage" );
    $module->redirectTo( $fromURL );
    return;
}


$removedItems = array();

$basket = eZBasket::currentBasket();

$tpl = templateInit();
if ( isset( $Params['Error'] ) )
{
    $tpl->setVariable( 'error', $Params['Error'] );
    if ( $Params['Error'] == 'options' )
    {
        $tpl->setVariable( 'error_data', $http->sessionVariable( 'BasketError') );
        $http->removeSessionVariable( 'BasketError');
    }
}
$tpl->setVariable( "removed_items", $removedItems);
$tpl->setVariable( "basket", $basket );
$tpl->setVariable( "module_name", 'shop' );
$tpl->setVariable( "vat_is_known", $basket->isVATKnown() );


// Add shipping cost to the total items price and store the sum to corresponding template vars.
require_once( 'kernel/classes/ezshippingmanager.php' );
$shippingInfo = eZShippingManager::getShippingInfo( $basket->attribute( 'productcollection_id' ) );
if ( $shippingInfo !== null )
{
    // to make backwards compability with old version, allways set the cost inclusive vat.
    if ( ( isset( $shippingInfo['is_vat_inc'] ) and $shippingInfo['is_vat_inc'] == 0 ) or
         !isset( $shippingInfo['is_vat_inc'] ) )
    {
        $additionalShippingValues = eZShippingManager::vatPriceInfo( $shippingInfo );
        $shippingInfo['cost'] = $additionalShippingValues['total_shipping_inc_vat'];
        $shippingInfo['is_vat_inc'] = 1;
    }

    $totalIncShippingExVat  = $basket->attribute( 'total_ex_vat'  ) + $shippingInfo['cost'];
    $totalIncShippingIncVat = $basket->attribute( 'total_inc_vat' ) + $shippingInfo['cost'];

    $tpl->setVariable( 'shipping_info', $shippingInfo );
    $tpl->setVariable( 'total_inc_shipping_ex_vat', $totalIncShippingExVat );
    $tpl->setVariable( 'total_inc_shipping_inc_vat', $totalIncShippingIncVat );
}

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/basket.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'kernel/shop', 'Basket' ) ) );
?>
