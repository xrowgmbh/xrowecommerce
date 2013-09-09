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
$result = false;
$email = "";

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
            $sys = eZSys::instance();
            $storage_dir = $sys->cacheDirectory() . '/priceupload';
            
            if ( !file_exists( $storage_dir ) )
            {
                eZDir::mkdir( $storage_dir, false, true );
            }
            
            $fileName = $binaryFile->attribute( "filename" );

            $file = eZClusterFileHandler::instance( $fileName );
            
            // create dest filename in the same manner as eZHTTPFile::store()
            // grab file's suffix
            $fileSuffix = eZFile::suffix( $binaryFile->attribute( "original_filename" ) );
            #eZDebug::writeDebug( $fileSuffix );
            // prepend dot
            if ( $fileSuffix )
                $fileSuffix = '.' . $fileSuffix;
            // grab filename without suffix
            $fileBaseName = basename( $fileName, $fileSuffix );
            // create dest filename
            $newFileName = md5( $fileBaseName . microtime() . mt_rand() ) . $fileSuffix;
            eZDebug::writeDebug( $newFileName );

            // rename the file, and update the database data
            $newFilePath = $storage_dir . '/' . $newFileName;
            $file->move( $newFilePath );
            chmod($newFilePath, 0666);
            
            $country = $http->postVariable( 'Country' );
            $email = $http->postVariable( "Email" );
            
            if ( mb_strlen( $content ) > 0 )
            {
                $db = eZDB::instance();
                $action = "xrowpriceimport";
                $params = array( 'email' => $email, 
                                 'country' => $country,
                                 'file' => $newFilePath );
                
                $params = $db->escapeString( serialize( $params ) );
                
                $sql = "INSERT INTO ezpending_actions ( action, param, created ) VALUES ( '$action', '$params', '" . time() . "' )";
                $db->query( $sql );
                
                $result = true;
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

$tpl->setVariable( "result", $result );
$tpl->setVariable( "email", $email );
$tpl->setVariable( "upload", $upload );
$tpl->setVariable( "error_array", $errorArray );
$tpl->setVariable( "report_array", $reportArray );

$Result = array();
$Result['content'] = $tpl->fetch( "design:xrowecommerce/price_import.tpl" );
$Result['path'] = array( array( 'url' => false,
                                'text' => ezpI18n::tr( 'extension/xrowecommerce', 'Price import' ) ) );



?>
