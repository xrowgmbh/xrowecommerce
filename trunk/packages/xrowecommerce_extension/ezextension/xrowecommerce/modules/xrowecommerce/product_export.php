<?php

set_time_limit( 0 );

$http = eZHTTPTool::instance();
$Module = $Params['Module'];

$export = true;
$exportArray = array();

$tpl = eZTemplate::factory();

$defaultLanguage = eZContentLanguage::topPriorityLanguage();
$languageCode = $defaultLanguage->attribute( 'locale' );

$attributeList = xrowProductAttribute::fetchList( array(), true, false, false, array( 'name' => 'asc' ) );
$attributeCount = xrowProductAttribute::fetchListCount();

#if ( $http->hasPostVariable( 'ExportButton' ) )
#{
    $export = false;
    if ( isset( $Params['ObjectID'] ) && is_numeric( $Params['ObjectID'] ) )
    {
        $objID = $Params['ObjectID'];
        $obj = eZContentObject::fetch( $objID );
        if ( $obj instanceof eZContentObject )
        {
            $dm = $obj->dataMap();
            foreach ( $dm as $attr )
            {
                if ( $attr->attribute( 'data_type_string' ) == 'xrowproductvariation' )
                {
                    $export = true;
                    break;
                }
            }
        }
    }
    if ( $export )
    {
        $country = $http->postVariable( 'Country' );
        $xINI = eZINI::instance( 'xrowproduct.ini' );
        $separator = $xINI->variable( 'ExportSettings', 'Separator' );
        $lineEnding = $xINI->variable( 'ExportSettings', 'LineEnding' );
        $exportParentNodeID = $xINI->variable( 'ExportSettings', 'ExportNodeID' );
        $exportClasses = $xINI->variable( 'ExportSettings', 'ExportClassArray' );
        $precision = $xINI->variable( 'ExportSettings', 'Precision' );
        $skuField = $xINI->variable( 'ExportSettings', 'SKUIdentifier' );
        $priceField = $xINI->variable( 'PriceSettings', 'PriceIdentifier' );
        $decPoint = $xINI->variable( 'ExportSettings', 'DecimalPoint' );

        $defaultLanguage = eZContentLanguage::topPriorityLanguage();
        $languageCode = $defaultLanguage->attribute( 'locale' );
        $fieldArray = array();

        $exportFieldArray = array( 'id' => 'a.id',
                                   #'object_name' => '( SELECT name from ezcontentobject where id = a.object_id) as object_name',
                                   #'object_id' => 'a.object_id',
                                   'placement' => 'a.placement',
                                   #'attribute_id' => 'a.attribute_id',
                                   #'template_id' => 'template_id',
                                   #'template_name' => '( SELECT name from xrowproduct_template c WHERE c.id = a.template_id ) template_name',
#                                  'price' => "( SELECT REPLACE( ROUND( g.price, 2 ), '.', '$decPoint' ) FROM xrowproduct_price g WHERE g.price_id = a.price AND g.country LIKE '$country' AND g.amount = 1 ) price",
                                  );
        $exportAttrArray = array();

        $template = xrowProductData::fetchTemplateByObjectID( $objID );
        if ( $template instanceof xrowProductTemplate )
        {
            $template->updateAttributes();
            $fieldArray = $template->AttrIDList;
            foreach ( $fieldArray as $field )
            {
                if ( $field != 'price' )
                {
                    $exportFieldArray[$field] = 'a.' . $field;
                }
            }
        }

        $sql = "SELECT " . implode( ",\n", $exportFieldArray ) . "
                FROM
                    xrowproduct_data a,
                    ezcontentobject o
                WHERE
                    a.object_id = '$objID' AND
                    a.object_id = o.id AND
                    a.version = o.current_version AND
                    o.status = 1
                ORDER BY a.object_id, a.placement, a.id";

        #eZDebug::writeDebug( $sql, 'sql' );
        $db = eZDB::instance();
        $result = $db->arrayQuery( $sql );

        /**
         * Export all products, even hidden ones
         */
        if ( count( $result ) > 0 )
        {
            $content = implode( $separator, array_keys( $exportFieldArray ) );
            foreach ( $result as $row )
            {
                $content .= "\r\n" . '"' . implode( '"' . $separator . '"', $row ) . '"';
            }
            # I luv excel... doesn't understand utf8
            $content = utf8_decode( $content );
            $dir =  eZSys::cacheDirectory().'/';
            $file = $dir . date( "Y-m-d-H-i" ) . "-product-export-$objID.csv";
            @unlink( $file );
            eZFile::create( $file, false, $content );

            eZFile::download( $file );
            #$Module->redirectToView( 'productexport' );
        }

    }
    if ( $obj instanceof eZContentObject )
    {
        $mn = $obj->mainNode();
        $url = $mn->urlAlias();
        eZURI::transformURI( $url );
        return $http->redirect( $url );
    }
#}

$http->redirect( '/content/view/full/2' );


?>