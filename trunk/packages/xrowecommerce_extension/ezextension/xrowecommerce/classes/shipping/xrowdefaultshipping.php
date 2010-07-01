<?php

class xrowDefaultShipping implements xrowShipment
{

    static function getBoxes( eZOrder $order )
    {
        $return = array();
        $treeParameters['Offset'] = false;
        $treeParameters['Language'] = false;
        $treeParameters['Depth'] = false;
        $treeParameters['DepthOperator'] = false;
        $treeParameters['AsObject'] = true;
        $treeParameters['LoadDataMap'] = true;
        $treeParameters['GroupBy'] = false;
        $treeParameters['MainNodeOnly'] = true;
        $treeParameters['IgnoreVisibility'] = true;
        $treeParameters['ObjectNameFilter'] = false;
        $treeParameters['ClassFilterType'] = 'include';
        $treeParameters['Limitation'] = array();
        
        $treeParameters['ClassFilterArray'] = array( 
            'xrow_package' 
        );
        $treeParameters['Limitation'] = array();
        
        $treeParameters['SortBy'] = array( 
            'published' , 
            false 
        );
        
        $boxes = eZContentObjectTreeNode::subTreeByNodeID( $treeParameters, 2 );
        
        foreach ( $boxes as $item )
        {
            $object = $item->object();
            $dm = $object->dataMap();
            
            $tmp = new xrowPackage( $dm['length']->content(), $dm['width']->content(), $dm['height']->content() );
            $tmp->name = $object->name();
            $tmp->id = $object->ID;
            $return[] = $tmp;
        }
        return $return;
    
    }

    static function getProducts( eZOrder $order )
    {
        $return = array();
        
        foreach ( $order->productItems() as $item )
        {
            $object = $item['item_object']->attribute( 'contentobject' );
            $dm = $object->dataMap();
            for ( $i = 0; $i < $item['item_object']->ItemCount; $i ++ )
            {
                $length = 0;
                $width = 0;
                $height = 0;
                if ( isset( $dm['length'] ) && is_object( $dm['length'] )  )
                {
                    $length = $dm['length']->content();
                }
                if ( isset( $dm['width'] ) && is_object( $dm['width'] ) )
                {
                    $width = $dm['width']->content();
                }
                if ( isset( $dm['height'] ) && is_object( $dm['height'] )  )
                {
                    $height = $dm['height']->content();
                }
                $tmp = new xrowShippedProduct( $length, $width, $height );

                if ( $tmp instanceof xrowShippedProduct )
                {
                    $tmp->name = $object->name();
                    $tmp->id = $object->ID;
                    $tmp->weight = $dm['weight']->content();
                    $return[] = $tmp;
                }
            }
        
        }
        return $return;
    }

    static function compute( $boxes = array(), $products = array() )
    {
        $packlist = new ArrayObject();
        
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
            
            if ( !$product )
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
                    /** @var $currentbox xrowPackage */
                    $currentbox = &$boxes[$key];
                    
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
            $packlist->append( $box );
        }
        while ( count( $products ) > 0 );
        return $packlist;
    }
}
?>