<?php
/*
 * List the attributes of the product variation datatype
 */

require_once( 'kernel/common/template.php' );

$Module = $Params['Module'];
$Module->setTitle( 'Product variation - column list' );

$tpl = templateInit();
$http = eZHTTPTool::instance();

if ( isset( $Params['Language'] ) and strlen( $Params['Language'] ) > 1 )
    $languageCode = $Params['Language'];
else
{
    $languageObj = eZContentLanguage::topPriorityLanguage();
    $languageCode = $languageObj->attribute( 'locale' );
}

$languageID = eZContentLanguage::idByLocale( $languageCode );

if ( $http->hasPostVariable( "NewButton" ) )
{
    if ( $http->hasPostVariable( "LanguageCode" ) )
        $languageCode = $http->postVariable( "LanguageCode" );
    $params = array( null, $languageCode );
    $unorderedParams = array( 'Language' => $languageCode );
    $Module->run( 'attributeedit', $params, $unorderedParams );
    return;
}

$limit = eZPreferences::value( 'admin_xrow_attribute_list_limit' );
if ( $limit == false )
{
    $limit = 10;
    eZPreferences::setValue( 'admin_xrow_attribute_list_limit', $limit );
}

$offset = 0;
if ( isset( $Params['Offset'] ) )
    $offset = $Params['Offset'];

$attributeList = xrowProductAttribute::fetchList( array(), true, $offset, $limit, array( 'name' => 'asc' ) );
$attributeCount = xrowProductAttribute::fetchListCount();

$viewParameters = array( 'offset' => $offset );

$tpl->setVariable( 'attribute_list', $attributeList );
$tpl->setVariable( 'attribute_count', $attributeCount );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'language_code', $languageCode );
$tpl->setVariable( 'number_of_items', $limit );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:productvariation/attributelist.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'extension/xrowecommerce/productvariation', 'Product variation attributes' ),
                                'url' => false ) );
?>