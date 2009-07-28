<?php
class xrowShippedProduct extends xrowCube
{

    function __construct( $length, $width, $height )
    {
        parent::__construct( $length, $width, $height );
    }
    public $id;
    public $name;
}
?>