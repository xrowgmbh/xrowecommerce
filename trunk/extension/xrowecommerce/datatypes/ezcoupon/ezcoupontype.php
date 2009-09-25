<?php

class ezCouponType extends eZDataType
{
	const DISCOUNT_TYPE_PERCENT = 0;
	const DISCOUNT_TYPE_FLAT = 1;
	const DISCOUNT_TYPE_FREE_SHIPPING = 2;
	const DEFAULT_CURRENT_COUPON = 1;
	const DEFAULT_EMTPY = 0;

	const COUPON = 'ezcoupon';
	const COUPON_DEFAULT = 'data_int1';

    function ezCouponType()
    {
        $this->eZDataType( self::COUPON, ezi18n( 'kernel/classes/datatypes', "Coupon", 'Datatype name' ),
                           array( 'serialize_supported' => true ) );
    }


    function validateDateTimeHTTPInput( $day, $month, $year, &$contentObjectAttribute )
    {
        $state = eZDateTimeValidator::validateDate( $day, $month, $year );
        if ( $state == EZ_INPUT_VALIDATOR_STATE_INVALID )
        {
            $contentObjectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Date is not valid.' ) );
            return EZ_INPUT_VALIDATOR_STATE_INVALID;
        }
        return $state;
    }
    /*!
     Validates the input and returns true if the input was
     valid for this datatype.
    */
    function validateObjectAttributeHTTPInput( $http, $base, $objectAttribute )
    {
        $return = eZInputValidator::STATE_ACCEPTED;
        $id = $objectAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $base . '_coupon_year_' . $id  ) and
             $http->hasPostVariable( $base . '_coupon_month_' . $id ) and
             $http->hasPostVariable( $base . '_coupon_day_' . $id ) )
        {
            $year  = $http->postVariable( $base . '_coupon_year_' . $id );
            $month = $http->postVariable( $base . '_coupon_month_' . $id );
            $day   = $http->postVariable( $base . '_coupon_day_' . $id );
            $classAttribute = $objectAttribute->contentClassAttribute();

            if ( $year == '' or $month == '' or $day == '' )
            {
                if ( !( $year == '' and $month == '' and $day == '' ) or
                     $objectAttribute->validateIsRequired() )
                {
                    $objectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Missing date input.' ) );
                    $return = eZInputValidator::STATE_INVALID;
                }
            }
            else
            {
                if ( $this->validateDateTimeHTTPInput( $day, $month, $year, $objectAttribute ) == EZ_INPUT_VALIDATOR_STATE_INVALID )
                    $return = eZInputValidator::STATE_INVALID;
                $date = new eZDate();
                $date->setMDY( $month, $day, $year );
            }
        }

        if ( $http->hasPostVariable( $base . '_coupon_till_year_' . $id ) and
             $http->hasPostVariable( $base . '_coupon_till_month_' . $id ) and
             $http->hasPostVariable( $base . '_coupon_till_day_' . $id ) )
        {
            $year  = $http->postVariable( $base . '_coupon_till_year_' . $id );
            $month = $http->postVariable( $base . '_coupon_till_month_' . $id );
            $day   = $http->postVariable( $base . '_coupon_till_day_' . $id );
            $classAttribute = $objectAttribute->contentClassAttribute();

            if ( $year == '' or $month == '' or $day == '' )
            {
                if ( !( $year == '' and $month == '' and $day == '' ) or
                     $objectAttribute->validateIsRequired() )
                {
                    $objectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                         'Missing date input.' ) );
                    $return = eZInputValidator::STATE_INVALID;
                }
            }
            else
            {
                if ( $this->validateDateTimeHTTPInput( $day, $month, $year, $objectAttribute ) == eZInputValidator::STATE_INVALID )
                    $return = eZInputValidator::STATE_INVALID;
                $date2 = new eZDate();
                $date2->setMDY( $month, $day, $year );
            }
        }
        if ( is_object( $date ) and is_object( $date2 ) and ( $date->timeStamp() > $date2->timeStamp() or time() > $date2->timeStamp() ) )
        {
            $objectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Expiry date incorrect.' ) );
            $return = eZInputValidator::STATE_INVALID;
        }
        if ( $http->hasPostVariable( $base . "_coupon_discount_" . $objectAttribute->attribute( "id" ) ) )
        {
            $data = $http->postVariable( $base . "_coupon_discount_" . $objectAttribute->attribute( "id" ) );

            $locale = eZLocale::instance();
            $data = $locale->internalCurrency( $data );

            if( $objectAttribute->validateIsRequired() && ( $data == "" or  $data <= 0 ) )
            {
                $objectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'No discount set.' ) );
                $return = eZInputValidator::STATE_INVALID;
            }
            if ( !preg_match( "#^[0-9]+(.){0,1}[0-9]{0,2}$#", $data ) )
            {
                $return = eZInputValidator::STATE_INVALID;

                $objectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Invalid discount.' ) );
            }
            if ( preg_match( "#^[0-9]+(.){0,1}[0-9]{0,2}$#", $data ) and
                 (int)$http->postVariable( $base . "_coupon_discount_type_" . $id ) == self::COUPON_DISCOUNT_TYPE_PERCENT )
            {
                if( !( $data > 0 and $data < 100 ) )
                {
                   $objectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                                 'Give a discount value between nero and 100.' ) );
                   $return = eZInputValidator::STATE_INVALID;
                }
            }
        }
        if ( $http->hasPostVariable( $base . '_coupon_code_' . $id ) and
             $http->postVariable( $base . '_coupon_code_' . $id ) == "" )
        {
            $return = eZInputValidator::STATE_INVALID;
            $objectAttribute->setValidationError( ezi18n( 'kernel/classes/datatypes',
                                                          'Invalid coupon code.' ) );
        }
        return $return;
    }

    /*!
     Fetches the http post var integer input and stores it in the data instance.
    */
    function fetchObjectAttributeHTTPInput( $http, $base, $objectAttribute )
    {
        $id = $objectAttribute->attribute( 'id' );
    	if ( $http->hasPostVariable( $base . '_coupon_year_' . $id ) and
             $http->hasPostVariable( $base . '_coupon_month_' . $id ) and
             $http->hasPostVariable( $base . '_coupon_day_' . $id ) )
        {

            $year  = $http->postVariable( $base . '_coupon_year_' . $id );
            $month = $http->postVariable( $base . '_coupon_month_' . $id );
            $day   = $http->postVariable( $base . '_coupon_day_' . $id );
            $date = new eZDate();
            $contentClassAttribute = $objectAttribute->contentClassAttribute();

            if ( ( $year == '' and $month == '' and $day == '' ) or
                 !checkdate( $month, $day, $year ) or
                 $year < 1970 )
            {
                $date->setTimeStamp( 0 );
            }
            else
            {
                $date->setMDY( $month, $day, $year );
            }
        }
        if ( $http->hasPostVariable( $base . '_coupon_till_year_' . $id ) and
             $http->hasPostVariable( $base . '_coupon_till_month_' . $id ) and
             $http->hasPostVariable( $base . '_coupon_till_day_' . $id ) )
        {

            $year  = $http->postVariable( $base . '_coupon_till_year_' . $id );
            $month = $http->postVariable( $base . '_coupon_till_month_' . $id );
            $day   = $http->postVariable( $base . '_coupon_till_day_' . $id );
            $datetill = new eZDate();
            #$contentClassAttribute = $objectAttribute->contentClassAttribute();

            if ( ( $year == '' and $month == '' and $day == '' ) or
                 !checkdate( $month, $day, $year ) or
                 $year < 1970 )
            {
                $datetill->setTimeStamp( 0 );
            }
            else
            {
                $datetill->setMDY( $month, $day, $year );
            }
        }
        $type = (int)$http->postVariable( $base . '_coupon_discount_type_' . $id );

        $discount = $http->postVariable( $base . '_coupon_discount_' . $id );
        $discount = floatval( $discount );
        $objectAttribute->setAttribute( 'data_text', strtoupper( $http->postVariable( $base . '_coupon_code_' .
                                                                        $id ) ). ";" . $date->timeStamp().";".$datetill->timeStamp() );
        $objectAttribute->setAttribute( 'data_float', $discount );
        $objectAttribute->setAttribute( 'data_int', $type );
        return true;
    }

    /*!
     Returns the content.
    */
    function objectAttributeContent( $objectAttribute )
    {
        $tmp = $objectAttribute->attribute( 'data_text' );
        $tmparray = split(";",$tmp,3);
        $date = new eZDate( );
        $stamp = $tmparray[1];
        $date->setTimeStamp( $stamp );
        $date2 = new eZDate( );
        $stamp = $tmparray[2];
        $date2->setTimeStamp( $stamp );
        $coupon = array(
            'from' => $date,
            'till' => $date2,
            'discount' => $objectAttribute->attribute( 'data_float' ),
            'discount_type' => $objectAttribute->attribute( 'data_int' ),
            'code' => $tmparray[0]
        );
        return $coupon;
    }

    /*!
     Set class attribute value for template version
    */
    function initializeClassAttribute( $classAttribute )
    {
        if ( $classAttribute->attribute( self::COUPON_DEFAULT ) == null )
            $classAttribute->setAttribute( self::COUPON_DEFAULT, 0 );
        $classAttribute->store();
    }

    /*!
     Sets the default value.
    */
    function initializeObjectAttribute( $objectAttribute, $currentVersion, $originalContentObjectAttribute )
    {
        if ( $currentVersion != false )
        {
            $dataInt = $originalContentObjectAttribute->attribute( "data_int" );
            $objectAttribute->setAttribute( "data_int", $dataInt );
        }
        else
        {
            $contentClassAttribute = $objectAttribute->contentClassAttribute();
            $defaultType = $contentClassAttribute->attribute( self::COUPON_DEFAULT );
            if ( $defaultType == 1 )
                $objectAttribute->setAttribute( "data_int", time() );
        }
    }

    function fetchClassAttributeHTTPInput( $http, $base, $classAttribute )
    {
        $default = $base . "_ezcoupon_default_" . $classAttribute->attribute( 'id' );
        if ( $http->hasPostVariable( $default ) )
        {
            $defaultValue = $http->postVariable( $default );
            $classAttribute->setAttribute( self::COUPON_DEFAULT,  $defaultValue );
        }
        return true;
    }

    /*!
     \reimp
    */
    function isIndexable()
    {
        return true;
    }

    /*!
     Returns the meta data used for storing search indeces.
    */
    function metaData( $contentObjectAttribute )
    {
        $array = $contentObjectAttribute->objectAttributeContent( );
        $retVal = $array['code'];
        return $retVal;
    }

    /*!
     Returns the date.
    */
    function title( $objectAttribute, $name = null )
    {
        $array = $objectAttribute->objectAttributeContent();
        $retVal = $array['code'];
        return $retVal;
    }

    function hasObjectAttributeContent( $contentObjectAttribute )
    {
        return $contentObjectAttribute->attribute( "data_float" ) != 0;
    }

    /*!
     \reimp
    */
    function sortKey( $contentObjectAttribute )
    {
        return (int)$contentObjectAttribute->attribute( 'data_float' );
    }

    /*!
     \reimp
    */
    function sortKeyType()
    {
        return 'float';
    }
}

eZDataType::register( ezCouponType::COUPON, "ezcoupontype" );

?>
