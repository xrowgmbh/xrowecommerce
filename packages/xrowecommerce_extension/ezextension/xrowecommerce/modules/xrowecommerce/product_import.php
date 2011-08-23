<?php



$http = eZHTTPTool::instance();
$Module = $Params['Module'];

$upload = false;
$errorArray = array();
$reportArray = array( 'total_lines' => 0,
                      'update_ok' => 0,
                      'same_price' => 0,
                      'empty_line' => 0,
                      'sku_not_found' => 0,
                      'no_number' => 0,
                      'new_price' => 0,
                      'new_sliding_price' => 0
                        );
$tpl = eZTemplate::factory();

if ( $http->hasPostVariable( 'ImportButton' ) )
{
    if ( !eZHTTPFile::canFetch( 'UploadCSVFile' ) )
    {
    	$errorArray[] = ezpI18n::tr( 'extension/xrowecommerce', 'Cannot fetch uploaded file, please choose a valid CSV file.' );
    }
    else
    {
		$binaryFile = eZHTTPFile::fetch( 'UploadCSVFile' );
	    $content = file_get_contents( $binaryFile->attribute( 'filename' ) );
        $extension = eZFile::suffix( $binaryFile->attribute( "original_filename" ) );

        if ( strtolower( $extension ) != 'csv' )
        {
        	$errorArray[] = ezpI18n::tr( 'extension/xrowecommerce', 'File has a wrong extension. Only CSV files are supported.' );
        }
        else
        {
        	#$country = $http->postVariable( 'Country' );
        	if ( mb_strlen( $content ) > 0 )
        	{
                $content = str_replace( "\r\n", "\n", $content );
                $content = mb_convert_encoding( $content, 'UTF-8', mb_detect_encoding( $content, 'UTF-8, ISO-8859-1', true ) );
                $contentArray = explode( "\n", $content );
                $reportArray['total_lines'] = count( $contentArray );
                $xINI = eZINI::instance( 'xrowproduct.ini' );
                $separator = $xINI->variable( 'ImportSettings', 'Separator' );
                
				$firstLine = array_shift( $contentArray );
				$keyArray = explode( $separator, $firstLine );
				$importFieldArray = array();
				foreach ( $keyArray as $key => $field )
				{
					$field = trim( $field, '"' );
					if ( xrowProductAttribute::checkColumnName( $field ) ||  $field == 'id' || $field == 'placement' )
					{
						$importFieldArray[$key] = $field;
					}
				}
				if ( count( $importFieldArray ) > 0 )
				{
					foreach ( $contentArray as $line )
	                {
	                    $lineArray = explode( $separator, $line );
	                    
	                    if ( count( $lineArray ) >= 2 )
	                    {
	                    	$pv = xrowProductData::fetch( $lineArray[0] );
	                    	if ( $pv instanceof xrowProductData )
	                    	{
	                    		# start the import
	                    		foreach ( $keyArray as $key => $field )
	                    		{
	                    			if ( isset( $importFieldArray[$key] ) )
	                    			{
	                    				if ( $importFieldArray[$key] != 'price' && $importFieldArray[$key] != 'id' )
	                    				{
	                    					$pv->setAttribute( $importFieldArray[$key], $lineArray[$key] ); 
	                    				}
	                    			}
	                    		}
	                    		$pv->store();
	                    	}	
	                    }
	                    else
	                    {
	                    	$reportArray['empty_line']++;
	                    }
	                }
	                eZContentCacheManager::clearAllContentCache();
	        	}					
			}
                
        	else
        	{
        		$errorArray[] = ezpI18n::tr( 'extension/xrowecommerce', 'Empty file uploaded.' );
        	}
        }

	    #eZDebug::writeDebug( $content, 'content' );
	    $upload = true;
	    #$tpl->setVariable( "view_parameters", $viewParameters );
	    #$tpl->setVariable( "section", $section );
    }
    
}

if ( $Params['ObjectID'] and is_numeric( $Params['ObjectID'] ) )
{
	$obj = eZContentObject::fetch( $Params['ObjectID'] );
	if ( $obj instanceof eZContentObject )
	{
		$mn = $obj->mainNode();
		$url = $mn->urlAlias();
		eZURI::transformURI( $url );
		$http->redirect( $url );
	}
}

#$tpl->setVariable( "upload", $upload );
#$tpl->setVariable( "error_array", $errorArray );
#$tpl->setVariable( "report_array", $reportArray );

#$Result = array();
#$Result['content'] = $tpl->fetch( "design:xrowecommerce/product_import.tpl" );
#$Result['path'] = array( array( 'url' => false,
#                                'text' => ezpI18n::tr( 'extension/xrowecommerce', 'Product data import' ) ) );



?>