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
    public $id = false;
    public $image_link = false;
    public $manufacturer = false;
    public $description = false;
    public $price = false;
    public $color = false;
    public $model_number = false;
    public $bild_url = false;
    public $preis = false;
    public $breite = false;
    public $höhe = false;
    public $länge = false;
    public $beschreibung = false;


    /**
     * Simple constructor
     *
     * @return void
     * @ignore
     */
    public function __construct( array $exportFieldArray )
    {
        foreach ( $exportFieldArray as $exportFieldName => $exportFieldValue )
        {
            $this->$exportFieldName = $exportFieldValue;
        }
    }
}
?>