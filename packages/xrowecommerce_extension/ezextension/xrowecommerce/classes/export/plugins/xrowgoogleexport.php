<?php

class xrowGoogleExport implements xrowExportProductPlugin
{

    function export( xrowExportProductList $list )
    {

        $xrowIni = eZINI::instance( 'xrowecommerce.ini');

        ezcFeed::registerModule( 'GoogleProduct', 'ezcFeedGoogleProductModule', 'g' );
        $feed = new ezcFeed( );
        $dom = new DOMDocument();

        $feed->title = 'Products of ' . eZINI::instance( 'site.ini')->variable( 'SiteSettings', 'SiteName');
        # site url
        $feed->link = 'http://' . eZINI::instance( 'site.ini')->variable( 'SiteSettings', 'SiteURL');
        // Sitename from INI
        $feed->description = 'Products of ' . eZINI::instance( 'site.ini')->variable( 'SiteSettings', 'SiteName');

        $descField = 'description';
        if ( $xrowIni->hasVariable( 'GoogleExportSettings', 'DescField' ) )
        {
            $descField = $xrowIni->variable( 'GoogleExportSettings', 'DescField' );
        }

        if ( $xrowIni->hasVariable( 'GoogleExportSettings', 'GoogleAttributes' ) )
        {
            $googleMapping = $xrowIni->variable( 'GoogleExportSettings', 'GoogleAttributes' );
        }
        else
        {
            $googleMapping = array(
                'description' => 'description',
                'id' => 'id',
                'price' => 'price',
                'brand' => 'manufacturer',
                'condition' => 'condition',
                'product_type' => 'product_type',
                'payment_accepted' => 'payment_accepted',
                'image_link' => 'image_link',
                'color' => 'color',
                'model_number', 'model_number'
            );
        }

        foreach ( $list as $product )
        {

            $item = $feed->add( 'item' );
            $item->title = $product->name;
            $item->link = $product->link;
            //$item->description = $dom->createCDATASection( $product->description );
            if ( isset( $product->description ) )
            {
                $item->$descField = $product->description;
            }
            elseif ( isset( $product->beschreibung ) )
            {
                $item->$descField = $product->beschreibung;
            }

            $module = $item->addModule( 'GoogleProduct' );

            foreach ( $googleMapping as $gKey => $gItem )
            {
                if ( isset( $product->$gItem ) && $product->$gItem )
                {
                   $module->$gKey = $product->$gItem;
                }
            }

            if ( isset( $googleMapping['product_type'] ) )
            {
                $module->product_type = $xrowIni->variable( 'GoogleExportSettings', 'GoogleProductType' );
            }
            if ( isset( $googleMapping['condition'] ) )
            {
                $module->condition = 'new';
            }
            if ( isset( $googleMapping['zustand'] ) )
            {
                $module->zustand = 'neu';
            }
            if ( isset( $googleMapping['payment_accepted'] ) )
            {
                $module->payment_accepted = '';
            }
        }
        /*
  Try to add TAX with var_dump(xrowECommerce::merchantsLocations());
  $module->tax = '';
  Try to add payment gateways from workflow;
  $module->payment_accepted = '';
 */

        /*repeat and add an item*/

        $xml = $feed->generate( 'rss2' );

        $ftpfilename = $xrowIni->variable( 'GoogleExportSettings', 'GoogleFileName' );

        if ( $xrowIni->hasVariable( 'GoogleExportSettings', 'ExportAsFile' ) &&
             $xrowIni->variable( 'GoogleExportSettings', 'ExportAsFile' ) == 'true' )
        {
            $cacheDir = eZSys::cacheDirectory();
            eZFile::create( $ftpfilename, $cacheDir . '/googleexport', $xml );
        }

        $options = array(
            'ftp' => array(
                'overwrite' => true
            )
        );

        $context = stream_context_create( $options );

        // connect to the ftp server
        if ( $xrowIni->hasVariable( 'GoogleExportSettings', 'ExportToFTP' ) &&
             $xrowIni->variable( 'GoogleExportSettings', 'ExportToFTP' ) == 'true' )
        {
            $ftpserver = 'uploads.google.com';
            $ftpconn_id = ftp_connect($ftpserver);
            if ( !$ftpconn_id )
            {
                throw new Exception( 'No connection to uploads.google.com' );
            }
            $ftpuser = $xrowIni->variable( 'GoogleExportSettings', 'GoogleFTPUsername' );
            $ftppassword = $xrowIni->variable( 'GoogleExportSettings', 'GoogleFTPPassword' );
            $ftpfilename = $xrowIni->variable( 'GoogleExportSettings', 'GoogleFileName' );
            // login to the ftp server
            $login_result = ftp_login($ftpconn_id, $ftpuser, $ftppassword);
            if ( !$login_result )
            {
                throw new Exception( 'Wrong login data' );
            }

            $result = file_put_contents( 'ftp://' . $ftpuser . ':' . $ftppassword . '@' . $ftpserver . '/' . $ftpfilename, $xml, false, $context );

            if ( ! $result )
            {
                throw new Exception( 'Google Product upload failed' );
            }
        }

        return true;
    }
}