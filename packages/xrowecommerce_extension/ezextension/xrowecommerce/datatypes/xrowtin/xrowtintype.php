<?php

//
// Definition of xrowTIN class
//
// Created on: <27-Apr-2009 11:49:14>
// Validate the VATIN (Value added tax identification number)
//


class xrowTINType extends eZDataType
{
    const DATA_TYPE_STRING = 'xrowtin';
    const STATUS_NOT_VALIDATED = 0;
    const STATUS_VALIDATED = 1;
    const STATUS_VALIDATED_BY_ADMIN = 2;
    const STATUS_TMP_VALIDATED = 3;

    /*!
     Initializes with a string id and a description.
    */
    function xrowTINType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', 'Tax Identification Number', 'Datatype name' ), array( 
            'serialize_supported' => true , 
            'object_serialize_map' => array( 
                'data_int' => 'int' , 
                'data_text' => 'text' 
            ) 
        ) );
    }

    /*!
     \reimp
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_xrowtin_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $tax_id = $http->postVariable( $base . '_xrowtin_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
            $tax_id = strtoupper( trim( $tax_id ) );
            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            
            if ( $tax_id == "" )
            {
                if ( ! $classAttribute->attribute( 'is_information_collector' ) and $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input required.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
            }
        }
        $draft = eZContentObjectVersion::fetchVersion( $contentObjectAttribute->Version, $contentObjectAttribute->ContentObjectID );
        $dm = $draft->dataMap();
        $countryField = eZINI::instance( 'xrowecommerce.ini' )->variable( 'TaxSettings', 'CountryIndentifier' );
        $companyField = eZINI::instance( 'xrowecommerce.ini' )->variable( 'TaxSettings', 'CompanyNameIndentifier' );
        if ( isset( $dm[$countryField] ) )
        {
            $dm[$countryField]->fetchInput( $http, $base );
            $countryValue = $dm[$countryField]->content();
            /* Warning  datatype MIGHT return different values depending on setup */
            if ( is_array( $countryValue['value'] ) )
            {
                $countryValue = array_shift( $countryValue['value'] );
            }
            else
            {
                $countryValue = eZCountryType::fetchCountry( $countryValue['value'], false );
            }
        }
        else
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'extension/xrowtin', 'The class identifier name of the country field is incorrect in xrowecommerce.ini' ) );
        }
        if ( isset( $dm[$companyField] ) )
        {
            $dm[$companyField]->fetchInput( $http, $base );
            $companyValue = $dm[$companyField]->content();
        }
        else
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'extension/xrowtin', 'The class identifier name of the company field is incorrect in xrowecommerce.ini' ) );
            return eZInputValidator::STATE_INVALID;
        }
        if ( $tax_id and empty( $companyValue ) )
        {
            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'extension/xrowtin', 'Please provide a company name with your companies tax ID.' ) );
            return eZInputValidator::STATE_INVALID;
        }
        if ( $tax_id )
        {
            $countryPrefix = substr( $tax_id, 0, 2 );
            $Alpha2 = $countryValue['Alpha2'];
            /* EU doesn`t use ISO all the time */
            if ( $Alpha2 == 'GR' )
            {
                $Alpha2 = 'EL';
            }
            $idsEU = array( 
                "AT" , 
                "BE" , 
                "BG" , 
                "CY" , 
                "CZ" , 
                "DE" , 
                "DK" , 
                "EE" , 
                "EL" , 
                "ES" , 
                "FI" , 
                "FR" , 
                "GB" , 
                "HU" , 
                "IE" , 
                "IT" , 
                "LT" , 
                "LU" , 
                "LV" , 
                "MT" , 
                "NL" , 
                "PL" , 
                "PT" , 
                "RO" , 
                "SE" , 
                "SI" , 
                "SK" 
            );
            
            if ( in_array( $Alpha2, $idsEU ) )
            {
                $matches = array();
                if ( preg_match( "/^(" . join( '|', $idsEU ) . ")([0-9]+)/i", $tax_id, $matches ) )
                {
                    if ( $Alpha2 != $matches[1] )
                    {
                        $contentObjectAttribute->setValidationError( ezpI18n::tr( 'extension/xrowtin', 'Country doesn`t match tax ID number.' ) );
                        return eZInputValidator::STATE_INVALID;
                    }
                    
                    if ( $contentObjectAttribute->attribute( 'data_int' ) != self::STATUS_VALIDATED_BY_ADMIN )
                    {
                        try
                        {
                            $ret = xrowECommerce::checkVat( $countryPrefix, $matches[2] );
                            if ( ! $ret )
                            {
                                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'extension/xrowtin', 'Your companies tax ID number is not valid.' ) );
                                return eZInputValidator::STATE_INVALID;
                            }
                            else
                            {
                                $contentObjectAttribute->setAttribute( 'data_int', self::STATUS_TMP_VALIDATED );
                                $contentObjectAttribute->store();
                            }
                        }
                        catch ( Exception $e )
                        {
                            eZDebug::writeError( $e->getMessage(), 'TAX ID Validation problem' );
                            return eZInputValidator::STATE_ACCEPTED;
                        }
                    }
                }
                elseif ( in_array( $Alpha2, xrowECommerce::merchantsCountries() ) )
                {
                    if ( ! xrowECommerce::validateTIN( $Alpha2, $tax_id, $errors2 ) )
                    {
                        foreach ( $errors2 as $error )
                        {
                            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'extension/xrowtin', $error ) );
                        }
                        return eZInputValidator::STATE_INVALID;
                    }
                    else
                    {
                        return eZInputValidator::STATE_ACCEPTED;
                    }
                }
                else
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'extension/xrowtin', 'Your companies tax ID number is not valid.' ) );
                    return eZInputValidator::STATE_INVALID;
                }
            }
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    /*!
     \reimp
    */
    function validateCollectionAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezstring_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_ezstring_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
            $classAttribute = $contentObjectAttribute->contentClassAttribute();
            
            if ( $data == "" )
            {
                if ( $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input required.' ) );
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
        if ( $http->hasPostVariable( $base . '_xrowtin_data_text_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $tax_id = $http->postVariable( $base . '_xrowtin_data_text_' . $contentObjectAttribute->attribute( 'id' ) );
            $tax_id = strtoupper( trim( $tax_id ) );
            $contentObjectAttribute->setAttribute( 'data_text', $tax_id );
        }
        if ( $http->hasPostVariable( $base . '_xrowtin_status_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $data = $http->postVariable( $base . '_xrowtin_status_' . $contentObjectAttribute->attribute( 'id' ) );
            if ( $data == self::STATUS_VALIDATED_BY_ADMIN and ( $contentObjectAttribute->attribute( 'data_int' ) == self::STATUS_NOT_VALIDATED or $contentObjectAttribute->attribute( 'data_int' ) == self::STATUS_TMP_VALIDATED or $contentObjectAttribute->attribute( 'data_int' ) == self::STATUS_VALIDATED ) )
            {
                $contentObjectAttribute->setAttribute( 'data_int', self::STATUS_VALIDATED_BY_ADMIN );
            }
            if ( $data == self::STATUS_NOT_VALIDATED and $contentObjectAttribute->attribute( 'data_int' ) != self::STATUS_TMP_VALIDATED )
            {
                $contentObjectAttribute->setAttribute( 'data_int', self::STATUS_NOT_VALIDATED );
            }
        }
        
        if ( $contentObjectAttribute->attribute( 'data_int' ) == self::STATUS_TMP_VALIDATED )
        {
            $contentObjectAttribute->setAttribute( 'data_int', self::STATUS_VALIDATED );
        }
        $contentObjectAttribute->store();
        return true;
    }

    /*!
     Fetches the http post variables for collected information
    */
    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_ezstring_data_text_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $dataText = $http->postVariable( $base . "_ezstring_data_text_" . $contentObjectAttribute->attribute( "id" ) );
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
     \reimp
     Simple string insertion is supported.
    */
    function isSimpleStringInsertionSupported()
    {
        return true;
    }

    /*!
     \reimp
     Inserts the string \a $string in the \c 'data_text' database field.
    */
    function insertSimpleString( $object, $objectVersion, $objectLanguage, $objectAttribute, $string, &$result )
    {
        $result = array( 
            'errors' => array() , 
            'require_storage' => true 
        );
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

    /*!
     \reimp
    */
    function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
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
        return true;
    }

    /*!
     \reimp
    */
    function sortKey( $contentObjectAttribute )
    {
        //include_once( 'lib/ezi18n/classes/ezchartransform.php' );
        $trans = eZCharTransform::instance();
        return $trans->transformByGroup( $contentObjectAttribute->attribute( 'data_text' ), 'lowercase' );
    }

    /*!
     \reimp
    */
    function sortKeyType()
    {
        return 'string';
    }

    /*!
     \reimp
    */
    function serializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $maxLength = $classAttribute->attribute( self::MAX_LEN_FIELD );
        $defaultString = $classAttribute->attribute( self::DEFAULT_STRING_FIELD );
        $dom = $attributeParametersNode->ownerDocument;
        $maxLengthNode = $dom->createElement( 'max-length', $maxLength );
        $attributeParametersNode->appendChild( $maxLengthNode );
        if ( $defaultString )
        {
            $defaultStringNode = $dom->createElement( 'default-string', $defaultString );
        }
        else
        {
            $defaultStringNode = $dom->createElement( 'default-string' );
        }
        $attributeParametersNode->appendChild( $defaultStringNode );
    }

    /*!
     \reimp
    */
    function unserializeContentClassAttribute( $classAttribute, $attributeNode, $attributeParametersNode )
    {
        $maxLength = $attributeParametersNode->getElementsByTagName( 'max-length' )->item( 0 )->textContent;
        $defaultString = $attributeParametersNode->getElementsByTagName( 'default-string' )->item( 0 )->textContent;
        $classAttribute->setAttribute( self::MAX_LEN_FIELD, $maxLength );
        $classAttribute->setAttribute( self::DEFAULT_STRING_FIELD, $defaultString );
    }

    /*!
      \reimp
    */
    function diff( $old, $new, $options = false )
    {
        //include_once( 'lib/ezdiff/classes/ezdiff.php' );
        $diff = new eZDiff( );
        $diff->setDiffEngineType( $diff->engineType( 'text' ) );
        $diff->initDiffEngine();
        $diffObject = $diff->diff( $old->content(), $new->content() );
        return $diffObject;
    }
    
    /// \privatesection
    /// The max len validator
    public $MaxLenValidator;
}

eZDataType::register( xrowTINType::DATA_TYPE_STRING, 'xrowTinType' );

?>
