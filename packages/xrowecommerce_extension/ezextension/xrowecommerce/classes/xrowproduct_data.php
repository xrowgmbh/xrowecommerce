<?php

/**
 * class xrowProductData
 * Handles the product variation data
 */
class xrowProductData extends eZPersistentObject
{

    function xrowProductData( $row )
    {
        $this->eZPersistentObject( $row );
    }

    public static function refreshDefCache()
    {
        if ( isset( $GLOBALS['xrowproductdata_def'] ) )
        {
            unset( $GLOBALS['xrowproductdata_def'] );
        }
        self::createDefinition( true );
    }

    public static function createDefinition( $refresh = false )
    {
        $cacheDir = eZSys::cacheDirectory() . '/xrowproductdata';
        if ( ! is_dir( $cacheDir ) )
        {
            mkdir( $cacheDir, 0777, true );
        }
        ezcCacheManager::createCache( 'xrowproductdatadef', $cacheDir, 'ezcCacheStorageFileArray' );
        $cacheKey = 'xrowproductdatadef';
        $cache = ezcCacheManager::getCache( $cacheKey );

        if ( $refresh || ( $GLOBALS['xrowproductdata_def'] = $cache->restore( $cacheKey ) ) === false )
        {
            $db = eZDB::instance();
            $sql = "SHOW COLUMNS FROM xrowproduct_data";
            $columnArray = $db->arrayQuery( $sql );

            $def = array();

            foreach ( $columnArray as $item )
            {
                $key = $item['Field'];

                $column = array();
                $column['name'] = $key;

                if ( strtolower( substr( $item['Type'], 0, 7 ) ) == 'varchar' or preg_match( "/date/", $item['Type'], $matches ) )
                {
                    $column['datatype'] = 'string';
                    if ( preg_match( "/varchar\((\d{0,3})\)/i", $item['Type'], $matches ) )
                    {
                        $column['max_length'] = $matches[1];
                    }
                }
                else
                {
                    if ( preg_match( "/int/i", $item['Type'], $matches ) )
                    {
                        $column['datatype'] = 'integer';
                    }
                    else
                    {
                        if ( preg_match( "/float/i", $item['Type'], $matches ) )
                        {
                            $column['datatype'] = 'float';
                        }
                        else
                        {
                            if ( preg_match( "/text/", $item['Type'], $matches ) )
                            {
                                $column['datatype'] = 'text';
                            }
                            else
                            {
                                eZDebug::writeError( 'Unknown column type: ' . $item['Type'], 'xrowProductData::definition()' );
                            }
                        }
                    }
                }

                $column['required'] = false;
                $column['default'] = null;
                $def['fields'][$key] = $column;
            }

            $GLOBALS['xrowproductdata_def'] = array_merge( $def, array(
                'function_attributes' => array(
                    'template' => 'fetchTemplate' ,
                    'contentobject_attribute' => 'contentObjectAttribute' ,
                    'variation_name' => 'getVariationName'
                ) ,
                'keys' => array(
                    'id'
                ) ,
                'sort' => array(
                    'id' => 'asc'
                ) ,
                'class_name' => 'xrowProductData' ,
                'name' => 'xrowproduct_data'
            ) );

            $cache->store( $cacheKey, $GLOBALS['xrowproductdata_def'] );
        }
    }

    static function definition()
    {
        if ( ! isset( $GLOBALS['xrowproductdata_def'] ) )
        {
            self::createDefinition();
        }

        return $GLOBALS['xrowproductdata_def'];
    }

    function fetchTemplate()
    {
        $template = xrowProductTemplate::fetch( $this->attribute( 'template_id' ) );
        $languageCode = $this->attribute( 'language_code' );
        if ( $template and strlen( $languageCode ) > 0 )
            $template->updateAttributes( $languageCode );
        return $template;
    }

    /**
     * Fetchtes the template by object id
     * @param int $objectID
     * @return mixed
     */
    public static function fetchTemplateByObjectID( $objectID )
    {
        $db = eZDB::instance();
        $objectID = $db->escapeString( $objectID );
        $sql = "SELECT DISTINCT template_id FROM xrowproduct_data WHERE object_id = '$objectID'";
        $res = $db->arrayQuery( $sql );
        $template = null;
        if ( $res && isset( $res[0] ) )
        {
            $templateID = $res[0]['template_id'];
            $template = xrowProductTemplate::fetch( $templateID );
        }
        return $template;
    }

    /**
     * Translates a variation
     *
     * @param string $languageCode
     * @param integer $newAttributeID
     * @return xrowProductData
     */
    function cloneVariation( $languageCode, $newAttributeID, $version = false )
    {
        $newVariation = clone $this;
        $newVariation->setAttribute( 'id', null );
        $newVariation->setAttribute( 'attribute_id', $newAttributeID );
        $newVariation->setAttribute( 'language_code', $languageCode );
        if ( $version !== false )
        {
            $newVariation->setAttribute( 'version', $version );
        }

        $template = $this->attribute( 'template' );
        if ( $template )
        {
            $template->updateAttributes( $languageCode );
            foreach ( $template->AttributeList as $attribute )
            {
                $dataType = $attribute['attribute']->dataType();
                $field = $attribute['attribute']->Identifier;
                if ( $dataType )
                {
                    $newVariation->setAttribute( $field, 
                                                 $dataType->cloneVariation( $this->attribute( $field ), 
                                                                            $this, 
                                                                            $attribute['attribute'], 
                                                                            $template ) );
                }
            }
        }
        return $newVariation;
    }

    static function fetch( $id, $asObject = true )
    {
        $num = 0;
        if ( $asObject )
            $num = 1;
        if ( ! isset( $GLOBALS['xrowproductdatavariation'][$id][$num] ) )
        {
            $GLOBALS['xrowproductdatavariation'][$id][$num] = eZPersistentObject::fetchObject( self::definition(), null, array(
                "id" => $id
            ), $asObject );
        }
        return $GLOBALS['xrowproductdatavariation'][$id][$num];
    }

    function store( $fieldFilters = null )
    {
        $id = $this->attribute( 'id' );
        if ( $id and isset( $GLOBALS['xrowproductdatavariation'][$id] ) )
            unset( $GLOBALS['xrowproductdatavariation'][$id] );

        parent::storeObject( $this, $fieldFilters );

        $template = $this->attribute( 'template' );
        if ( $template )
        {
            foreach ( $template->AttributeList as $attribute )
            {
                $dataType = $attribute['attribute']->dataType();
                $field = $attribute['attribute']->Identifier;
                if ( $dataType )
                {
                    $dataType->storeVariation( $this, $field, $attribute );
                }
            }
        }
    }

    function metaData()
    {
        $result = array();
        $template = $this->attribute( 'template' );
        if ( $template )
        {
            foreach ( $template->AttributeList as $attribute )
            {
                $dataType = $attribute['attribute']->dataType();
                $field = $attribute['attribute']->Identifier;
                if ( $dataType and isset( $attribute['search'] ) and $attribute['search'] == true )
                {
                    $temp = $dataType->metaData( $this, $field );
                    if ( is_array( $temp ) )
                    {
                        $result = array_merge( $result, $temp );
                    }
                    else
                    {
                       $result[] = $temp;
                    }
                }
            }
        }
        return $result;
    }

    /**
     * Returns the name of a variation
     *
     * @return string name
     */
    public function getVariationName()
    {
        $result = "";
        $template = $this->attribute( 'template' );
        if ( $template )
        {
            $attribute = $this->contentObjectAttribute();
            $cObj = $attribute->content();
            if ( $cObj )
            {
                $content = $cObj->attribute( 'content' );
                $columnNameArray = $content['column_name_array'];
            }
            $xrowIni = eZINI::instance( 'xrowproduct.ini' );
            $delimiter = $xrowIni->variable( 'XrowProductDataTypes', 'NameDelimiter' );
            foreach ( $template->AttributeList as $attribute )
            {
                $dataType = $attribute['attribute']->dataType();
                $id = $attribute['attribute']->Identifier;
                $field = $id;
                if ( $dataType and isset( $attribute['search'] ) and $attribute['search'] === true and isset( $attribute['frontend'] ) and $attribute['frontend'] === true )
                {
                    $colName = '';
                    if ( isset( $columnNameArray[$id] ) )
                        $colName = $columnNameArray[$id];
                    else
                        $colName = $attribute['column_name'];

                    $value = $dataType->metaName( $this, $field );
                    if ( $value !== false )
                    {
                        $result .= " $colName: $value" . $delimiter;
                    }
                }
            }
            $result = trim( $result, $delimiter );
        }
        return $result;
    }

    static function fetchList( $conditions = null, $asObjects = true, $offset = false, $limit = false, $sortBy = array() )
    {
        $limitation = null;
        if ( $offset !== false or $limit !== false )
            $limitation = array(
                'offset' => $offset ,
                'length' => $limit
            );

        $result = eZPersistentObject::fetchObjectList( self::definition(), null, $conditions, $sortBy, $limitation, $asObjects );
        return $result;
    }

    static function fetchListCount( $conditions, $type = false )
    {
        $custom = array(
            array(
                'operation' => 'count( id )' ,
                'name' => 'count'
            )
        );

        $rows = eZPersistentObject::fetchObjectList( self::definition(), array(), $conditions, null, null, false, false, $custom );
        return $rows[0]['count'];
    }

    function remove( $conditions = null, $extraConditions = null )
    {
        $id = $this->attribute( 'id' );
        $db = eZDB::instance();
        $db->begin();
        $attributeList = xrowProductAttribute::fetchList( array(), true, false, false );

        if ( count( $attributeList ) > 0 )
        {
            foreach ( $attributeList as $attribute )
            {
                $dataType = $attribute->dataType();
                if ( $dataType )
                {
                    $dataType->deleteVariation( $this->attribute( $attribute->Identifier ) );
                }
            }
        }

        if ( isset( $GLOBALS['xrowproductdatavariation'][$id] ) )
            unset( $GLOBALS['xrowproductdatavariation'][$id] );

        eZPersistentObject::removeObject( $this->definition(), array(
            'id' => $id
        ), null );

        $db->commit();
    }

    /**
     * Returns the current translations of the variation
     *
     * @param integer $attributeID
     * @param integer $version
     * @param string $languageCode
     * @return mixed language list
     */
    static function fetchLanguageArray( eZContentObjectAttribute $attribute )
    {
        $classAttributeID = $attribute->attribute( 'contentclassattribute_id' );
        $objectID = $attribute->attribute( 'contentobject_id' );
        $languageCode = $attribute->attribute( 'language_code' );
        $version = $attribute->attribute( 'version' );

        $params = array(
            'contentobject_id' => $objectID ,
            'version' => $version ,
            'contentclassattribute_id' => $classAttributeID ,
            'language_code' => array(
                '!=' ,
                $languageCode
            )
        );

        return self::fetchObjectList( eZContentObjectAttribute::definition(), array(
            'id' ,
            'language_code'
        ), $params );
    }

    /**
     * Fetches the belonging content object attribute
     *
     * @return mixed
     */
    public function contentObjectAttribute()
    {
        $result = eZContentObjectAttribute::fetch( $this->attribute( 'attribute_id' ), $this->attribute( 'version' ) );
        return $result;
    }

    /**
     * return true if the given attribute has sliding prices
     *
     * @param int $attribute_id
     * @param int $version
     * @param string $language
     * @return bool
     */
    public static function hasSlidingPrice( $attribute_id, $version, $language )
    {
        $xINI = eZINI::instance( 'xrowproduct.ini' );
        $priceField = $xINI->variable( 'PriceSettings', 'PriceIdentifier' );

        $db = eZDB::instance();
        $sql = "SELECT
                    COUNT(*) counter
                FROM
                    xrowproduct_data a,
                    xrowproduct_price b
                WHERE
                    a.attribute_id = '$attribute_id'
                    AND a.version = '$version'
                    AND a.language_code = '$language'
                    AND a.$priceField = b.price_id
                    AND b.amount > 1";

        $result = $db->arrayQuery( $sql );

        if ( $result[0]['counter'] > 0 )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    /**
     * Fetches the first variation data where the given sku is found
     *
     * @param string $sku
     * @param bool $asObject
     * @return mixed
     */
    public static function fetchDataBySKU( $sku, $asObject = true )
    {
        $xINI = eZINI::instance( 'xrowproduct.ini' );
        $skuField = $xINI->variable( 'ExportSettings', 'SKUIdentifier' );

        $defaultLanguage = eZContentLanguage::topPriorityLanguage();
        $languageCode = $defaultLanguage->attribute( 'locale' );

        $db = eZDB::instance();
        $skuSql = $db->escapeString( $sku );
        $sql = "SELECT
                    a.*
                FROM
                    xrowproduct_data a,
                    ezcontentobject_tree b
                WHERE
                    a.$skuField = '$skuSql'
                    AND a.object_id = b.contentobject_id
                    AND a.version = b.contentobject_version
                    AND b.contentobject_is_published = 1
                    AND b.is_hidden = 0
                    AND b.is_invisible = 0";

        $result = $db->arrayQuery( $sql );

        if ( count( $result ) > 0 )
        {
            $languageArray = array();
            foreach ( $result as $item )
            {
                $language = $item['language_code'];
                $languageArray[$language] = $item;
            }

            if ( isset( $languageArray[$languageCode] ) )
            {
                if ( $asObject )
                    return new xrowProductData( $languageArray[$languageCode] );
                else
                    return $languageArray[$languageCode];
            }
            else
            {
                $prioLangArray = eZContentLanguage::prioritizedLanguageCodes();
                foreach ( $prioLangArray as $currentLang )
                {
                    if ( $languageCode == $currentLang )
                        continue;
                    if ( isset( $languageArray[$currentLang] ) )
                    {
                        if ( $asObject )
                            return new xrowProductData( $languageArray[$currentLang] );
                        else
                            return $languageArray[$currentLang];
                    }
                }
            }
        }
    }
}

?>
