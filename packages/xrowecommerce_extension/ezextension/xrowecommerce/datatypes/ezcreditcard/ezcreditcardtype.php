<?php

class ezcreditcardType extends eZDataType
{
    const DATA_TYPE_STRING = "ezcreditcard";
    const CREDITCARD_GATEWAY_FIELD = 'data_text5';
    const MASTERCARD = 1;
    const VISA = 2;
    const DISCOVER = 3;
    const AMERICANEXPRESS = 4;
    const EUROCARD = 5;
    const KEY_ECNAME = 'ecname';
    const KEY_ACCOUNTNUMBER = 'accountnumber';
    const KEY_BANKCODE = 'bankcode';
    const KEY_TYPE = 'type';
    const KEY_NUMBER = 'number';
    const KEY_SECURITYCODE = 'securitycode';
    const KEY_MONTH = 'month';
    const KEY_YEAR = 'year';
    const MAINKEY_CREDITCARD = 'creditcard';
    const NO_STORED_CARD = 101;

    /*!
     Initializes with a string id and a description.
    */
    function ezcreditcardType()
    {
        $this->eZDataType( self::DATA_TYPE_STRING, ezpI18n::tr( 'kernel/classes/datatypes', 'Credit card', 'Datatype name' ), array( 
            'serialize_supported' => true , 
            'object_serialize_map' => array( 
                'data_text' => 'text' 
            ) 
        ) );
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
    }

    /*
     Private method, only for using inside this class.
    */
    function validateCard( $data, $contentObjectAttribute, $classAttribute )
    {
        $classContent = $this->classAttributeContent( $classAttribute );
        $gatewayArray = $classContent['gateway'];
        if ( isset( $gatewayArray[$data['type']] ) )
        {
            $gateway = $gatewayArray[$data['type']];
            $payObj = xrowPaymentInfo::getInfoClassObj( $gateway );
            if ( is_object( $payObj ) )
            {
                if ( $payObj->validateCardData( $contentObjectAttribute, $classAttribute, $data ) )
                {
                    return eZInputValidator::STATE_ACCEPTED;
                }
                else
                {
                    return eZInputValidator::STATE_INVALID;
                }
            } 
            else
            {
                eZDebug::writeError( 'PaymentInfo Object not found: ' . $gateway . 'Info', 'eZCreditcardType::validateCard' );
                return eZInputValidator::STATE_INVALID;
            }
        } 
        else
        {
            eZDebug::writeError( 'Gateway with ID ' . $data['type'] . ' not found', 'eZCreditcardType::validateCard' );
            return eZInputValidator::STATE_INVALID;
        }
    }

    /*!
     \reimp
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        include_once (eZExtension::baseDirectory() . '/xrowecommerce/classes/subscriptions/recurringordercollection.php');
        $classAttribute = $contentObjectAttribute->contentClassAttribute();
        if ( $http->hasPostVariable( $base . '_ezcreditcard_type_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $type = (int) $http->postVariable( $base . '_ezcreditcard_type_' . $contentObjectAttribute->attribute( 'id' ) );
            if ( $type == self::EUROCARD )
            {
                if ( $http->hasPostVariable( $base . '_ezcreditcard_ecname_' . $contentObjectAttribute->attribute( 'id' ) ) and $http->hasPostVariable( $base . '_ezcreditcard_accountnumber_' . $contentObjectAttribute->attribute( 'id' ) ) and $http->hasPostVariable( $base . '_ezcreditcard_bankcode_' . $contentObjectAttribute->attribute( 'id' ) ) )
                {
                    $data = array();
                    $data['type'] = $type;
                    $data['ecname'] = trim( $http->postVariable( $base . '_ezcreditcard_ecname_' . $contentObjectAttribute->attribute( 'id' ) ) );
                    $data['accountnumber'] = trim( $http->postVariable( $base . '_ezcreditcard_accountnumber_' . $contentObjectAttribute->attribute( 'id' ) ) );
                    $data['bankcode'] = trim( $http->postVariable( $base . '_ezcreditcard_bankcode_' . $contentObjectAttribute->attribute( 'id' ) ) );
                    if ( $data['ecname'] == "" and $data['accountnumber'] == "" and $data['bankcode'] == "" )
                    {
                        if ( $this->hasOrderCollection( $contentObjectAttribute ) )
                        {
                            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input is required, if you have active subscriptions or recurring orders.' ) );
                            return eZInputValidator::STATE_INVALID;
                        }
                        if ( ! $classAttribute->attribute( 'is_information_collector' ) and $contentObjectAttribute->validateIsRequired() )
                        {
                            // users with a test order are allowed to bypass
                            // the card check
                            if ( ! $http->hasSessionVariable( 'xrowTestAccountOrder' ) )
                            {
                                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input required.' ) );
                                return eZInputValidator::STATE_INVALID;
                            } 
                            else
                            {
                                return eZInputValidator::STATE_ACCEPTED;
                            }
                        } 
                        else
                        {
                            return eZInputValidator::STATE_ACCEPTED;
                        }
                    } 
                    else
                    {
                        return $this->validateCard( $data, $contentObjectAttribute, $classAttribute );
                    }
                } 
                else
                {
                    if ( ! $classAttribute->attribute( 'is_information_collector' ) and $contentObjectAttribute->validateIsRequired() )
                    {
                        // users with a test order are allowed to bypass
                        // the card check
                        if ( ! $http->hasSessionVariable( 'xrowTestAccountOrder' ) )
                        {
                            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input required.' ) );
                            return eZInputValidator::STATE_INVALID;
                        } 
                        else
                        {
                            return eZInputValidator::STATE_ACCEPTED;
                        }
                    } 
                    else
                    {
                        return eZInputValidator::STATE_ACCEPTED;
                    }
                }
            } 
            elseif ( in_array( $type, array( 
                self::AMERICANEXPRESS , 
                self::DISCOVER , 
                self::MASTERCARD , 
                self::VISA 
            ) ) )
            {
                if ( $http->hasPostVariable( $base . '_ezcreditcard_name_' . $contentObjectAttribute->attribute( 'id' ) ) and $http->hasPostVariable( $base . '_ezcreditcard_number_' . $contentObjectAttribute->attribute( 'id' ) ) and $http->hasPostVariable( $base . '_ezcreditcard_securitycode_' . $contentObjectAttribute->attribute( 'id' ) ) and $http->hasPostVariable( $base . '_ezcreditcard_month_' . $contentObjectAttribute->attribute( 'id' ) ) and $http->hasPostVariable( $base . '_ezcreditcard_year_' . $contentObjectAttribute->attribute( 'id' ) ) )
                {
                    $data = array();
                    $data['type'] = $type;
                    $data['name'] = trim( $http->postVariable( $base . '_ezcreditcard_name_' . $contentObjectAttribute->attribute( 'id' ) ) );
                    $data['number'] = trim( $http->postVariable( $base . '_ezcreditcard_number_' . $contentObjectAttribute->attribute( 'id' ) ) );
                    $data['securitycode'] = trim( $http->postVariable( $base . '_ezcreditcard_securitycode_' . $contentObjectAttribute->attribute( 'id' ) ) );
                    $data['month'] = $http->postVariable( $base . '_ezcreditcard_month_' . $contentObjectAttribute->attribute( 'id' ) );
                    $data['year'] = $http->postVariable( $base . '_ezcreditcard_year_' . $contentObjectAttribute->attribute( 'id' ) );
                    if ( $data['name'] == "" and $data['number'] == "" and $data['securitycode'] == "" )
                    {
                        if ( $this->hasOrderCollection( $contentObjectAttribute ) )
                        {
                            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input is required, if you have active subscriptions or recurring orders.' ) );
                            return eZInputValidator::STATE_INVALID;
                        }
                        if ( ! $classAttribute->attribute( 'is_information_collector' ) and $contentObjectAttribute->validateIsRequired() )
                        {
                            // users with a test order are allowed to bypass
                            // the card check
                            if ( ! $http->hasSessionVariable( 'xrowTestAccountOrder' ) )
                            {
                                $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input required.' ) );
                                return eZInputValidator::STATE_INVALID;
                            } 
                            else
                            {
                                return eZInputValidator::STATE_ACCEPTED;
                            }
                        } 
                        else
                        {
                            return eZInputValidator::STATE_ACCEPTED;
                        }
                    } 
                    else
                    {
                        return $this->validateCard( $data, $contentObjectAttribute, $classAttribute );
                    }
                } 
                else
                {
                    if ( ! $classAttribute->attribute( 'is_information_collector' ) and $contentObjectAttribute->validateIsRequired() )
                    {
                        // users with a test order are allowed to bypass
                        // the card check
                        if ( ! $http->hasSessionVariable( 'xrowTestAccountOrder' ) )
                        {
                            $contentObjectAttribute->setValidationError( ezpI18n::tr( 'kernel/classes/datatypes', 'Input required.' ) );
                            return eZInputValidator::STATE_INVALID;
                        } 
                        else
                        {
                            return eZInputValidator::STATE_ACCEPTED;
                        }
                    } 
                    else
                    {
                        return eZInputValidator::STATE_ACCEPTED;
                    }
                }
            }
        }
        return eZInputValidator::STATE_ACCEPTED;
    }

    function hasOrderCollection()
    {
        if ( isset( $this->hasOrderCollection ) )
        {
            return $this->hasOrderCollection; 
        }
        else
        {
            $this->hasOrderCollection = false;
        }
        $collections = XROWRecurringOrderCollection::fetchByUser();
        foreach ( $collections as $collection )
        {
            $list = $collection->fetchList();
            if ( count( $list ) > 0 )
            {
                $this->hasOrderCollection = true;
                return $this->hasOrderCollection;
            }
        }
        return $this->hasOrderCollection;
    }

    /*!
     Fetches the http post var string input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . '_ezcreditcard_type_' . $contentObjectAttribute->attribute( 'id' ) ) )
        {
            $type = (int) $http->postVariable( $base . '_ezcreditcard_type_' . $contentObjectAttribute->attribute( 'id' ) );
            if ( $type == self::EUROCARD )
            {
                if ( $http->hasPostVariable( $base . '_ezcreditcard_ecname_' . $contentObjectAttribute->attribute( 'id' ) ) and $http->hasPostVariable( $base . '_ezcreditcard_accountnumber_' . $contentObjectAttribute->attribute( 'id' ) ) and $http->hasPostVariable( $base . '_ezcreditcard_bankcode_' . $contentObjectAttribute->attribute( 'id' ) ) )
                {
                    $data = array();
                    $data['type'] = $type;
                    $data['ecname'] = $http->postVariable( $base . '_ezcreditcard_ecname_' . $contentObjectAttribute->attribute( 'id' ) );
                    $data['accountnumber'] = $http->postVariable( $base . '_ezcreditcard_accountnumber_' . $contentObjectAttribute->attribute( 'id' ) );
                    $data['bankcode'] = $http->postVariable( $base . '_ezcreditcard_bankcode_' . $contentObjectAttribute->attribute( 'id' ) );
                    $data = ezcreditcardType::encodeData( $data );
                    $doc = ezcreditcardType::createDOMTreefromArray( 'creditcard', $data );
                    $text = $doc->saveXML();
                    $contentObjectAttribute->setAttribute( 'data_text', $text );
                    return true;
                }
            } 
            else 
            {
                if ( in_array( $type, array( 
                    self::AMERICANEXPRESS , 
                    self::DISCOVER , 
                    self::MASTERCARD , 
                    self::VISA 
                ) ) )
                {
                    if ( $http->hasPostVariable( $base . '_ezcreditcard_name_' . $contentObjectAttribute->attribute( 'id' ) ) or $http->hasPostVariable( $base . '_ezcreditcard_number_' . $contentObjectAttribute->attribute( 'id' ) ) or $http->hasPostVariable( $base . '_ezcreditcard_securitycode_' . $contentObjectAttribute->attribute( 'id' ) ) or $http->hasPostVariable( $base . '_ezcreditcard_month_' . $contentObjectAttribute->attribute( 'id' ) ) or $http->hasPostVariable( $base . '_ezcreditcard_year_' . $contentObjectAttribute->attribute( 'id' ) ) )
                    {
                        $data = array();
                        $data['type'] = $type;
                        $data['name'] = $http->postVariable( $base . '_ezcreditcard_name_' . $contentObjectAttribute->attribute( 'id' ) );
                        $data['number'] = $http->postVariable( $base . '_ezcreditcard_number_' . $contentObjectAttribute->attribute( 'id' ) );
                        $data['securitycode'] = $http->postVariable( $base . '_ezcreditcard_securitycode_' . $contentObjectAttribute->attribute( 'id' ) );
                        $data['month'] = $http->postVariable( $base . '_ezcreditcard_month_' . $contentObjectAttribute->attribute( 'id' ) );
                        $data['year'] = $http->postVariable( $base . '_ezcreditcard_year_' . $contentObjectAttribute->attribute( 'id' ) );
                        $data = ezcreditcardType::encodeData( $data );
                        // it's not allowed to store the cvv2 code for security reasons
                        // $data['securitycode']   = ezcreditcardType::gpgEncode( $data['securitycode'] );
                        $doc = XROWRecurringordersCommonFunctions::createDOMTreefromArray( 'creditcard', $data );
                        $contentObjectAttribute->setAttribute( 'data_text', $doc->saveXML() );
                        return true;
                    }
                }
            }
        }
        return false;
    }

    function customObjectAttributeHTTPAction( $http, $action, $objectAttribute, $parameters )
    {
        if ( $action == "delete_creditcard" )
        {
            $objectAttribute->setAttribute( 'data_text', null );
            $objectAttribute->store();
        }
    }

    /**
     * Does nothing since it uses the data_text field in the content object attribute.
     * See fetchObjectAttributeHTTPInput for the actual storing.
     */
    function storeObjectAttribute( $attribute )
    {
        if ( isset( $GLOBALS['eZCreditcardCache'] ) )
        {
            unset( $GLOBALS['eZCreditcardCache'] );
        }
    }

    function storeClassAttribute( $attribute, $version )
    {
    }

    function storeDefinedClassAttribute( $attribute )
    {
    }

    function validateClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        return eZInputValidator::STATE_ACCEPTED;
    }

    function encodeData( $data )
    {
        if ( ! isset( $data['type'] ) )
        {
            return $data;
        }
        if ( $data['type'] == self::EUROCARD )
        {
            if ( isset( $data['accountnumber'] ) and $data['accountnumber'] > 0 )
            {
                $data['accountnumber'] = xrowEPaymentGateway::encryptData( $data['accountnumber'] );
            }
            if ( isset( $data['ecname'] ) and strlen( $data['ecname'] ) > 0 )
            {
                $data['ecname'] = xrowEPaymentGateway::encryptData( $data['ecname'] );
            }
            if ( isset( $data['bankcode'] ) and $data['bankcode'] > 0 )
            {
                $data['bankcode'] = xrowEPaymentGateway::encryptData( $data['bankcode'] );
            }
        } 
        else
        {
            if ( isset( $data['number'] ) and $data['number'] > 0 )
            {
                $data['number'] = xrowEPaymentGateway::encryptData( $data['number'] );
            }
            if ( isset( $data['name'] ) and strlen( $data['name'] ) > 0 )
            {
                $data['name'] = xrowEPaymentGateway::encryptData( $data['name'] );
            }
            if ( isset( $data['securitycode'] ) and strlen( $data['securitycode'] ) > 0 )
            {
                $data['securitycode'] = xrowEPaymentGateway::encryptData( $data['securitycode'] );
            }
        }
        return $data;
    }

    function decodeData( $data )
    {
        if ( ! isset( $data['type'] ) )
        {
            return $data;
        }
        if ( $data['type'] == self::EUROCARD )
        {
            if ( isset( $data['accountnumber'] ) and strlen( $data['accountnumber'] ) > 0 )
            {
                $data['accountnumber'] = xrowEPaymentGateway::decryptData( $data['accountnumber'] );
            }
            if ( isset( $data['ecname'] ) and strlen( $data['ecname'] ) > 0 )
            {
                $data['ecname'] = xrowEPaymentGateway::decryptData( $data['ecname'] );
            }
            if ( isset( $data['bankcode'] ) and strlen( $data['bankcode'] ) > 0 )
            {
                $data['bankcode'] = xrowEPaymentGateway::decryptData( $data['bankcode'] );
            }
        } 
        else
        {
            if ( isset( $data['number'] ) and strlen( $data['number'] ) > 0 )
            {
                $data['number'] = xrowEPaymentGateway::decryptData( $data['number'] );
            }
            if ( isset( $data['name'] ) and strlen( $data['name'] ) > 0 )
            {
                $data['name'] = xrowEPaymentGateway::decryptData( $data['name'] );
            }
            if ( isset( $data['securitycode'] ) and strlen( $data['securitycode'] ) > 0 )
            {
                $data['securitycode'] = xrowEPaymentGateway::decryptData( $data['securitycode'] );
            }
        }
        return $data;
    }
    

    function onPublish( $contentObjectAttribute, $contentObject, $publishedNodes )
    {
        $hasContent = $contentObjectAttribute->hasContent();
        if ( $hasContent )
        {
            $data = $contentObjectAttribute->content();
            if ( $data['type'] == self::EUROCARD )
            {
                if ( strlen( $data['ecname'] ) > 0 and $data['accountnumber'] > 0 and $data['bankcode'] > 0 )
                {    
                    $data['has_stored_card'] = 1; 
                }
                else
                {    
                    $data['has_stored_card'] = 0;
                }
            } 
            else
            {
                if ( $data['number'] > 0 and strlen( $data['name'] ) > 0 and strlen( $data['securitycode'] ) > 0 )
                {
                    $data['has_stored_card'] = 1; 
                }
                else
                {
                    $data['has_stored_card'] = 0;
                }
            }
            
            $data = ezcreditcardType::encodeData( $data );
            // define attributes for the node creditcard with the encrypt data, maybe we will need it later
            // it looks like <creditcard encryption='mcrypt' mode='..'>...</creditcard>
            $root_attribute = array();
            $xrowini = eZINI::instance( 'xrowecommerce.ini' );
            if ( $xrowini->hasVariable( 'EncryptionSettings', 'Mode' ) || $xrowini->hasVariable( 'EncryptionSettings', 'Algorithm' ) )
            {
                $root_attribute['encryption'] = 'mcrypt';
                if ( $xrowini->hasVariable( 'EncryptionSettings', 'Mode' ) )
                {
                    $root_attribute['mode'] = $xrowini->hasVariable( 'EncryptionSettings', 'Mode' );
                }
                if ( $xrowini->hasVariable( 'EncryptionSettings', 'Algorithm' ) )
                {
                    $root_attribute['algorithm'] = $xrowini->hasVariable( 'EncryptionSettings', 'Algorithm' );
                }
            }
            $doc = XROWRecurringordersCommonFunctions::createDOMTreefromArray( 'creditcard', $data, false, $root_attribute );
            $contentObjectAttribute->setAttribute( 'data_text', $doc->saveXML() );
            $contentObjectAttribute->store();
        }
    }

    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        if ( $http->hasPostVariable( 'ContentClass_ezcreditcard_gateway_' . $classAttribute->attribute( 'id' ) ) )
        {
            $gatewayArray = $http->postVariable( 'ContentClass_ezcreditcard_gateway_' . $classAttribute->attribute( 'id' ) );
            $classAttribute->setAttribute( self::CREDITCARD_GATEWAY_FIELD, serialize( $gatewayArray ) );
        }
        return true;
    }

    function objectAttributeContent( $contentObjectAttribute )
    {
        if ( isset( $GLOBALS['eZCreditcardCache'][$contentObjectAttribute->ID][$contentObjectAttribute->Version] ) )
        {
            return $GLOBALS['eZCreditcardCache'][$contentObjectAttribute->ID][$contentObjectAttribute->Version];
        } 
        else
        {
            $content = XROWRecurringordersCommonFunctions::createArrayfromXML( $contentObjectAttribute->attribute( 'data_text' ) );
            $content = ezcreditcardType::decodeData( $content );
            if ( isset( $content['type'] ) )
            {
                $content['type_name'] = ezcreditcardType::getCardTypeName( $content['type'] );
            }
            if ( isset( $content['has_stored_card'] ) and $content['has_stored_card'] == 1 )
            {
                $content['has_stored_card'] = 1; 
            }
            else
            {
                $content['has_stored_card'] = 0;
            }
            $GLOBALS['eZCreditcardCache'][$contentObjectAttribute->ID][$contentObjectAttribute->Version] = $content;
            return $content;
        }
    }

    function metaData( $contentObjectAttribute )
    {
        return false;
    }

    function classAttributeContent( $classAttribute )
    {
        if ( isset( $GLOBALS['xrowCCClassInfo'][$classAttribute->ID][$classAttribute->Version] ) )
        {
            return $GLOBALS['xrowCCClassInfo'][$classAttribute->ID][$classAttribute->Version];
        }
        $content = array();
        $content['gateway_array'] = xrowPaymentInfo::getGateways();
        $content['card_array'] = ezcreditcardType::getCardTypeName( - 1 );
        $cardGatewayArray = array();
        foreach ( $content['card_array'] as $cardKey => $card )
        {
            if ( count( $content['gateway_array'] ) > 0 )
            {
                foreach ( $content['gateway_array'] as $gatewayKey => $gateway )
                {
                    $payObj = xrowPaymentInfo::getInfoClassObj( $gateway['value'] );
                    if ( is_object( $payObj ) )
                    {
                        if ( $payObj->isCardAvailable( $cardKey ) == true )
                        {
                            $cardGatewayArray[$cardKey][$gateway['value']] = $gateway;
                        }
                    } 
                    else
                    {
                        eZDebug::writeError( 'PaymentInfo Object not found: ' . $gateway['value'] . 'Info', 'eZCreditcardType::classAttributeContent' );
                    }
                }
            } else
            {
                eZDebug::writeError( 'No gateways installed.', 'eZCreditcardType::classAttributeContent' );
            }
        }
        //eZDebug::writeDebug ( $cardGatewayArray, 'cardGatewayArray' );
        $content['card_gateway_array'] = $cardGatewayArray;
        $content['gateway'] = unserialize( $classAttribute->attribute( self::CREDITCARD_GATEWAY_FIELD ) );
        $GLOBALS['xrowCCClassInfo'][$classAttribute->ID][$classAttribute->Version] = $content;
        
        return $content;
    }

    /**
     * Returns the content of the string for use as a title
     */
    function title( $objectAttribute, $name = null )
    {
        return false;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return trim( $contentObjectAttribute->attribute( 'data_text' ) ) != '';
    }

    function isIndexable()
    {
        return false;
    }

    function isInformationCollector()
    {
        return false;
    }

    function sortKeyType()
    {
        return 'string';
    }

    function diff( $old, $new, $options = false )
    {
        include_once ('lib/ezdiff/classes/ezdiff.php');
        $diff = new eZDiff( );
        $diff->setDiffEngineType( $diff->engineType( 'text' ) );
        $diff->initDiffEngine();
        $diffObject = $diff->diff( $old->content(), $new->content() );
        return $diffObject;
    }

    /**
     *  returns the name of the creditcard if $type is found and positve
     *  if type is -1 the array of available types
     *  This can be called like ezcreditcardType::getCardTypeName( $type )
     */
    function getCardTypeName( $type = null )
    {
        if ( ! isset( $GLOBALS['xrowCreditCardArray'] ) )
        {
            $GLOBALS['xrowCreditCardArray'] = array( 
                self::MASTERCARD => ezpI18n::tr( 'kernel/classes/datatypes', 'Mastercard' ) , 
                self::VISA => ezpI18n::tr( 'kernel/classes/datatypes', 'Visa' ) , 
                self::DISCOVER => ezpI18n::tr( 'kernel/classes/datatypes', 'Discover' ) , 
                self::AMERICANEXPRESS => ezpI18n::tr( 'kernel/classes/datatypes', 'American Express' ) , 
                self::EUROCARD => ezpI18n::tr( 'kernel/classes/datatypes', 'Debit card' ) 
            );
        }
        if ( $type == -1 )
        {
            return $GLOBALS['xrowCreditCardArray'];
        } 
        elseif ( isset( $GLOBALS['xrowCreditCardArray'][$type] ) )
        {
            return $GLOBALS['xrowCreditCardArray'][$type];
        } 
        else
        {
            eZDebug::writeError( 'Card type not found.', 'ezcreditcardtype' );
            return false;
        }
    }

    /**
     *  returns true, if an user has a stored card
     *  @access public
     */
    function hasStoredCard( $user = false )
    {
        if ( $user == false )
        {
            $user = eZUser::currentUser();
        }
        if ( ! $user->isLoggedIn() )
        {
            return false;
        }
        $userObj = $user->contentObject();
        if ( ! is_object( $userObj ) )
        {
            return false;
        }
        $contentObjectAttributes = $userObj->contentObjectAttributes();
        foreach ( $contentObjectAttributes as $key => $attribute )
        {
            if ( $attribute->DataTypeString == self::DATA_TYPE_STRING )
            {
                $content = $attribute->content();
                if ( isset( $content['has_stored_card'] ) and $content['has_stored_card'] == 1 )
                {
                    return true;
                }
                else
                {
                    return false;
                }
            }
        }
        return false;
    }
    /// \privatesection
    var $hasOrderCollection;
}
eZDataType::register( ezcreditcardType::DATA_TYPE_STRING, 'ezcreditcardtype' );
?>
