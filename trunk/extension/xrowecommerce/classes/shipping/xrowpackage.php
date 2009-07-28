<?php

class xrowPackage extends xrowCube
{

    function __construct( $length, $width, $height )
    {
        parent::__construct( $length, $width, $height );
    
    }

    /*
     * @param array Array of remaining items to place in the container. Treats array as a stack and removes elements from the array.
     * @return void
     */
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
     * @param xrowCube Cube added to the package
     * @param xrowCube choosen segment of the filled container ( $this )
     * @return void
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
        $product = $container->can_contain( $product );
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
        $cubes = array();
        $cubes[] = new xrowCube( $container->length - $product->length, $product->width, $product->height );
        $cubes[] = new xrowCube( $container->length, $container->width, $container->height - $product->height );
        $cubes[] = new xrowCube( $container->length, $container->width - $product->width, $product->height );
        /** Alternate calculation for the remaining space 
        $cubes[] = new xrowCube( $container->length - $product->length, $container->width, $container->height );
        $cubes[] = new xrowCube( $container->length, $container->width, $container->height - $product->height );
        $cubes[] = new xrowCube( $container->length, $container->width - $product->width, $container->height );
         */
        
        foreach ( $cubes as $key => $cube )
        {
            if ( $cube->volume() > 0 )
            {
                array_push( $this->free_segments, $cubes[$key] );
            }
        }
        #self::sortByVolume( $this->free_segments, xrowCube::SORT_ASC );
    }
    public $id;
    public $name;
    public $typ;
}
?>