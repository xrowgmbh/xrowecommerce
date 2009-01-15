<?php

class xrowProductTextType extends xrowProductDataType
{
    const DATA_TYPE_STRING = "text";

    function xrowProductTextType()
    {
        $this->Table = 'xrowproduct_data';

        // definition of column
        $this->ColumnArray = array( 0 => array( 'sql_type' => 'TEXT',
                                                'type' => 'text',
                                                'name' => '' ) );

         // params
        $params = array( 'translation_allowed' => true,
                         'unique' => false,
                         'required' => false );

        $this->xrowProductDataType( self::DATA_TYPE_STRING,
                                    ezi18n( 'extension/xrowecommerce/productvariation', "Text block", 'Datatype name' ),
                                    ezi18n( 'extension/xrowecommerce/productvariation', "Stores text of the variation", 'Datatype description' ),
                                    $params );
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
        }
    }
}

xrowProductDataType::register( xrowProductTextType::DATA_TYPE_STRING, "xrowProductTextType" );

?>
