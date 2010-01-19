<?php

class xrowProductBooleanType extends xrowProductDataType
{
    const DATA_TYPE_STRING = "boolean";

    function xrowProductBooleanType()
    {
         $this->Table = 'xrowproduct_data';

        // definition of column
        $this->ColumnArray = array( 0 => array( 'sql_type' => 'INTEGER',
                                                'type' => 'number',
                                                'name' => '' ) );

         // params
        $params = array( 'translation_allowed' => true,
                         'unique' => false,
                         'required' => false );

        $this->xrowProductDataType( self::DATA_TYPE_STRING,
                                    ezi18n( 'extension/xrowecommerce/productvariation', "Boolean", 'Datatype name' ),
                                    ezi18n( 'extension/xrowecommerce/productvariation', "Stores true or false.", 'Datatype description' ),
                                    $params );
    }

    /**
     * Returns the metadata for the search engine if searching is allowed
     *
     * @param xrowProductData $variation
     * @param string $column
     * @param $attribute
     * @return string
     */
    function metaData( xrowProductData $variation, $column )
    {
        return "";
    }

    /**
     * Returns the content for the option name field
     *
     * @param xrowProductData $variation
     * @param string $column
     * @return string
     */
    public function metaName( xrowProductData $variation, $column )
    {
        $content = $variation->attribute( $column );
        if ( $content == 1 )
            return ezi18n( 'extension/xrowcommerce/productvariation', 'yes' );
        else
            return ezi18n( 'extension/xrowcommerce/productvariation', 'no' );
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
            $content = 1;
        $variation->setAttribute( $column, $content );
    }
}

?>
