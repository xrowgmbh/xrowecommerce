<?php
$list = new xrowExportProductList( );

//@TODO get the products
$list->append( new xrowExportProduct( 'Product 1' ) );
$list->append( new xrowExportProduct( 'Product 2' ) );

$plugins = eZINI::instance( 'xrowecommerce.ini' )->variable( 'ExportSettings', 'ActivePlugins' );
if ( is_array( $plugins ) )
{
    foreach ( $plugins as $plugin )
    {
        $export = new $plugin( );
        $export->export( $list );
        unset( $export );
    }
}