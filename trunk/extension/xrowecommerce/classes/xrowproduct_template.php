<?php
/**
 * Class xrowProductTemplate
 * Stores definitions of the product templates
 */
class xrowProductTemplate extends eZPersistentObject
{
    function xrowProductTemplate( $row )
    {
        if ( is_array( $row ) and count( $row ) > 0 )
        {
            $this->eZPersistentObject( $row );
            if ( strlen( $this->SerializedData ) > 0 )
            {
                $this->Data = @unserialize( $this->SerializedData );
            }
        }
    }

    function updateAttributes( $language = false )
    {
        $this->AttributeList = array();
        if ( isset( $this->Data['attributes'] )
             and count( $this->Data['attributes'] ) > 0 )
        {
            $languageList = eZContentLanguage::prioritizedLanguages();
            $langArray = array();
            foreach ( $languageList as $item )
            {
                $langArray[] = $item->attribute( 'locale' );
            }

            if ( $language !== false )
            {
                $langArray = array_unique( array_merge( array( $language ), $langArray ) );
            }

            foreach( $this->Data['attributes'] as $key => $item )
            {
                $this->AttributeList[$key] = $item;
                $this->AttributeList[$key]['attribute'] = xrowProductAttribute::fetch( $key );
                if ( !is_object( $this->AttributeList[$key]['attribute'] ) or
                     $this->AttributeList[$key]['attribute']->attribute( 'active' ) == 0 )
                {
                    unset( $this->AttributeList[$key] );
                    continue;
                }

                if ( isset( $this->AttributeList[$key]['translation'] )
                     and $this->AttributeList[$key]['translation']
                     and count( $this->AttributeList[$key]['default_value_array'] ) > 0 )
                {
                    $this->AttributeList[$key]['default_value'] = $this->getLangValue( $langArray, $this->AttributeList[$key], 'default_value_array' );
                }
                if ( !isset( $this->AttributeList[$key]['default_value'] ) )
                    $this->AttributeList[$key]['default_value'] = null;

                if ( count( $this->AttributeList[$key]['column_name_array'] ) > 0 )
                {
                    $this->AttributeList[$key]['column_name'] = $this->getLangValue( $langArray, $this->AttributeList[$key], 'column_name_array' );
                }
                if ( !isset( $this->AttributeList[$key]['column_name'] ) )
                    $this->AttributeList[$key]['column_name'] = null;

                if ( count( $this->AttributeList[$key]['column_desc_array'] ) > 0 )
                {
                    $this->AttributeList[$key]['column_desc'] = $this->getLangValue( $langArray, $this->AttributeList[$key], 'column_desc_array' );
                }
                if ( !isset( $this->AttributeList[$key]['column_desc'] ) )
                    $this->AttributeList[$key]['column_desc'] = null;
            }

            foreach ( $this->AttributeList as $key => $item )
            {
            	$identifier = $item['attribute']->attribute( 'identifier' );
            	$this->AttributeListByIdentifier[$identifier] = $item;
            }
        }
    }

    function getLangValue( $langArray, $dataArray, $key )
    {
        foreach ( $langArray as $langLocale )
        {
            if ( isset( $dataArray[$key][$langLocale] ) )
            {
                return $dataArray[$key][$langLocale];
            }
        }
        return null;
    }

    function resetAttributeList()
    {
        $this->AttributeList = array();
        $this->Data['attributes'] = array();
        $this->AttributeListByIdentifier = array();
    }

    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                         'name' => array( 'name' => 'Name',
                                                                'datatype' => 'string',
                                                                'default' => 0,
                                                                'required' => false,
                                                                'max_length' => 255 ),
                                         'pre_sku' => array( 'name' => 'PreSKU',
                                                                'datatype' => 'string',
                                                                'default' => '',
                                                                'required' => false,
                                                                'max_length' => 255 ),
                                         'sortorder' => array( 'name' => 'SortOrder',
                                                                  'datatype' => 'string',
                                                                  'default' => '',
                                                                  'required' => false,
                                                                  'max_length' => 255 ),
                                         'serialized_data' => array( 'name' => 'SerializedData',
                                                                 'datatype' => 'text',
                                                                 'default' => '',
                                                                 'required' => false ),
                                         'created' => array( 'name' => 'Created',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => false ),
                                         'modified' => array( 'name' => 'Modified',
                                                                       'datatype' => 'integer',
                                                                       'default' => 0,
                                                                       'required' => false ),
                                         'user_id' => array( 'name' => 'UserID',
                                                                'datatype' => 'integer',
                                                                'default' => 0,
                                                                'required' => false,
                                                                'foreign_class' => 'eZUser',
                                                                'foreign_attribute' => 'contentobject_id',
                                                                'multiplicity' => '1..*' ),
                                         'language_mask' => array( 'name' => 'LanguageMask',
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => false ),
                                         'initial_language_id' => array( 'name' => "InitialLanguageID",
                                                                         'datatype' => 'integer',
                                                                         'default' => 0,
                                                                         'required' => false,
                                                                         'foreign_class' => 'eZContentLanguage',
                                                                         'foreign_attribute' => 'id',
                                                                         'multiplicity' => '1..*' ),
                                         'active' => array( 'name' => 'Active',
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => false ),
                                                        ),
                      'function_attributes' => array( 'user' => 'fetchUser',
                                                      'name' => 'name',
                                                      'description' => 'description',
                                                      'languages' => 'languages',
                                                      'prioritized_languages' => 'prioritizedLanguages',
                                                      'top_priority_language_locale' => 'topPriorityLanguageLocale',
                                                      'data' => 'getData',
                                                      'attribute_list' => 'attributeList',
                                                      'attribute_list_by_identifier' => 'attributeListByIdentifier',
                                                      'has_attribute_list' => 'hasAttributeList',
                                                      'sortby' => 'sortBy'

                                                       ),
                      'keys' => array( 'id' ),
                      'increment_key' => 'id',
                      'sort' => array( 'name' => 'asc' ),
                      'class_name' => 'xrowProductTemplate',
                      'name' => 'xrowproduct_template' );
    }

    function store( $fieldFilters = null )
    {
        $this->SerializedData = '';
        if ( count( $this->Data ) > 0 )
            $this->SerializedData = serialize( $this->Data );

        $this->Modified = time();

        eZPersistentObject::storeObject( $this, $fieldFilters );

    }

    static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( self::definition(),
                                                null,
                                                array( "id" => $id ),
                                                $asObject );
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

    function fetchUser()
    {
        $userID = $this->attribute( 'user_id' );
        $user = eZUser::fetch( $userID );
        return $user;
    }

    /**
     * Wrapper for eZContentLanguage::topPriorityLanguage.
     *
     * @return string
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
                if ( !$languageLocale )
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

    /**
     * Returns a the name of the template in \a $languageLocale language.
     * Uses siteaccess language list if \a $languageLocale is 'false'.
     *
     * @param string $languageLocale
     * @return string
     */
    function name( $languageLocale = false )
    {
        $name = $this->getData( 'name', $languageLocale );
        if ( !$name )
            return $this->Name;
        else
            return $name;
    }

    /**
     * Returns a template description in \a $languageLocale language.
     *
     * @param string $languageLocale
     * @return string
     */
    function description( $languageLocale = false )
    {
        $desc = $this->getData( 'desc', $languageLocale );
        if ( !$desc )
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

    /**
     * Wrapper for xrowProductNameList::prioritizedLanguages.
     *
     * @return array
     */
    function prioritizedLanguages()
    {
        return eZContentLanguage::prioritizedLanguages();
    }

    /**
     * Returns a list of attributes for the current template
     * Check the current local and set the right default value
     * @return mixed array( 1 => array( 'id' => 'id',
     *                                  'required' => false,
     *                                  'translation' => true,
     *                                  'default_value' => null,
     *                                  'default_value_array' => array( 'ger-DE' => 'Standard Text',
     *                                                                  'eng-GB' => 'Default text' ) ) );
     */

    function attributeList()
    {
        return $this->AttributeList;
    }

    function attributeListByIdentifier()
    {
    	return $this->AttributeListByIdentifier;
    }

    function hasAttributeList()
    {
        if ( isset( $this->AttributeList ) and count( $this->AttributeList ) > 0 )
            return true;
        else
            return false;
    }

    function sortBy()
    {
        if ( $this->SortBy === null and
             strlen( $this->SortOrder ) > 0 )
        {
            $this->SortBy['method'] = '';
            $this->SortBy['attribute'] = '';
            $sortArray = explode( " ", $this->SortOrder );
            $this->SortBy['attribute'] = $sortArray[0];
            $this->SortBy['method'] = $sortArray[1];
        }
        return $this->SortBy;
    }

     /// \privatesection
    public $ID;
    // serialized array of data
    public $SerializedData;
    public $Data = false;
    public $UserID;
    public $Created;
    public $Modified;
    public $LanguageMask;
    public $InitialLanguageID;
    public $PreSKU;
    public $SortOrder;
    public $SortBy;
    public $Name;
    public $AttributeList = array();
    public $AttributeListByIdentifier = array();
}

?>
