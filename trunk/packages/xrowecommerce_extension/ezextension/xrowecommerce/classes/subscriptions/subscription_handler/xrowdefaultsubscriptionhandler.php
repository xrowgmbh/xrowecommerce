<?php

class xrowDefaultSubscriptionHandler
{
    /**
     * XROWRecurringOrderItem
     *
     * @var XROWRecurringOrderItem
     */
    public $item;
    public $contentObject;
    public $dataText;
    public $xml;
    public $info;
    public $relatedProduct;
    public $price;

	function xrowDefaultSubscriptionHandler( $itemID = false )
	{
	    if ( $itemID !== false )
	    {
	        $this->item = XROWRecurringOrderItem::fetch( $itemID );
	        if ( is_object( $this->item ) )
	           $this->info = $this->getInfo();
	    }
	}

	/*!
     \return list of supported attributes
    */
    function attributes()
    {
        return array( 'price',
                      'related_product',
                      'product_name',
                      'info',
                      'name',
                      'contentobject'
                     );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

    function &attribute( $name )
    {
        switch ( $name )
        {
            case 'price':
            {
                return $this->getPrice();
            }break;

            case 'related_product':
            {
                return $this->relatedProduct();
            }break;

            case 'product_name':
            {
                return $this->getProductName();
            }break;

            case 'info':
            {
                return $this->getInfo();
            }break;

            case 'contentobject':
            {
                return $this->contentObject();
            }break;

            case 'name':
            {
                return $this->getName();
            }break;
        }
    }

	function getInfo()
	{
	    if ( is_object( $this->item ) )
	    {
	        $this->info = $this->item->attribute( 'content' );
	        return $this->info;
	    }
	    return array();
	}

	function relatedProduct()
	{
	    if ( is_object( $this->relatedProduct ) )
	       return $this->relatedProduct;

	    if ( isset( $this->info['product-relation'] ) )
	       $this->relatedProduct = eZContentObject::fetch( $this->info['product-relation'] );

	    return $this->relatedProduct;
	}

	function getProductName()
	{
		$relatedProduct = $this->relatedProduct();
		if ( is_object( $relatedProduct ) )
		    return $relatedProduct->attribute( 'name' );
		else
		    return '';
	}

	function contentObject()
	{
	    if ( isset( $this->contentObject ) )
	        return $this->contentObject;

	    if ( is_object( $this->item ) )
	    {
	        $this->contentObject = eZContentObject::fetch( $this->item->attribute( 'contentobject_id' ) );
	        return $this->contentObject;
	    }
	}

	function getName()
	{
	    $contentObject = $this->contentObject();
	    if ( is_object( $contentObject ) )
	        return $contentObject->attribute( 'name' );
	    else
	        return '';
	}

	function getPrice()
	{
	    if ( isset( $this->price ) )
	       return $this->price;

	    $relatedProduct = $this->relatedProduct();
		if ( is_object( $relatedProduct ) )
		{
    		$attributes = $relatedProduct->contentObjectAttributes();

            $priceFound = false;

            include_once( 'kernel/shop/classes/ezshopfunctions.php' );

            foreach ( $attributes as $attribute )
            {
                $dataType = $attribute->dataType();
                if ( eZShopFunctions::isProductDatatype( $dataType->isA() ) )
                {
                    $priceObj =& $attribute->content();
                    $this->price = $priceObj->attribute( 'price' );
                    return $this->price;
                }
            }
		}
	}

	/*!
     \static
     This can be called like xrowDefaultSubscriptionHandler::createDOMTreefromArray( $name, $array )
    */
    function createDOMTreefromArray( $name, $array, $root = false )
    {
    	// @TODO rewrite with PHP DOM
        $doc = new eZDOMDocument( $name );
        if ( !$root )
            $root = $doc->createElementNode( $name );

        $keys = array_keys( $array );
        foreach ( $keys as $key )
        {
            if ( is_array( $array[$key] ) )
            {
                $node = xrowDefaultSubscriptionHandler::createDOMTreefromArray( $key, $array[$key] );
                $root->appendChild( $node );
            }
            else
            {
                $node = $doc->createElementNode( (string)$key );
                $node->appendChild( $doc->createTextNode( $array[$key] ) );
                $root->appendChild( $node );
            }
            unset( $node );
        }
        return $root;
    }

    /*!
     \static
     This can be called like xrowDefaultSubscriptionHandler::createArrayfromXML( $xmlDoc )
    */
    function createArrayfromXML( $xmlDoc )
    {
    	// @TODO rewrite with PHP DOM
        $result = array();
        $xml = new eZXML();
        $dom = $xml->domTree( $xmlDoc );
        if ( is_object( $dom ) )
        {
            $node = $dom->get_root();
            $children = $node->children();
            foreach ( $children as $child )
            {
                $contentnode = $child->firstChild();
                if ( $contentnode->type === EZ_XML_NODE_TEXT )
                {
                    $result[$child->name()] = $contentnode->textContent();
                }
                else
                {
                    $result[$child->name()] = xrowDefaultSubscriptionHandler::createArrayfromDOMNODE( $child );
                }
            }
        }
        return $result;
    }
    /*!
     \static
     This can be called like xrowDefaultSubscriptionHandler::createArrayfromDOMNODE( $node )
    */
    function createArrayfromDOMNODE( $node )
    {
    	// @TODO rewrite with PHP DOM
        $result = array();
        if ( is_object( $node ) )
        {
            $children = $node->children();
            foreach ( $children as $child )
            {
                $contentnode = $child->firstChild();
                if ( $contentnode->type === EZ_XML_NODE_TEXT )
                {
                    $result[$child->name()] = $contentnode->textContent();
                }
                else
                {
                    $result[$child->name()] = xrowDefaultSubscriptionHandler::createArrayfromDOMNODE( $child );
                }
            }
        }
        return $result;
    }

    function signupAction()
    {
    }
    function cancleAction()
    {
    }
    function removeAction()
    {
    }
    function suspendAction()
    {
    }
}
?>