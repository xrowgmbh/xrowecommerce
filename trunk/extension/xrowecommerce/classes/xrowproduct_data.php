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

    static function definition()
    {
        if ( !isset( $GLOBALS['xrowproductdata_def'] ) )
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

                if ( strtolower( substr( $item['Type'], 0, 7 ) ) == 'varchar' )
                {
                    $column['datatype'] = 'string';
                    if ( preg_match( "/varchar\((\d{0,3})\)/i", $item['Type'], $matches ) )
                        $column['max_length'] = $matches[1];
                }
                else if ( preg_match( "/int/i", $item['Type'], $matches ) )
                    $column['datatype'] = 'integer';
                else if ( preg_match( "/float/i", $item['Type'], $matches ) )
                    $column['datatype'] = 'float';
                else if ( preg_match( "/text/", $item['Type'], $matches ) )
                    $column['datatype'] = 'text';
                else
                    eZDebug::writeError( 'Unknown column type: ' . $item['Type'], 'xrowProductData::definition()' );

                $column['required'] = false;
                $column['default'] = null;
                $key = $key;
                $def['fields'][$key] = $column;
            }

            $GLOBALS['xrowproductdata_def'] = array_merge( $def,
                                                            array(
                                                                  'function_attributes' => array( 'template' => 'fetchTemplate',
                                                                                                  'contentobject_attribute' => 'contentObjectAttribute' ),
                                                                  'keys' => array( 'id' ),
                                                                  'sort' => array( 'id' => 'asc' ),
                                                                  'class_name' => 'xrowProductData',
                                                                  'name' => 'xrowproduct_data' ) );
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
            $newVariation->setAttribute( 'version', $version );

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
                                                                            $attribute,
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
        if ( !isset( $GLOBALS['xrowproductdatavariation'][$id][$num] ) )
        {
            $GLOBALS['xrowproductdatavariation'][$id][$num] = eZPersistentObject::fetchObject( self::definition(),
                                                                                               null,
                                                                                               array( "id" => $id ),
                                                                                               $asObject );
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
    	$result = "";
    	$template = $this->attribute( 'template' );
        if ( $template )
        {
            foreach ( $template->AttributeList as $attribute )
            {
                $dataType = $attribute['attribute']->dataType();
                $field = $attribute['attribute']->Identifier;
                if ( $dataType and
                     isset( $attribute['search'] ) and
                     $attribute['search'] == true )
                {
                    $result .= " " . $dataType->metaData( $this, $field );
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
                if ( $dataType and
                     isset( $attribute['search'] ) and
                     $attribute['search'] === true and
                     isset( $attribute['frontend'] ) and
                     $attribute['frontend'] === true )
                {
                    $colName = '';
                    if ( isset( $columnNameArray[$id] ) )
                        $colName = $columnNameArray[$id];
                    else
                        $colName = $attribute['column_name'];

                    $value = $dataType->metaName( $this, $field );
                    if ( $value !== false )
                    {
                        $result .= " $colName: $value" .  $delimiter;
                    }
                }
            }
            $result = trim( $result, $delimiter );
        }
        return $result;
    }

    static function fetchList( $conditions = null,
                               $asObjects = true,
                               $offset = false,
                               $limit = false,
                               $sortBy = array() )
    {
        $limitation = null;
        if ( $offset !== false or $limit !== false )
            $limitation = array( 'offset' => $offset, 'length' => $limit );

        $result = eZPersistentObject::fetchObjectList( self::definition(),
                                                    null,
                                                    $conditions,
                                                    $sortBy,
                                                    $limitation,
                                                    $asObjects );
        return $result;
    }

    static function fetchListCount( $conditions, $type = false )
    {
        $custom = array( array( 'operation' => 'count( id )',
                                'name' => 'count' ) );

        $rows = eZPersistentObject::fetchObjectList( self::definition(),
                                                     array(),
                                                     $conditions,
                                                     null,
                                                     null,
                                                     false,
                                                     false,
                                                     $custom );
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
            foreach( $attributeList as $attribute )
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

        eZPersistentObject::removeObject( $this->definition(), array( 'id' => $id ), null );

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

    	$params = array( 'contentobject_id' => $objectID,
                         'version' => $version,
    	                 'contentclassattribute_id' => $classAttributeID,
                         'language_code' => array( '!=', $languageCode ) );

        return self::fetchObjectList( eZContentObjectAttribute::definition(), array( 'id', 'language_code' ), $params );
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
}

?>
