<?php

class xrowProductPriceType extends xrowProductDataType
{
    const DATA_TYPE_STRING = "price";

    function xrowProductPriceType()
    {
        $this->Table = 'xrowproduct_data';

        // definition of column
        $this->ColumnArray = array( 0 => array( 'sql_type' => 'INTEGER',
                                                'type' => 'number',
                                                'name' => '' ) );

         // params
        $params = array( 'translation_allowed' => false,
                         'unique' => true,
                         'required' => true );

        $this->xrowProductDataType( self::DATA_TYPE_STRING,
                                    ezi18n( 'extension/xrowecommerce/productvariation', "Price", 'Datatype name' ),
                                    ezi18n( 'extension/xrowecommerce/productvariation', "Stores price and the SKU of the variation", 'Datatype description' ),
                                    $params );
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

        if ( !isset( $template->Data['attributes'][$id] ) )
            $template->Data['attributes'][$id] = array();
        $result =& $template->Data['attributes'][$id];

        $key = "XrowProductTemplate_" . $id . "_";

        $defaultKey = $key . 'default';
        if ( $http->hasPostVariable( $defaultKey ) )
            $result['default_value'] = trim( $http->postVariable( $defaultKey ) );

        $locale = eZLocale::instance();
        $result['default_value'] = str_replace(" ", "", $result['default_value'] );
        $result['default_value'] = $locale->internalNumber( $result['default_value'] );

        $result['translation'] = false;
        $result['required'] = true;
        $result['search'] = true;

        $slidingKey = $key . 'sliding';
        if ( $http->hasPostVariable( $slidingKey ) )
            $result['sliding'] = true;
        else
            $result['sliding'] = false;
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
        $attributeID = $attribute->attribute( 'id' );
        $content = false;
        if ( isset( $template->Data['attributes'][$attributeID]['default_value'] ) )
        {
            $content = $template->Data['attributes'][$attributeID]['default_value'];
        }

        if ( $content !== false and strlen( $content ) > 0 )
        {
            $validator = new eZFloatValidator( false, false );
            $ok = $validator->validate( $content );
            if ( $ok === eZInputValidator::STATE_ACCEPTED )
                return true;
            else
            {
                $error[$attributeID]['default_value'] = true;
                return false;
            }
        }
        return true;
    }

    function validateVariationInput( array $variationArray,
                                     $line,
                                     $column,
                                     eZContentObjectAttribute $contentObjectAttribute,
                                     $attribute,
                                     eZHTTPTool $http,
                                     array &$errorArray )
    {

        if ( !isset( $variationArray[$line][$column] ) or
             count( $variationArray[$line][$column] ) == 0 )
        {
            $errorArray[$line][$column]['required'] = true;
            return;
        }

        $contentArray = $variationArray[$line][$column];

        $ini = eZINI::instance( 'xrowproduct.ini' );
        $currencyArray = $ini->variable( 'PriceSettings', 'CountryArray' );
        $countryList = array_keys( $currencyArray );

        $locale = eZLocale::instance();
        $validator = new eZFloatValidator( false, false );

        if ( !in_array( 1, $contentArray['amount'] ) and
             !in_array( "1", $contentArray['amount'] ) )
        {
        	$errorArray[$line][$column]['amount_1'] = true;
        }
        $validator = new eZFloatValidator( false, false );
        foreach ( $contentArray['amount'] as $key => $amount )
        {
            if ( !is_numeric( $amount ) )
                $errorArray[$line][$column]['amount'][$amount] = true;

            foreach( $countryList as $country )
            {
                $price = str_replace( " ", "", $contentArray[$country][$key] );
                $price = $locale->internalNumber( $price );

                $ok = $validator->validate( $price );
                if ( $ok == eZInputValidator::STATE_INVALID )
				{
				    $errorArray[$line][$column]['price'][$key][$country] = true;
				    $errorArray[$line][$column]['not_valid'] = true;
				}
            }
        }
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
    function fetchVariationInput( array &$data,
                                  $line,
                                  $column,
                                  xrowProductData &$variation,
                                  eZContentObjectAttribute &$contentObjectAttribute,
                                  xrowProductAttribute &$productAttribute,
                                  eZHTTPTool $http )
    {
        if ( isset( $data[$line][$column] ) )
        {
            // create ID
            $priceID = (int) $variation->attribute( $column );
            $newEntry = false;
        	if ( !$priceID )
        	{
        		$priceID = xrowProductPriceID::create();
        		$variation->setAttribute( $column, $priceID );
        		$newEntry = true;
        	}

        	$contentArray = $data[$line][$column];

            $ini = eZINI::instance( 'xrowproduct.ini' );
            $currencyArray = $ini->variable( 'PriceSettings', 'CountryArray' );
            $countryList = array_keys( $currencyArray );
            $priceArray = array();

            $locale = eZLocale::instance();

            $existingEntries = array();
            $existingEntriesRaw = array();
            if ( !$newEntry )
                $existingEntriesRaw = xrowProductPrice::fetchList( array( 'price_id' => $priceID ),
                                                                   true,
                                                                   false,
                                                                   false,
                                                                   array( 'amount' => 'asc' ) );

            if ( count( $existingEntriesRaw ) > 0 )
            {
                foreach( $existingEntriesRaw as $item )
                {
                	$amount = $item->attribute( 'amount' );
                	$country = $item->attribute( 'country' );
                	$existingEntries[$amount][$country] = $item;
                }
            }

            foreach ( $contentArray['amount'] as $key => $amount )
            {
            	foreach( $countryList as $country )
            	{
            	   $price = str_replace(" ", "", $contentArray[$country][$key] );
                   $price = $locale->internalNumber( $price );

                   if ( isset( $existingEntries[$amount][$country] ) )
                   {
                        $priceItem = $existingEntries[$amount][$country];
                        unset( $existingEntries[$amount][$country] );
                   }
                   else
                   {
                   	   $row['country'] = $country;
	                   $row['amount'] = $amount;
	                   $row['price_id'] = $priceID;
	                   $priceItem = new xrowProductPrice( $row );
                   }

            	   $priceItem->setAttribute( 'price', $price );
                   $priceArray[$amount][$country] = $priceItem;
            	}
            	#ksort( $priceArray );
            }
            if ( count( $existingEntries ) > 0 )
            {
            	foreach( $existingEntries as $entry )
            	{
					foreach ( $entry as $item )
					{
					    if ( $item )
                            $item->remove();
					}
            	}
            }
            $GLOBALS['XrowProductPriceData'][$priceID] = $priceArray;
        }
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
        $id = $variation->attribute( $identifier );
        if ( $id > 0 )
        {
	        if ( !isset( $GLOBALS['XrowProductPriceData'][$id] ) )
	        {
	            $result = xrowProductPrice::fetchList( array( 'price_id' => $id ),
                                                       true,
                                                       false,
                                                       false,
                                                       array( 'amount' => 'asc' ) );
	            foreach( $result as $item )
	            {
	            	$amount = $item->attribute( 'amount' );
	            	$country = $item->attribute( 'country' );
	            	$GLOBALS['XrowProductPriceData'][$id][$amount][$country] = $item;
	            }
	        }
	        return $GLOBALS['XrowProductPriceData'][$id];
        }
        return null;
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
    	$content = self::variationContent( $variation, $column );
    	if ( count( $content ) > 0 )
    	{
    		foreach ( $content as $item )
    		{
    			foreach ( $item as $country )
    			{
    				$country->store();
    			}
    		}
    	}
    }

    /**
     * Deletes the content of the current datatype
     * Needed for complex datatypes
     *
     * @param mixed $data
     */
    function deleteVariation( $data )
    {
    	if ( $data > 0 )
    	{
    		$price = new xrowProductPriceID( $data );
    		if ( $price )
    		    $price->remove();
    	}
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
    function cloneVariation( $value,
                             xrowProductData $variation,
                             xrowProductAttribute $attribute,
                             xrowProductTemplate $template )
    {
        $result = xrowProductPrice::fetchList( array( 'price_id' => $value ),
                                                       true,
                                                       false,
                                                       false,
                                                       array( 'amount' => 'asc' ) );

        $priceID = xrowProductPriceID::create();
        foreach( $result as $item )
		{
		    $newItem = clone $item;
			$newItem->setAttribute( 'price_id', $priceID );
			$newItem->setAttribute( 'id', null );
		    $newItem->store();
		}
    	return $priceID;
    }

/**
     * Returns the metadata for the search engine if searching is allowed
     *
     * @param xrowProductData $variation
     * @param string $column
     * @param $attribute
     * @return string
     */
    function metaData( xrowProductData $variation, $column, $attribute )
    {
        $result = "";
        if ( isset( $attribute['search'] ) and $attribute['search'] )
        {
            $id = $variation->attribute( $column );
            $resultArray = xrowProductPrice::fetchList( array( 'price_id' => $id ),
                                                       true,
                                                       false,
                                                       false,
                                                       array( 'amount' => 'asc' ) );
        	foreach ( $resultArray as $item )
        	{
        		$result .= " " . $item->attribute( 'price' );
        	}
        	$result = trim( $result );
        }
        return $result;
    }
}

xrowProductDataType::register( xrowProductPriceType::DATA_TYPE_STRING, "xrowProductPriceType" );

?>
