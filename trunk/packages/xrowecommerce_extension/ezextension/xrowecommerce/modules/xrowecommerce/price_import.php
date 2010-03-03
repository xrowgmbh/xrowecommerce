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
        	$country = $http->postVariable( 'Country' );
        	if ( mb_strlen( $content ) > 0 )
        	{
                $content = str_replace( "\r\n", "\n", $content );
                $content = mb_convert_encoding( $content, 'UTF-8', mb_detect_encoding( $content, 'UTF-8, ISO-8859-1', true ) );
                $contentArray = explode( "\n", $content );
                $reportArray['total_lines'] = count( $contentArray );
                $xINI = eZINI::instance( 'xrowproduct.ini' );
                $separator = $xINI->variable( 'ImportSettings', 'Separator' );
                $locale = eZLocale::instance();
                $validator = new eZFloatValidator( false, false );

                $priceIdentifier = xrowProductTemplate::findPriceAttributeIdentifier();

                foreach ( $contentArray as $line )
                {
                    $lineArray = explode( $separator, $line );
                    if ( count( $lineArray ) >= 2 )
                    {
                    	$sku = $lineArray[0];
                    	$productVariations = xrowProductData::fetchList( array( 'sku' => $sku ) );
                    	if ( count( $productVariations ) > 0 )
                    	{
	                    	foreach ( $productVariations as $variation )
	                        {
	                            $priceID = $variation->attribute( $priceIdentifier );
	                            $priceList = xrowProductPrice::fetchList( array( 'price_id' => $priceID, 'country' => $country ),
	                                                                      true,
											                              false,
											                              false,
											                              array( 'amount' => 'asc' ) );

                                #eZDebug::writeDebug( $priceList, 'price list' );
                                for ( $i = 1; $i < count( $lineArray ); $i++ )
	                            {
	                                $price = $locale->internalNumber( $lineArray[$i] );
	                                #eZDebug::writeDebug( $price, 'new price' );

							        $ok = $validator->validate( $price );
							        if ( $ok !== eZInputValidator::STATE_ACCEPTED )
							        {
							            $reportArray['no_number']++;
							        }
							        else
							        {
								        if ( isset( $priceList[$i-1] ) )
	                                    {
	                                        #eZDebug::writeDebug( $priceList[$i-1]->attribute( 'price' ), 'old price' );
	                                    	if ( $priceList[$i-1]->attribute( 'price' ) != $price )
	                                        {
	                                           $priceList[$i-1]->setAttribute( 'price', $price );
	                                           $priceList[$i-1]->store();
	                                           $reportArray['update_ok']++;
	                                        }
	                                        else
	                                        {
	                                            $reportArray['same_price']++;
	                                        }
	                                    }
	                                    else
	                                    {
	                                        /*
	                                         * Only import normal prices with 1 amount...
	                                         */
	                                        if ( count( $lineArray ) == 2 )
	                                        {
	                                            $row = array (
	                                                 'price_id' => $priceID,
	                                                 'amount' => 1,
	                                                 'country' => $country,
	                                                 'price' => $price
	                                            );
	                                            $priceObj = new xrowProductPrice( $row );
	                                            $priceObj->store();
	                                            $reportArray['new_price']++;
	                                        }
	                                        else
	                                        {
	                                        	$reportArray['new_sliding_price']++;
	                                        }
	                                    }
							        }
	                            }
	                        }
                    	}
                    	else
                    	{
                    		$reportArray['sku_not_found']++;
                    		$reportArray['sku_not_found_array'][] = $sku;
                    	}
                    }
                    else
                    {
                    	$reportArray['empty_line']++;
                    }
                }
                eZContentCacheManager::clearAllContentCache();
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

$tpl->setVariable( "upload", $upload );
$tpl->setVariable( "error_array", $errorArray );
$tpl->setVariable( "report_array", $reportArray );

$Result = array();
$Result['content'] = $tpl->fetch( "design:xrowecommerce/price_import.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'extension/xrowecommerce', 'Price import' ) ) );



?>