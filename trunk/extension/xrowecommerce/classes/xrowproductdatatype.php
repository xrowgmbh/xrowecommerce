<?php

class xrowProductDataType
{

    /*!
     Initializes the datatype with the string id \a $dataTypeString and
     the name \a $name.
    */
    function xrowProductDataType( $dataTypeString, $name, $desc, $properties = array() )
    {
        $this->DataTypeString = $dataTypeString;
        $this->Name = $name;
        $this->Description = $desc;
        
        $translationAllowed = true;
        if ( isset( $properties['translation_allowed'] ) )
            $translationAllowed = $properties['translation_allowed'];
        
        $required = false;
        if ( isset( $properties['required'] ) )
            $required = $properties['required'];
        
        $unique = false;
        if ( isset( $properties['unique'] ) )
            $unique = $properties['unique'];
        
        $this->Attributes = array();
        $this->Attributes["is_indexable"] = $this->isIndexable();
        $this->Attributes["properties"] = array( 
            "translation_allowed" => $translationAllowed 
        );
        $this->Attributes["name"] = $name;
        $this->Attributes["desc"] = $desc;
        $this->Attributes["required"] = $required;
        $this->Attributes["unique"] = $unique;
        $this->Attributes["data_type_string"] = $dataTypeString;
    }

    /*!
     \static
     Crates a datatype instance of the datatype string id \a $dataTypeString.
     \note It only creates one instance for each datatype.
    */
    static function create( $dataTypeString )
    {
        if ( ! isset( $GLOBALS['xrowProductDataTypes'][$dataTypeString] ) )
        {
            $types = self::loadAndRegisterAllTypes();
        }
        
        if ( isset( $GLOBALS['xrowProductDataTypes'][$dataTypeString] ) )
        {
            return $GLOBALS['xrowProductDataTypes'][$dataTypeString];
        }
        
        return null;
    }

    /*!
     \static
     \return a list of datatypes which has been registered.
     \note This will instantiate all datatypes.
    */
    static function registeredDataTypes()
    {
        if ( ! isset( $GLOBALS['xrowProductDataTypes'] ) )
        {
            $GLOBALS['xrowProductDataTypes'] = self::loadAndRegisterAllTypes();
        }
        return $GLOBALS['xrowProductDataTypes'];
    }

    static function allowedTypes()
    {
        $allowedTypes = & $GLOBALS["xrowProductDataTypeAllowedTypes"];
        if ( ! is_array( $allowedTypes ) )
        {
            $productINI = eZINI::instance( 'xrowproduct.ini' );
            $allowedTypes = $productINI->variable( 'XrowProductDataTypes', 'ProductDataTypeArray' );
            $types = self::loadAndRegisterAllTypes();
        }
        return $allowedTypes;
    }

    static function loadAndRegisterAllTypes()
    {
        $allowedTypes = self::allowedTypes();
        $types = & $GLOBALS['xrowProductDataTypes'];
        if ( ! is_array( $types ) )
        {
            foreach ( $allowedTypes as $dataTypeString => $className )
            {
                $types[$dataTypeString] = new $className( );
            }
        }
        return $types;
    }

    /*!
     \return the attributes for this datatype.
    */
    function attributes()
    {
        return array_keys( $this->Attributes );
    }

    /*!
     \return true if the attribute \a $attr exists in this object.
    */
    function hasAttribute( $attr )
    {
        return isset( $this->Attributes[$attr] );
    }

    /*!
     \return the data for the attribute \a $attr or null if it does not exist.
    */
    function attribute( $attr )
    {
        if ( isset( $this->Attributes[$attr] ) )
        {
            return $this->Attributes[$attr];
        }
        
        eZDebug::writeError( "Attribute '$attr' does not exist", 'xrowProductDataType::attribute()' );
        $attributeData = null;
        return $attributeData;
    }

    /*!
     Sets the default value.
    */
    function initializeVariation( $variation, $currentVersion, $originalVariation )
    {
    }

    /**
     * Initialize the attribute
     */
    function initializeAttribute( $attribute )
    {
        $db = eZDB::instance();
        
        if ( ! isset( $attribute->Data['column_array'] ) or count( $attribute->Data['column_array'] ) == 0 )
        {
            $identifier = $attribute->attribute( 'identifier' );
            
            $db->begin();
            $sql = "ALTER TABLE " . $this->Table;
            $first = true;
            foreach ( $this->ColumnArray as $item )
            {
                if ( $first )
                    $first = false;
                else
                    $sql .= ", ";
                $columnName = $identifier;
                if ( strlen( $item['name'] ) > 0 )
                    $columnName .= $item['name'];
                
                $data = $item;
                $data['identifier'] = $columnName;
                $attribute->Data['column_array'][] = $data;
                $sql .= " ADD COLUMN `$columnName` " . $item['sql_type'];
            }
            
            $db->query( $sql );
            $db->commit();
            $attribute->store();
        }
    }

    /**
     * Renames existing columns of product data table
     * @param object $attribute
     * @param string $oldIdentifier
     */
    
    function renameColumns( $attribute, $oldIdentifier )
    {
        $db = eZDB::instance();
        
        if ( strlen( $oldIdentifier ) == 0 )
            return;
        
        $identifier = $attribute->attribute( 'identifier' );
        if ( isset( $attribute->Data['column_array'] ) and count( $attribute->Data['column_array'] ) > 0 )
        {
            $identifier = $attribute->attribute( 'identifier' );
            
            $db->begin();
            $sql = "ALTER TABLE " . $this->Table;
            $first = true;
            foreach ( $this->ColumnArray as $key => $item )
            {
                if ( $first )
                    $first = false;
                else
                    $sql .= ", ";
                $newColumnName = $identifier;
                $oldColumnName = $oldIdentifier;
                if ( strlen( $item['name'] ) > 0 )
                {
                    $newColumnName .= $item['name'];
                    $oldColumnName .= $item['name'];
                }
                
                $data = $item;
                $data['identifier'] = $newColumnName;
                $attribute->Data['column_array'][$key] = $data;
                $sql .= " CHANGE COLUMN `$oldColumnName` `$newColumnName` " . $item['sql_type'];
            }
            
            $db->query( $sql );
            $db->commit();
            $attribute->store();
        }
    }

    /**
     * Validates the input of the variation
     *
     * @param array $variationArray
     * @param integer $line
     * @param string $column
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param array $attribute
     * @param eZHTTPTool $http
     * @param array $errorArray
     */
    function validateVariationInput( array $variationArray, $line, $column, eZContentObjectAttribute $contentObjectAttribute, $attribute, eZHTTPTool $http, array &$errorArray )
    {
    }

    /**
     * Fetches the content for the current datatype
     *
     * @param array $data contains the complete data of the http input
     * @param integer $line
     * @param string $column
     * @param xrowProductData $variation contains the current variation data
     * @param eZContentObjectAttribute $contentObjectAttribute
     * @param xrowProductAttribute $attribute
     * @param eZHTTPTool $http
     */
    function fetchVariationInput( array &$data, $line, $column, xrowProductData &$variation, eZContentObjectAttribute &$contentObjectAttribute, xrowProductAttribute &$productAttribute, eZHTTPTool $http )
    {
        if ( isset( $data[$line][$column] ) )
        {
            $variation->setAttribute( $column, $data[$line][$column] );
        }
    }

    /**
     * Updates the translations of the current variation
     *
     * @param xrowProductData $currentVariation
     * @param xrowProductData $translationVariation
     * @param unknown_type $attribute
     * @param unknown_type $column
     */
    function updateVariationInput( xrowProductData $currentVariation, xrowProductData &$translationVariation, $attribute, $column )
    {
        if ( ! isset( $attribute['translation'] ) or $attribute['translation'] === false )
        {
            $translationVariation->setAttribute( $column, $currentVariation->attribute( $column ) );
        }
    }

    /**
     * Doing datatype specific storing
     * Only needed for complex datatypes
     *
     * @param xrowProductData $variation
     * @param string $column the current column of the variation
     * @param array $attribute contains the template specific content
     */
    function storeVariation( xrowProductData $variation, $column, array $attribute )
    {
    }

    /**
     * Returns the data of the product variation item
     * Needs to be overridden by complex datatypes
     *
     * @param xrowProductData $variation
     * @param string $identifier
     * @return mixed
     */
    function variationContent( xrowProductData $variation, $identifier )
    {
        return $variation->attribute( $identifier );
    }

    /**
     * Returns true if the current datatype has content
     *
     * @param xrowProductData $variation
     * @param unknown_type $identifier
     * @return boolean
     */
    function hasVariationContent( xrowProductData $variation, $identifier )
    {
        $data = self::variationContent( $variation, $identifier );
        if ( $data === null or $data === false or $data === '' )
            return false;
        else
            return true;
    }

    /**
     * Validate the input of the attribute
     *
     * @param xrowProductAttribute $attribute
     * @param array $error
     */
    function validateAttributeInput( xrowProductAttribute &$attribute, array &$error, $languageCode )
    {
    }

    /**
     * Fetches the attribute input
     *
     * @param eZHTTPTool $http
     * @param xrowProductAttribute $attribute
     * @param array $error
     * @param string $languageCode
     */
    function fetchAttributeInput( eZHTTPTool $http, xrowProductAttribute &$attribute, array &$error, $languageCode )
    {
    }

    /**
     * Validates the input of the template and
     * returns true if the input was valid for this datatype.
     * @param xrowProductTemplate $template
     * @param xrowProductAttribute $attribute
     * @param array $error
     * @param string $languageCode
     * @return boolean
     */
    function validateTemplateInput( xrowProductTemplate &$template, xrowProductAttribute &$attribute, array &$error, $languageCode )
    {
        return true;
    }

    /**
     * Fetches the http post input for the datatype
     *
     * @param xrowProductTemplate $template
     * @param xrowProductAttribute $attribute
     * @param eZHTTPTool $http
     * @param string $languageCode
     */
    function fetchTemplateInput( xrowProductTemplate &$template, xrowProductAttribute &$attribute, eZHTTPTool $http, $languageCode )
    {
        $result = array();
        $id = $attribute->attribute( 'id' );
        $result['id'] = $id;
        $key = "XrowProductTemplate_" . $id . "_";
        
        $result = & $template->Data['attributes'][$id];
        
        $defaultKey = $key . 'default';
        if ( $http->hasPostVariable( $defaultKey ) )
            $result['default_value_array'][$languageCode] = trim( $http->postVariable( $defaultKey ) );
        
        $requiredKey = $key . 'required';
        if ( $http->hasPostVariable( $requiredKey ) )
            $result['required'] = true;
        else
            $result['required'] = false;
        
        $translationKey = $key . 'translation';
        if ( $http->hasPostVariable( $translationKey ) )
            $result['translation'] = true;
        else
            $result['translation'] = false;
        
        $frontendKey = $key . 'frontend';
        if ( $http->hasPostVariable( $frontendKey ) )
            $result['frontend'] = true;
        else
            $result['frontend'] = false;
        
        $searchKey = $key . 'search';
        if ( $http->hasPostVariable( $searchKey ) )
            $result['search'] = true;
        else
            $result['search'] = false;
        
        $nameKey = $key . 'column_name';
        if ( $http->hasPostVariable( $nameKey ) )
            $result['column_name_array'][$languageCode] = trim( $http->postVariable( $nameKey ) );
        
        $descKey = $key . 'column_desc';
        if ( $http->hasPostVariable( $descKey ) )
            $result['column_desc_array'][$languageCode] = trim( $http->postVariable( $descKey ) );
        
        if ( ! $result['translation'] )
        {
            $result['default_value'] = $result['default_value_array'][$languageCode];
            $result['default_value_array'] = array();
        }
        
        $uniqueSKUKey = $key . 'unique_sku';
        if ( $http->hasPostVariable( $uniqueSKUKey ) )
            $result['unique_sku'] = true;
        else
            $result['unique_sku'] = false;
        
        $selectKey = $key . 'select';
        if ( $http->hasPostVariable( $selectKey ) )
            $result['select'] = true;
        else
            $result['select'] = false;
    }

    /**
     * Deletes the content of the current datatype
     * Needed for complex datatypes
     *
     * @param mixed $data
     */
    function deleteVariation( $data )
    {
    }

    /**
     * Clones the value of the current datatype
     * Needed for complex datatypes
     *
     * @param mixed $value
     * @param xrowProductAttribute $attribute
     * @param xrowProductTemplate $template
     * @return mixed
     */
    function cloneVariation( $value, xrowProductData $variation, xrowProductAttribute $attribute, xrowProductTemplate $template )
    {
        return $value;
    }

    function templateHTTPAction( xrowProductTemplate &$template, xrowProductAttribute &$attribute, eZHTTPTool $http, $languageCode, &$module, array $error, $action )
    {
    }

    function hasAttributeContent( $attribute, $template = null )
    {
        return true;
    }

    function attributeContent( xrowProductAttribute $attribute, xrowProductTemplate $template = null )
    {
        return '';
    }

    function templateContent( $template, $attribute = null )
    {
    }

    function hasTemplateContent( $template, $attribute = null )
    {
        return true;
    }

    /**
     * Returns the metadata for the search engine if searching is allowed
     *
     * @param xrowProductData $variation
     * @param string $column
     * @return string
     */
    function metaData( xrowProductData $variation, $column )
    {
        return $variation->attribute( $column );
    }

    /**
     * Returns the content for the option name field
     *
     * @param xrowProductData $variation
     * @param string $column
     * @return string
     */
    public function metaName( xrowProductData $variation, $column )
    {
        return $variation->attribute( $column );
    }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }
    
    /// \privatesection
    /// The datatype string ID, used for uniquely identifying a datatype
    public $DataTypeString;
    /// The descriptive name of the datatype, usually used for displaying to the user
    public $Name;
    /// A short description of the product variation datatype
    public $Description;
    
    public $Attributes;
    
    public $ColumnArray;
    
    public $VariationContent = null;
    public $VariationHasContent = null;
    
    public $Table;
}

?>