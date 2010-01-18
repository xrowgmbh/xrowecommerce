<?php

class xrowGoogleExport implements xrowExportProductPlugin
{

    function export( xrowExportProductList $list )
    {

        $ini = eZINI::instance( "xrowecommerce.ini");

        ezcFeed::registerModule( 'GoogleProduct', 'ezcFeedGoogleProductModule', 'g' );
        $feed = new ezcFeed( );
        
        $feed->title = 'Products of ' . eZINI::instance( "site.ini")->variable( 'SiteSettings', 'SiteName');
        # site url
        $feed->link = 'http://' . eZINI::instance( "site.ini")->variable( 'SiteSettings', 'SiteURL');
        // Sitename from INI
        $feed->description = 'Products of ' . eZINI::instance( "site.ini")->variable( 'SiteSettings', 'SiteName');
        
        foreach ( $list as $product )
        {
            $item = $feed->add( 'item' );
            $item->title = $product->name;
            $item->link = $product->link;
            $item->description = $product->description;
            
            $module = $item->addModule( 'GoogleProduct' );
            $module->id = $product->id;
            $module->price = $product->price;
            $module->brand = $product->manufacturer;
            $module->condition = 'new';
            $module->product_type = $ini->variable( 'ExportSettings', 'GoogleProductType' );
            $module->payment_accepted = '';
            $module->image_link = $product->image_link;
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

        $result = file_put_contents( 'ftp://' . $ini->variable( 'ExportSettings', 'GoogleFTPUsername' ) . ':' . $ini->variable( 'ExportSettings', 'GoogleFTPPassword' ) . '@uploads.google.com/' . $ini->variable( 'ExportSettings', 'GoogleFileName' ), $xml, false, $context );

        if ( ! $result )
        {
            throw new Exception( "Google Product upload faild" );
        }
        return true;
    }
}