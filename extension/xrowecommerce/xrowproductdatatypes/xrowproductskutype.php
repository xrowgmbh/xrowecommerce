<?php

class xrowProductSkuType extends xrowProductDataType
{
    const DATA_TYPE_STRING = "sku";

    function xrowProductSkuType()
    {
         $this->Table = 'xrowproduct_data';

        // definition of column
        $this->ColumnArray = array( 0 => array( 'sql_type' => 'VARCHAR(255)',
                                                'type' => 'text',
                                                'name' => '' ) );

         // params
        $params = array( 'translation_allowed' => false,
                         'unique' => true,
                         'required' => true );

        $this->xrowProductDataType( self::DATA_TYPE_STRING,
                                    ezi18n( 'extension/xrowecommerce/productvariation', "stockkeeping unit", 'Datatype name' ),
                                    ezi18n( 'extension/xrowecommerce/productvariation', "Stores the stockkeeping unit of the variation", 'Datatype description' ),
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
        if ( !isset( $variationArray[$line][$column] )
               or strlen( $variationArray[$line][$column] ) == 0 )
        {
            $errorArray[$line][$column]['required'] = true;
        }
        else
        {
        	if ( $attribute['unique_sku'] )
        	{
	        	$content = $variationArray[$line][$column];

	        	$db = eZDB::instance();

	        	$idsql = "";
	        	if ( isset( $variationArray[$line]['id'] ) )
	        	   $idsql = " AND a.id != '" . $db->escapeString( $variationArray[$line]['id'] ) . "' ";

	        	$sql = "SELECT
	        	           COUNT(*) counter
	        	        FROM
	        	           xrowproduct_data a,
	        	           ezcontentobject v
	        	        WHERE
	        	           a.`$column` = '" . $db->escapeString( $content ) . "'
	        	           $idsql
	        	           AND a.object_id = v.id
	        	           AND a.attribute_id != " . $contentObjectAttribute->attribute( 'id' ) . "
	        	           AND a.language_code = '" . $contentObjectAttribute->attribute( 'language_code' ) . "'
	        	           AND a.version = v.current_version
	        	           AND v.status = " . eZContentObject::STATUS_PUBLISHED . "
	        	           ";
	        	#eZDebug::writeDebug( $sql, 'dupe query' );
	        	$result = $db->arrayQuery( $sql );
	        	#eZDebug::writeDebug( $result, 'result' );
	        	if ( $result[0]['counter'] > 0 )
	        	   $errorArray[$line][$column]['dupe'] = true;

	            $skuArray = array();
	        	foreach( $variationArray as $line => $variation )
	            {
	                if ( !in_array( $variation[$column], $skuArray ) )
	            	   $skuArray[] = $variation[$column];
	            	else
	            	   $errorArray[$line][$column]['dupe'] = true;
	            }
	        }
        }
    }
}

xrowProductDataType::register( xrowProductSkuType::DATA_TYPE_STRING, "xrowProductSkuType" );

?>
