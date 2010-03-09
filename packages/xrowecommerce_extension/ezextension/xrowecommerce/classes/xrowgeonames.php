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
        
        if ( ! is_dir( 'var/cache/geonames/country' ) )
        {
            mkdir( 'var/cache/geonames/country', 0777, true );
        }
        ezcCacheManager::createCache( 'countries', 'var/cache/geonames/country', 'ezcCacheStorageFileArray' );
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
            
            foreach ( $lines as $key => $value )
            {
                if ( is_array( $value[4] ) )
                {
                    var_dump( $value );
                }
                $value[4] = trim( $value[4] );
                $value[5] = trim( $value[5] );
            }
            usort( $lines, 'xrowGeonames::compareCountryNames' );
            foreach ( $lines as $key => $value )
            {
                if ( $value[0] )
                {
                    $this->country_map[$value[0]] = $value;
                }
            }
            $cache->store( 'countries', $this->country_map );
        }
        if ( ! is_dir( 'var/cache/geonames/states' ) )
        {
            mkdir( 'var/cache/geonames/states', 0777, true );
        }
        ezcCacheManager::createCache( 'states', 'var/cache/geonames/states', 'ezcCacheStorageFileArray' );
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
                        $this->map[$this->country_map[$matches[1][0]][1]][$matches[2][0]] = $matches[3][0];
                    }
                }
            
            }
            $cache->store( 'states', $this->map );
        }
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
        return strcoll( $a[4], $b[4] );
    }

    static function getSubdivisionName( $country, $id )
    {
        return self::instance()->map[$country][$id];
    }

    static function getCountry( $country )
    {
        return self::instance()->country_map[$country];
    }

    static function getCountries()
    {
        return self::instance()->country_map;
    }

    static function getSubdivisions( $country )
    {
        if ( isset( $country ) )
        {
            return self::instance()->map[$country];
        }
        else
        {
            return array();
        }
    }
}