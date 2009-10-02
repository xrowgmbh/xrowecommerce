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
        if ( array_search( $country, $list ) )
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

    function getPrice()
    {
        return false;
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

class xmlparser
{

    function GetChildren( $vals, &$i )
    {
        $children = array();
        
        if ( isset( $vals[$i]['value'] ) )
            $children['VALUE'] = $vals[$i]['value'];
        
        while ( ++ $i < count( $vals ) )
        {
            switch ( $vals[$i]['type'] )
            {
                case 'cdata':
                    if ( isset( $children['VALUE'] ) )
                        $children['VALUE'] .= $vals[$i]['value'];
                    else
                        $children['VALUE'] = $vals[$i]['value'];
                    break;
                
                case 'complete':
                    if ( isset( $vals[$i]['attributes'] ) )
                    {
                        $children[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
                        $index = count( $children[$vals[$i]['tag']] ) - 1;
                        
                        if ( isset( $vals[$i]['value'] ) )
                            $children[$vals[$i]['tag']][$index]['VALUE'] = $vals[$i]['value'];
                        else
                            $children[$vals[$i]['tag']][$index]['VALUE'] = '';
                    }
                    else
                    {
                        if ( isset( $vals[$i]['value'] ) )
                            $children[$vals[$i]['tag']][]['VALUE'] = $vals[$i]['value'];
                        else
                            $children[$vals[$i]['tag']][]['VALUE'] = '';
                    }
                    break;
                
                case 'open':
                    if ( isset( $vals[$i]['attributes'] ) )
                    {
                        $children[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
                        $index = count( $children[$vals[$i]['tag']] ) - 1;
                        $children[$vals[$i]['tag']][$index] = array_merge( $children[$vals[$i]['tag']][$index], $this->GetChildren( $vals, $i ) );
                    }
                    else
                    {
                        $children[$vals[$i]['tag']][] = $this->GetChildren( $vals, $i );
                    }
                    break;
                
                case 'close':
                    return $children;
            }
        }
    }

    function GetXMLTree( $xml )
    {
        $data = $xml;
        
        $parser = xml_parser_create();
        xml_parser_set_option( $parser, XML_OPTION_SKIP_WHITE, 1 );
        xml_parse_into_struct( $parser, $data, $vals, $index );
        xml_parser_free( $parser );
        
        //print_r($index);
        

        $tree = array();
        $i = 0;
        
        if ( isset( $vals[$i]['attributes'] ) )
        {
            $tree[$vals[$i]['tag']][]['ATTRIBUTES'] = $vals[$i]['attributes'];
            $index = count( $tree[$vals[$i]['tag']] ) - 1;
            $tree[$vals[$i]['tag']][$index] = array_merge( $tree[$vals[$i]['tag']][$index], $this->GetChildren( $vals, $i ) );
        }
        else
        {
            $tree[$vals[$i]['tag']][] = $this->GetChildren( $vals, $i );
        }
        return $tree;
    }

    function printa( $obj )
    {
        global $__level_deep;
        if ( ! isset( $__level_deep ) )
            $__level_deep = array();
        
        if ( is_object( $obj ) )
            print '[obj]';
        elseif ( is_array( $obj ) )
        {
            foreach ( array_keys( $obj ) as $keys )
            {
                array_push( $__level_deep, "[" . $keys . "]" );
                $this->printa( $obj[$keys] );
                array_pop( $__level_deep );
            }
        }
        else
            print implode( " ", $__level_deep ) . " = $obj\n";
    }
}

?>