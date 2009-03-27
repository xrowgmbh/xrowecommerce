<?php

//
// Definition of eZOption class
//
// Created on: <28-Jun-2002 11:05:48 bf>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.0.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2007 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//


/*!
  \class eZOption ezoption.php
  \ingroup eZDatatype
  \brief eZOption handles option set datatypes

  \code

  //include_once( "kernel/classes/datatypes/ezoption/ezoption.php" );

  $option = new eZOption( "Colour" );
  $option->addValue( "Red" );
  $option->addValue( "Green" );

  // Serialize the class to an XML document
  $xmlString = $option->xmlString();

  \endcode
*/

class eZOption2
{

    /*!
    */
    function eZOption2( $contentObjectAttribute, $name = false, $init = true )
    {
        if ( $init )
        {
        	$this->decodeXML( $contentObjectAttribute->attribute( "data_text" ) );
        }
        if ( $name !== false )
        {
            $this->Name = $name;
        }
        $this->contentObjectAttribute = $contentObjectAttribute;
        
    
    }

    /*!
     Sets the name of the option
    */
    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
     Returns the name of the option set.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
     Adds an option
    */
    function addOption( $valueArray )
    {
        $valueArray['value'] = isset( $valueArray['value'] ) ? $valueArray['value'] : '';
        $valueArray['comment'] = isset( $valueArray['comment'] ) ? $valueArray['comment'] : '';
        $valueArray['weight'] = isset( $valueArray['weight'] ) ? $valueArray['weight'] : '';
        #$image = isset( $valueArray['image'] ) ? eZContentObject::fetch($valueArray['image']) : null;
        $valueArray['image'] = isset( $valueArray['image'] ) ? $valueArray['image'] : null;
        $valueArray['description'] = isset( $valueArray['description'] ) ? $valueArray['description'] : '';
        $valueArray['additional_price'] = isset( $valueArray['additional_price'] ) ? $valueArray['additional_price'] : 0;
        if( !$valueArray['id'] )
        {
            $valueArray['id'] = uniqid( "option-" );
        }
        $valueArray['is_default'] = false;
        $this->Options[] = $valueArray;
        
        $this->OptionCount += 1;
    }

    function insertOption( $valueArray, $beforeID )
    {
    	throw new Exception("deprecated ".__METHOD__);
    	$valueArray['is_default'] = false;
    	$valueArray['id'] = $this->OptionCount;
        array_splice( $this->Options, $beforeID, 0, array( 
      $valueArray
        ) );
        $this->OptionCount += 1;
    }

    function removeOptions( $array_remove )
    {
        $shiftvalue = 0;
        foreach ( $array_remove as $id )
        {
            array_splice( $this->Options, $id - $shiftvalue, 1 );
            $shiftvalue ++;
        }
        $this->OptionCount -= $shiftvalue;
    }

    function attributes()
    {
        return array( 
            'name' , 
            'option_list' 
        );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function VATType()
    {
        if ( ! $this->VATType )
        {
            $this->VATType = eZVatType::create();
        }
        
        return $this->VATType;
    }

    function attribute( $name )
    {
        switch ( $name )
        {
            case 'vat_type':
                {
                    return $this->VATType()->VATTypeList();
                }
                break;
            case "name":
                {
                    return $this->Name;
                }
                break;
            case "option_list":
                {
                    // put code in here!
                    foreach ( $this->Options as $index => $options )
                    {
                        if ( is_numeric( $options["image"] ) && $options["image"] > 0 )
                        {
                            $this->Options[$index]["image"] = eZContentObject::fetch( $options["image"] );
                        }
                    }
                    return $this->Options;
                }
                break;
            default:
                {
                    eZDebug::writeError( "Attribute '$name' does not exist", 'eZOption::attribute' );
                    return null;
                }
                break;
        }
    }

    /*!
     Will decode an xml string and initialize the eZ option object
    */
    function decodeXML( $xmlString )
    {
        if ( $xmlString != "" )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $xmlString );
            
            // set the name of the node
            $nameNode = $dom->getElementsByTagName( "name" )->item( 0 );
            $this->setName( $nameNode->textContent );
            
            $optionNodes = $dom->getElementsByTagName( "option" );
            $this->OptionCount = 0;
            
            foreach ( $optionNodes as $optionNode )
            {
                $PriceList = array();
                $mpl = $optionNode->getElementsByTagName( "multi_price" );
                if ( $mpl )
                {
                    $mp = $mpl->item( 0 );
                }
                if ( is_object( $mp ) )
                {
                    foreach ( $mp->getElementsByTagName( "price" ) as $priceNode )
                    {
                        ;
                        $price = array( 
                            'type' => $priceNode->getAttribute( 'type' ) , 
                            'value' => $priceNode->getAttribute( 'value' ) , 
                            'currency_code' => $priceNode->getAttribute( 'currency_code' ) 
                        );
                        $PriceList[$priceNode->getAttribute( 'currency_code' )] = $price;
                    }
                }
                $this->addOption( array( 
                    'id' => $optionNode->getAttribute( 'id' ),
                    'value' => $optionNode->textContent , 
                    'description' => $optionNode->getAttribute( 'description' ) , 
                    'comment' => $optionNode->getAttribute( 'comment' ) , 
                    'weight' => $optionNode->getAttribute( 'weight' ) , 
                    'image' => $optionNode->getAttribute( 'image' ) , 
                    'additional_price' => $optionNode->getAttribute( 'additional_price' ) , 
                    'multi_price' => new eZOptionMultiPrice( $PriceList ) 
                ) )

                ;
            }
        
        }
        else
        {
            $this->addOption();
        }
    }

    function store()
    {
        $this->contentObjectAttribute->setAttribute( "data_text", $this->xmlString() );
        $this->contentObjectAttribute->store();
    }

    /*!
     Will return the XML string for this option set.
    */
    function xmlString()
    {
        $doc = new DOMDocument( '1.0', 'utf-8' );
        
        $root = $doc->createElement( "ezoption" );
        $doc->appendChild( $root );
        
        $name = $doc->createElement( "name", $this->Name );
        $root->appendChild( $name );
        
        $options = $doc->createElement( "options" );
        $root->appendChild( $options );
        
        foreach ( $this->Options as $option )
        {
            unset( $optionNode );
            $optionNode = $doc->createElement( "option", $option["value"] );
            $optionNode->setAttribute( "id", $option['id'] );
            $optionNode->setAttribute( "description", $option['description'] );
            $optionNode->setAttribute( "comment", $option['comment'] );
            $optionNode->setAttribute( "weight", $option['weight'] );
            $optionNode->setAttribute( "image", $option['image'] );
            //$optionNode->setAttribute( 'additional_price', $option['additional_price'] );
            $multi_price = $doc->createElement( "multi_price" );
            if ( $option['multi_price'] and count( $option['multi_price']->PriceList ) > 0 )
            {
                
                foreach ( $option['multi_price']->PriceList as $price )
                {
                    if ( $price['type'] != eZMultiPriceData::VALUE_TYPE_CUSTOM )
                    {
                        continue;
                    }
                    
                    $priceNode = $doc->createElement( 'price' );
                    
                    $priceNode->setAttribute( 'currency_code', $price['currency_code'] );
                    $priceNode->setAttribute( 'value', $price['value'] );
                    $priceNode->setAttribute( 'type', $price['type'] );
                    
                    $multi_price->appendChild( $priceNode );
                    unset( $priceNode );
                }
                $optionNode->appendChild( $multi_price );
                unset( $multi_price );
            }
            
            $options->appendChild( $optionNode );
        }
        
        $xml = $doc->saveXML();
        
        return $xml;
    }
    
    /// Contains the Option name
    public $Name;
    
    /// Contains the Options
    public $Options = array();
    
    /// Contains the option counter value
    public $OptionCount = 0;
}

?>
