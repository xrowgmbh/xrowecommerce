<?php
//
// Created on: <10-Feb-2007 00:00:00 ar>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Online Editor MCE extension for eZ Publish
// SOFTWARE RELEASE: 5.0
// COPYRIGHT NOTICE: Copyright (C) 2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

include_once( 'kernel/common/template.php' );

$objectID        = isset( $Params['ObjectID'] ) ? (int) $Params['ObjectID'] : 0;
$objectVersion   = isset( $Params['ObjectVersion'] ) ? (int) $Params['ObjectVersion'] : 0;
$variationName   = isset( $Params['VariationName'] ) ? (int) $Params['VariationName'] : 0;
$variationID     = isset( $Params['VariationID'] ) ? (int) $Params['VariationID'] : 0;

// Supported content types: image, media and file
// Media is threated as file for now
$contentType   = 'object';

if ( isset( $Params['ContentType'] ) && $Params['ContentType'] !== '' )
{
    $contentType   = $Params['ContentType'];
}

    
if ( $objectID === 0  || $objectVersion === 0 )
{
   echo ezi18n( 'design/standard/ezoe', 'Invalid or missing parameter: %parameter', null, array( '%parameter' => 'ObjectID/ObjectVersion' ) );
   eZExecution::cleanExit();
}


$user = eZUser::currentUser();
if ( $user instanceOf eZUser )
{
    $result = $user->hasAccessTo( 'ezoe', 'relations' );
}
else
{
    $result = array('accessWord' => 'no');
}

if ( $result['accessWord'] === 'no' )
{
   echo ezi18n( 'design/standard/error/kernel', 'Your current user does not have the proper privileges to access this page.' );
   eZExecution::cleanExit();
}



$object    = eZContentObject::fetch( $objectID );
$http      = eZHTTPTool::instance();
$imageIni  = eZINI::instance( 'image.ini' );
$params    = array('dataMap' => array('image'));


if ( !$object )
{
   echo ezi18n( 'design/standard/ezoe', 'Invalid parameter: %parameter = %value', null, array( '%parameter' => 'ObjectId', '%value' => $objectID ) );
   eZExecution::cleanExit();
}


// is this a upload?
// forcedUpload is needed since hasPostVariable returns false if post size exceeds
// allowed size set in max_post_size in php.ini
if ( $http->hasPostVariable( 'uploadButton' ) )
{
    //include_once( 'kernel/classes/ezcontentupload.php' );
    $upload = new eZContentUpload();

    $location = false;
    if ( $http->hasPostVariable( 'location' ) )
    {
        $location = $http->postVariable( 'location' );
        if ( $location === 'auto' || trim( $location ) === '' ) $location = false;
    }

    $objectName = '';
    if ( $http->hasPostVariable( 'objectName' ) )
    {
        $objectName = trim( $http->postVariable( 'objectName' ) );
    }

    $uploadedOk = $upload->handleUpload( $result, 'fileName', $location, false, $objectName );


    if ( $uploadedOk )
    {
        $newObject = $result['contentobject'];
        $newObjectID = $newObject->attribute( 'id' );
        
        // edit attributes
    }
    else
    {
    }
}


$siteIni       = eZINI::instance( 'site.ini' );
$contentIni    = eZINI::instance( 'content.ini' );

$groups             = $contentIni->variable( 'RelationGroupSettings', 'Groups' );
$defaultGroup       = $contentIni->variable( 'RelationGroupSettings', 'DefaultGroup' );
$imageDatatypeArray = $siteIni->variable( 'ImageDataTypeSettings', 'AvailableImageDataTypes' );


// $hasContentTypeGroup   = false;
// $contentTypeGroupName  = $contentType . 's';

$tpl = templateInit();
$tpl->setVariable( 'object', $object );
$tpl->setVariable( 'object_id', $objectID );
$tpl->setVariable( 'newObject', $newObject );
$tpl->setVariable( 'newObjectID', $newObjectID );
$tpl->setVariable( 'object_version', $objectVersion );
$tpl->setVariable( 'variationName', $variationName );
$tpl->setVariable( 'variationID', $variationID );
$tpl->setVariable( 'persistent_variable', array() );

$Result = array();
if( $uploadedOk )
{
	$Result['content'] = $tpl->fetch( 'design:variationupload/uploaddone.tpl' );
}
else
{
	$Result['content'] = $tpl->fetch( 'design:variationupload/upload.tpl' );
}

$Result['pagelayout'] = 'design:popup_pagelayout.tpl';
$Result['persistent_variable'] = $tpl->variable( 'persistent_variable' );

return $Result;

?>
