<?php

//
// Definition of eZOptionType class
//
//Created on: <28-Jun-2002 11:12:51 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 1.1-0
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
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


/*!
  \class eZOptionType ezoptiontype.php
  \ingroup eZDatatype
  \brief Stores option values

*/

#include_once( "kernel/classes/ezdatatype.php" );
#include_once( "extension/ezoption2/datatypes/ezoption2/ezoption.php" );


class eZOption2Type extends eZDataType
{
    const DEFAULT_NAME_VARIABLE = "_ezoption_default_name_";
    const OPTION2 = "ezoption2";

    function eZOption2Type()
    {
        $this->eZDataType( self::OPTION2, ezi18n( 'kernel/classes/datatypes', "Option2", 'Datatype name' ), array( 
            'serialize_supported' => true 
        ) );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $count = 0;
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        if ( $http->hasPostVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $idList = $http->postVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) );
            $valueList = $http->postVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) );
            $dataName = $http->postVariable( $base . "_data_option_name_" . $contentObjectAttribute->attribute( "id" ) );
            
            if ( $http->hasPostVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) ) )
                $optionAdditionalPriceList = $http->postVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) );
            else
                $optionAdditionalPriceList = array();
                /*
            for ( $i = 0; $i < count( $valueList ); ++ $i )
                if ( trim( $valueList[$idList[$i]] ) != '' )
                {
                    ++ $count;
                    break;
                }
*/
            if ( $contentObjectAttribute->validateIsRequired() and trim( $dataName ) == '' )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'NAME is required.' ) );
                return eZInputValidator::STATE_INVALID;
            }
            foreach ( $idList as $id )
            {
                if ( trim( $valueList[$id] ) == "" )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'The option value must be provided.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
                if ( isset( $optionAdditionalPriceList[$id] ) && strlen( $optionAdditionalPriceList[$id] ) && ! preg_match( "#^[-|+]?[0-9]+(\.){0,1}[0-9]{0,2}$#", trim( $optionAdditionalPriceList[$id] ) ) )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'The Additional price value is not valid.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
            }
        }
        if ( $contentObjectAttribute->validateIsRequired() and ! $classAttribute->attribute( 'is_information_collector' ) )
        {
            if ( count( $idList ) == 0 )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'At least one option is required.' ) );
                return eZInputValidator::STATE_INVALID;
            }
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     Store content
    */
    function storeObjectAttribute( $contentObjectAttribute )
    {
        $option = $contentObjectAttribute->content();
        $contentObjectAttribute->setAttribute( "data_text", $option->xmlString() );
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $option = new eZOption2( $contentObjectAttribute );
        
        return $option;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_text" );
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $optionName = $http->postVariable( $base . "_data_option_name_" . $contentObjectAttribute->attribute( "id" ) );
        
        if ( $http->hasPostVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) ) )
            $optionIDArray = $http->postVariable( $base . "_data_option_id_" . $contentObjectAttribute->attribute( "id" ) );
        else
            $optionIDArray = array();
        
        if ( $http->hasPostVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) ) )
            $optionValueArray = $http->postVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) );
        else
            $optionValueArray = array();
        
        if ( $http->hasPostVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) ) )
            $optionAdditionalPriceArray = $http->postVariable( $base . "_data_option_additional_price_" . $contentObjectAttribute->attribute( "id" ) );
        else
            $optionAdditionalPriceArray = array();
        
        if ( $http->hasPostVariable( $base . "_data_option_comment_" . $contentObjectAttribute->attribute( "id" ) ) )
            $optionCommentArray = $http->postVariable( $base . "_data_option_comment_" . $contentObjectAttribute->attribute( "id" ) );
        else
            $optionCommentArray = array();
        
        if ( $http->hasPostVariable( $base . "_data_option_description_" . $contentObjectAttribute->attribute( "id" ) ) )
            $optionDescriptionArray = $http->postVariable( $base . "_data_option_description_" . $contentObjectAttribute->attribute( "id" ) );
        else
            $optionDescriptionArray = array();
        
        if ( $http->hasPostVariable( $base . "_data_option_weight_" . $contentObjectAttribute->attribute( "id" ) ) )
            $optionWeightArray = $http->postVariable( $base . "_data_option_weight_" . $contentObjectAttribute->attribute( "id" ) );
        else
            $optionWeightArray = array();
        
        if ( $http->hasPostVariable( $base . "_data_option_image_" . $contentObjectAttribute->attribute( "id" ) ) )
            $optionImageArray = $http->postVariable( $base . "_data_option_image_" . $contentObjectAttribute->attribute( "id" ) );
        else
            $optionImageArray = array();
        $option = new eZOption2( $contentObjectAttribute, $optionName, false );
        
        foreach ( $optionAdditionalPriceArray as $index => $additionalPrice )
        {
            if ( isset( $additionalPrice ) )
            {
                $optionAdditionalPriceArray[$index] = trim( $additionalPrice );
            }
        }
        if ( $http->hasPostVariable( $base . "_price_array_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $optionPriceArraytmp = $http->postVariable( $base . "_price_array_" . $contentObjectAttribute->attribute( "id" ) );
        }
        else
        {
            $optionPriceArray = array();
        }
        
        foreach ( $optionIDArray as $id )
        {
            $optionPriceArray = array();
            foreach ( $optionPriceArraytmp[$id] as $key => $price )
            {
                $optionPriceArray[$key] = array( 
                    'value' => sprintf( "%01.2f", $price ) ,  #number_format($price, 2, '.') , 
                    'currency_code' => $key , 
                    'type' => eZMultiPriceData::VALUE_TYPE_CUSTOM 
                );
            }
            $option->addOption( array( 
                'id' => $id , 
                'value' => $optionValueArray[$id] , 
                'comment' => $optionCommentArray[$id] , 
                'weight' => $optionWeightArray[$id] , 
                'description' => $optionDescriptionArray[$id] , 
                'image' => $optionImageArray[$id] , 
                'additional_price' => ( isset( $optionAdditionalPriceArray[$id] ) ? $optionAdditionalPriceArray[$id] : 0 ) , 
                'multi_price' => new eZOptionMultiPrice( $optionPriceArray, $contentObjectAttribute ) 
            ) );
        }
        $contentObjectAttribute->setContent( $option );
        return true;
    }

    /*!
     Fetches the http post variables for collected information
    */
    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $optionValue = $http->postVariable( $base . "_data_option_value_" . $contentObjectAttribute->attribute( "id" ) );
            
            $collectionAttribute->setAttribute( 'data_int', $optionValue );
            $attr = $contentObjectAttribute->attribute( 'contentclass_attribute' );
            
            return true;
        }
        return false;
    }

    /*!
    */
    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute, $parameters )
    {
        switch ( $action )
        {
            case 'set_custom_price':
                {
                    $selectedCurrencyName = 'ContentObjectAttribute' . '_selected_currency_' . $contentObjectAttribute->attribute( 'id' );
                    if ( $http->hasPostVariable( $selectedCurrencyName ) )
                    {
                        
                        $selectedCurrency = $http->postVariable( $selectedCurrencyName );
                        $optiona = $contentObjectAttribute->content();
                        
                        foreach ( $optiona->Options as $key => $option )
                        {
                            
                            $price = $optiona->Options[$key]['multi_price']->priceByCurrency( $selectedCurrency );
                            $optiona->Options[$key]['multi_price']->removePriceByCurrency( $selectedCurrency );
                            $optiona->Options[$key]['multi_price']->setCustomPrice( $selectedCurrency, $price['value'] );
                        }
                        
                        $optiona->store();
                    
                    }
                }
                break;
            
            case 'remove_custom_price':
                {
                    $removePriceArrayName = 'ContentObjectAttribute' . '_selected_currency_' . $contentObjectAttribute->attribute( 'id' );
                    if ( $http->hasPostVariable( $removePriceArrayName ) )
                    {
                        
                        $currencyCode = $http->postVariable( $removePriceArrayName );
                        $optiona = $contentObjectAttribute->content();
                        
                        foreach ( $optiona->Options as $key => $option )
                        {
                            $optiona->Options[$key]['multi_price']->setAutoPrice( $currencyCode, false );
                            $optiona->Options[$key]['multi_price']->updateAutoPriceList();
                        }
                        $optiona->store();
                    }
                }
                break;
        }
        $params = explode( '-', $action );
        
        switch ( $params[0] )
        {
            
            case "new_option":
                {
                    $option = $contentObjectAttribute->content();
                    
                    if ( $option->OptionCount > 0 )
                    {
                        
                        foreach ( array_keys( $option->Options[0]['multi_price']->customPriceList() ) as $currency )
                        {
                            $priceList[$currency] = array( 
                                'currency_code' => $currency , 
                                'value' => '0.00' , 
                                'type' => eZMultiPriceData::VALUE_TYPE_CUSTOM 
                            );
                        }
                    }
                    $option->addOption( array( 
                        'multi_price' => new eZOptionMultiPrice( $priceList, $contentObjectAttribute ) 
                    ) );
                    
                    $contentObjectAttribute->setContent( $option );
                    $contentObjectAttribute->store();
                
                }
                break;
            case "remove_selected":
                {
                    $option = $contentObjectAttribute->content();
                    $postvarname = "ContentObjectAttribute" . "_data_option_remove_" . $contentObjectAttribute->attribute( "id" );
                    $array_remove = $http->postVariable( $postvarname );
                    $option->removeOptions( $array_remove );
                    $contentObjectAttribute->setContent( $option );
                    $contentObjectAttribute->store();
                    $option = new eZOption2( $contentObjectAttribute );
                    $contentObjectAttribute->setContent( $option );
                }
                break;
            case "set_object_relation":
                {
                    if ( $http->hasPostVariable( 'BrowseActionName' ) and $http->postVariable( 'BrowseActionName' ) == ( 'AddRelatedObject_' . $contentObjectAttribute->attribute( 'id' ) ) and $http->hasPostVariable( "SelectedObjectIDArray" ) )
                    {
                        if ( ! $http->hasPostVariable( 'BrowseCancelButton' ) )
                        {
                            $selectedObjectIDArray = $http->postVariable( "SelectedObjectIDArray" );
                            
                            // Delete the old version from ezcontentobject_link if count of translations > 1
                            #$this->removeContentObjectRelation( $contentObjectAttribute );
                            $options = $contentObjectAttribute->content();
                            foreach ( $options->Options as $key => $option )
                            {
                                if ( $option['id'] == $params[1] and isset( $selectedObjectIDArray[0] ) )
                                {
                                    $options->Options[$key]["image"] = $selectedObjectIDArray[0];
                                }
                            }

                            $contentObjectAttribute->store();
                        }
                    }
                }
                break;
            case "browse_object":
                {
                    $module = $parameters['module'];
                    $redirectionURI = $parameters['current-redirection-uri'];
                    $ini = eZINI::instance( 'content.ini' );
                    
                    //include_once( 'kernel/classes/ezcontentbrowse.php' );
                    $browseType = 'AddRelatedObjectToDataType';
                    $browseTypeINIVariable = $ini->variable( 'ObjectRelationDataTypeSettings', 'ClassAttributeStartNode' );
                    foreach ( $browseTypeINIVariable as $value )
                    {
                        list ( $classAttributeID, $type ) = explode( ';', $value );
                        if ( $classAttributeID == $contentObjectAttribute->attribute( 'contentclassattribute_id' ) && strlen( $type ) > 0 )
                        {
                            $browseType = $type;
                            break;
                        }
                    }
                    eZContentBrowse::browse( array( 
                        'action_name' => 'AddRelatedObject_' . $contentObjectAttribute->attribute( 'id' ) , 
                        'type' => $browseType , 
                        'browse_custom_action' => array( 
                            'name' => 'CustomActionButton[' . $contentObjectAttribute->attribute( 'id' ) . '_set_object_relation-' . $params[1] . ']' , 
                            'value' => $contentObjectAttribute->attribute( 'id' ) 
                        ) , 
                        'persistent_data' => array( 
                            'HasObjectInput' => 0 
                        ) , 
                        'from_page' => $redirectionURI 
                    ), $module );
                }
                break;
            
            case "remove_object":
                {
                    // Delete the old version from ezcontentobject_link if count of translations > 1
                    $options = $contentObjectAttribute->content();

                    foreach ( $options->Options as $key => $option )
                    {
                        if ( $option['id'] == $params[1] )
                        {
                            $options->Options[$key]["image"] = '';
                        }
                    }
                    $contentObjectAttribute->store();
                }
                break;
            default:
                {
                    eZDebug::writeError( "Unknown custom HTTP action: " . $action, "eZOptionType" );
                }
                break;
        }
    }

    /*!
     Finds the option which has the ID that matches \a $optionID, if found it returns
     an option structure.
    */
    function productOptionInformation( $objectAttribute, $optionID, $productItem )
    {
        $option = $objectAttribute->attribute( 'content' );
        $list = $option->attribute( 'option_list' );
        foreach ( $list as $optionArray )
        {
            if ( $optionArray['id'] == $optionID )
            {
                return array( 
                    'id' => $optionArray['id'] , 
                    'name' => $option->attribute( 'name' ) , 
                    'value' => $optionArray['value'] , 
                    'comment' => $optionArray['comment'] , 
                    'weight' => $optionArray['weight'] , 
                    'image' => $optionArray['image'] , 
                    'additional_price' => $optionArray['multi_price']->price() 
                );
            }
        }
        return false;
    }

    /*!
     Returns the integer value.
    */
    function title( $contentObjectAttribute, $name = "name" )
    {
        $option = $contentObjectAttribute->content();
        
        $value = $option->attribute( $name );
        
        return $value;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $option = $contentObjectAttribute->content();
        $options = $option->attribute( 'option_list' );
        return count( $options ) > 0;
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion == false )
        {
            $option = $contentObjectAttribute->content();
            $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
            if ( ! $option )
            {
                $option = new eZOption2( $contentObjectAttribute, $contentClassAttribute->attribute( 'data_text1' ) );
            }
            else
            {
                $option->setName( $contentClassAttribute->attribute( 'data_text1' ) );
            }
            $contentObjectAttribute->setAttribute( "data_text", $option->xmlString() );
            $contentObjectAttribute->setContent( $option );
        }
        else
        {
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
    }

    /*!
     \reimp
    */
    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $defaultValueName = $base . eZOption2Type::DEFAULT_NAME_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $defaultValueName ) )
        {
            $defaultValueValue = $http->postVariable( $defaultValueName );
            
            if ( $defaultValueValue == "" )
            {
                $defaultValueValue = "";
            }
            $classAttribute->setAttribute( 'data_text1', $defaultValueValue );
            return true;
        }
        return false;
    }

    /*!
     \reimp
    */
    function isInformationCollector()
    {
        return true;
    }
    /*!
     \param contentobject attribute object
     \param domnode object
    */
    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        foreach ( $attributeNode->childNodes as $childNode )
        {
            if ( $childNode->nodeType == XML_ELEMENT_NODE )
            {
                $xmlString = $childNode->ownerDocument->saveXML( $childNode );
                $doc = new DOMDocument( '1.0', 'utf-8' );
        		$success = $doc->loadXML( $xmlString );
        		$objects = $doc->getElementsByTagName( 'option' );
        		$modified = self::transformRemoteLinksToLinks( $objects, $objectAttribute );
       			$xmlString = $doc->saveXML();        
        		$objectAttribute->setAttribute( 'data_text', $xmlString );
                break;
            }
        }
    }
    static function transformRemoteLinksToLinks( DOMNodeList $nodeList, $objectAttribute )
    {
        $modified = false;

        $contentObject = $objectAttribute->attribute( 'object' );
        foreach ( $nodeList as $node )
        {
            $objectRemoteID = $node->getAttribute( 'object_remote_id' );
            if ( $objectRemoteID )
            {
                $objectArray = eZContentObject::fetchByRemoteID( $objectRemoteID, false );

                if ( !is_array( $objectArray ) )
                {
                    eZDebug::writeWarning( "Can't fetch object with remoteID = $objectRemoteID", 'eZXMLTextType::unserialize' );
                    continue;
                }

                $objectID = $objectArray['id'];
                
                $node->setAttribute( 'image', $objectID );
                $node->removeAttribute( 'object_remote_id' );
                $modified = true;

                // add as related object
                if ( $contentObject )
                {
                    $relationType = $node->localName == 'link' ? eZContentObject::RELATION_LINK : eZContentObject::RELATION_EMBED;
                    $contentObject->addContentObjectRelation( $objectID, $objectAttribute->attribute( 'version' ), 0, $relationType );
                }
            }
        }

        return $modified;
    }
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {

        $DOMNode = $this->createContentObjectAttributeDOMNode( $objectAttribute );
        $xmlString = $objectAttribute->attribute( 'data_text' );

        if ( $xmlString != '' )
        {
            $doc = new DOMDocument( '1.0', 'utf-8' );
            $success = $doc->loadXML( $xmlString );

            /* For all links found in the XML, do the following:
             * - add "href" attribute fetching it from ezurl table.
             * - remove "id" attribute.
             */

            $objects = $doc->getElementsByTagName( 'option' );

            self::transformLinksToRemoteLinks( $objects );

            $importedRootNode = $DOMNode->ownerDocument->importNode( $doc->documentElement, true );
            $DOMNode->appendChild( $importedRootNode );
        }

        return $DOMNode;
    }
    static function transformLinksToRemoteLinks( DOMNodeList $nodeList )
    {
        foreach ( $nodeList as $node )
        {

            $isObject = ( $node->getAttribute( 'image' ) != '' );
            $objectID = $isObject ? $node->getAttribute( 'image' ) : false;

            if ( $objectID )
            {
                $object = eZContentObject::fetch( $objectID, false );
                if ( is_array( $object ) )
                {
                    $node->setAttribute( 'object_remote_id', $object['remote_id'] );
                }

                if ( $isObject )
                {
                    $node->removeAttribute( 'image' );
                }
                else
                {
                    $node->removeAttribute( 'object_id' );
                }
            }
        }
    }
    function onPublish( $contentObjectAttribute, $contentObject, $publishedNodes )
    {
        
        $trans = $contentObjectAttribute->fetchAttributeTranslations();
        $xml = $contentObjectAttribute->attribute( "data_text" );
        #echo "START $contentObjectAttribute->LanguageCode\n";
        #echo $xml . "\n";
        $dom2 = new DOMDocument( '1.0', 'utf-8' );
        $success = $dom2->loadXML( $xml );
        $options = $dom2->getElementsByTagName( "option" );
        $current = array();
        if ( $options->length > 0 )
        {
            foreach ( $options as $option )
            {
                $current[$option->getAttribute( 'id' )] = $option;
            }
        }
        foreach ( $trans as $translation )
        {
            if ( $contentObjectAttribute->LanguageCode == $translation->LanguageCode )
            {
                continue;
            }
            $old = $translation->attribute( "data_text" );
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $old );
            #echo "OLD $translation->LanguageCode\n";
            #echo $old . "\n";
            $toptionNode = $dom->getElementsByTagName( "options" )->item( 0 );
            $toptions = $dom->getElementsByTagName( "option" );
            if ( $options->length > 0 )
            {
                foreach ( $toptions as $toption )
                {
                    if ( array_key_exists( $toption->getAttribute( 'id' ), $current ) )
                    {
                        $toption->setAttribute( 'weight', $current[$toption->getAttribute( 'id' )]->getAttribute( 'weight' ) );
                        
                        $toption->replaceChild( $dom->importNode( $current[$toption->getAttribute( 'id' )]->firstChild, true ), $toption->firstChild );
                        $txpath = new DOMXPath( $dom );
                        $tentries = $txpath->query( 'multi_price', $toption );
                        $xpath = new DOMXPath( $dom2 );
                        $entries = $xpath->query( 'multi_price', $current[$toption->getAttribute( 'id' )] );
                        
                        if ( $tentries->length > 0 and $entries->length > 0 )
                        {
                            $toption->removeChild( $tentries->item( 0 ) );
                            $toption->appendChild( $dom->importNode( $entries->item( 0 ), true ) );
                        }
                        elseif ( $tentries->length > 0 and $entries->length == 0 )
                        {
                            $toption->removeChild( $tentries->item( 0 ) );
                        }
                        elseif ( $tentries->length == 0 and $entries->length > 0 )
                        {
                            $toption->appendChild( $dom->importNode( $entries->item( 0 ), true ) );
                        }
                    
                    }
                    else
                    {
                        $toptionNode->removeChild( $toption );
                    }
                }
                foreach ( array_keys( $current ) as $key )
                {
                    $ok = false;
                    foreach ( $toptions as $toption )
                    {
                        if ( $toption->getAttribute( 'id' ) == $key )
                        {
                            $ok = true;
                            break;
                        }
                    
                    }
                    if ( $ok === false )
                    {
                        $toptionNode->appendChild( $dom->importNode( $current[$key], true ) );
                    }
                }
            }
            $translation->setAttribute( "data_text", $dom->saveXML() );
            #echo "$translation->LanguageCode\n";
            #echo $translation->attribute( "data_text" ) . "\n";
            

            eZPersistentObject::storeObject( $translation );
        
        }
    }
}

eZDataType::register( eZOption2Type::OPTION2, "ezoption2type" );

?>
