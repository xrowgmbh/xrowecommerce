<?php

//
// Definition of eZShippingInterfaceType class
//
//
/*! \file ezshippinginterfacetype.php
*/

/*!
  \class eZShippingInterfaceType ezshippinginterfacetype.php
  \brief The class eZShippingInterfaceType handles adding shipping cost to an order via UPS and USPS
*/

class eZShippingInterfaceType extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = 'ezshippinginterface';

    /*!
     Constructor
    */
    function __construct()
    {
        $this->eZWorkflowEventType( eZShippingInterfaceType::WORKFLOW_TYPE_STRING, ezpI18n::tr( 'kernel/workflow/event', "Shipping Interface" ) );
        $this->setTriggerTypes( array( 
            'shop' => array( 
                'confirmorder' => array( 
                    'before' 
                ) 
            ) , 
            'recurringorders' => array( 
                'checkout' => array( 
                    'before' 
                ) 
            ) 
        ) );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        switch ( $attr )
        {
            case 'methods':
                {
                    return xrowShippingInterface::fetchAll();
                }
                break;
            default:
                return eZWorkflowEventType::attribute( $attr );
        }
    }

    function fetchHTTPInput( $http, $base, $event )
    {
        $VariableFlag = $base . "_ezshippinginterface_active_shipping_" . $event->attribute( "id" ) . "_flag";
        $Variable = $base . "_ezshippinginterface_active_shipping_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $VariableFlag ) and $http->hasPostVariable( $Variable ) )
        {
            $Value = $http->postVariable( $Variable );
            $event->setAttribute( 'data_text3', serialize( $Value ) );
            echo $event->store();
        
        }
        else 
            if ( $http->hasPostVariable( $VariableFlag ) and ! $http->hasPostVariable( $Variable ) )
            {
                $event->setAttribute( 'data_text3', serialize( array() ) );
                $event->store();
            }
    
    }

    function attributes()
    {
        return array_merge( array( 
            'methods' 
        ), eZWorkflowEventType::attributes() );
    }

    function workflowEventContent( $event )
    {
        $id = $event->attribute( "id" );
        $version = $event->attribute( "version" );
        $content = unserialize( $event->attribute( 'data_text3' ) );
        return $content;
    }

    function execute( $process, $event )
    {
        
        // Fetch Workflow Settings
        $ini = eZINI::instance( 'shipping.ini' );
        
        // Setting to control calculations (Product Option Attribute Processing)
        $settingUseeZoption2ProductVariations = ( $ini->variable( "Settings", "eZoption2ProductVariations" ) == 'Enabled' );
        $FreeShippingProducts = $ini->variable( "Settings", "FreeShippingProducts" );
        $FreeShippingAdditionalProducts = $ini->variable( "Settings", "FreeShippingAdditionalProducts" );
        $FreeShippingProductConditions = $ini->variable( "Settings", "FreeShippingProductConditions" );
        $FreeShippingHandlingGateways = $ini->variable( "Settings", "FreeShippingHandlingGateways" );
        $FreeShippingHandlingCountries = $ini->variable( "Settings", "FreeShippingHandlingCountries" );
        $handling_fee = $ini->variable( "Settings", "HandlingFeeAmount" );
        $handling_fee_include = $ini->variable( "Settings", "HandlingFeeInclude" );
        $add_handling_fee = $ini->variable( "Settings", "HandlingFee" );
        $handling_fee_name = $ini->variable( "Settings", "HandlingFeeName" );
        
        // Process parameters
        $parameters = $process->attribute( 'parameter_list' );
        $orderID = $parameters['order_id'];
        
        // Fetch order
        $order = eZOrder::fetch( $orderID );
        
        // If order class was fetched
        if ( $order instanceof eZOrder )
        {
            $xml = new SimpleXMLElement( $order->attribute( 'data_text_1' ) );
            
            if ( $xml != null )
            {
                $state = (string) $xml->state;
                $shipping = (string) $xml->shipping;
                $shippingtype = (string) $xml->shippingtype;
                $shipping_country = (string) $xml->country;
                $shipping_s_country = (string) $xml->s_country;
                $shipping_city = (string) $xml->city;
                $shipping_s_city = (string) $xml->s_city;
                $shipping_zip = (string) $xml->zip;
                $shipping_s_zip = (string) $xml->s_zip;
                $shipping_state = (string) $xml->state;
                $shipping_s_state = (string) $xml->s_state;
                
                // If order has a shipping country use it instead.
                if ( isset( $shipping_s_country ) and $shipping_s_country != '' )
                    $shipping_country = $shipping_s_country;
                
     // If order has a shipping state use it instead.
                if ( isset( $shipping_s_state ) and $shipping_s_state != '' )
                    $shipping_state = $shipping_s_state;
                
     // If order has a shipping zip use it instead.
                if ( isset( $shipping_s_zip ) and $shipping_s_zip != '' )
                    $shipping_zip = $shipping_s_zip;
                
     // If order has a shipping city use it instead.
                if ( isset( $shipping_s_city ) and $shipping_s_city != '' )
                    $shipping_city = $shipping_s_city;
            }
        }
        
        $gateway = xrowShippingInterface::instanceByMethod( $shippingtype );

        $tax_country = $shipping_country;
        $tax_state = $shipping_state;
        
        // Fetch order products
        $productcollection = $order->productCollection();
        
        // Fetch order items
        $items = $productcollection->itemList();
        $freeshippingproduct = false;
        $freehandlingproduct = false;
        
        $hazardousproducts = array();
        foreach ( $items as $item )
        {
            // fetch order item option
            $option = eZProductCollectionItemOption::fetchList( $item->attribute( "id" ) );
            
            if ( is_array( $option ) and array_key_exists( 0, $option ) )
                $option = $option[0];
            
     // Fetch object
            $co = eZContentObject::fetch( $item->attribute( 'contentobject_id' ) );
            
            // Fetch object datamap
            $dm = $co->dataMap();
            
            // FreeShipping Item check
            if ( in_array( $shippingtype, $FreeShippingHandlingGateways ) and in_array( $shipping_country, $FreeShippingHandlingCountries ) and in_array( $item->attribute( 'contentobject_id' ), $FreeShippingProducts ) )
            {
                $freeshippingproduct = true;
            }
            elseif ( $FreeShippingAdditionalProducts != "enabled" )
            {
                $freeshippingproduct = false;
            }
            
            if ( array_key_exists( $item->attribute( 'contentobject_id' ), $FreeShippingProductConditions ) and $item->ItemCount >= $FreeShippingProductConditions[$item->attribute( 'contentobject_id' )] and in_array( $shippingtype, $FreeShippingHandlingGateways ) and in_array( $shipping_country, $FreeShippingHandlingCountries ) and array_key_exists( 'freeshipping', $dm ) and $dm['freeshipping']->DataInt == 1 )
            {
                $freeshippingproduct = true;
            }
            elseif ( $FreeShippingAdditionalProducts != "enabled" )
            {
                $freeshippingproduct = false;
            }
            
            // FreeHandling Item check
            if ( in_array( $shippingtype, $FreeShippingHandlingGateways ) and in_array( $shipping_country, $FreeShippingHandlingCountries ) and array_key_exists( 'freehandling', $dm ) and $dm["freehandling"]->DataInt == 1 )
            {
                if ( $item->ItemCount >= $FreeShippingProductConditions[$item->attribute( 'contentobject_id' )] )
                {
                    $freehandlingproduct = true;
                }
                elseif ( $FreeShippingAdditionalProducts != "enabled" )
                {
                    $freehandlingproduct = false;
                }
            }
            
            // Hazardous Item check
            if ( array_key_exists( 'hazardous', $dm ) and $dm["hazardous"]->DataInt == 1 )
            {
                if ( $gateway->is_air === true )
                {
                    $hazardousproducts[] = $item;
                    $item->remove();
                    continue;
                }
            }
        }

        // Order product total weight calculation
        $ini = eZINI::instance( 'xrowecommerce.ini' );
        
        // ABSTRACTION LAYER FOR WEIGHT
        // Also builds Packagelist
        $totalweight = 0;
        $boxweight = 0;
        if ( $ini->hasVariable( 'ShippingInterfaceSettings', 'ShippingInterface' ) and class_exists( $ini->variable( 'ShippingInterfaceSettings', 'ShippingInterface' ) ) )
        {
            $interfaceName = $ini->variable( 'ShippingInterfaceSettings', 'ShippingInterface' );
            $impl = new $interfaceName();
        }
        else
        {
            $impl = new xrowDefaultShipping();
        }
        
        if ( $impl instanceof xrowShipment )
        {
            $boxes = $impl->getBoxes( $order );
            foreach ( $boxes as $box )
            {
                $boxweight += $box->totalWeight();
            }
            eZDebug::writeDebug( $boxweight, 'Weight of Packages' );
            $products = $impl->getProducts( $order );
            $packlist = $impl->compute( $boxes, $products );
            $totalweight = 0;
            foreach ( $packlist as $package )
            {
                $totalweight += $package->totalWeight();
            }
            $totalboxweight = $totalweight - $boxweight;
            eZDebug::writeDebug( $totalboxweight, 'Weight of Products' );
            eZDebug::writeDebug( $totalweight, 'Weight of Packages with Products' );
            $xmlstring = $order->attribute( 'data_text_1' );
            if ( $xmlstring != null )
            {
                $doc = new DOMDocument();
                $doc->loadXML( $xmlstring );
                $root = $doc->documentElement;
                $packagelist = $root->getElementsByTagName( xrowECommerce::ACCOUNT_KEY_PACKAGES );
                if ( $packagelist->length == 1 )
                {
                    $root->removeChild( $packagelist->item( 0 ) );
                }
                $packagelist = $doc->createElement( xrowECommerce::ACCOUNT_KEY_PACKAGES );
                foreach ( $packlist as $parcel )
                {
                    $domPackage = $doc->createElement( 'package' );
                    $domPackage->setAttribute( 'name', $parcel->name );
                    $domPackage->setAttribute( 'id', $parcel->id );
                    $list = $parcel->contains;
                    while ( count( $list ) > 0 )
                    {
                        $product = array_shift( $list );
                        $i = 1;
                        foreach ( $list as $key2 => $product2 )
                        {
                            if ( $product->id == $product2->id )
                            {
                                $i ++;
                                unset( $list[$key2] );
                            }
                        }
                        $domProduct = $doc->createElement( 'product' );
                        $domProduct->setAttribute( 'name', $product->name );
                        $domProduct->setAttribute( 'id', $product->id );
                        $domProduct->setAttribute( 'amount', $i );
                        $domPackage->appendChild( $domProduct );
                    }
                    $packagelist->appendChild( $domPackage );
                }
                $root->appendChild( $packagelist );
            }
            
            $order->setAttribute( 'data_text_1', $doc->saveXML() );
            $order->store();
        }
        else
        {
            throw new Exception( "Shipping Interface not set. xrowecommerce.ini[ShippingInterfaceSettings][ShippingInterface] " );
        }

        // @TODO show template that hazardous items got removed
        /*

        $tpl = eZTemplate::factory();
        $tpl->setVariable( "hazardous", $hazardousproducts );
    return eZWorkflowType::STATUS_ACCEPTED;
        */
        
        #### SHIPPING COST CALCULATION
        $shippingerror = false;
        
        if ( $gateway )
        {
            try
            {
                $gateway->order = $order;
                if ( $totalweight >= 0 && $totalweight < 1 )
                {
                    $totalweight = 1;
                }
                eZDebug::writeDebug( $totalweight, "Order Weight" );
                
                $gateway->setOrder( $order );
                $gateway->setWeight( $totalweight );
                $gateway->setAddressTo( $shipping_country, $shipping_state, $shipping_zip, $shipping_city );
                $details = $gateway->getShippingDetails();
                if ( $details )
                {
                    $shippingmethod = $gateway->getService( $details->list, $details->method );
                }
                else
                {
                    $shippingmethod = false;
                }
                $description = $gateway->getDescription( $shippingmethod );
                $cost = $gateway->getPrice( $shippingmethod );
                if ( $freeshippingproduct )
                {
                    $cost = 0.00;
                    $description_discounted_shipping = "Discounted Shipping! $description";
                    $description = $description_discounted_shipping;
                }
            }
            catch ( xrowShippingException $e )
            {
                $process->Template = array();
                $process->Template['templateName'] = 'design:workflow/shipping/error_shipping.tpl';
                $process->Template['path'] = array( 
                    array( 
                        'url' => false , 
                        'text' => ezpI18n::tr( 'extension/xrowecommerce', 'Shipping Information' ) 
                    ) 
                );
                $process->Template['templateVars'] = array( 
                    'event' => $event , 
                    'message' => $e->getMessage() , 
                    'type' => $shippingtype 
                );
                
                return eZWorkflowType::STATUS_FETCH_TEMPLATE_REPEAT;
            }
            catch ( xrowShippingGatewayException $e )
            {
                $process->Template = array();
                $process->Template['templateName'] = 'design:workflow/shipping/error_shippinggateway.tpl';
                $process->Template['path'] = array( 
                    array( 
                        'url' => false , 
                        'text' => ezpI18n::tr( 'extension/xrowecommerce', 'Shipping Information' ) 
                    ) 
                );
                $process->Template['templateVars'] = array( 
                    'event' => $event , 
                    'message' => $e->getMessage() , 
                    'type' => $shippingtype 
                );
                
                return eZWorkflowType::STATUS_FETCH_TEMPLATE_REPEAT;
            }
        }
        else
        {
            $description = ezpI18n::tr( 'extension/xrowecommerce', "Sorry, the shipping method you have selected is no longer supported. Vendor will call you to calculate the shipping price." );
            $cost = 0.00;
        }

        // get actual tax value
        $vat_value = eZVATManager::getVAT( false, false );
        // adding Handling_fee to shipping_cost?
        $orderlist = eZOrderItem::fetchListByType( $orderID, 'handlingfee' );
        if ( count( $orderlist ) > 0 )
        {
            foreach ( $orderlist as $item )
            {
                $item->remove();
            }
        }
        if ( $add_handling_fee == "enabled" and $handling_fee_include == "enabled" and $handling_fee > 0 and ! $freehandlingproduct )
        {
            $cost = $cost + $handling_fee;
        }
        else
        {
            if ( ! $freehandlingproduct and $add_handling_fee == "enabled" and $handling_fee_include != "enabled" )
            {
                $HandlingItem = new eZOrderItem( array( 
                    'order_id' => $orderID , 
                    'description' => $handling_fee_name , 
                    'price' => $handling_fee , 
                    'vat_value' => $vat_value , 
                    'type' => 'handlingfee' 
                ) );
                $HandlingItem->store();
            }
        }

        // Remove any existing order shipping item before appendeding a new item
        $orderlist = eZOrderItem::fetchListByType( $orderID, 'shippingcost' );
        if ( count( $orderlist ) > 0 )
        {
            foreach ( $orderlist as $item )
            {
                $item->remove();
            }
        }
        
        $ini = eZINI::instance( 'shipping.ini' );
        if ( $ini->variable( 'Settings', 'ShowShippingWeight' ) == 'enabled' )
        {
            $description .= ' ( ' . $totalweight . ' ' . $ini->variable( 'Settings', 'WeightUnit' ) . ' )';
        }
        
        $orderItem = new eZOrderItem( array( 
            'order_id' => $orderID , 
            'description' => $description , 
            'price' => $cost , 
            'vat_value' => $vat_value , 
            'type' => 'shippingcost' 
        ) );
        $orderItem->store();

        return eZWorkflowType::STATUS_ACCEPTED;
    }
}

eZWorkflowEventType::registerEventType( eZShippingInterfaceType::WORKFLOW_TYPE_STRING, "ezshippinginterfacetype" );

?>