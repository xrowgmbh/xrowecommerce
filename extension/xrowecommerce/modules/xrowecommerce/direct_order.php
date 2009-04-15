<?php

require_once( "kernel/common/template.php" );

$http = eZHTTPTool::instance();
$Module = $Params['Module'];

$tpl = templateInit();

$SKUArray = array();
$amountArray = array();
$descArray = array();

if ( $http->hasPostVariable( 'SearchButton' ) )
{
    $SKUArray = $http->postVariable( 'SKUArray' );
    #$amountArray = $http->postVariable( 'AmountArray' );
    foreach( $SKUArray as $key => $sku )
    {
    	$sku = trim( $sku );
    	if ( strlen( $sku ) > 0 )
    	{
    	   $descArray[$key] = xrowProductData::fetchDataBySKU( $sku, true );
    	}
    	else
    	{
    		$descArray[$key] = false;
    	}
    }
}
elseif ( $http->hasPostVariable( 'OrderButton' ) )
{
    $SKUArray = $http->postVariable( 'SKUArray' );
    #$amountArray = $http->postVariable( 'AmountArray' );
    $basket = eZBasket::currentBasket();
    foreach( $SKUArray as $key => $sku )
    {
        $sku = trim( $sku );
        if ( strlen( $sku ) > 0 )
        {
			$product = xrowProductData::fetchDataBySKU( $sku, true );
			$amount = trim( $amountArray[$key] );
			if ( !is_numeric( (int) $amount ) or $amount < 0 )
			{
			     $amount = 1;
			}
			if ( $product )
			{

				//eZShopOperationCollection::addToBasket()
				$data = array( $product->attribute( 'attribute_id' ) => array( $product->attribute( 'attribute_id' ) => $product->attribute( 'id' ) ) );
				$operationResult = eZOperationHandler::execute( 'shop', 'addtobasket', array( 'basket_id' => $basket->attribute( 'id' ),
			                                                                   'object_id' => $product->attribute( 'object_id' ),
			                                                                   'option_list' => $data,
			                                                                   'quantity' => $amount ) );
			}
        }
        else
        {
            $descArray[$key] = false;
        }
    }
    return $Module->redirectTo( 'shop/basket' );
}

//eZDebug::writeDebug( $descArray, 'desc' );

$tpl->setVariable( "sku_array", $SKUArray );
$tpl->setVariable( "amount_array", $amountArray );
$tpl->setVariable( "desc_array", $descArray );


$Result = array();
$Result['content'] = $tpl->fetch( "design:xrowecommerce/direct_order.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezi18n( 'extension/xrowecommerce/directorder', 'Direct order' ) ) );



?>