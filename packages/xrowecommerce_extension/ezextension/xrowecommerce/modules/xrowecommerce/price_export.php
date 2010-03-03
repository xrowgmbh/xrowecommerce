<?php



$http = eZHTTPTool::instance();
$Module = $Params['Module'];

$export = true;
$exportArray = array();
$errorArray = array();

$tpl = eZTemplate::factory();

if ( $http->hasPostVariable( 'ExportButton' ) )
{
    $export = true;
	$country = $http->postVariable( 'Country' );
    $xINI = eZINI::instance( 'xrowproduct.ini' );
    $separator = $xINI->variable( 'ExportSettings', 'Separator' );
    $lineEnding = $xINI->variable( 'ExportSettings', 'LineEnding' );
    $exportParentNodeID = $xINI->variable( 'ExportSettings', 'ExportNodeID' );
    $exportClasses = $xINI->variable( 'ExportSettings', 'ExportClassArray' );
    $precision = $xINI->variable( 'ExportSettings', 'Precision' );
    $skuField = $xINI->variable( 'ExportSettings', 'SKUIdentifier' );
    $priceField = $xINI->variable( 'PriceSettings', 'PriceIdentifier' );
    $decPoint = $xINI->variable( 'ExportSettings', 'DecimalPoint' );

    $defaultLanguage = eZContentLanguage::topPriorityLanguage();
    $languageCode = $defaultLanguage->attribute( 'locale' );

    /**
     * Fetch all products, even hidden ones
     */
	$contentNode = eZContentObjectTreeNode::fetch( $exportParentNodeID );

    $nodeList = array();

    if ( $contentNode )
    {
        $offset = 0;
        $limit = 50;
    	$params = array( 'IgnoreVisibility' => true,
                         'Limitation' => array(),
                         'ClassFilterType' => 'include',
                         'ClassFilterArray' => array_keys( $exportClasses ) );

    	$contentCount = $contentNode->subTreeCount( $params );
    	if ( $contentCount > 0 )
    	{
    		$params['Limit'] = $limit;
    		$params['SortBy'] = array( 'node_id' => 'asc' );

    		while( $offset < $contentCount )
    		{
    			$params['Offset'] = $offset;
    			$productArray = $contentNode->subTree( $params );

    			foreach ( $productArray as $product )
    			{
    				//$dataMap = $product->attribute( 'data_map' );
    				$classIdentifier = $product->attribute( 'class_identifier' );
    				$attributeID = $exportClasses[$classIdentifier];
    				$classAttributeID = (int) eZContentObjectTreeNode::classAttributeIDByIdentifier( $classIdentifier . '/' . $attributeID );

                    $variationAttribute = eZPersistentObject::fetchObject( eZContentObjectAttribute::definition(),
                                                null,
                                                array( 'contentclassattribute_id' => $classAttributeID,
                                                       'contentobject_id' => (int) $product->attribute( 'contentobject_id' ),
                                                       'version' => (int) $product->attribute( 'contentobject_version' ),
                                                       'language_code' => $languageCode ),
                                                true );

                    if ( $variationAttribute instanceof eZContentObjectAttribute )
                    {
	                    $id = $variationAttribute->attribute( 'id' );
	                    $version = $variationAttribute->attribute( 'version' );

	                    $dataArray = xrowProductData::fetchList( array( 'attribute_id' => $id,
	                                                               'version' => $version,
	                                                               'language_code' => $languageCode ),
	                                                        true,
	                                                        false,
	                                                        false );

	                    foreach ( $dataArray as $variation )
	                    {
	                        $sku = $variation->attribute( $skuField );
	                        if ( !isset( $exportArray[$sku] ) )
	                        {
	                            $exportArray[$sku] = array( $sku );
	                            $priceID = $variation->attribute( $priceField );
	                            $priceList = xrowProductPrice::fetchList( array( 'price_id' => $priceID, 'country' => $country ),
	                                                                              true,
	                                                                              false,
	                                                                              false,
	                                                                              array( 'amount' => 'asc' ) );

	                            foreach ( $priceList as $price )
	                            {
	                                $exportArray[$sku][] = number_format( $price->attribute( 'price' ), $precision, $decPoint, '' );
	                            }
	                        }
	                    }
                    }
    			}
    			$offset += $limit;
                /**
                 * Unset the cache
                 */
			    global $eZContentObjectContentObjectCache;
			    unset( $eZContentObjectContentObjectCache );
			    global $eZContentObjectVersionCache;
			    unset( $eZContentObjectVersionCache );

			    unset( $GLOBALS['xrowProductVariation'] );
			    unset( $GLOBALS['XrowProductPriceData'] );
			    unset( $GLOBALS['xrowProductAttributeCache'] );
			    unset( $GLOBALS['xrowproductdatavariation'] );

    		}
    		$content = "";
    		ksort( $exportArray );
    		foreach ( $exportArray as $line )
    		{
    			$content .= implode( $separator, $line ) . "\n";

    		}
    		$dir =  eZSys::cacheDirectory().'/';
            $file = $dir . date( "Y-m-d-H-i" ) . "-price-export-" . strtolower( $country ) . ".csv";
            @unlink( $file );
		    eZFile::create( $file, false, $content );

		    !eZFile::download( $file );
		    $module->redirectToView( 'priceexport' );
    	}
    }
}

$tpl->setVariable( "export", $export );
$tpl->setVariable( "error_array", $errorArray );


$Result = array();
$Result['content'] = $tpl->fetch( "design:xrowecommerce/price_export.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'extension/xrowecommerce', 'Price export' ) ) );



?>