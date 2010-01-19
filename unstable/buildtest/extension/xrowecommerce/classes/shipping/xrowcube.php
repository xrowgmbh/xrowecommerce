<?php

class xrowCube
{
    public $contains = array();
    public $free_segments = array();
    public $length;
    public $width;
    public $height;
    public $weight;
    const SORT_ASC = 1;
    const SORT_DESC = 0;

    function __construct( $length, $width, $height )
    {
        $this->length = $length;
        $this->width = $width;
        $this->height = $height;
    }

    /**
     * This function exhibits the same behaviour is array_shift(), except
     * it returns a reference to the first element of the array instead of a copy.
     *
     * @param array &$array
     * @return mixed
     */
    static function &array_shift_reference( &$array )
    {
        if ( count( $array ) > 0 )
        {
            $keys = array_keys( $array );
            $first = & $array[$keys[0]];
        }
        else
        {
            $first = null;
        }
        array_shift( $array );
        return $first;
    }

    /* Helper comparison function
     * 
     */
    final private static function _cmp( $a, $b )
    {
        if ( $a['a'] == $b['a'] )
        {
            return 0;
        }
        return ( $a['a'] < $b['a'] ) ? - 1 : 1;
    }

    /* Checks if a Cube fits in another Cube
 * @return returns a flipped version of the object or false
 */
    final public function can_contain( xrowCube $content )
    {
        // $this is the container
        $lBox = $this->length;
        $wBox = $this->width;
        $hBox = $this->height;
        $lProd = $content->length;
        $wProd = $content->width;
        $hProd = $content->height;
        
        // compose all areas of the cube
        $areas = array( 
            array( 
                'a' => $lProd * $wProd , 
                'l' => $hProd , 
                'w' => $lProd , 
                'h' => $wProd 
            ) , 
            array( 
                'a' => $lProd * $hProd , 
                'l' => $wProd , 
                'w' => $lProd , 
                'h' => $hProd 
            ) , 
            array( 
                'a' => $wProd * $hProd , 
                'l' => $lProd , 
                'w' => $wProd , 
                'h' => $hProd 
            ) , 
            array( 
                'a' => $lProd * $wProd , 
                'l' => $hProd , 
                'w' => $wProd , 
                'h' => $lProd 
            ) , 
            array( 
                'a' => $lProd * $hProd , 
                'l' => $wProd , 
                'w' => $hProd , 
                'h' => $lProd 
            ) , 
            array( 
                'a' => $wProd * $hProd , 
                'l' => $lProd , 
                'w' => $hProd , 
                'h' => $wProd 
            ) 
        );
        // sort the resulting array by biggest area 
        for ( $i = 5; $i > 0; $i -- )
        {
            for ( $j = 0; $j < $i; $j ++ )
            {
                if ( $areas[$j]['a'] < $areas[$j + 1]['a'] )
                {
                    $swap = $areas[$j];
                    $areas[$j] = $areas[$j + 1];
                    $areas[$j + 1] = $swap;
                }
            }
        }
        // looks for largest area in the array and flips the product.
        for ( $loop = 0; $loop < 6; $loop ++ )
        {
            if ( $areas[$loop]['l'] <= $lBox && $areas[$loop]['w'] <= $wBox && $areas[$loop]['h'] <= $hBox )
            {
                $obj = clone $content;
                $obj->height = $areas[$loop]['h'];
                $obj->width = $areas[$loop]['w'];
                $obj->length = $areas[$loop]['l'];
                return $obj;
            }
        }
        /* Alternate sort might sort a bit different
        // sort the resulting array by biggest area 
        uasort( $areas, 'xrowCube::_cmp' );
do
{
	$part = array_pop( $areas );
            if ( $part['l'] <= $lBox && $part['w'] <= $wBox && $part['h'] <= $hBox )
            {
                $obj = clone $content;
                $obj->height = $part['h'];
                $obj->width = $part['w'];
                $obj->length = $part['l'];
                return $obj;
            }
}
while ( count( $areas ) > 0 );

*/
        // Produkt passt nicht in die Box
        return false;
    }

    final public function compare( xrowCube $cube )
    {
        if ( $this->height == $cube->height and $this->width == $cube->width and $this->length == $cube->length )
        {
            return true;
        }
        return false;
    }

    final public function volume()
    {
        return $this->width * $this->length * $this->height;
    }

    final public function usedSpace()
    {
        $total = 0;
        
        foreach ( $this->contains as $c )
        {
            $total += $c->volume();
        }
        return $total;
    }

    final public function freeSpace()
    {
        $total = self::volume();
        
        foreach ( $this->contains as $c )
        {
            $total -= $c->volume();
        }
        return $total;
    }

    /**
     * Remove a xrowCube from an array
     *
     * @param array Array of xrowCubes
     * @param xrowCube
     */
    static function removeFromArray( &$cubes, $cube )
    {
        foreach ( $cubes as $key => $p )
        {
            if ( $p === $cube )
            {
                unset( $cubes[$key] );
                break;
            }
        }
    }

    /** 
     * Bubblesort for array to sort by volume
     * 
     * @param array Array of xrowCube
     * @param const SORT_DESC | SORT_ASC
     */
    final static function sortByVolume( &$boxes, $sort = SORT_DESC )
    {
        $nbrBoxes = count( $boxes );
        for ( $i = $nbrBoxes - 1; $i > 0; $i -- )
        {
            for ( $j = 0; $j < $i; $j ++ )
            {
                $keys = array_keys( $boxes );
                if ( $sort == SORT_DESC )
                {
                    
                    if ( $boxes[$keys[$j]]->volume() < $boxes[$keys[$j + 1]]->volume() )
                    {
                        $swap = $boxes[$keys[$j]];
                        $boxes[$keys[$j]] = $boxes[$keys[$j + 1]];
                        $boxes[$keys[$j + 1]] = $swap;
                    }
                
                }
                else
                {
                    if ( $boxes[$keys[$j]]->volume() > $boxes[$keys[$j + 1]]->volume() )
                    {
                        $swap = $boxes[$keys[$j]];
                        $boxes[$keys[$j]] = $boxes[$keys[$j + 1]];
                        $boxes[$keys[$j + 1]] = $swap;
                    }
                }
            }
        }
    }

}
?>