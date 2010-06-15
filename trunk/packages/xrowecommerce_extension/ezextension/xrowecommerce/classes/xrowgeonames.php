<?php

class xrowGeonames
{
    public $map = array();
    public $country_map = array();

    /*
     * @return xrowGeonames
     */
    static function instance()
    {
        $globalsKey = 'xrowGeonamesInstance';
        if ( ! isset( $GLOBALS[$globalsKey] ) || ! ( $GLOBALS[$globalsKey] instanceof xrowGeonames ) )
        {
            $GLOBALS[$globalsKey] = new xrowGeonames();
        }
        return $GLOBALS[$globalsKey];
    }

    function __construct()
    {
        
        if ( ! is_dir( 'var/cache/geonames.org/country' ) )
        {
            mkdir( 'var/cache/geonames.org/country', 0777, true );
        }
        ezcCacheManager::createCache( 'countries', 'var/cache/geonames.org/country', 'ezcCacheStorageFileArray' );
        $cache = ezcCacheManager::getCache( 'countries' );
        if ( ( $this->country_map = $cache->restore( 'countries' ) ) === false )
        {
            
            /*
array(19) {
    [0]=>
    string(4) "#ISO"
    [1]=>
    string(4) "ISO3"
    [2]=>
    string(11) "ISO-Numeric"
    [3]=>
    string(4) "fips"
    [4]=>
    string(7) "Country"
    [5]=>
    string(7) "Capital"
    [6]=>
    string(14) "Area(in sq km)"
    [7]=>
    string(10) "Population"
    [8]=>
    string(9) "Continent"
    [9]=>
    string(3) "tld"
    [10]=>
    string(12) "CurrencyCode"
    [11]=>
    string(12) "CurrencyName"
    [12]=>
    string(5) "Phone"
    [13]=>
    string(18) "Postal Code Format"
    [14]=>
    string(17) "Postal Code Regex"
    [15]=>
    string(9) "Languages"
    [16]=>
    string(9) "geonameid"
    [17]=>
    string(10) "neighbours"
    [18]=>
    string(18) "EquivalentFipsCode"
  }
*/
            $this->country_map = array();
            // Original URL/Source : http://download.geonames.org/export/dump/countryInfo.txt
            $countries = file_get_contents( 'extension/xrowecommerce/share/geonames.org/countryInfo.txt' );
            $countries = str_replace( "\n", "\t", $countries );
            $countries = substr( $countries, strpos( $countries, '#ISO' ) );
            $csvNumColumns = 19;
            $lines = array_chunk( str_getcsv( $countries, "\t" ), $csvNumColumns );
            array_shift( $lines );
            $namedkeys = array( );
            $namedkeys[0] = 'Alpha2';
            $namedkeys[1] = 'Alpha3';
            $namedkeys[4] = 'Name';

            foreach ( $lines as $key => $value )
            {
            	if ( $value[0] )
            	{
            	foreach( $namedkeys as $key2 => $value2 )
            	{
            		$lines[$key][$value2] = trim( $value[$key2] );
            		unset( $lines[$key][$key2] );
            	}
            	}
            	else
            	{
            		unset( $lines[$key] );
            	}
            }
            usort( $lines, 'xrowGeonames::compareCountryNames' );
            foreach ( $lines as $key => $value )
            {
                if ( $value['Alpha2'] )
                {
                    $this->country_map[$value['Alpha2']] = $value;
                }
            }
            $cache->store( 'countries', $this->country_map );
        }
        if ( ! is_dir( 'var/cache/geonames.org/states' ) )
        {
            mkdir( 'var/cache/geonames.org/states', 0777, true );
        }
        ezcCacheManager::createCache( 'states', 'var/cache/geonames.org/states', 'ezcCacheStorageFileArray' );
        $cache = ezcCacheManager::getCache( 'states' );
        if ( ( $this->map = $cache->restore( 'states' ) ) === false )
        {
            $this->map = array();
            // Original URL/Source : http://download.geonames.org/export/dump/admin1Codes.txt
            $lines = file( 'extension/xrowecommerce/share/geonames.org/admin1Codes.txt' );

            foreach ( $lines as $line_num => $line )
            {
                if ( preg_match_all( '/([\w][\w])\.([\w][\w])\t(.*)\n/', $line, $matches ) )
                {
                    if ( $matches[2][0] != '00' )
                    {
                        $this->map[$this->country_map[$matches[1][0]]['Alpha3']][$matches[2][0]] = $matches[3][0];
                    }
                }
            
            }
            foreach ( $this->map as $key => $value )
            {
            	uasort( $this->map[$key], 'xrowGeonames::compareSubdivisionNames' );
            }
            $cache->store( 'states', $this->map );
        }
    }
    /**
     * Sort callback used by fetchTranslatedNames to compare two subdivision arrays
     *
     * @param array $a Country 1
     * @param array $b Country 2
     * @return bool
     */
    protected static function compareSubdivisionNames( $a, $b )
    {
        return strcoll( $a, $b );
    }
    /**
     * Sort callback used by fetchTranslatedNames to compare two country arrays
     *
     * @param array $a Country 1
     * @param array $b Country 2
     * @return bool
     */
    protected static function compareCountryNames( $a, $b )
    {
        return strcoll( $a['Name'], $b['Name'] );
    }

    static function getSubdivisionName( $country, $id )
    {
        return self::instance()->map[$country][$id];
    }

    static function getCountry( $country )
    {
    	if( isset( self::instance()->country_map[$country] ) )
    	{
        	return self::instance()->country_map[$country];
    	}
    	else
    	{
    		foreach ( self::instance()->country_map as $country2 )
    		{
    			if( $country2['Alpha3'] == $country)
    			{
    				 return $country2;
    			}
    		}
    	}
    }

    static function getCountries()
    {
        return self::instance()->country_map;
    }

    static function getSubdivisions( $country )
    {
    	$instance = self::instance();
        if ( isset( $instance->map[$country] ) )
        {
            return $instance->map[$country];
        }
        else
        {
            return array();
        }
    }
}