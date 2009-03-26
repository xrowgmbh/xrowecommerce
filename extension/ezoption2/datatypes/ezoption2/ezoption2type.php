<?php

//
// Definition of eZOptionType class
//
//Created on: <28-Jun-2002 11:12:51 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.7.x
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
            
            for ( $i = 0; $i < count( $valueList ); ++ $i )
                if ( trim( $valueList[$i] ) != '' )
                {
                    ++ $count;
                    break;
                }
            if ( $contentObjectAttribute->validateIsRequired() and trim( $dataName ) == '' )
            {
                $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'NAME is required.' ) );
                return eZInputValidator::STATE_INVALID;
            }
            if ( $count != 0 )
            {
                for ( $i = 0; $i < count( $idList ); $i ++ )
                {
                    $value = $valueList[$i];
                    if ( trim( $value ) == "" )
                    {
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'The option value must be provided.' ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                    if ( isset( $optionAdditionalPriceList[$i] ) && strlen( $optionAdditionalPriceList[$i] ) && ! preg_match( "#^[-|+]?[0-9]+(\.){0,1}[0-9]{0,2}$#", trim( $optionAdditionalPriceList[$i] ) ) )
                    {
                        $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes', 'The Additional price value is not valid.' ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                }
            }
        }
        if ( $contentObjectAttribute->validateIsRequired() and ! $classAttribute->attribute( 'is_information_collector' ) )
        {
            if ( $count == 0 )
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
        $i = 0;
        
        foreach ( $optionIDArray as $id )
        {
            $optionPriceArray = array();
            foreach ( $optionPriceArraytmp[$i] as $key => $price )
            {
                $optionPriceArray[$key] = array( 
                    'value' => $price , 
                    'currency_code' => $key , 
                    'type' => eZMultiPriceData::VALUE_TYPE_CUSTOM 
                );
            }
            $option->addOption( array( 
                'value' => $optionValueArray[$i] , 
                'comment' => $optionCommentArray[$i] , 
                'weight' => $optionWeightArray[$i] , 
                'description' => $optionDescriptionArray[$i] , 
                'image' => $optionImageArray[$i] , 
                'additional_price' => ( isset( $optionAdditionalPriceArray[$i] ) ? $optionAdditionalPriceArray[$i] : 0 ) , 
                'multi_price' => new eZOptionMultiPrice( $optionPriceArray ) 
            ) );
            $i ++;
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
                    $postvarname = "ContentObjectAttribute" . "_data_option_remove_" . $contentObjectAttribute->attribute( "id" );
                    if ( $http->hasPostVariable( $postvarname ) )
                    {
                        $idArray = $http->postVariable( $postvarname );
                        $beforeID = array_shift( $idArray );
                        if ( $beforeID >= 0 )
                        {
                            $option->insertOption( array( 
                                'multi_price' => new eZOptionMultiPrice( ) 
                            ), $beforeID );
                            
                            $contentObjectAttribute->setContent( $option );
                            $contentObjectAttribute->store();
                            $option = new eZOption2( $contentObjectAttribute );
                            $contentObjectAttribute->setContent( $option );
                            return;
                        }
                    }
                    $option->addOption( "" );
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
                            $selectedObjectArray = $http->hasPostVariable( "SelectedObjectIDArray" );
                            $selectedObjectIDArray = $http->postVariable( "SelectedObjectIDArray" );
                            
                            // Delete the old version from ezcontentobject_link if count of translations > 1
                            #$this->removeContentObjectRelation( $contentObjectAttribute );
                            $option = $contentObjectAttribute->content();
                            $optionarray = $option->Options;
                            $objectID = $selectedObjectIDArray[0];
                            $optionarray[$params[1]]["image"] = $objectID;
                            $option->Options = $optionarray;
                            $contentObjectAttribute->setAttribute( 'data_Text', $option );
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
                    $option = $contentObjectAttribute->content();
                    $optionarray = $option->Options;
                    $objectID = $selectedObjectIDArray[0];
                    $optionarray[$params[1]]["image"] = "";
                    $option->Options = $optionarray;
                    $contentObjectAttribute->setAttribute( 'data_Text', $option );
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
        foreach ( $option->attribute( 'option_list' ) as $optionArray )
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
    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $defaultValue = $classAttribute->attribute( 'data_text1' );
        $attributeParametersNode->appendChild( eZDOMDocument::createElementTextNode( 'default-value', $defaultValue ) );
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $defaultValue = $attributeParametersNode->elementTextContentByName( 'default-value' );
        $classAttribute->setAttribute( 'data_text1', $defaultValue );
    }

    /*!
     \reimp
    */
    function serializeContentObjectAttribute( $package, $objectAttribute )
    {
        $node = $this->createContentObjectAttributeDOMNode( $objectAttribute );
        
        $xml = new eZXML( );
        $domDocument = $xml->domTree( $objectAttribute->attribute( 'data_text' ) );
        $node->appendChild( $domDocument->root() );
        
        return $node;
    }

    /*!
     \reimp
    */
    function unserializeContentObjectAttribute( $package, $objectAttribute, $attributeNode )
    {
        $rootNode = $attributeNode->firstChild();
        $xmlString = $rootNode->attributeValue( 'local_name' ) == 'data-text' ? $rootNode->toString( 0 ) : '';
        $objectAttribute->setAttribute( 'data_text', $xmlString );
    }

    /*!
     \reimp
    */
    function isInformationCollector()
    {
        return true;
    }
    function onPublish( $contentObjectAttribute, $contentObject, $publishedNodes )
    {
    	$trans = $contentObjectAttribute->fetchAttributeTranslations();
    	$xml = $contentObjectAttribute->attribute( "data_text" );
    	$mpNode = $dom->getElementsByTagName( "multi_price" )->item( 0 );
    	foreach ( $trans as $translation )
    	{
    		$old = $translation->attribute( "data_text" );
    		$dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $old );
            $translationMPNode = $dom->getElementsByTagName( "multi_price" )->item( 0 );
            $translationMPNode = clone $mpNode;
    		$translation->setAttribute( "data_text", $dom->saveXML() );
    		eZPersistentObject::storeObject( $translation );
    	}

    }
}

eZDataType::register( eZOption2Type::OPTION2, "ezoption2type" );

?>
