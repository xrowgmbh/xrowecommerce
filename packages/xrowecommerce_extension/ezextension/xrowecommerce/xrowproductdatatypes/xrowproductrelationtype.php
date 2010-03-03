<?php

class xrowProductRelationType extends xrowProductDataType
{
    const DATA_TYPE_STRING = "relation";

    function xrowProductRelationType()
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
                                    ezpI18n::tr( 'extension/xrowecommerce/productvariation', "Object relation", 'Datatype name' ),
                                    ezpI18n::tr( 'extension/xrowecommerce/productvariation', "Stores an object relation. (e.g. to an image)", 'Datatype description' ),
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
        $classesKey = $key . 'class';
        if ( $http->hasPostVariable( $classesKey ) )
            $result['class_array'] = $http->postVariable( $classesKey );
        else
            $result['class_array'] = array();

        if ( !is_array( $result['class_array'] ) )
            $result['class_array'] = array( $result['class_array'] );

        if ( in_array( -1, $result['class_array'] ) )
            $result['class_array'] = array();

        $requiredKey = $key . 'required';
        if ( $http->hasPostVariable( $requiredKey ) )
            $result['required'] = true;
        else
            $result['required'] = false;

        $translationKey = $key . 'translation';
        if ( $http->hasPostVariable( $translationKey ) )
            $result['translation'] = true;
        else
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

    function templateHTTPAction( xrowProductTemplate &$template,
                                 xrowProductAttribute &$attribute,
                                 eZHTTPTool $http,
                                 $languageCode,
                                 &$module,
                                 array $error,
                                 $action )
    {
        $id = $attribute->attribute( 'id' );
        $templateID = $template->attribute( 'id' );
        switch( $action )
        {
            case "set_object_relation":
            {
                $browseName = "TemplateCustomActionButton[$id][set_object_relation]";
                if ( $http->hasPostVariable( 'BrowseActionName' ) and
                     $http->postVariable( 'BrowseActionName' ) == $browseName and
                     $http->hasPostVariable( "SelectedNodeIDArray" ) )
                {
                    if ( !$http->hasPostVariable( 'BrowseCancelButton' ) )
                    {
                        $selectedNodeIDArray = $http->postVariable( "SelectedNodeIDArray" );
                        if ( isset( $template->AttributeList[$id]['translation'] )
                             and $template->AttributeList[$id]['translation'] )
                        {
                            $template->Data['attributes'][$id]['default_value_array'][$languageCode] = $selectedNodeIDArray[0];
                        }
                        else
                        {
                            $template->Data['attributes'][$id]['default_value'] = $selectedNodeIDArray[0];
                            $template->Data['attributes'][$id]['default_value_array'][$languageCode] = $selectedNodeIDArray[0];
                        }
                        $template->updateAttributes( $languageCode );
                    }
                }
            }break;
            case "browse":
            {
                $redirectionURI = 'productvariation/templateedit/' . $templateID . '/' . $languageCode;
                $browseName = "TemplateCustomActionButton[$id][set_object_relation]";
                $browseType = 'xrowProductVariationTemplateBrowse';

                eZContentBrowse::browse( array( 'action_name' => $browseName,
                                                'type' =>  $browseType,
                                                'browse_custom_action' => array( 'name' => $browseName,
                                                                                 'value' => 1 ),
                                                'persistent_data' => array( 'HasObjectInput' => 0 ),
                                                'from_page' => $redirectionURI ),
                                         $module );
            }break;
            case "remove":
            {
                $template->Data['attributes'][$id]['default_value_array'][$languageCode] = null;
                $template->Data['attributes'][$id]['default_value'] = null;
                $template->updateAttributes( $languageCode );
            }break;
        }
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
