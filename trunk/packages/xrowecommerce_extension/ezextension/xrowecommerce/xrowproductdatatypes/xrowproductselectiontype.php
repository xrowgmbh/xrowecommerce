<?php

class xrowProductSelectionType extends xrowProductDataType
{
    const DATA_TYPE_STRING = "selection";

    function xrowProductSelectionType()
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
                                    ezi18n( 'extension/xrowecommerce/productvariation', "Selection", 'Datatype name' ),
                                    ezi18n( 'extension/xrowecommerce/productvariation', "Stores a selection like colours green, blue, red....", 'Datatype description' ),
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
        $attributeID = $attribute->attribute( 'id' );
        $content = false;
        if ( isset( $template->Data['attributes'][$attributeID]['default'] ) )
        {
            $content = $template->Data['attributes'][$attributeID]['default'];
        }

        if ( $content !== false and strlen( $content ) > 0 )
        {
            $validator = new eZIntegerValidator( false, false );
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

    /**
     * Returns the content for the option name field
     *
     * @param xrowProductData $variation
     * @param string $column
     * @return string
     */
    public function metaName( xrowProductData $variation, $column )
    {
        return false;
    }
}

?>
