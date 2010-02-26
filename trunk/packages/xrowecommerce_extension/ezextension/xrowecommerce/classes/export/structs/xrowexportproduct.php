<?php
/**
 * File containing the xrowExportProduct struct
 *
 * @package Shop
 * @version 1.4.3
 * @copyright Copyright (C) 2005-2009 xrow GmbH. All rights reserved.
 * @license http://ez.no/licenses/new_bsd New BSD License
 */

class xrowExportProduct extends ezcBaseStruct
{
    /**
     * Name of dataset
     * 
     * @var string
     */
    public $name = false;
    public $link = false;
    public $description = false;
    public $id = false;
    public $price = false;
    public $manufacturer = false;
    public $image_link = false;

    /**
     * Simple constructor 
     * 
     * @return void
     * @ignore
     */
    public function __construct( $name = null, $link = null, $description = null, $id = null, $price = null, $manufacturer = null )
    {
        $this->name = $name;
        $this->link = $link;
        $this->description = $description;
        $this->id = $id;
        $this->price = $price;
        $this->manufacturer = $manufacturer;
    }

}
