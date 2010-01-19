<?php
/*
 * List the columns of the product variation datatype
 */

require_once( "kernel/common/template.php" );

$Module = $Params['Module'];
$Module->setTitle( 'Product variation - attribute edit' );

$tpl = templateInit();
$http = eZHTTPTool::instance();

$id = false;
if ( isset( $Params['ID'] ) and $Params['ID'] > 0 )
    $id = $Params['ID'];

$languageCode = false;

if ( isset( $Params['Language'] ) and strlen( $Params['Language'] ) > 1 )
    $languageCode = $Params['Language'];
else if ( $http->hasPostVariable( 'EditLanguage' ) )
{
    $languageCode = $http->postVariable( 'EditLanguage' );
    $Module->redirectTo( $Module->functionURI( 'attributeedit' ) . '/' . $id . '/' . $languageCode );
    return;
}

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
        $tpl->setVariable( 'formurl', 'productvariation/attributeedit/' . $id );
        $Result = array();
        $Result['content'] = $tpl->fetch( 'design:productvariation/select_language.tpl' );
        $Result['path'] = array( array( 'url' => false,
                                        'text' => ezi18n( 'extension/xrowecommerce/productvariation', 'Attribute edit' ) ) );
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
    $attribute = new xrowProductAttribute();
    $attribute->setData( 'name', $languageCode, ezi18n( 'extension/xrowecommerce/productvariation', 'New attribute' ) );
    $attribute->setAttribute( 'name', ezi18n( 'extension/xrowecommerce/productvariation', 'New attribute' ) );

    $editLanguageID = eZContentLanguage::idByLocale( $languageCode );
    $langMask = eZContentLanguage::maskByLocale( array( $languageCode ) );
    $attribute->setAttribute( 'initial_language_id', $editLanguageID );
    $attribute->setAttribute( 'language_mask', $langMask );
    $attribute->setAttribute( 'user_id', $userID );
    $attribute->setAttribute( 'created', time() );
    $attribute->setAttribute( 'active', 0 );

    $attribute->store();

    $id = $attribute->attribute( 'id' );
    $Module->redirectTo( $Module->functionURI( 'attributeedit' ) . '/' . $id . '/' . $languageCode );
    return;

}
else
{
    $attribute = xrowProductAttribute::fetch( $id );
}

if ( !is_object( $attribute ) )
{
    eZDebug::writeError( "Unknown xrowProductAttribute with ID $id", 'xrowEcommerce - product variation' );
    $Module->setExitStatus( eZModule::STATUS_FAILED );
    return $Module->handleError( eZError::KERNEL_NOT_AVAILABLE, 'kernel' );
}

if ( $http->hasPostVariable( 'CancelButton' ) )
{
    if ( $attribute->attribute( 'data_type' ) == "" and
         $attribute->attribute( 'active' ) != 1 )
        $attribute->remove();
    return $Module->redirectTo( $Module->functionURI( 'attributelist' )  );
}

$dataType = $attribute->attribute( 'data_type' );
if ( !$dataType or strlen( $dataType ) == 0 )
    $editDataType = true;
else
    $editDataType = false;

$error = array();

if ( $editDataType )
{
    $dataTypeString = '';
    if ( $http->hasPostVariable( 'StoreButton' ) )
    {
        if ( $http->hasPostVariable( 'DataTypeString' ) )
        {
            $dataTypeString = $http->postVariable( 'DataTypeString' );
            if ( strlen( $dataTypeString ) > 0 )
            {
                $dataType = xrowProductDataType::create( $dataTypeString );
                if ( !$dataType )
                    $error['datatype'] = true;
                else
                {
                    $attribute->setAttribute( 'data_type', $dataTypeString );
                    $attribute->store();
                    return $Module->redirectTo( $Module->functionURI( 'attributeedit' ) . '/' . $id . '/' . $languageCode );
                }
            }
            else
                $error['datatype'] = true;
        }
        else
            $error['datatype'] = true;
    }
    $tpl->setVariable( 'data_type', $dataType );
    $tpl->setVariable( 'edit_data_type', true );

    xrowProductDataType::loadAndRegisterAllTypes();
    $dataTypeArray = xrowProductDataType::registeredDataTypes();

    // remove datatypes which must be unique, like price
    foreach ( $dataTypeArray as $key => $item )
    {
        if ( $item->attribute( 'unique' ) )
        {
            if ( xrowProductAttribute::hasDatatype( $item ) )
            {
                #eZDebug::writeDebug( $item->DataTypeString . " already used, unset", "xrowproductvariation attributeedit" );
                unset( $dataTypeArray[$key] );
            }
        }
    }

    $tpl->setVariable( 'data_type_array', $dataTypeArray );
}
else
{
    if ( $http->hasPostVariable( 'StoreButton' ) )
    {
        $name = trim( $http->postVariable( 'name' ) );
        if ( strlen( $name ) == 0 )
            $error['name'] = true;

        $attribute->setData( 'name', $languageCode, $name );
        if ( $attribute->attribute( 'initial_language_id' ) == eZContentLanguage::idByLocale( $languageCode ) )
            $attribute->setAttribute( 'name', $name );

        $identifier = trim( $http->postVariable( 'identifier' ) );
        if ( strlen( $identifier ) == 0 )
            $error['identifier'] = true;
        else
        {
            $trans = eZCharTransform::instance();
            $identifier = $trans->transformByGroup( $identifier, 'identifier' );

            if ( $attribute->checkIdentifierDupe( $identifier ) )
                $error['identifier_dupe'] = true;
        }
        $desc = trim( $http->postVariable( 'description' ) );
        $attribute->setData( 'desc', $languageCode, $desc );

        $active = 0;
        if ( $http->hasPostVariable( 'active' ) )
            $active = 1;

        $attribute->setAttribute( 'active', $active );

        $oldIdentifier = $attribute->attribute( 'identifier' );
        $attribute->setAttribute( 'identifier', $identifier );

        if ( count( $error ) == 0 )
        {
            $locales = array_keys( $attribute->Data['name'] );
            $mask = eZContentLanguage::maskByLocale( $locales );
            $attribute->setAttribute( 'language_mask', $mask );

            if ( $dataType = $attribute->dataType() )
            {
                $dataType->initializeAttribute( $attribute );
                if ( $oldIdentifier != $identifier )
                    $dataType->renameColumns( $attribute, $oldIdentifier );

                $dataType->fetchAttributeInput( $http, $attribute );
                $dataType->validateAttributeInput( $attribute, $error );
            }

            if ( count( $error ) == 0 )
            {
                $attribute->store();
                return $Module->redirectTo( $Module->functionURI( 'attributelist' ) );
            }
        }
    }

    $tpl->setVariable( 'edit_data_type', false );
    $tpl->setVariable( 'name', $attribute->name( $languageCode ) );
    $tpl->setVariable( 'desc', $attribute->description( $languageCode ) );
    $tpl->setVariable( 'identifier', $attribute->attribute( 'identifier' ) );
    $tpl->setVariable( 'active', $attribute->attribute( 'active' ) );
}

$tpl->setVariable( 'attribute_id', $id );
$tpl->setVariable( 'language_locale', $languageCode );
$tpl->setVariable( 'error', $error );
$tpl->setVariable( 'attribute', $attribute );

$Result = array();
$Result['content'] = $tpl->fetch( "design:productvariation/attributeedit.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'extension/xrowecommerce/productvariation', 'Product variation - edit attribute' ),
                                'url' => false ) );
?>