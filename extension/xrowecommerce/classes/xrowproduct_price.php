<?php
/*!
 * class xrowProductPrice
 * Stores price information of a xrowproduct
 */
class xrowProductPrice extends eZPersistentObject
{
    function xrowProductPrice( $row )
    {
        $this->eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 'fields' => array( 'id' => array( 'name' => 'ID',
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                         'country' => array( 'name' => 'Country',
                                                                  'datatype' => 'string',
                                                                  'default' => '',
                                                                  'required' => true,
                                                                  'max_length' => 10 ),
                                         'amount' => array( 'name' => 'Amount',
                                                                  'datatype' => 'integer',
                                                                  'default' => 1,
                                                                  'required' => true ),
                                         'price' => array( 'name' => 'Price',
                                                        'datatype' => 'float',
                                                        'default' => 0,
                                                        'required' => false ),
                                         'price_id' => array( 'name' => 'PriceID',
                                                                  'datatype' => 'integer',
                                                                  'default' => 0,
                                                                  'required' => true ),
                                                        ),
                      'function_attributes' => array(),
                      'keys' => array( 'id' ),
                      'sort' => array( 'amount' => 'asc', 'country' => 'asc' ),
                      'class_name' => 'xrowProductPrice',
                      'name' => 'xrowproduct_price' );
    }

    public static function fetchList( $conditions = null,
                               $asObjects = true,
                               $offset = false,
                               $limit = false,
                               $sortBy = array() )
    {
        $limitation = null;
        if ( $offset !== false or $limit !== false )
            $limitation = array( 'offset' => $offset, 'length' => $limit );

        $result = eZPersistentObject::fetchObjectList( self::definition(),
                                                    null,
                                                    $conditions,
                                                    $sortBy,
                                                    $limitation,
                                                    $asObjects );
        return $result;
    }

    public static function fetchListCount( $conditions )
    {
        $custom = array( array( 'operation' => 'count( id )',
                                'name' => 'count' ) );

        $rows = eZPersistentObject::fetchObjectList( self::definition(),
                                                     array(),
                                                     $conditions,
                                                     null,
                                                     null,
                                                     false,
                                                     false,
                                                     $custom );
        return $rows[0]['count'];
    }

    /**
     * Fetches a price by code and amount
     *
     * @param int $priceID
     * @param int $amount
     * @param string $code
     * @return float
     */
    public static function fetchPriceByAmount( $priceID, $amount )
    {
    	//eZDebug::writeDebug( $amount, 'amount' );
    	$conditions = array( 'price_id' => $priceID,
    	                     'amount' => array( '<=', $amount ) );
        $limitation = array( 'offset' => 0, 'length' => 1 );
        $sortBy = array( 'amount' => 'desc' );

        $countryList = self::countryArray();
        foreach ( $countryList as $key )
        {
            $conditions['country'] = $key;
        	$rows = eZPersistentObject::fetchObjectList( self::definition(),
                                                         array( 'price' ),
                                                         $conditions,
                                                         $sortBy,
                                                         $limitation,
                                                         false );

            if ( count( $rows ) == 1 )
	        {
	            return $rows[0]['price'];
	        }
        }
        eZDebug::writeDebug( 'price not found', 'xrowProductPrice::fetchPriceByAmount()' );
        return 0.0;
    }

    /**
     * returns the priorized list of currencies / countries
     *
     */
    public static function countryArray()
    {
    	if ( !isset( $GLOBALS['xrowpricecountryarray'] ) )
    	{
            $xrowINI = eZINI::instance( 'xrowproduct.ini');
            $countryArray = $xrowINI->variable( 'PriceSettings', 'CountryArray' );
            $GLOBALS['xrowpricecountryarray'] = array_keys( $countryArray );
    	}
    	return $GLOBALS['xrowpricecountryarray'];
    }
}

?>
