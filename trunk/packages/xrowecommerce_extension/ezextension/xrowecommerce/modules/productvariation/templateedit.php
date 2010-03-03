<?php
/**
 * List the columns of the product variation datatype
 */



$Module = $Params['Module'];
$Module->setTitle( 'Product variation - template edit' );

$tpl = eZTemplate::factory();
$http = eZHTTPTool::instance();

$error = array();

$id = false;
if ( isset( $Params['ID'] ) and $Params['ID'] > 0 )
    $id = $Params['ID'];

$languageCode = false;

if ( isset( $Params['Language'] ) and strlen( $Params['Language'] ) > 1 )
    $languageCode = $Params['Language'];
else if ( $http->hasPostVariable( 'EditLanguage' ) )
{
    $languageCode = $http->postVariable( 'EditLanguage' );
    $Module->redirectTo( $Module->functionURI( 'templateedit' ) . '/' . $id . '/' . $languageCode );
    return;
}
else if ( $http->hasPostVariable( 'LanguageCode' ) )
    $languageCode = $http->postVariable( 'LanguageCode' );

// No language was specified in the URL, we need to figure out
// the language to use.
if ( !$languageCode && !$http->hasPostVariable( 'CancelButton' ) )
{
    // Check number of languages
    //include_once( 'kernel/classes/ezcontentlanguage.php' );
    $languages = eZContentLanguage::fetchList();
    // If there is only one language we choose it for the user.
    if ( count( $languages ) == 1 )
    {
        $language = array_shift( $languages );
        $languageCode = $language->attribute( 'locale' );
    }
    else
    {
        $tpl->setVariable( 'formurl', 'productvariation/templateedit/' . $id );
        $Result = array();
        $Result['content'] = $tpl->fetch( 'design:productvariation/select_language.tpl' );
        $Result['path'] = array( array( 'url' => false,
                                        'text' => ezpI18n::tr( 'extension/xrowecommerce/productvariation', 'Template edit' ) ) );
        return $Result;
    }
}

if ( !$id )
{
    if ( $http->hasPostVariable( 'CancelButton' ) )
    {
        return $Module->redirectTo( $Module->functionURI( 'templatelist' )  );
    }
    $user = eZUser::currentUser();
    $userID = $user->attribute( 'contentobject_id' );
    $template = new xrowProductTemplate();
    $name = ezpI18n::tr( 'extension/xrowecommerce/productvariation', 'Product template' );
    $template->setData( 'name', $languageCode, $name );
    $template->setAttribute( 'name', $name );

    $editLanguageID = eZContentLanguage::idByLocale( $languageCode );
    $langMask = eZContentLanguage::maskByLocale( array( $languageCode ) );
    $template->setAttribute( 'initial_language_id', $editLanguageID );
    $template->setAttribute( 'language_mask', $langMask );
    $template->setAttribute( 'user_id', $userID );
    $timestamp = time();
    $template->setAttribute( 'created', $timestamp );
    $template->setAttribute( 'active', 0 );

    $template->store();

    $id = $template->attribute( 'id' );
    $Module->redirectTo( $Module->functionURI( 'templateedit' ) . '/' . $id . '/' . $languageCode );
    return;

}
else
{
    $template = xrowProductTemplate::fetch( $id );
    $template->updateAttributes( $languageCode );
}

if ( !is_object( $template ) )
{
    eZDebug::writeError( "Unknown xrowProductTemplate with ID $id", 'xrowEcommerce - product variation' );
    $Module->setExitStatus( eZModule::STATUS_FAILED );
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( $http->hasPostVariable( 'CancelButton' ) )
{
    return $Module->redirectTo( $Module->functionURI( 'templatelist' )  );
}

$stored = false;
$hasObjectInput = false;
if ( $http->hasPostVariable( 'StoreButton' ) or
     $http->hasPostVariable( 'TemplateCustomActionButton' ) )
     $hasObjectInput = true;
if ( $http->hasPostVariable( 'HasObjectInput' ) and
     $http->hasPostVariable( 'HasObjectInput' ) == 0 )
     $hasObjectInput = false;
if ( ( $http->hasPostVariable( 'StoreButton' ) or
       $http->hasPostVariable( 'TemplateCustomActionButton' ) )
       and $hasObjectInput
      )
{
    $name = $template->getData( 'name', $languageCode );
    if ( $http->hasPostVariable( 'name' ) )
    {
        $name = trim( $http->postVariable( 'name' ) );
        $template->setData( 'name', $languageCode, $name );
    }
    if ( strlen( $name ) == 0 )
        $error['name'] = true;

    if ( $template->attribute( 'initial_language_id' ) == eZContentLanguage::idByLocale( $languageCode ) )
        $template->setAttribute( 'name', $name );

    $desc = $template->getData( 'desc', $languageCode );
    if ( $http->hasPostVariable( 'description' ) )
    {
        $desc = trim( $http->postVariable( 'description' ) );
        $template->setData( 'desc', $languageCode, $desc );
    }

    $active = 0;
    if ( $http->hasPostVariable( 'active' ) )
    {
        $active = 1;
    }
    $template->setAttribute( 'active', $active );

    $locales = array_keys( $template->Data['name'] );
    $mask = eZContentLanguage::maskByLocale( $locales );
    $template->setAttribute( 'language_mask', $mask );

    $oldAttributes = $template->AttributeList;
    $oldAttributesData = $template->Data['attributes'];

    if ( $http->hasPostVariable( 'AttributeIDArray' ) )
    {
        $template->resetAttributeList();
        $attributeList = $http->postVariable( 'AttributeIDArray' );

        foreach ( $attributeList as $key => $attributeID )
        {
            if ( isset( $oldAttributes[$attributeID]) )
            {
                $template->AttributeList[$attributeID] = $oldAttributes[$attributeID];
                $template->Data['attributes'][$attributeID] = $oldAttributesData[$attributeID];
                $attribute = $oldAttributes[$attributeID]['attribute'];
            }
            else
                $attribute = xrowProductAttribute::fetch( $attributeID );

            if ( $attribute instanceof xrowProductAttribute )
            {
                $dataType = $attribute->dataType();
                if ( $dataType )
                {
                    $dataType->fetchTemplateInput( $template, $attribute, $http, $languageCode );
                    $dataType->validateTemplateInput( $template, $attribute, $error, $languageCode );
                }
                else
                    eZDebug::writeError( "Datatype error", "xrowproductvariation - templateedit" );
            }
            else
                eZDebug::writeError( "Attribute error", "xrowproductvariation - templateedit" );
        }
        $template->updateAttributes( $languageCode );
    }

    $sortOrder = '';
    if ( $http->hasPostVariable( 'AttributeSortList' ) )
    {
        $sortOrder = trim( $http->postVariable( 'AttributeSortList' ) );
        if ( strlen( $sortOrder ) > 0 )
        {
            $sortOrder .= ' ' . $http->postVariable( 'AttributeSortMethod' );
        }
    }
    $template->setAttribute( 'sortorder', $sortOrder );

    if ( count( $error ) == 0 )
    {
        $template->store();
        if ( !$http->hasPostVariable( 'TemplateCustomActionButton' ) )
            return $Module->redirectTo( $Module->functionURI( 'templatelist' ) );
    }
}

if ( $http->hasPostVariable( 'TemplateCustomActionButton' )
     and count( $error ) == 0 )
{
    $customActionArray = $http->postVariable( 'TemplateCustomActionButton' );
    $customKeys = array_keys( $customActionArray );
    $customID = $customKeys[0];
    $actionKeys = array_keys( $customActionArray[$customID] );
    $action = $actionKeys[0];
    if ( $template->AttributeList[$customID]['attribute'] )
    {
        $customAttribute = $template->AttributeList[$customID]['attribute'];
        $dataType = $customAttribute->dataType();
        if ( $dataType )
        {
            $dataType->templateHTTPAction( $template,
                                           $customAttribute,
                                           $http,
                                           $languageCode,
                                           $Module,
                                           $error,
                                           $action );
            $template->store();
            $template->updateAttributes( $languageCode );
            if ( $Module->exitStatus() == eZModule::STATUS_REDIRECT )
                return;
        }
    }
}

$attributeList = xrowProductAttribute::fetchList( array(), true, false, false, array( 'name' => 'asc' ) );

$usedDataTypes = array();
if ( count( $attributeList ) > 0 )
{
    foreach( $attributeList as $item )
    {
        if ( $item->attribute( 'active' ) == 1 )
            $activeAttributeList[$item->attribute( 'id' )] = $item;
    }
}


$tpl->setVariable( 'edit_data_type', false );
$tpl->setVariable( 'name', $template->name( $languageCode ) );
$tpl->setVariable( 'desc', $template->description( $languageCode ) );
$tpl->setVariable( 'active', $template->attribute( 'active' ) );

$tpl->setVariable( 'attribute_list', $attributeList );
$tpl->setVariable( 'active_attribute_list', $activeAttributeList );

$tpl->setVariable( 'language_locale', $languageCode );
$tpl->setVariable( 'error', $error );
$tpl->setVariable( 'template', $template );

eZDebug::writeDebug( $template->Data, 'Data' );
#eZDebug::writeDebug( $template->AttributeList, 'Data' );

$Result = array();
$Result['content'] = $tpl->fetch( "design:productvariation/templateedit.tpl" );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'extension/xrowecommerce/productvariation', 'Product variation - edit template' ),
                                'url' => false ) );
?>