<?php

$db = eZDB::instance();

$sql = "SELECT * FROM ezpending_actions where action LIKE 'xrowpriceexport'";
$res = $db->arrayQuery( $sql );

if ( isset( $res[0] ) )
{
    $params = unserialize( $res[0]['params'] );
    $email = $params['email'];
    $country = $params['country'];

    $export = true;
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

    /**
     * Fetch all products, even hidden ones
    */
    $contentNode = eZContentObjectTreeNode::fetch( $exportParentNodeID );

    $nodeList = array();

    if ( $contentNode )
    {
        $offset = 0;
        $limit = 50;
        $params = array( 'IgnoreVisibility' => true,
        'Limitation' => array(),
        'ClassFilterType' => 'include',
        'ClassFilterArray' => array_keys( $exportClasses ) );

        $contentCount = $contentNode->subTreeCount( $params );
        if ( $contentCount > 0 )
        {
            $params['Limit'] = $limit;
            $params['SortBy'] = array( 'node_id' => 'asc' );

            while( $offset < $contentCount )
            {
                $params['Offset'] = $offset;
                $productArray = $contentNode->subTree( $params );

                foreach ( $productArray as $product )
                {
                    //$dataMap = $product->attribute( 'data_map' );
                    $classIdentifier = $product->attribute( 'class_identifier' );
                    $attributeID = $exportClasses[$classIdentifier];
                    $classAttributeID = (int) eZContentObjectTreeNode::classAttributeIDByIdentifier( $classIdentifier . '/' . $attributeID );

                    $variationAttribute = eZPersistentObject::fetchObject( eZContentObjectAttribute::definition(),
                    null,
                    array( 'contentclassattribute_id' => $classAttributeID,
                    'contentobject_id' => (int) $product->attribute( 'contentobject_id' ),
                    'version' => (int) $product->attribute( 'contentobject_version' ),
                    'language_code' => $languageCode ),
                    true );

                    if ( $variationAttribute instanceof eZContentObjectAttribute )
                    {
                        $id = $variationAttribute->attribute( 'id' );
                        $version = $variationAttribute->attribute( 'version' );

                        $dataArray = xrowProductData::fetchList( array( 'attribute_id' => $id,
                        'version' => $version,
                        'language_code' => $languageCode ),
                        true,
                        false,
                        false );

                        foreach ( $dataArray as $variation )
                        {
                            $sku = $variation->attribute( $skuField );
                            if ( !isset( $exportArray[$sku] ) )
                            {
                                $exportArray[$sku] = array( $sku );
                                $priceID = $variation->attribute( $priceField );
                                $priceList = xrowProductPrice::fetchList( array( 'price_id' => $priceID, 'country' => $country ),
                                true,
                                false,
                                false,
                                array( 'amount' => 'asc' ) );

                                foreach ( $priceList as $price )
                                {
                                    $exportArray[$sku][] = number_format( $price->attribute( 'price' ), $precision, $decPoint, '' );
                                }
                            }
                        }
                    }
                }
                $offset += $limit;
                /**
                 * Unset the cache
                 */

                // delete cache to avoid memory overflow
                eZContentObject::clearCache();
                xrowProductDataType::clearCache();

            }
            $content = "";
            ksort( $exportArray );
            foreach ( $exportArray as $line )
            {
                $content .= implode( $separator, $line ) . "\n";

            }
            $dir =  eZSys::cacheDirectory().'/';
            $file = $dir . date( "Y-m-d-H-i" ) . "-price-export-" . strtolower( $country ) . ".csv";
            @unlink( $file );
            eZFile::create( $file, false, $content );

            $ini = eZINI::instance();
            $xrowINI = eZINI::instance( 'xrowecommerce.ini' );
            $emailSender = $xrowINI->variable( 'MailSettings', 'Email' );
            if ( ! $emailSender )
            {
                $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
            }
            if ( ! $emailSender )
            {
                $emailSender = $ini->variable( "MailSettings", "AdminEmail" );
            }

            # send email with the export
            ezcMailTools::setLineBreak( "\n" );
            $mail = new ezcMailComposer();
            $mail->charset = 'utf-8';

            $smtpUser = $ini->variable( "MailSettings", "TransportUser" );
            $smtpPwd = $ini->variable( "MailSettings", "TransportPassword" );
            $smtpHost = $ini->variable( "MailSettings", "TransportServer" );

            $mail->from = new ezcMailAddress( $senderEMail,  $senderEMail, 'utf-8' );
            $mail->addTo( new ezcMailAddress( $email, $email, 'utf-8' ) );

            $templateResult = "Price export.";

            $mail->subject = "Price Export";
            $mail->htmlText = $templateResult;
            $disposition = new ezcMailContentDispositionHeader();
            $disposition->fileName =  $file;
            $disposition->fileNameCharSet = 'utf-8'; // if using non-ascii characters in the file name
            $disposition->disposition = 'attachment'; // default value is 'inline'
            $mail->addFileAttachment( $file, null, null, $disposition );

            $mail->build();

            $options = new ezcMailSmtpTransportOptions();
            $options->connectionType = ezcMailSmtpTransport::CONNECTION_SSLV3;

            $transport = new ezcMailSmtpTransport( $smtpHost, $smtpUser, $smtpPwd, null, $options );

            // The option can also be specified via the option property:
            $transport->options->connectionType = ezcMailSmtpTransport::CONNECTION_SSLV3;
            try
            {
                // Use the SMTP transport to send the created mail object
                $transport->send( $mail );
            }
            catch ( ezcMailTransportException $e )
            {
                eZDebug::writeError( $e->getMessage(), 'Price export' );
            }
        }
    }
    $sql = "DELETE FROM ezpending_actions where id = '" . $res['id'] . "'";
    $db->query( $sql );

}

echo "done\n";