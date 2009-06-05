<?php

/*!
 * class xrowProductAttribute
 * Stores definitions about the product variation attributes
 */
class xrowProductAttribute extends eZPersistentObject
{

    function xrowProductAttribute( $row = array() )
    {
        if ( is_array( $row ) and count( $row ) > 0 )
        {
            $this->eZPersistentObject( $row );
            $columnArray = array();
            if ( strlen( $this->SerializedData ) > 0 )
            {
                $this->Data = @unserialize( $this->SerializedData );
                if ( isset( $this->Data['column_array'] ) and count( $this->Data['column_array'] ) > 0 )
                {
                    $columnArray = $this->Data['column_array'];
                }
            }
            
            $this->dataType( $columnArray );
        }
    }

    static function definition()
    {
        return array( 
            'fields' => array( 
                'id' => array( 
                    'name' => 'ID' , 
                    'datatype' => 'integer' , 
                    'default' => 0 , 
                    'required' => true 
                ) , 
                'name' => array( 
                    'name' => 'Name' , 
                    'datatype' => 'string' , 
                    'default' => '' , 
                    'required' => false , 
                    'max_length' => 255 
                ) , 
                'identifier' => array( 
                    'name' => 'Identifier' , 
                    'datatype' => 'string' , 
                    'default' => '' , 
                    'required' => true , 
                    'max_length' => 255 
                ) , 
                'language_mask' => array( 
                    'name' => 'LanguageMask' , 
                    'datatype' => 'integer' , 
                    'default' => 0 , 
                    'required' => false 
                ) , 
                'initial_language_id' => array( 
                    'name' => "InitialLanguageID" , 
                    'datatype' => 'integer' , 
                    'default' => 0 , 
                    'required' => true , 
                    'foreign_class' => 'eZContentLanguage' , 
                    'foreign_attribute' => 'id' , 
                    'multiplicity' => '1..*' 
                ) , 
                'active' => array( 
                    'name' => 'Active' , 
                    'datatype' => 'integer' , 
                    'default' => 1 , 
                    'required' => true 
                ) , 
                'data_type' => array( 
                    'name' => 'DataTypeString' , 
                    'datatype' => 'string' , 
                    'default' => '' , 
                    'required' => true , 
                    'max_length' => 255 
                ) , 
                'created' => array( 
                    'name' => 'Created' , 
                    'datatype' => 'integer' , 
                    'default' => 0 , 
                    'required' => true 
                ) , 
                'user_id' => array( 
                    'name' => 'UserID' , 
                    'datatype' => 'integer' , 
                    'default' => 0 , 
                    'required' => true , 
                    'foreign_class' => 'eZUser' , 
                    'foreign_attribute' => 'contentobject_id' , 
                    'multiplicity' => '1..*' 
                ) , 
                'serialized_data' => array( 
                    'name' => 'SerializedData' , 
                    'datatype' => 'text' , 
                    'default' => '' , 
                    'required' => false 
                ) 
            ) , 
            'function_attributes' => array( 
                'user' => 'fetchUser' , 
                'name' => 'name' , 
                'desc' => 'description' , 
                'languages' => 'languages' , 
                'prioritized_languages' => 'prioritizedLanguages' , 
                'top_priority_language_locale' => 'topPriorityLanguageLocale' , 
                'data' => 'getData' , 
                'data_type_list' => 'dataTypeList' , 
                'required' => 'required' , 
                'translatable' => 'translatable' , 
                'unique' => 'unique' , 
                'data_type_obj' => 'dataType' , 
                'content' => 'content' , 
                'has_content' => 'hasContent' 
            ) , 
            'increment_key' => 'id' , 
            'keys' => array( 
                'id' 
            ) , 
            'class_name' => 'xrowProductAttribute' , 
            'name' => 'xrowproduct_attribute' 
        );
    }

    function store( $fieldFilters = null )
    {
        $this->SerializedData = '';
        if ( count( $this->Data ) > 0 )
            $this->SerializedData = serialize( $this->Data );
        
        eZPersistentObject::storeObject( $this, $fieldFilters );
        
        $id = $this->attribute( 'id' );
        
        if ( isset( $GLOBALS['xrowProductAttributeCache'][$id] ) )
            unset( $GLOBALS['xrowProductAttributeCache'][$id] );
    }

    static function fetch( $id, $asObject = true )
    {
        if ( $asObject )
            $key = 0;
        else
            $key = 1;
        if ( ! isset( $GLOBALS['xrowProductAttributeCache'][$id][$key] ) )
            $GLOBALS['xrowProductAttributeCache'][$id][$key] = eZPersistentObject::fetchObject( self::definition(), null, array( 
                'id' => $id 
            ), $asObject );
        
        return $GLOBALS['xrowProductAttributeCache'][$id][$key];
    }

    static function fetchList( $conditions = null, $asObjects = true, $offset = false, $limit = false, $sortBy = array() )
    {
        $limitation = null;
        if ( $offset !== false or $limit !== false )
            $limitation = array( 
                'offset' => $offset , 
                'length' => $limit 
            );
        
        $def = self::definition();
        
        $result = eZPersistentObject::fetchObjectList( $def, null, $conditions, $sortBy, $limitation, $asObjects );
        return $result;
    }

    static function fetchListCount( $conditions = null, $type = false )
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

    function fetchUser()
    {
        $userID = $this->attribute( 'user_id' );
        $user = eZUser::fetch( $userID );
        return $user;
    }

    /*!
     Wrapper for eZContentLanguage::topPriorityLanguage.
    */
    function topPriorityLanguageLocale()
    {
        return eZContentLanguage::topPriorityLanguage();
    }

    function getData( $key = false, $languageLocale = false )
    {
        if ( $key == false and $languageLocale == false )
            return $this->Data;
        
        if ( count( $this->Data ) > 0 )
        {
            if ( $languageLocale and isset( $this->Data[$key][$languageLocale] ) )
                return $this->Data[$key][$languageLocale];
            else
            {
                if ( ! $languageLocale )
                {
                    $languageList = eZContentLanguage::prioritizedLanguages();
                    foreach ( $languageList as $language )
                    {
                        if ( isset( $this->Data[$key][$language->attribute( 'locale' )] ) )
                        {
                            return $this->Data[$key][$language->attribute( 'locale' )];
                        }
                    }
                }
            }
        }
        
        return false;
    }

    /*!
     Returns a product attribute name in \a $languageLocale language.
     Uses siteaccess language list if \a $languageLocale is 'false'.

     \return string with product attribute name.
    */
    function name( $languageLocale = false )
    {
        $name = self::getData( 'name', $languageLocale );
        if ( ! $name )
            return $this->Name;
        else
            return $name;
    }

    /*!
     Returns a product attribute description in \a $languageLocale language.

     \return string with a description.
    */
    function description( $languageLocale = false )
    {
        $desc = self::getData( 'desc', $languageLocale );
        if ( ! $desc )
            return '';
        else
            return $desc;
    }

    function setData( $key, $languageLocale, $data )
    {
        $this->Data[$key][$languageLocale] = $data;
    }

    function languages()
    {
        if ( isset( $this->Data['name'] ) and count( $this->Data['name'] ) > 0 )
            return array_keys( $this->Data['name'] );
        else
            return array();
    }

    /*!
     Wrapper for xrowProductNameList::prioritizedLanguages.
    */
    function prioritizedLanguages()
    {
        return eZContentLanguage::prioritizedLanguages();
    }

    function dataTypeList()
    {
        xrowProductDataType::loadAndRegisterAllTypes();
    }

    /**
     * Returns the connected datatype
     */
    function dataType()
    {
        if ( ! $this->DataType )
        {
            if ( isset( $this->DataTypeString ) and strlen( $this->DataTypeString ) > 0 )
                $this->DataType = xrowProductDataType::create( $this->DataTypeString );
        }
        if ( $this->DataType )
            return $this->DataType;
        else
            return false;
    }

    /**
     * Checks if a datatype is already set in the attribute definition.
     */
    static function hasDatatype( $dataType )
    {
        $def = self::definition();
        $table = $def['name'];
        $db = eZDB::instance();
        $dataTypeString = $db->escapeString( $dataType->DataTypeString );
        $sql = "SELECT COUNT(*) counter FROM $table WHERE data_type LIKE '$dataTypeString'";
        $result = $db->arrayQuery( $sql );
        if ( $result[0]['counter'] > 0 )
            return true;
        else
            return false;
    }

    function required()
    {
        if ( $this->DataType )
        {
            if ( $this->DataType->Attributes["required"] )
                return true;
        }
        return false;
    }

    function unique()
    {
        if ( $this->DataType )
        {
            if ( $this->DataType->Attributes["unique"] )
                return true;
        }
        return false;
    }

    function translatable()
    {
        if ( $this->DataType )
        {
            if ( $this->DataType->Attributes['translation_allowed'] == true )
                if ( isset( $this->Data['translatable'] ) )
                    return $this->Data['translatable'];
        }
        return false;
    }

    /**
     * return true if the identifier is already in use_soap_error_handler
     * @param string identifier
     * @return true or false
     */
    function checkIdentifierDupe( $identifier )
    {
        $def = self::definition();
        $table = $def['name'];
        $db = eZDB::instance();
        $identifier = $db->escapeString( $identifier );
        $id = $this->attribute( 'id' );
        $sql = "SELECT * FROM $table WHERE identifier LIKE '$identifier' and id != $id";
        $result = $db->arrayQuery( $sql );
        if ( count( $result ) > 0 )
            return true;
        else
            return false;
    }

    function content()
    {
        if ( $this->Content === null )
            $this->Content = $this->DataType->attributeContent( $this );
        return $this->Content;
    }

    function hasContent()
    {
        if ( $this->HasContent === null )
            $this->HasContent = $this->DataType->hasAttributeContent( $this );
        return $this->HasContent;
    }
    
    /// \privatesection
    public $ID;
    // serialized array of setup data
    public $SerializedData;
    public $Data = false;
    public $Content;
    public $HasContent;
    public $Identifier;
    public $UserID;
    public $DataTypeString;
    public $DataType = false;
    public $Created;
    public $LanguageMask;
    public $InitialLanguageID;
    public $Name;
    public $Desc;
    public $Active;

}

?>
