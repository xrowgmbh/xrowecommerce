<?php

class xrowShipmentCalculator implements xrowShipment
{

    static function compute( $boxes = array(), $products = array() )
    {
        $packlist = array();

        xrowCube::sortByVolume( $boxes, SORT_ASC );
        
        xrowCube::sortByVolume( $products );
        
        do
        {
            // Volumen aller noch nicht verpackten Produkte ermitteln
            $volumen = 0;
            foreach ( $products as $product )
            {
                $volumen += $product->volume();
            }

            $product = xrowCube::array_shift_reference( $products );
            
            if ( ! $product )
            {
                return $packlist;
            }

            // Find the smallest Package for the biggest product

            $found = false;
            foreach ( $boxes as $key => $box )
            {
                
                if ( $box->can_contain( $product ) )
                {
                    $found = true;
                    $boxType = $boxes[$key]->typ;
                }
            }
            
            if ( $found === false )
            {
                throw new Exception( "No parcel suitable for at least one product." );
            }
            
            // Sucht nach der kleinsten Kiste, welche die ermittelte Typennummer hat 
            // und in welches das Gesamtvolumen der nicht verpackten Produkte passt.
            $found = false;
            foreach ( $boxes as $key => $box )
            {
                if ( $box->typ == $boxType )
                {
                    $boxIndex = $key;
                    /** @var $currentbox xrowParcel */
                    $currentbox = & $boxes[$key];
                    
                    if ( $box->volume() >= $volumen )
                    {
                        $found = true;
                        break;
                    }
                }
            }
            // clone the box so that we can fill it with products
            $box = clone $currentbox;
            // Pack the biggest product
            $box->add( $product );
            // Try to fill the rest of the products into the box
            $box->fill( $products );
            $packlist[] =& $box;
        }
        while ( count( $products ) > 0 );
        return $packlist;
    }
}
?>