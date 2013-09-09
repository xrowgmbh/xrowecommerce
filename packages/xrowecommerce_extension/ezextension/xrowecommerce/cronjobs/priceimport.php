<?php

$db = eZDB::instance();

$sql = "SELECT * FROM ezpending_actions where action LIKE 'xrowpriceimport'";
$res = $db->arrayQuery( $sql );

if ( isset( $res[0] ) )
{
    $tpl = eZTemplate::factory();
    
    $params = unserialize( $res[0]['param'] );
    $email = $params['email'];
    $country = $params['country'];
    $file = $params['file'];
    
    $content = file_get_contents( $file );

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
    
    $sql = "DELETE FROM ezpending_actions where id = '" . $res[0]['id'] . "'";
    #$db->query( $sql );

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

    $tpl->setVariable( 'report_array', $reportArray );
    $templateResult = $tpl->fetch( "design:xrowecommerce/price_import_report.tpl" );
    #var_dump( $templateResult );

    $mail->subject = "Price import";
    $mail->htmlText = $templateResult;
    
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

echo "\ndone\n";