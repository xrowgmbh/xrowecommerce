<?php

class xrowProductExport implements xrowExportProductPlugin
{
    function export( xrowExportProductList $list )
    {
        // headline
        $counter = 0;
        $headline = array();
        foreach ( $list as $product )
        {
            foreach( $product as $key => $value )
            {
                $headline[] = $key;
            }
            if( $counter == 0 )
                break;
        }
        $data .= "\"".implode( "\";\"", $headline )."\"\n";
        $dataArray = array();
        foreach ( $list as $product )
        {
            foreach( $product as $key => $value )
            {
                $dataArray[$key] = $value;
            }
            $data .= "\"".implode( "\";\"", $dataArray )."\"\n";
            $dataArray = array();
        }

        $cache_file = eZSys::cacheDirectory().'/productlist.csv';
        eZFile::create( $cache_file, false, $data );

        return true;
    }
}