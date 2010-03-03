<?php



$http = eZHTTPTool::instance();
$Module = $Params['Module'];

$tpl = eZTemplate::factory();

$SKUArray = array();
$amountArray = array();
$descArray = array();
$notFound = false;

if ( $http->hasPostVariable( 'SearchButton' ) )
{
    $SKUArray = $http->postVariable( 'SKUArray' );
    $amountArray = $http->postVariable( 'AmountArray' );
    foreach( $SKUArray as $key => $sku )
    {
    	$sku = trim( $sku );
    	if ( strlen( $sku ) > 0 )
    	{
    	   $descArray[$key] = xrowProductData::fetchDataBySKU( $sku, true );
    	   if ( ( $descArray[$key] instanceof xrowProductData ) == false )
    	   {
    	   	   $notFound = true;
    	   }
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
    $amountArray = $http->postVariable( 'AmountArray' );
    $basket = eZBasket::currentBasket();
    foreach( $SKUArray as $key => $sku )
    {
        $sku = trim( $sku );
        if ( strlen( $sku ) > 0 )
        {
			$descArray[$key] = xrowProductData::fetchDataBySKU( $sku, true );
			if ( ( $descArray[$key] instanceof xrowProductData ) == false )
			{
			    $notFound = true;
			}
        }
        else
        {
            $descArray[$key] = false;
        }
    }
    if ( $notFound == false )
    {
	    foreach( $descArray as $key => $item )
	    {
	        $amount = trim( $amountArray[$key] );
			if ( !is_numeric( (int) $amount ) or $amount <= 0 )
			{
			     $amount = 1;
			}
			if ( $descArray[$key] instanceof xrowProductData )
			{
			    //eZShopOperationCollection::addToBasket()
			    $id = $descArray[$key]->attribute( 'attribute_id' );
			    $data = array( $id => array( $id => $descArray[$key]->attribute( 'id' ) ) );
			    $operationResult = eZOperationHandler::execute( 'shop', 'addtobasket', array( 'basket_id' => $basket->attribute( 'id' ),
			                                                                                  'object_id' => $descArray[$key]->attribute( 'object_id' ),
			                                                                                  'quantity' => $amount,
			                                                                                  'option_list' => $data ) );
			}
	    }
	    return $Module->redirectTo( 'shop/basket' );
    }
}

//eZDebug::writeDebug( $descArray, 'desc' );

$tpl->setVariable( "sku_array", $SKUArray );
$tpl->setVariable( "amount_array", $amountArray );
$tpl->setVariable( "desc_array", $descArray );


$Result = array();
$Result['content'] = $tpl->fetch( "design:xrowecommerce/direct_order.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'extension/xrowecommerce/directorder', 'Direct order' ) ) );



?>