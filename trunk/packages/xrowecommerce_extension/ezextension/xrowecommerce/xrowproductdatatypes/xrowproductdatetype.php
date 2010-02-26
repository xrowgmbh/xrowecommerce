<?php

class xrowProductDateType extends xrowProductDataType
{
    const DATA_TYPE_STRING = "date";

    function xrowProductDateType()
    {
         $this->Table = 'xrowproduct_data';

        // definition of column
        $this->ColumnArray = array( 0 => array( 'sql_type' => 'DATE',
                                                'type' => 'string',
                                                'name' => '' ) );

         // params
        $params = array( 'translation_allowed' => false,
                         'unique' => false,
                         'required' => false );

        $this->xrowProductDataType( self::DATA_TYPE_STRING,
                                    ezi18n( 'extension/xrowecommerce/productvariation', "Date", 'Datatype name' ),
                                    ezi18n( 'extension/xrowecommerce/productvariation', "Stores a date value.", 'Datatype description' ),
                                    $params );
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
        /**
          * @todo
          */
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
        if ( ( $attribute['required'] and !isset( $variationArray[$line][$column] ) )
               or ( $attribute['required'] and strlen( $variationArray[$line][$column] ) == 0 ) )
        {
            $errorArray[$line][$column]['required'] = true;
            return;
        }

        if ( !isset( $variationArray[$line][$column] ) )
        {
            return;
        }

        $year = $month = $day = false;
        self::extractDate( $variationArray[$line][$column], $year, $month, $day );
        
        if ( !checkdate( self::addZero( $month ), self::addZero( $day ), $year ) )
        {
        	$errorArray[$line][$column]['not_valid'] = true;
        }
    }
    
    public static function extractDate( $content, &$year = false, &$month = false, &$day = false )
    {
        $content = str_replace( " ", '', $content );

        if ( strlen( $content ) == 0 )
        {
            return;
        }

        if ( is_numeric( $content ) )
        {
        	settype( $content, 'string' );
        	$year = substr( 0, 4, $content );
        	$month = substr( 4, 2, $content );    
        	$day = substr( 6, 2, $content );
        }
        else
        {
	        $contentArray = explode( "-", $content );
	        if ( count( $contentArray ) == 3 )
	        {
	            $year = $contentArray[0];
	            $month = $contentArray[1];
	            $day = $contentArray[2];
	        }
	        else
	        {
	            $contentArray = explode( ".", $content );
	            if ( count( $contentArray ) == 3 )
	            {
		            $year = $contentArray[2];
		            $month = $contentArray[1];
		            $day = $contentArray[0];
	            }
	        }
        }
        if ( $year > 0 and $year < 100 )
        {
        	$year += 1900;
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
    function fetchVariationInput( array &$data, $line, $column, xrowProductData &$variation, eZContentObjectAttribute &$contentObjectAttribute, xrowProductAttribute &$productAttribute, eZHTTPTool $http )
    {
        if ( isset( $data[$line][$column] ) )
        {
            $year = $month = $day = false;
            self::extractDate( $data[$line][$column], $year, $month, $day );
            
	        if ( checkdate( self::addZero( $month ), self::addZero( $day ), $year ) )
	        {
	            $contentdate = $year . self::addZero( $month ) . self::addZero( $day );
	            eZDebug::writeDebug( $contentdate, 'date' );
	        	$variation->setAttribute( $column, $contentdate );
	        }
	        else
	        {
	        	eZDebug::writeDebug( $data[$line][$column], 'no valid date to store' );
	        }
        }
    }
    
    public static function addZero( $value )
    {
        return sprintf("%02d", $value);
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
        $content = $variation->attribute( $identifier );
    	$year = $month = $day = false;
        self::extractDate( $content, $year, $month, $day );
    	
    	$isValid = false;
    	$timestamp = 0;
    	if ( checkdate( self::addZero( $month ), self::addZero( $day ), $year ) )
    	{
    		$isValid = true;
    		$timestamp = mktime( 0, 0, 0, $month, $day, $year );
    	}
    	$result = array( 
    	   'year' => $year, 
    	   'month' => $month, 
    	   'day' => $day, 
    	   'timestamp' => $timestamp, 
    	   'content' => $content,
    	   'is_valid' => $isValid 
    	);
        return $result;
    }
}

?>
