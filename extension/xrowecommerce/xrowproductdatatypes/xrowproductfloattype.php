<?php

class xrowProductFloatType extends xrowProductDataType
{
    const DATA_TYPE_STRING = "float";

    function xrowProductFloatType()
    {
         $this->Table = 'xrowproduct_data';

        // definition of column
        $this->ColumnArray = array( 0 => array( 'sql_type' => 'FLOAT',
                                                'type' => 'number',
                                                'name' => '' ) );

         // params
        $params = array( 'translation_allowed' => false,
                         'unique' => false,
                         'required' => false );

        $this->xrowProductDataType( self::DATA_TYPE_STRING,
                                    ezi18n( 'extension/xrowecommerce/productvariation', "Float", 'Datatype name' ),
                                    ezi18n( 'extension/xrowecommerce/productvariation', "Stores a floating-point number.", 'Datatype description' ),
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

        $requiredKey = $key . 'required';
        $result['translation'] = false;
        if ( $http->hasPostVariable( $requiredKey ) )
            $result['required'] = true;
        else
            $result['required'] = false;

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
        if ( ( $attribute['required'] and !isset( $variationArray[$line][$column] ) )
               or ( $attribute['required'] and strlen( $variationArray[$line][$column] ) == 0 ) )
        {
            $errorArray[$line][$column]['required'] = true;
            return;
        }

        if ( !isset( $variationArray[$line][$column] ) )
            return;

        $content = trim( $variationArray[$line][$column] );

        if ( strlen( $content ) == 0 )
            return;

        $locale = eZLocale::instance();
        $content = str_replace(" ", "", $content );
        $content = $locale->internalNumber( $content );

        $validator = new eZFloatValidator( false, false );
        $ok = $validator->validate( $content );
        if ( $ok !== eZInputValidator::STATE_ACCEPTED )
        {
            $errorArray[$line][$column]['not_valid'] = true;
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
        $content = 0;
    	if ( isset( $data[$line][$column] ) )
        {
            $content = $data[$line][$column];

	        $locale = eZLocale::instance();
	        $content = str_replace(" ", "", $content );
	        $content = $locale->internalNumber( $content );
        }
        $variation->setAttribute( $column, $content );
    }
}

xrowProductDataType::register( xrowProductFloatType::DATA_TYPE_STRING, "xrowProductFloatType" );

?>
