<?php
$contentIni = eZINI::instance( 'content.ini' );
$root_node = $contentIni->variable( 'NodeSettings', 'RootNode' );

$siteIni = eZINI::instance( 'site.ini' );
$xrowIni = eZINI::instance( 'xrowecommerce.ini' );

$baseUrl = 'www.alconeco.com';
$exportSiteAccess = '/alcone';
$exportSettingsFieldsArray = array( 'id'=>'product_id',
                                    'name'=>'name',
                                    'short_description'=>'short_description',
                                    'description'=>'description',
                                    'keywords'=>'keywords',
                                    'image'=>'image',
                                    'image_link'=>'image_link',
                                    'manufacturer'=>'manufacturer',
                                    'price'=>'price',
                                    'weight'=>'weight',
                                    'unicode'=>'unicode' );
$classIdentifier = 'xrow_product';
$priceLang = 'USD';
$vat = 1.08875;
$country = 'USA';


$list = new xrowExportProductListAsFile();
$nodeDataMap = array();
$exportFields = array();

// get the products
$nodeList = eZContentObjectTreeNode::subTreeByNodeID( array(
    'ClassFilterType' => 'include',
    'ClassFilterArray' => array(
        $classIdentifier
    ) ,
    'MainNodeOnly' => true,
    'IgnoreVisibility' => false,
    'AttributeFilter' => array(
        array(
            'published',
            '<=' ,
            time()
        )
    ),
), $root_node );

if ( ! $isQuiet )
{
    $cli->output( 'Exporting ' . count( $nodeList ) . ' products.' );
}

// only export a product once
$idArray = array();

// xrowproductvariation settings
$xINI = eZINI::instance( 'xrowproduct.ini' );
$precision = $xINI->variable( 'ExportSettings', 'Precision' );
$skuField = $xINI->variable( 'ExportSettings', 'SKUIdentifier' );
$priceField = $xINI->variable( 'PriceSettings', 'PriceIdentifier' );
$decPoint = $xINI->variable( 'ExportSettings', 'DecimalPoint' );
$exportClasses = $xINI->variable( 'ExportSettings', 'ExportClassArray' );
$xFieldArray = $xrowIni->variable( 'GoogleExportSettings', 'ExportVariationFieldArray' );
$skuArray = array();

if ( ! $isQuiet )
{
    $output = new ezcConsoleOutput();
    $bar = new ezcConsoleProgressbar( $output, count( $nodeList ) );
}

// append the products to the export list
foreach ( $nodeList as $node )
{
    // only export visible nodes
    if ( $node->IsInvisible )
    {
        continue;
    }

    // take complete path in name - SEO optimisation
    $title = $node->attribute( 'name' );
    $pathArray = $node->attribute( 'path' );
    $pathArray = array_reverse( $pathArray );
    foreach ( $pathArray as $pitem )
    {
        if ( mb_strlen( $title . ', ' . $pitem->attribute( 'name' ) ) > 70 or $pitem->attribute( 'depth' ) < 3 )
        {
            break;
        }
        $title .= ', ' . trim( $pitem->attribute( 'name' ) );
    }

    $exportFields['name'] = trim( $title, ', ' );

    unset( $exportSettingsFieldsArray['name'] );

    $url = $node->attribute( 'url_alias' );
    eZURI::transformURI( $url, true );
    $exportFields['link'] = 'http://' . $baseUrl . $exportSiteAccess . $url;
    unset( $exportSettingsFieldsArray['link'] );

    $nodeDataMap = $node->attribute( 'data_map' );
    // get the product_id
    if ( isset( $nodeDataMap['product_id'] ) )
    {
        $exportFields['id'] = $nodeDataMap['product_id']->content();
        unset( $exportSettingsFieldsArray['id'] );
    }
    // get the rest of details
    foreach ( $exportSettingsFieldsArray as $exportSettingsFieldIndex => $exportSettingsFieldName )
    {
        if ( array_key_exists( $exportSettingsFieldName, $nodeDataMap ) )
        {
            $handler = $nodeDataMap[$exportSettingsFieldName]->attribute( 'content' );
            $content = '';
            switch ( $nodeDataMap[$exportSettingsFieldName]->DataTypeString )
            {
                case 'ezxmltext':
                {
                    $content = get_xml_text( $handler );
                }break;
                case 'ezimage':
                {
                    $content = get_image_path( $handler, $baseUrl );
                }break;
                case 'ezmultiprice':
                {
                    $content = get_multi_price( $handler, $priceLang );
                }break;
                case 'ezobjectrelation':
                {
                    if( isset( $handler->Name ) )
                    {
                        $content = $handler->Name;
                    }
                    else
                    {
                        $content = '';
                    }
                }break;
                case 'ezprice':
                {
                    $cur = new eZCurrency( $handler->attribute( 'inc_vat_price' ) );
                    $content = $cur->Locale->formatCleanCurrency( $cur->Value );
                }break;
                case 'ezfloat':
                case 'ezinteger':
                {
                    $content = $handler;
                }break;
                default:
                {
                    $content = $nodeDataMap[$exportSettingsFieldName]->DataText;
                }break;
            }

            $exportFields[$exportSettingsFieldIndex] = $content;
        }
    }
    // now check whether the product have options/variations
    $variationFieldName = 'options';

    if ( isset( $nodeDataMap[$variationFieldName] ) and $nodeDataMap[$variationFieldName]->attribute( 'data_type_string' ) == 'ezoption2' )
    {
        $option2_handler = $nodeDataMap[$variationFieldName]->attribute( 'content' );
        $option2_list = $option2_handler->attribute( 'option_list' );
        if ( is_array( $option2_list ) && count( $option2_list ) > 0 )
        {
            $optionArray = array();
            $exportVariationFieldsArray = array( 'id'=>'id',
                                                 'value'=>'value',
                                                 'description'=>'description',
                                                 'comment'=>'comment',
                                                 'weight'=>'weight',
                                                 'image'=>'image',
                                                 'price'=>'multi_price' );
            $default_name = '';
            foreach ( $option2_list as $option )
            {
                foreach ( $exportVariationFieldsArray as $exportVariationFieldIndex => $exportVariationFieldName )
                {
                    // get the full name
                    if ( $option[$exportVariationFieldName] != '' )
                    {
                        switch ( $exportVariationFieldIndex )
                        {
                            case 'name':
                                $default_name = $exportFields['name'];
                                $exportFields['name'] = $exportFields['name'] . ' ' . $option[$exportVariationFieldName];
                                break;

                            case 'id':
                                $default_id = $exportFields['id'];
                                $exportFields['id'] = $exportFields['id'] . '-' . $option[$exportVariationFieldName];
                                break;

                            case 'price':
                                $exportFields['price'] = get_multi_price( $option[$exportVariationFieldName], $priceLang, 'array' );
                                break;

                            case 'image_link':
                            case 'image':
                                $image_contentobject_attributes = $option[$exportVariationFieldName]->attribute( 'contentobject_attributes' );
                                $image_handler = $image_contentobject_attributes[2]->attribute( 'content' );
                                $exportFields['image_link'] = get_image_path( $image_handler, $baseUrl );
                                break;

                            default:
                                $exportFields[$exportVariationFieldIndex] = $option[$exportVariationFieldName];
                                break;
                        }

                    }
                }
                $list->append( new xrowExportProductAsFile( $exportFields ) );
                $exportFields['name'] = $default_name;
                $exportFields['id'] = $default_id;
                foreach ( $exportVariationFieldsArray as $exportVariationFieldIndex => $exportVariationFieldName )
                {
                    if ( $exportVariationFieldIndex != 'name' && $exportVariationFieldIndex != 'id' )
                    {
                        unset( $exportFields[$exportVariationFieldIndex] );
                    }
                }
            }
        }
    }
    elseif ( isset( $nodeDataMap[$variationFieldName] ) and $nodeDataMap[$variationFieldName]->attribute( 'data_type_string' ) == 'xrowproductvariation' )
    {
        if ( !isset( $attributeID ) )
        {
            $classIdentifier = $node->attribute( 'class_identifier' );
            $attributeID = $exportClasses[$classIdentifier];
            $classAttributeID = (int) eZContentObjectTreeNode::classAttributeIDByIdentifier( $classIdentifier . '/' . $attributeID );
        }

        $obj = $node->object();
        $languageCode = $obj->currentLanguage();

        $variationAttribute = eZPersistentObject::fetchObject( eZContentObjectAttribute::definition(), null, array(
            'contentclassattribute_id' => $classAttributeID,
            'contentobject_id' => (int) $node->attribute( 'contentobject_id' ),
            'version' => (int) $node->attribute( 'contentobject_version' ),
            'language_code' => $languageCode
        ), true );

        if ( $variationAttribute instanceof eZContentObjectAttribute )
        {
            $id = $variationAttribute->attribute( 'id' );
            $version = $variationAttribute->attribute( 'version' );

            $dataArray = xrowProductData::fetchList( array(
                'attribute_id' => $id ,
                'version' => $version ,
                'language_code' => $languageCode
            ), true, 0, 1, array( 'placement' => 'asc' ) );

            if ( count( $dataArray ) == 0 )
            {
                continue;
            }

            // only export 1st item
            $variation = $dataArray[0];
            // avoid to short skus, filling with zeros
            $sku = str_pad( $variation->attribute( $skuField ), 10, "0", STR_PAD_LEFT);

            if ( in_array( $sku, $skuArray ) )
            {
                continue;
            }
            else
            {
                $skuArray[] = $sku;
            }

            $exportFields['id'] = $sku;

            $priceID = $variation->attribute( $priceField );
            $priceList = xrowProductPrice::fetchList( array(
                    'price_id' => $priceID ,
                    'country' => $country
                ), true, false, false, array(
                    'amount' => 'asc'
                ) );

            $price = $priceList[0]->attribute( 'price' ) * $vat;
            $exportFields['price'] = number_format( $price, $precision, $decPoint, '' );
            foreach( $xFieldArray as $xkey => $xfield )
            {
                if ( $variation->hasAttribute( $xkey ) )
                {
                    $ta = $variation->attribute( $xkey );
                    if ( $ta && strlen( $ta ) > 0 )
                    {
                        $exportFields[$xfield] = $ta;
                    }
                }
            }

            $list->append( new xrowExportProductAsFile( $exportFields ) );
            foreach( $xFieldArray as $xkey => $xfield )
            {
                if ( isset( $exportFields[$xfield] ) )
                {
                   unset( $exportFields[$xfield] );
                }
            }

        }
        xrowProductDataType::clearCache();
    }
    else
    {
        $list->append( new xrowExportProductAsFile( $exportFields ) );
    }
    if ( ! $isQuiet )
    {
        $bar->advance();
    }

    // delete cache to avoid memory overflow
    eZContentObject::clearCache();
}


if ( ! $isQuiet )
{
    $bar->finish();
    $output->outputLine();
}
$export = new xrowProductExport();
$export->export( $list );

/**
 * get the xml data an convert it to strin
 *
 * @param eZContentObjectAttribute $contentObjectAttribute
 * @return string $result
 */
function get_xml_text( $handler )
{
    $xml_data = '';
    if ( is_object( $handler ) )
    {
        $output = $handler->attribute( 'output' );
        if ( is_object( $output ) )
        {
            $xml_data = trim( $output->attribute( 'output_text' ) );

        }
    }

    $result = str_replace( "\r", '', $xml_data );
    $result = str_replace( "\n", " ", $result );
    $result = str_replace( "\t", " ", $result );
    $result = preg_replace( "#<br\s?/?>#si", " ", $result );

    $result = xrowTextUtils::replaceWordChars( $result, true );
    $result = xrowTextUtils::cleanUpHTML( $result );

    $result = html_entity_decode( $result, ENT_COMPAT, 'UTF-8' );
    # ez bug
    $result = str_replace( "&nbsp;", " ", $result );
    $result = xrowTextUtils::strip_whitespaces( $result );

    $result = trim( $result );
    return $result;
}

/**
 * get the image path
 *
 * @param eZContentObjectAttribute $contentObjectAttribute
 * @param string $baseUrl
 * @return string $result
 */
function get_image_path( $handler, $baseUrl )
{
    $image = $handler->attribute( 'original' );
    eZURI::transformURI( $image['url'], true );
    $result = 'http://' . $baseUrl . $image['url'];
    return $result;
}

/**
 * get the price out of a multi_price list
 *
 * @param eZContentObjectAttribute $contentObjectAttribute
 * @return string $result
 */
function get_multi_price( $handler, $priceLang, $type = '' )
{
    $multi_price = $handler->attribute( 'price_list' );
    // $locale = eZLocale::instance();
    // $price_symbol = $locale->attribute( 'currency_symbol' );
    if ( $type == 'array' )
    {
        $result = $multi_price[$priceLang]['value'];
    }
    else
    {
        $result = $multi_price[$priceLang]->Value;
    }
    return $result;
}
?>