<?php
interface xrowShipment
{
    static function getBoxes( eZOrder $order );
    static function getProducts( eZOrder $order );
    /**
     * Enter description here...
     *
     * @param unknown_type $boxes Array of xrowCube
     * @param unknown_type $products Array of xrowCube
     * @return ArrayObject List of Parcels packed with Products
     */
    static function compute( $boxes = array(), $products = array() );
}
?>