<?php

class xrowPackage extends xrowCube
{

    function __construct( $length, $width, $height )
    {
        parent::__construct( $length, $width, $height );

    }
    public function fill( &$products )
    {
        // versuchen so viel wie möglich, der anderen Produkte in die Kiste zu packen
        foreach ( $products as $key => $product )
        {
            // Überprüfen, ob es in eines der Boxsegmente passt.
            $segmentIndex = 0;
            while ( $segmentIndex < count( $this->free_segments ) )
            {
                $keys = array_keys( $this->free_segments );
                if ( $this->free_segments[$keys[$segmentIndex]]->can_contain( $products[$key] ) )
                {
                    
                    $this->add( $products[$key], $this->free_segments[$keys[$segmentIndex]] );
                    
                    self::removeFromArray( $products, $products[$key] );
                    $segmentIndex = 0;
                    continue 2;
                
                }
                else
                {
                    $segmentIndex ++;
                }
            }
        }
    }

    /*
     * @param xrowCube
     * @param xrowCube
     */
    public function add( xrowCube $product, $segment = false )
    {
        if ( $segment )
        {
            self::removeFromArray( $this->free_segments, $segment );
        }
        $this->contains[] = & $product;
        if ( $segment === false )
        {
            $container = & $this;
        }
        else
        {
            $container = & $segment;
        }
        /*
         *    / --------------
         *   /              /|
         *  |--------------/ |
         *  | |            | |
         *  | |            | |
         *  | /------------|-/
         *  |/             |/
         *  /--------------/
         */
        $cubes[] = new xrowCube( $container->length - $product->length, $container->width, $container->height );
        $cubes[] = new xrowCube( $product->length, $container->width, $container->height - $product->height );
        $cubes[] = new xrowCube( $product->length, $container->width - $product->width, $product->height );
        foreach ( $cubes as $key => $cube )
        {
            if ( $cube->volume() > 0 )
            {
                $this->free_segments[] = & $cubes[$key];
            }
        }
        self::sortByVolume( $this->free_segments, xrowCube::SORT_ASC );
        /*
        echo $this->volume() . " Total Space " . "\n";
        echo $this->usedSpace() . " Used Space " . "\n";
        echo $product->volume() . " Product Space " . "\n";
        echo $this->freeSpace() . " Remaining Space " . "\n";
*/
    
    }
    public $id;
    public $name;
    public $typ;
}
?>