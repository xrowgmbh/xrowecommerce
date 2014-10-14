<?php
/**
 * class xrowProductVariationType xrowproductvariationtype.php
 * This allows the user to add several variations of one product
 */

class xrowProductVariationType extends eZDataType
{
    const DATA_TYPE_STRING = "xrowproductvariation";

    /*!
     Constructor to initialize the datatype.
    */
    function xrowProductVariationType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', "Product variations", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }

    /*!
     \reimp
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {

        if ( $http->hasSessionVariable( 'xrowProductVariationErrorArray' ) )
            $http->removeSessionVariable( 'xrowProductVariationErrorArray' );

        $errorArray = array();

        $dataKey = "XrowProductVariation";
        $id = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObjectAttribute->attribute( 'version' );
        if ( $http->hasPostVariable( $dataKey ) )
        {
            $languageCode = $contentObjectAttribute->attribute( 'language_code' );

            $templateKey = "XrowProductTemplate_templateid_" . $id;
            if ( $http->hasPostVariable( $templateKey ) )
            {
                $templateID = (int) $http->postVariable( $templateKey );
            }
            else
                $templateID = $contentObjectAttribute->attribute( 'data_int' );

            $template = xrowProductTemplate::fetch( $templateID );

            if ( !$template )
            {
                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                 'Please choose a template for the product variation.' ) );
                return eZInputValidator::STATE_INVALID;
            }

            $template->updateAttributes( $languageCode );

            if ( $template )
            {
                $variationAllArray = $http->postVariable( $dataKey );
                if ( isset( $variationAllArray[$id] ) )
                {
                    $variationArray = $variationAllArray[$id];

                    foreach( $variationArray as $line => $variation )
                    {
                        foreach( $template->AttributeList as $attribute )
                        {
                            $dataType = $attribute['attribute']->dataType();
                            $column = $attribute['attribute']->Identifier;
                            if ( $dataType )
                            {
                                $dataType->validateVariationInput( $variationArray,
                                                                   $line,
                                                                   $column,
                                                                   $contentObjectAttribute,
                                                                   $attribute,
                                                                   $http,
                                                                   $errorArray );

                            }
                        }
                    }
                }
            }
        }

        $GLOBALS['xrowProductVariationError'][$id][$version] =& $errorArray;

        if ( count( $errorArray ) > 0 )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                 'Please correct the input of product variations. The wrong or missing input is marked with a red border.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        else
            return eZInputValidator::STATE_ACCEPTED;
    }

    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $cObj = $contentObjectAttribute->content();
        $content = $cObj->attribute( 'content' );

        $id = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObjectAttribute->attribute( 'version' );
        $languageCode = $contentObjectAttribute->attribute( 'language_code' );

        $templateKey = "XrowProductTemplate_templateid_" . $id;
        if ( $http->hasPostVariable( $templateKey ) )
        {
            $templateID = (int) $http->postVariable( $templateKey );
            if ( $templateID != $contentObjectAttribute->attribute( 'data_int' )
                 AND $templateID > 0 )
                 $this->updateTemplateID( $templateID, $contentObjectAttribute, $version );
        }
        else
            $templateID = $contentObjectAttribute->attribute( 'data_int' );

        $template = xrowProductTemplate::fetch( $templateID );
        $template->updateAttributes( $languageCode );

        $content['template_id'] =& $templateID;
        $content['template'] =& $template;

        $content['data'] = array();

        if ( $template )
        {
            $columndef = array( 'column_name_array' => array(),
                                'column_desc_array' => array() );

            $columnDefNameKey = 'XrowProductColumnNameArray';
            if ( $http->hasPostVariable( $columnDefNameKey ) )
            {
                $columnNameArray = $http->postVariable( $columnDefNameKey );
                $columndef['column_name_array'] = $columnNameArray[$id];
            }
            $columnDefDescKey = 'XrowProductColumnDescArray';
            if ( $http->hasPostVariable( $columnDefDescKey ) )
            {
                $columnDescArray = $http->postVariable( $columnDefDescKey );
                $columndef['column_desc_array'] = $columnDescArray[$id];
            }
            $contentObjectAttribute->setAttribute( 'data_text', serialize( $columndef ) );

            $dataKey = "XrowProductVariation";
            if ( $http->hasPostVariable( $dataKey ) )
            {
                $variationAllArray = $http->postVariable( $dataKey );
                $objectID = $contentObjectAttribute->attribute( 'contentobject_id' );
                $contentClassAttributeID = $contentObjectAttribute->attribute( 'contentclassattribute_id' );

                if ( isset( $variationAllArray[$id] ) )
                {
                    $data = xrowProductData::fetchObjectList( xrowProductData::definition(),
                                                              null,
                                                              array( 'object_id' => $objectID,
                                                                     'contentclassattribute_id' => $contentClassAttributeID,
                                                                     'version' => $version ),
                                                              array( 'placement' => 'asc',
                                                                     'language_code' => 'asc' ) );
                    $delIDArray = array();
                    $existingAllVariationArray = array();
                    $existingCurrentVariationArray = array();

                    if ( count( $data ) > 0 )
                    {
                        foreach( $data as $item )
                        {
                            $currPlacement = $item->attribute( 'placement' );
                            $varLang = $item->attribute( 'language_code' );
                            $existingAllVariationArray[$currPlacement][$varLang] = $item;

                            if ( $varLang == $languageCode )
                            {
                                $tempID = $item->attribute( 'id' );
                                $delIDArray[$tempID] = $currPlacement;
                                $existingCurrentVariationArray[$tempID] = $item;
                            }
                        }
                        $translationArray = array();
                        foreach ( $existingAllVariationArray as $currPlacement => $item )
                        {
                            if ( isset( $item[$languageCode] ) )
                            {
                                $currID = $item[$languageCode]->attribute( 'id' );
                                foreach ( $item as $currLanguageCode => $translation )
                                {
                                    if ( $currLanguageCode == $languageCode )
                                    {
                                        continue;
                                    }
                                    $translationArray[$currID][$currLanguageCode] = $translation;
                                }
                            }
                        }
                    }
                    $variationArray =& $variationAllArray[$id];
                    # delete all variations which are not used anymore
                    if ( count( $variationArray ) > 0 )
                    {
                        foreach( $variationArray as $line => $variation )
                        {
                            if ( isset( $variation['id'] ) )
                            {
                                if ( isset( $delIDArray[$variation['id']] ) )
                                {
                                    unset( $delIDArray[$variation['id']] );
                                }
                                else
                                {
                                    unset( $variationArray[$line]['id'] );
                                }
                            }
                        }
                    }
                    if ( count( $delIDArray ) > 0 )
                    {
                        foreach( $delIDArray as $delID => $delPlacement )
                        {
                            if ( isset( $existingAllVariationArray[$delPlacement] ) )
                            {
                                foreach ( $existingAllVariationArray[$delPlacement] as $delVariation )
                                {
                                    $delVariation->remove();
                                }
                                unset( $existingAllVariationArray[$delPlacement] );
                                unset( $existingCurrentVariationArray[$delID] );
                            }
                        }
                    }
                    # fetch input
                    if ( count( $variationArray ) > 0 )
                    {
                        $placement = 0;
                        foreach( $variationArray as $line => $variation )
                        {
                            # is there an existing entry?
                            $currentData = false;
                            if ( isset( $variation['id'] ) )
                            {
                                if ( isset( $existingCurrentVariationArray[$variation['id']] ) )
                                {
                                    $currentData = $existingCurrentVariationArray[$variation['id']];
                                }
                            }
                            if ( !$currentData )
                            {
                                $row['attribute_id'] = $id;
                                $row['language_code'] = $languageCode;
                                $row['version'] = $version;
                                $row['object_id'] = $objectID;
                                $row['template_id'] = $templateID;
                                $row['contentclassattribute_id'] = $contentClassAttributeID;
                                $row['placement'] = $placement;

                                $currentData = new xrowProductData( $row );
                            }

                            $currentData->setAttribute( 'placement', $placement );

                            foreach( $template->AttributeList as $attribute )
                            {
                                $dataType = $attribute['attribute']->dataType();
                                $column = $attribute['attribute']->Identifier;
                                if ( $dataType )
                                {
                                    $dataType->fetchVariationInput( $variationArray,
                                                                    $line,
                                                                    $column,
                                                                    $currentData,
                                                                    $contentObjectAttribute,
                                                                    $attribute['attribute'],
                                                                    $http );

                                }
                            }

                            // update Content
                            foreach ( $template->AttributeList as $key => $item )
                            {
                                if ( is_object( $item['attribute'] ) )
                                {
                                    $identifier = $item['attribute']->Identifier;
                                    $dataType = $item['attribute']->dataType();
                                    if ( $dataType )
                                    {
                                        $content['data'][$placement][$identifier]['template_item'] = $item;
                                        $content['data'][$placement][$identifier]['raw'] = $currentData->attribute( $identifier );
                                        $content['data'][$placement][$identifier]['content'] = $dataType->variationContent( $currentData,
                                                                                                                            $identifier );
                                    }
                                }
                            }
                            self::fillVariationData( $currentData, $content['data'][$placement] );

                            $placement++;
                        }
                    }
                }
            }
            $cObj->Content = $content;
            $contentObjectAttribute->setContent( $cObj );
            $GLOBALS['xrowProductVariation'][$id][$version] = $cObj;

            return true;
        }

        return false;
    }

    function storeObjectAttribute( $attribute )
    {
        $id = $attribute->attribute( 'id' );
        $cObj = $attribute->content();
        $content = $cObj->attribute( 'content' );

        if ( count( $content['data'] ) > 0 )
        {
            $placement = 0;
            $template =& $content['template'];

            foreach ( $content['data'] as $newPlacement => $variation )
            {
                if ( isset( $variation['obj'] ) )
                {
                    $currentData =& $variation['obj'];

                    $newLine = false;
                    if ( !$currentData->attribute( 'id' ) )
                       $newLine = true;
                }
                else
                    continue;

                if ( $newLine )
                {
                    # getting no id here...
                    #$currentData->setAttribute( 'placement', $placement );
                    #$currentData->store();
                    #eZDebug::writeDebug( $currentData->attribute( 'id' ), 'id' );
                }

                // update translations...
                $langArray = xrowProductData::fetchLanguageArray( $attribute );
                if ( count( $langArray ) > 0 )
                {
                    if ( $newLine )
                    {
                        // clone the current entry for the other translations
                        foreach ( $langArray as $langItem )
                        {
                            $currentLang = $langItem['language_code'];
                            $attrID = $langItem['id'];
                            $langVariation = $currentData->cloneVariation( $currentLang, $attrID );
                            $langVariation->setAttribute( 'placement', $placement );
                            $langVariation->store();
                        }
                    }
                    else
                    {
                        // update existing translations of the variation
                        $dataID = $currentData->attribute( 'id' );

                        // fetch translations of current variation
                        $objectID = $contentObjectAttribute->attribute( 'contentobject_id' );
                        $contentClassAtributeID = $contentObjectAttribute->attribute( 'contentclassattribute_id' );
                        $translationArrayResult = xrowProductData::fetchObjectList( xrowProductData::definition(),
                                                                                    null,
                                                                                    array( 'attribute_id' => array( '!=', $dataID ),
                                                                                           'placement' => $currentData->attribute( 'placement' ),
                                                                                           'object_id' => $objectID,
                                                                                           'contentclassattribute_id' => $contentClassAtributeID ) );
                        $translationArray = array();
                        if ( count( $translationArrayResult ) > 0 )
                        {
                            foreach( $translationArrayResult as $item )
                            {
                                $translationArray[$item->attribute( 'language_locale' )] = $item;
                            }
                        }

                        foreach ( $langArray as $currentLang )
                        {
                            if ( !isset( $translationArray[$currentLang] ) )
                            {
                                $translationArray[$currentLang['language_code']] = $currentData->cloneVariation( $currentLang['language_code'],
                                                                                                                 $currentLang['id'] );
                            }
                            else
                            {
                                // update non-translatable attributes
                                $translationArray[$currentLang['language_code']]->setAttribute( 'placement', $placement );
                                foreach( $template->AttributeList as $attribute )
                                {
                                    $dataType = $attribute['attribute']->dataType();
                                    $column = $attribute['attribute']->Identifier;
                                    if ( $dataType )
                                    {
                                        $dataType->updateVariationInput( $currentData,
                                                                         $translationArray[$currentLang['language_code']],
                                                                         $attribute,
                                                                         $column );

                                    }
                                }
                            }
                            $translationArray[$currentLang['language_code']]->setAttribute( 'placement', $placement );
                            $translationArray[$currentLang['language_code']]->store();
                        }
                    }
                }

                # save a 2nd time
                $currentData->setAttribute( 'placement', $placement );
                $currentData->store();
                $placement++;
            }
        }
        if ( isset( $GLOBALS['xrowProductVariation'][$id] ) )
            unset( $GLOBALS['xrowProductVariation'][$id] );

        $attribute->setContent( null );
    }
/*
    function onPublish( $contentObjectAttribute, $contentObject, $publishedNodes )
    {

    }
*/

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        $id = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObjectAttribute->attribute( 'version' );
        $languageCode = $contentObjectAttribute->attribute( 'language_code' );

        if ( !isset( $GLOBALS['xrowProductVariation'][$id][$version] ) )
        {
            $GLOBALS['xrowProductVariation'][$id][$version] = array();
            $content['template_list'] = xrowProductTemplate::fetchList( array( 'active' => 1 ), true, false, false, array( 'name' => 'asc' ) );
            $content['template_id'] = $contentObjectAttribute->attribute( 'data_int' );
            $content['template'] = false;
            $content['data'] = array();
            $sortBy = null;
            if ( $content['template_id'] > 0 )
            {
                $columndef = array( 'column_name_array' => array(),
                                    'column_desc_array' => array() );
                $columnTemp = $contentObjectAttribute->attribute( 'data_text' );
                if ( strlen( $columnTemp ) > 0 )
                {
                    $columndef = @unserialize( $columnTemp );
                }
                $content['column_name_array'] = $columndef['column_name_array'];
                $content['column_desc_array'] = $columndef['column_desc_array'];

                $content['template'] = xrowProductTemplate::fetch( $content['template_id'] );
                $template =& $content['template'];
                if ( $template )
                {
                    $template->updateAttributes( $languageCode );
                    $sortBy = $content['template']->sortBy();
                    if ( $sortBy )
                    {
                        $sortBy = array( $sortBy['attribute'] => $sortBy['method'] );
                    }
                    else
                    {
                        $sortBy = array( 'placement' => 'asc' );
                    }

                    $data = xrowProductData::fetchList( array( 'attribute_id' => $id,
                                                               'version' => $version,
                                                               'language_code' => $languageCode ),
                                                        true,
                                                        false,
                                                        false,
                                                        $sortBy );

                    foreach( $data as $lineKey => $currentData )
                    {
                        foreach ( $template->AttributeList as $key => $item )
                        {
                            if ( is_object( $item['attribute'] ) )
                            {
                                $identifier = $item['attribute']->Identifier;
                                $dataType = $item['attribute']->dataType();
                                if ( $dataType )
                                {
                                    $content['data'][$lineKey][$identifier]['template_item'] = $item;
                                    $content['data'][$lineKey][$identifier]['raw'] = $currentData->attribute( $identifier );
                                    $content['data'][$lineKey][$identifier]['content'] = $dataType->variationContent( $currentData, $identifier );
                                }
                            }
                        }
                        self::fillVariationData( $currentData, $content['data'][$lineKey] );
                    }
                }
            }
            if ( !isset( $GLOBALS['xrowProductVariationError'][$id][$version] ) )
                $GLOBALS['xrowProductVariationError'][$id][$version] = array();
            $content['error'] =& $GLOBALS['xrowProductVariationError'][$id][$version];

            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            $GLOBALS['xrowProductVariation'][$id][$version] = new xrowProductVariationPrice( $contentObjectAttribute, $classAttribute, $content );
        }
        return $GLOBALS['xrowProductVariation'][$id][$version];
    }

    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $params = array( 'attribute_id' => $originalContentObjectAttribute->attribute( 'id' ),
                             'version' => $originalContentObjectAttribute->attribute( 'version' ),
                             'language_code' => $originalContentObjectAttribute->attribute( 'language_code' ) );

            $data = xrowProductData::fetchList( $params,
                                                true,
                                                false,
                                                false,
                                                array( 'placement' => 'asc' ) );

            // prevent copying of date
            $ini = eZINI::instance( 'xrowproduct.ini' );
            if ( ( $ini->hasVariable( 'XrowProductSettings', 'AllowDataCopying' ) 
                   && $ini->variable( 'XrowProductSettings', 'AllowDataCopying' ) == 'enabled' ) 
                  || $contentObjectAttribute->attribute( 'contentobject_id' ) == $originalContentObjectAttribute->attribute( 'contentobject_id' )
            )
            {
                foreach( $data as $currentData )
                {
                    $newData = $currentData->cloneVariation( $contentObjectAttribute->attribute( 'language_code' ),
                                                             $contentObjectAttribute->attribute( 'id' ),
                                                             $contentObjectAttribute->attribute( 'version' ) );
                    $newData->store();
                }
            }
        }
    }

    function fillVariationData( xrowProductData $variation, &$content )
    {
        $content['obj'] = $variation;
        $content['id'] = $variation->attribute( 'id' );
        $content['placement'] = $variation->attribute( 'placement' );
        $content['version'] = $variation->attribute( 'version' );
        $content['template_id'] = $variation->attribute( 'template_id' );
        $content['object_id'] = $variation->attribute( 'object_id' );
        $content['attribute_id'] = $variation->attribute( 'attribute_id' );
        $content['language_code'] = $variation->attribute( 'language_code' );
        $content['contentclassattribute_id'] = $variation->attribute( 'contentclassattribute_id' );
    }

    /*!
     \return \c true if the datatype finds any content in the attribute \a $contentObjectAttribute.
    */
    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        $cObj = self::objectAttributeContent( $contentObjectAttribute );
        $content = $cObj->attribute( 'content' );
        if ( count( $content['data'] ) > 0 )
            return true;
        else
            return false;
    }

    /*!
     Executes a custom action for an object attribute which was defined on the web page.
     \note Default implementation does nothing.
    */
    function customObjectAttributeHTTPAction( $http, $action, $contentObjectAttribute, $parameters )
    {
        $id = $contentObjectAttribute->attribute( 'id' );
        $version = $contentObjectAttribute->attribute( 'version' );
        #$languageCode = $objectAttribute->attribute( 'language_code' );

        switch( $action )
        {
            case "set-template":
            {
                $templateID = (int) $http->postVariable( "XrowProductTemplate_templateid_" . $id );
                $oldTemplateID = (int) $contentObjectAttribute->attribute( 'data_int' );

                if ( $templateID > 0 AND $templateID != $oldTemplateID )
                {
                    $this->updateTemplateID( $templateID, $contentObjectAttribute, $version );
                    if ( isset( $GLOBALS['xrowProductVariation'][$id] ) )
                        unset( $GLOBALS['xrowProductVariation'][$id] );
                }

            }break;
        }
    }

    /**
     * Update the template ID for all language versions of one content attribute
     *
     * @param integer $templateID
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param integer $version
     */
    function updateTemplateID( $templateID,
                               eZContentObjectAttribute &$contentObjectAttribute,
                               $version = null )
    {
        $attributeID = $contentObjectAttribute->attribute( 'id' );
        $classAttributeID = $contentObjectAttribute->attribute( 'contentclassattribute_id' );
        $objectID = $contentObjectAttribute->attribute( 'contentobject_id' );
        $contentObjectAttribute->setAttribute( 'data_int', $templateID );
        $contentObjectAttribute->store();

        $db = eZDB::instance();
        $db->begin();
        $sql = "UPDATE
                    ezcontentobject_attribute
                SET
                    data_int = '$templateID'
                WHERE
                    id != $attributeID
                    AND contentobject_id = '$objectID'
                    AND contentclassattribute_id = '$classAttributeID'
                    ";
        if ( $version !== null )
        {
            $sql .= "AND version = '$version'";
        }
        $db->query( $sql );

        $sql = "UPDATE
                    xrowproduct_data
                SET
                    template_id = '$templateID'
                WHERE
                    object_id = '$objectID'
                    AND contentclassattribute_id = '$classAttributeID'
                    ";
        if ( $version !== null )
        {
            $sql .= " AND version = '$version'";
        }
        $db->query( $sql );

        $db->commit();
    }


    /*!
     Clean up stored object attribute
     \note Default implementation does nothing.
    */
    function deleteStoredObjectAttribute( $objectAttribute, $version = null )
    {
        # delete all product variations of the current attribute version
        # need to check all attributes if they have content
        # because the template could be changed

        $contentClassAttributeID = $objectAttribute->attribute( 'contentclassattribute_id' );
        $objectID = $objectAttribute->attribute( 'contentobject_id' );

        $params['contentclassattribute_id'] = $contentClassAttributeID;
        $params['object_id'] = $objectID;
        if ( $version !== null )
            $params['version'] = $version;

        $variationList = xrowProductData::fetchList( $params, true );
        if ( count( $variationList ) > 0 )
        {
            foreach( $variationList as $variation )
            {
                $variation->remove();
            }
        }
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
       $result = array();
        if ( $contentObjectAttribute->hasContent() )
        {
            $cObj = $contentObjectAttribute->content();
            $content = $cObj->attribute( 'content' );
            foreach ( $content['data'] as $data )
            {
                if ( $data['obj'] )
                {
                    $result = array_merge( $result, $data['obj']->metaData() );
                }
            }
        }
        $final = array();
        foreach ( $result as $item )
        {
            if ( $item != '' )
            {
                $final[] = $item;
            }
        }
        # array is not supported, needs to be a string
        return implode( " ", $final );
    }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }

    /*!
     \reimp
    */
    function isInformationCollector()
    {
        return false;
    }

    /*!
     Fetches the product option information for option with ID \a $optionID and returns this information.
     This will be called from the basket when a new product with an option is added, it is then up to the
     specific datatype to return proper data. It will also be used to recalcuate prices.

     \param $objectAttribute The attribute that the datatype controls.
     \param $optionID The ID of the option which information should be returned from.
     \param $productItem The product item object which contains the option, is available for reading only.
     \return An array structure which contains:
             - id - The unique ID of the selected option, this must be unique in the attribute and will later on
                    be used to recalculate prices.
             - name - The name of the option list
             - value - The display string of the selected option
             - additional_price - A value which is added to the total product price, set to 0 or \c false if no price is used.
             If the option could not be found it should return \c false, if not supported it should return \c null.
     \sa handleProductOption
    */
    function productOptionInformation( $objectAttribute, $optionID, $productItem, $amount = 1 )
    {
        $result = array();
        $price = 0.0;
        $name = '';
        $value = '';
        $variation = xrowProductData::fetch( $optionID );
        if ( $variation )
        {
            $template = $variation->fetchTemplate();
            $priceIdentifier = $template->findPriceAttributeIdentifier();
            if ( $priceIdentifier != '' )
            {
                $priceID = $variation->attribute( $priceIdentifier );
                $amount = $productItem->attribute( 'item_count' );
                #eZDebug::writeDebug( $amount, 'amount' );
                $price = xrowProductPrice::fetchPriceByAmount( $priceID, $amount );
            }
            $name = $variation->getVariationName();
        }
        $result['value'] = $value;
        $result['name'] = $name;
        $result['additional_price'] = $price;
        $result['id'] = (int) $optionID;

        return $result;
    }

}
eZDataType::register( xrowProductVariationType::DATA_TYPE_STRING, "xrowProductVariationType" );
