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
                    if ( isset( $importFieldArray['id'] ) )
                    {
                        # update existing entries
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
                                                $pv->setAttribute( $importFieldArray[$key], trim( $lineArray[$key], '"' ) );
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
                    }
                    else
                    {
                        # add new lines
                        $countryList = xrowProductPriceType::countryList();
                        $locale = eZLocale::instance();
                        if ( $obj instanceof eZContentObject )
                        {
                            $dm = $obj->dataMap();
                            foreach ( $dm as $attr )
                            {
                                if ( $attr->attribute( 'data_type_string' ) == 'xrowproductvariation' )
                                {
                                    # found attribute

                                    $id = $attr->attribute( 'id' );
                                    $version = $attr->attribute( 'version' );
                                    $languageCode = $attr->attribute( 'language_code' );

                                    $templateID = $attr->attribute( 'data_int' );


                                    $objectID = $attr->attribute( 'contentobject_id' );
                                    $contentClassAttributeID = $attr->attribute( 'contentclassattribute_id' );

                                    $row['attribute_id'] = $attr->attribute( 'id' );
                                    $row['language_code'] = $languageCode;
                                    $row['version'] = $version;
                                    $row['object_id'] = $objectID;
                                    $row['template_id'] = $templateID;
                                    $row['contentclassattribute_id'] = $contentClassAttributeID;

                                    foreach ( $contentArray as $line )
                                    {
                                        if ( mb_strlen( trim( $line ) ) > 0 )
                                        {
                                            $row['placement'] = 0;

                                            $lineArray = explode( $separator, $line );
                                            foreach ( $keyArray as $key => $field )
                                            {
                                                if ( isset( $importFieldArray[$key] ) )
                                                {
                                                    if ( $importFieldArray[$key] != 'price' )
                                                    {
                                                        $row[$field] = trim( $lineArray[$key], '"' );
                                                    }
                                                }
                                            }

                                            $currentData = new xrowProductData( $row );
                                            $priceID = xrowProductPriceID::create();
                                            $currentData->setAttribute( 'price', $priceID );
                                            $prow = array();

                                            foreach( $countryList as $country )
                                            {
                                               $prow['country'] = $country;
                                               $prow['amount'] = 1;
                                               $prow['price_id'] = $priceID;
                                               $prow['price'] = 0;
                                               $priceItem = new xrowProductPrice( $prow );
                                               $priceItem->store();
                                               unset( $priceItem );
                                            }
                                            $currentData->store();
                                            unset( $currentData );
                                            unset( $priceID );
                                        }
                                    }
                                    break;
                                }
                            }
                        }
                        eZContentCacheManager::clearAllContentCache();
                    }
                }
            }

            else
            {
                $errorArray[] = ezpI18n::tr( 'extension/xrowecommerce', 'Empty file uploaded.' );
            }
        }
        $upload = true;
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

?>