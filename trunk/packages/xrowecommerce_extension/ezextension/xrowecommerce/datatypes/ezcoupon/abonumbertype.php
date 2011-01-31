<?php

class aboNumberType extends eZDataType
{
	const DATA_TYPE_STRING = 'abonumber';
    const MAX_COUNT_FIELD = 'data_int1';
    const MAX_COUNT = '_abonumber_max_count_';
    const DEFAULT_STRING_FIELD = "data_text1";
    const DEFAULT_STRING_VARIABLE = "_abonumber_default_value_";

    /*!
     Initializes with a string id and a description.
    */
    function aboNumberType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', 'Abonumber', 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_text' => 'text' ) ) );
        $this->MaxCountValidator = new eZIntegerValidator();
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataText = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $dataText );
        }
        else
        {
            $contentClassAttribute = $contentObjectAttribute->contentClassAttribute();
            $default = $contentClassAttribute->attribute( 'data_text1' );
            if ( $default !== '' && $default !== NULL )
            {
                $contentObjectAttribute->setAttribute( 'data_text', $default );
            }
        }
    }

    /*
     Private method, only for using inside this class.
    */
    function validateStringHTTPInput( $data, $contentObjectAttribute, $classAttribute )
    {
        // get all abonumbers with the same value
        $sql = 'SELECT COUNT(*) as number FROM ezcontentobject_attribute as ezattr, ezcontentobject as ezcont 
                WHERE ezattr.data_type_string = "abonumber"
                AND ezattr.data_text = ' . $data .'
                AND ezcont.id = ezattr.contentobject_id
                AND ezcont.current_version = ezattr.version
                AND ezcont.status = 1';
        $db = eZDB::instance();
        $resultCount = $db->arrayQuery( $sql );
        #$resultCount = count( $allUserWithAbo );
        $dbCount = $resultCount[0]['number'];
        $maxCount = $classAttribute->attribute( self::MAX_COUNT_FIELD );

        if ( $dbCount > $maxCount and $maxCount > 0 )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                      'The input is not valid. There are already %1 abonumbers with the same value.' ),
                                                         $maxCount );
            return eZInputValidator::STATE_INVALID;
        }
        return eZInputValidator::STATE_ACCEPTED;
    }


    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        $classAttribute = $contentObjectAttribute->contentClassAttribute();

        if ( $http->hasPostVariable( $base . '_abonumber_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_abonumber_data_text_' . $contentObjectAttribute->attribute( 'id' ) );

            if ( $data == "" )
            {
                if ( !$classAttribute->attribute( 'is_information_collector' ) and
                     $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                              'Input required.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
            }
            else
            {
                return $this->validateStringHTTPInput( $data, $contentObjectAttribute, $classAttribute );
            }
        }
        else if ( !$classAttribute->attribute( 'is_information_collector' ) and $contentObjectAttribute->validateIsRequired() )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input required.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    function validateCollectionAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_abonumber_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_abonumber_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
            $classAttribute = $contentObjectAttribute->contentClassAttribute();

            if ( $data == "" )
            {
                if ( $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes',
                                                                              'Input required.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
                else
                    return eZInputValidator::STATE_ACCEPTED;
            }
            else
            {
                return $this->validateStringHTTPInput( $data, $contentObjectAttribute, $classAttribute );
            }
        }
        else
            return eZInputValidator::STATE_INVALID;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_abonumber_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_abonumber_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
            $contentObjectAttribute->setAttribute( 'data_text', $data );
            return true;
        }
        return false;
    }

    /*!
     Fetches the http post variables for collected information
    */
    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_abonumber_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $dataText = $http->postVariable( $base . "_abonumber_data_text_" . $contentObjectAttribute->attribute( "id" ) );
            $collectionAttribute->setAttribute( 'data_text', $dataText );
            return true;
        }
        return false;
    }

    /*!
     Does nothing since it uses the data_text field in the content object attribute.
     See fetchObjectAttributeHTTPInput for the actual storing.
    */
    function storeObjectAttribute( $attribute )
    {
    }

    /*!
     Simple string insertion is supported.
    */
    function isSimpleStringInsertionSupported()
    {
        return true;
    }

    /*!
     Inserts the string \a $string in the \c 'data_text' database field.
    */
    function insertSimpleString( $object, $objectVersion, $objectLanguage,
                                 $objectAttribute, $string,
                                 &$result )
    {
        $result = array( 'errors' => array(),
                         'require_storage' => true );
        $objectAttribute->setContent( $string );
        $objectAttribute->setAttribute( 'data_text', $string );
        return true;
    }

    function storeClassAttribute( $attribute, $version )
    {
    }

    function storeDefinedClassAttribute( $attribute )
    {
    }

    function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $maxCountName = $base . self::MAX_COUNT. $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $maxCountName ) )
        {
            $maxCountValue = $http->postVariable( $maxCountName );
            $maxCountValue = str_replace(" ", "", $maxCountValue );
            if( ( $maxCountValue == "" ) ||  ( $maxCountValue == 0 ) )
            {
                $maxCountValue = 0;
                $http->setPostVariable( $maxCountName, $maxCountValue );
                return eZInputValidator::STATE_ACCEPTED;
            }
            else
            {
                $this->MaxCountValidator->setRange( 1, false );
                return $this->MaxCountValidator->validate( $maxCountValue );
            }
        }
        return eZInputValidator::STATE_INVALID;
    }

    function fixupClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $maxCountName = $base . self::MAX_COUNT . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $maxCountName ) )
        {
            $maxCountValue = $http->postVariable( $maxCountName );
            $this->MaxCountValidator->setRange( 1, false );
            $maxCountValue = $this->MaxCountValidator->fixup( $maxCountValue );
            $http->setPostVariable( $maxCountName, $maxCountValue );
        }
    }

    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $maxCountName = $base . self::MAX_COUNT . $classAttribute->attribute( 'id' );
        $defaultValueName = $base . self::DEFAULT_STRING_VARIABLE . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $maxCountName ) )
        {
            $maxCountValue = $http->postVariable( $maxCountName );
            $classAttribute->setAttribute( self::MAX_COUNT_FIELD, $maxCountValue );
        }
        if ( $http->hasPostVariable( $defaultValueName ) )
        {
            $defaultValueValue = $http->postVariable( $defaultValueName );

            $classAttribute->setAttribute( self::DEFAULT_STRING_FIELD, $defaultValueValue );
        }
        return true;
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }
    /*!
     \return string representation of an contentobjectattribute data for simplified export

    */
    function toString( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function fromString( $contentObjectAttribute, $string )
    {
        return $contentObjectAttribute->setAttribute( 'data_text', $string );
    }


    /*!
     Returns the content of the string for use as a title
    */
    function title( $contentObjectAttribute, $name = null )
    {
        return $contentObjectAttribute->attribute( 'data_text' );
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return trim( $contentObjectAttribute->attribute( 'data_text' ) ) != '';
    }

    function isIndexable()
    {
        return true;
    }

    function isInformationCollector()
    {
        return true;
    }

    function sortKey( $contentObjectAttribute )
    {
        $trans = eZCharTransform::instance();
        return $trans->transformByGroup( $contentObjectAttribute->attribute( 'data_text' ), 'lowercase' );
    }

    function sortKeyType()
    {
        return 'string';
    }

    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $maxCount = $classAttribute->attribute( self::MAX_COUNT_FIELD );
        $defaultString = $classAttribute->attribute( self::DEFAULT_STRING_FIELD );
        $dom = $attributeParametersNode->ownerDocument;
        $maxCountNode = $dom->createElement( 'max-length' );
        $maxCountNode->appendChild( $dom->createTextNode( $maxCount ) );
        $attributeParametersNode->appendChild( $maxCountNode );
        $defaultStringNode = $dom->createElement( 'default-string' );
        if ( $defaultString )
        {
            $defaultStringNode->appendChild( $dom->createTextNode( $defaultString ) );
        }
        $attributeParametersNode->appendChild( $defaultStringNode );
    }

    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $maxCount = $attributeParametersNode->getElementsByTagName( 'max-count' )->item( 0 )->textContent;
        $defaultString = $attributeParametersNode->getElementsByTagName( 'default-string' )->item( 0 )->textContent;
        $classAttribute->setAttribute( self::MAX_COUNT_FIELD, $maxCount );
        $classAttribute->setAttribute( self::DEFAULT_STRING_FIELD, $defaultString );
    }

    function diff( $old, $new, $options = false )
    {
        $diff = new eZDiff();
        $diff->setDiffEngineType( $diff->engineType( 'text' ) );
        $diff->initDiffEngine();
        $diffObject = $diff->diff( $old->content(), $new->content() );
        return $diffObject;
    }

    function supportsBatchInitializeObjectAttribute()
    {
        return true;
    }

    function batchInitializeObjectAttributeData( $classAttribute )
    {
        $default = $classAttribute->attribute( 'data_text1' );
        if ( $default !== '' && $default !== NULL )
        {
            $db = eZDB::instance();
            $default = "'" . $db->escapeString( $default ) . "'";
            $trans = eZCharTransform::instance();
            $lowerCasedDefault = $trans->transformByGroup( $default, 'lowercase' );
            return array( 'data_text' => $default, 'sort_key_string' => $lowerCasedDefault );
        }

        return array();
    }

    /// \privatesection
    /// The max len validator
    public $MaxCountValidator;
}

eZDataType::register( aboNumberType::DATA_TYPE_STRING, 'aboNumberType' );

?>