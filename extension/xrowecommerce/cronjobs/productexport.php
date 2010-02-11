<?php
$contentIni = eZINI::instance( 'content.ini' );
$root_node = $contentIni->variable( 'NodeSettings', 'RootNode' );

$siteIni = eZINI::instance( 'site.ini' );
$baseUrl = $siteIni->variable( 'SiteSettings', 'SiteURL' );

$xrowIni = eZINI::instance( 'xrowecommerce.ini' );
$exportSettingsFieldsArray = $xrowIni->variable( 'GoogleExportSettings', 'ExportFieldsArray' );
$classIdentifier = $xrowIni->variable( 'GoogleExportSettings', 'ClassIdentifier' );
$priceLang = $xrowIni->variable( 'GoogleExportSettings', 'ExportPriceLanguage' );
if ( trim( $priceLang ) == '' )
{
    $priceLang = 'EUR';
}

$list = new xrowExportProductList( );
$nodeDataMap = array();
$exportFields = array();

// get the products
$nodeList = eZContentObjectTreeNode::subTreeByNodeID( array( 
    'ClassFilterType' => 'include' , 
    'ClassFilterArray' => array( 
        $classIdentifier 
    ) , 
    'AttributeFilter' => array( 
        array( 
            'published' , 
            '<=' , 
            time() 
        ) 
    ) 
), $root_node );

// append the products to the export list
foreach ( $nodeList as $node )
{
    $exportFields['name'] = $node->attribute( 'name' );
    unset( $exportSettingsFieldsArray['name'] );
    
    $url = $node->attribute( 'url_alias' );
    eZURI::transformURI( $url, true );
    $exportFields['link'] = 'http://' . $baseUrl . $url;
    
    unset( $exportSettingsFieldsArray['link'] );
    
    $nodeDataMap = $node->attribute( 'data_map' );
    // get the product_id
    $exportFields['id'] = $nodeDataMap['product_id']->DataText;
    unset( $exportSettingsFieldsArray['id'] );
    
    // Check if the product have more options (more colors or more sizes)
    $option2_handler = $nodeDataMap['options']->attribute( 'content' );
    $option2_list = $option2_handler->attribute( 'option_list' );
    if ( is_array( $option2_list ) && count( $option2_list ) > 0 )
    {
        $optionArray = array();
        foreach ( $option2_list as $option )
        {
            // get the full name
            $default_name = $exportFields['name'];
            $exportFields['name'] = $exportFields['name'] . ' ' . $option['comment'];
            // get the full profukt id
            $default_id = $exportFields['id'];
            $exportFields['id'] = $exportFields['id'] . '-' . $option['id'];
            // get image path
            $image_contentobject_attributes = $option['image']->attribute( 'contentobject_attributes' );
            $image_handler = $image_contentobject_attributes[2]->attribute( 'content' );
            $exportFields['image_link'] = get_image_path( $image_handler, $baseUrl );
            // get manufacturer
            $exportFields['manufacturer'] = $nodeDataMap['manufacturer']->attribute( 'content' )->Name;
            // get description
            $exportFields['description'] = $option['description'];
            // get price
            $exportFields['price'] = get_multi_price( $option['multi_price'], $priceLang, 'array' );
            
            // add to the export list
            $list->append( new xrowExportProduct( $exportFields ) );
            $exportFields['name'] = $default_name;
            $exportFields['id'] = $default_id;
            unset( $exportFields['image_link'] );
            unset( $exportFields['manufacturer'] );
            unset( $exportFields['description'] );
            unset( $exportFields['price'] );
            unset( $exportFields['color'] );
            unset( $exportFields['model_number'] );
        }
    }
    else
    {
        foreach ( $exportSettingsFieldsArray as $exportSettingsFieldIndex => $exportSettingsFieldName )
        {
            if ( array_key_exists( $exportSettingsFieldName, $nodeDataMap ) )
            {
                $handler = $nodeDataMap[$exportSettingsFieldName]->attribute( 'content' );
                // get description
                if ( $nodeDataMap[$exportSettingsFieldName]->DataTypeString == 'ezxmltext' )
                {
                    $content = get_xml_text( $handler );
                }
                // get image path
                elseif ( $nodeDataMap[$exportSettingsFieldName]->DataTypeString == 'ezimage' )
                {
                    $content = get_image_path( $handler, $baseUrl );
                }
                // get price
                elseif ( $nodeDataMap[$exportSettingsFieldName]->DataTypeString == 'ezmultiprice' )
                {
                    $content = get_multi_price( $handler, $priceLang );
                }
                // get manufacturer
                elseif ( $nodeDataMap[$exportSettingsFieldName]->DataTypeString == 'ezobjectrelation' )
                {
                    $content = $handler->Name;
                }
                // get the rest
                else
                {
                    $content = $nodeDataMap[$exportSettingsFieldName]->DataText;
                }
                $exportFields[$exportSettingsFieldIndex] = $content;
            }
        }
        $list->append( new xrowExportProduct( $exportFields ) );
        unset( $exportFields );
    }
}

$plugins = $xrowIni->variable( 'GoogleExportSettings', 'ActivePlugins' );
if ( is_array( $plugins ) )
{
    foreach ( $plugins as $plugin )
    {
        $export = new $plugin( );
        $export->export( $list );
        unset( $export );
    }
}

/**
 * get the xml data an convert it to strin
 * 
 * @param eZContentObjectAttribute $contentObjectAttribute
 * @return string $result
 */
function get_xml_text( $handler )
{
    $xml_data = $handler->attribute( 'xml_data' );
    
    $result = str_replace( "\r", '', $xml_data );
    $result = str_replace( "\n", " ", $result );
    $result = str_replace( "\t", " ", $result );
    $result = preg_replace( "#<br\s?/?>#si", " ", $result );
    
    $result = gwf_TextUtils::replaceWordChars( $result, true );
    $result = gwf_TextUtils::cleanUpHTML( $result );
    $result = gwf_TextUtils::strip_whitespaces( $result );
    $result = html_entity_decode( $result );
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