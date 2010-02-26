<?php
/*
 * List the templates of the product variation datatype
 */

require_once( 'kernel/common/template.php' );

$Module = $Params['Module'];
$Module->setTitle( 'Product variation - template list' );

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
    $Module->run( 'templateedit', $params, $unorderedParams );
    return;
}

#eZDebug::writeDebug( $Params, 'params' );

$limit = eZPreferences::value( 'admin_xrow_template_list_limit' );
if ( $limit == false )
{
    $limit = 10;
    eZPreferences::setValue( 'admin_xrow_template_list_limit', $limit );
}

$offset = 0;
if ( $Params['Offset'] > 0 )
    $offset = $Params['Offset'];

$templateList = xrowProductTemplate::fetchList( array(), true, $offset, $limit, array( 'name' => 'asc' ) );
$templateCount = xrowProductTemplate::fetchListCount( array() );

$viewParameters = array( 'offset' => $offset );

$tpl->setVariable( 'template_list', $templateList );
$tpl->setVariable( 'template_count', $templateCount );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'language_code', $languageCode );
$tpl->setVariable( 'number_of_items', $limit );

$Result = array();
$Result['content'] = $tpl->fetch( 'design:productvariation/templatelist.tpl' );
$Result['path'] = array( array( 'text' => ezi18n( 'extension/xrowecommerce/productvariation', 'Product variation templates' ),
                                'url' => false ) );
?>