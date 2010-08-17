<?php

//
// Definition of ShippingInterface class
//
// Created on: <03-22-2007> xrow GbR, Hannover, www.xrow.de
//
/*! \file shippinginterface.php
*/

/*!
  \class ShippingInterfac shippinginterface.php
  \brief The class ShippingInterface calculates shipping costs
*/

class ShippingInterface
{
    public $server = "";
    public $license = "";
    public $userid = "";
    public $pass = "";
    
    public $service = "";
    public $services = "";
    public $weight_unit = "";
    public $weight = "";
    public $order;
    public $PackagingType = "";
    public $PickupType = "";
    
    public $length_unit = "";
    public $length = "0";
    public $width = "0";
    public $height = "0";
    
    public $address_from = array();
    public $address_to = array();
    public $method;

    function ShippingInterface()
    {
        return false;
    }

    function description()
    {
        foreach ( $this->methods() as $method )
        {
            if ( $method['identifier'] == $this->method )
            {
                $name = $method['name'];
            }
        }
        return $name . ' ( ' . $this->weight . ' lbs )';
    }

    /* Checks if a method is valid for a destination
     * 
     */
    function methodCheck( $country )
    {
        $list = self::getCountryList();

        if ( in_array( $country, $list ) )
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    final public function getCountryList()
    {
        $return = array();
        $list = eZCountryType::fetchCountryList();
        foreach ( $list as $country )
        {
            $return[] = $country['Alpha3'];
        }
        return $return;
    }

    /**
     * Check if the customer has discount
     */
    final public function hasDiscount()
    {
        $list = eZProductCollection::fetch( $this->order->ProductCollectionID );
        $discount = false;
        foreach ( $list->itemList() as $item )
        {
            if ( $item->DiscountValue > 0 )
            {
                $discount = true;
                break;
            }
        }
        return $discount;
    }

    /**
     * Lists available shipping medthods delivery by the plug-in
     *
     */
    function methods()
    {
        return array();
    }

    function loadConfiguration()
    {
        return false;
    }

    /* Checks if a destination is valid
     * 
     */
    function destinationCheck()
    {
        return true;
    }

    static function &instance( $name )
    {
        $return = new $name( );
        $return->loadConfiguration();
        return $return;
    }

    function setServer( $server )
    {
        $this->server = $server;
    }

    function setPackagingType( $PackagingType )
    {
        $this->PackagingType = $PackagingType;
    }

    function setPickupType( $PickupType )
    {
        $this->PickupType = $PickupType;
    }

    function setUserID( $userid )
    {
        $this->userid = $userid;
    }

    function setPass( $pass )
    {
        $this->pass = $pass;
    }

    function setAddressTo( $to_country, $to_state, $to_zip, $to_city )
    {
        $this->address_to = array( 
            "country" => $to_country , 
            "state" => $to_state , 
            "zip" => $to_zip , 
            "city" => $to_city 
        );
    }

    function setAddressFrom( $from_country, $from_state, $from_zip, $from_city )
    {
        $this->address_from = array( 
            "country" => $from_country , 
            "state" => $from_state , 
            "zip" => $from_zip , 
            "city" => $from_city 
        );
    }

    function setOrder( eZOrder $order )
    {
        $this->order = $order;
    }

    function setWeight( $lbs )
    {
        $this->weight = $lbs;
    }

    function setLength( $length )
    {
        $this->length = $length;
    }

    function setWidth( $width )
    {
        $this->width = $width;
    }

    function setHeight( $height )
    {
        $this->height = $height;
    }

    function setWeight_unit( $unit )
    {
        $this->weight_unit = $unit;
    }

    function getPrice( $shippingmethod )
    {
        return false;
    }
    
	function setAvailableFor( $availableFor ) 
	{
         $this->availableFor = $availableFor;
    }

    function convert_country( $country, $input_type = "Alpha2", $output_type = "Alpha3" )
    {
        $countrylist = eZCountryType::fetchCountryList();
        foreach ( $countrylist as $item )
        {
            if ( $item[$input_type] == $country )
                $country_return = $item[$output_type];
        }
        return $country_return;
    }

}

?>