<?php

class xrowBillingCycleType extends eZDataType
{
    const DATA_TYPE_STRING = "xrowbillingcycle";
    function xrowBillingCycleType()
    {
        $this->eZDataType( xrowBillingCycleType::DATA_TYPE_STRING, ezi18n( 'kernel/classes/datatypes', "Billing cycle", 'Datatype name' ),
                           array( 'serialize_supported' => true,
                                  'object_serialize_map' => array( 'data_int' => 'period', 'data_text' => 'quantity' ) ) );
    }

    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_billingcycle_period_" . $contentObjectAttribute->attribute( "id" ) )
             AND $http->hasPostVariable( $base . "_data_billingcycle_quantity_" . $contentObjectAttribute->attribute( "id" ) ))
        {
            $period = trim ( $http->postVariable( $base . "_data_billingcycle_period_" . $contentObjectAttribute->attribute( "id" ) ) );
            $quantity = trim ( $http->postVariable( $base . "_data_billingcycle_quantity_" . $contentObjectAttribute->attribute( "id" ) ) );

            $state = $this->validateHTTPInput( $period, $quantity );
            return $state;
        }
        else
            return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    function initializeObjectAttribute( $contentObjectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $period = $originalContentObjectAttribute->attribute( "data_int" );
            $contentObjectAttribute->setAttribute( "data_int", $period );

            $quantity = $originalContentObjectAttribute->attribute( "data_text" );
            $contentObjectAttribute->setAttribute( "data_text", $quantity );
        }
        else
        {
            $contentObjectAttribute->setAttribute( "data_int", XROWRECURRINGORDER_CYCLE_ONETIME );
            $contentObjectAttribute->setAttribute( "data_text", 0 );
        }
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_billingcycle_period_" . $contentObjectAttribute->attribute( "id" ) )
             AND $http->hasPostVariable( $base . "_data_billingcycle_quantity_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $period = $http->postVariable( $base . "_data_billingcycle_period_" . $contentObjectAttribute->attribute( "id" ) );
            $period = trim( $period ) != '' ? $period : null;

            $quantity = $http->postVariable( $base . "_data_billingcycle_quantity_" . $contentObjectAttribute->attribute( "id" ) );
            $quantity = trim( $quantity ) != '' ? $quantity : null;

            $contentObjectAttribute->setAttribute( "data_int", $period );
            $contentObjectAttribute->setAttribute( "data_text", $quantity );
            return true;
        }
        return false;
    }

    /*!
     \reimp
    */
    function validateCollectionAttributeHTTPInput( $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_billingcycle_period_" . $contentObjectAttribute->attribute( "id" ) )
             AND $http->hasPostVariable( $base . "_data_billingcycle_quantity_" . $contentObjectAttribute->attribute( "id" ) ))
        {
            $period = $http->postVariable( $base . "_data_billingcycle_period_" . $contentObjectAttribute->attribute( "id" ) );
            $period = str_replace(" ", "", $period );

            $quantity = $http->postVariable( $base . "_data_billingcycle_quantity_" . $contentObjectAttribute->attribute( "id" ) );
            $quantity = str_replace(" ", "", $quantity );

            $classAttribute = $contentObjectAttribute->contentClassAttribute();

            if ( $period == "" or $quantity == "" )
            {
                if ( $contentObjectAttribute->validateIsRequired() )
                {
                    $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Input required.' ) );
                    return EZ_INPUT_VALIDATOR_STATE_INVALID;
                }
                else
                    return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
            }
            else
            {
                return $this->validateHTTPInput( $period, $quantity );
            }
        }
        else
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
    }

    function validateHTTPInput ( $period, $quantity )
    {
        if ( !is_numeric( $period ) OR $period < 0 OR $period > 5 )
           return EZ_INPUT_VALIDATOR_STATE_INVALID;

        if ( !is_numeric( $quantity ) OR $quantity < 0 )
            return EZ_INPUT_VALIDATOR_STATE_INVALID;

        return EZ_INPUT_VALIDATOR_STATE_ACCEPTED;
    }

    /*!
     Fetches the http post variables for collected information
    */
    function fetchCollectionAttributeHTTPInput( $collection, $collectionAttribute, $http, $base, $contentObjectAttribute )
    {
        if ( $http->hasPostVariable( $base . "_data_billingcycle_period_" . $contentObjectAttribute->attribute( "id" ) )
             AND $http->hasPostVariable( $base . "_data_billingcycle_quantity_" . $contentObjectAttribute->attribute( "id" ) ) )
        {
            $period = $http->postVariable( $base . "_data_billingcycle_period_" . $contentObjectAttribute->attribute( "id" ) );
            $period = str_replace(" ", "", $period );

            $quantity = $http->postVariable( $base . "_data_billingcycle_quantity_" . $contentObjectAttribute->attribute( "id" ) );
            $quantity = str_replace(" ", "", $quantity );
            $collectionAttribute->setAttribute( "data_int", $period );
            $collectionAttribute->setAttribute( "data_text", $quantity );
            return true;
        }
        return false;
    }

    /*!
     Clears cache
    */
    function storeObjectAttribute( $contentObjectAttribute )
    {
        if ( isset( $GLOBALS['xrowBillingCycleCache'][$contentObjectAttribute->ContentObjectID][$contentObjectAttribute->Version] ) )
            unset( $GLOBALS['xrowBillingCycleCache'][$contentObjectAttribute->ContentObjectID][$contentObjectAttribute->Version] );
    }

    function storeClassAttribute( $attribute, $version )
    {
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $contentObjectAttribute )
    {
        if ( isset( $GLOBALS['xrowBillingCycleCache'][$contentObjectAttribute->ContentObjectID][$contentObjectAttribute->Version] ) )
        {
            return $GLOBALS['xrowBillingCycleCache'][$contentObjectAttribute->ContentObjectID][$contentObjectAttribute->Version];
        }
        else
        {
            $period     = $contentObjectAttribute->attribute( "data_int" );
            $quantity   = $contentObjectAttribute->attribute( "data_text" );
            $content    = new xrowBillingCycle ( $period, $quantity );
            $GLOBALS['xrowBillingCycleCache'][$contentObjectAttribute->ContentObjectID][$contentObjectAttribute->Version] = $content;
            return $content;
        }
    }


    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        $result = array ( 'period' => $contentObjectAttribute->attribute( "data_int" ),
                          'quantity' => $contentObjectAttribute->attribute( "data_text" ) );
        return $result;
    }

    /*!
     Returns the integer value.
    */
    function title( $contentObjectAttribute, $name = null )
    {
        $period = $contentObjectAttribute->attribute( "data_int" );
        $quantity = $contentObjectAttribute->attribute( "data_text" );
        $textField = XROWRecurringOrderCollection::getBillingCycleText( $period, $quantity );

        return $quantity . ' ' . $$textField;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return true;
    }

    function isInformationCollector()
    {
        return true;
    }

    /*!
     \return true if the datatype can be indexed
    */
    function isIndexable()
    {
        return true;
    }

    function sortKey( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( 'data_int' );
    }

    function sortKeyType()
    {
        return 'int';
    }
}

eZDataType::register( xrowBillingCycleType::DATA_TYPE_STRING, "xrowbillingcycletype" );

?>
