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
        
        foreach ( $list as $product )
        {
        	
            $item = $feed->add( 'item' );
            $item->title = $product->name;
            $item->link = $product->link;
            //$item->description = $dom->createCDATASection( $product->description );
            $item->description = $product->description;
            
            $module = $item->addModule( 'GoogleProduct' );
            $module->id = $product->id;
            $module->price = $product->price;
            $module->brand = $product->manufacturer;
            $module->condition = 'new';
            $module->product_type = $xrowIni->variable( 'GoogleExportSettings', 'GoogleProductType' );
            $module->payment_accepted = '';
            $module->image_link = $product->image_link;
            $module->color = $product->color;
            $module->model_number = $product->model_number;
        }
        /*
  Try to add TAX with var_dump(xrowECommerce::merchantsLocations());
  $module->tax = '';
  Try to add payment gateways from workflow;
  $module->payment_accepted = '';
 */
        
        /*repeat and add an item*/
        
        $xml = $feed->generate( 'rss2' );

        $options = array( 
            'ftp' => array( 
                'overwrite' => true 
            ) 
        );

        $context = stream_context_create( $options );

        // connect to the ftp server
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
        return true;
    }
}