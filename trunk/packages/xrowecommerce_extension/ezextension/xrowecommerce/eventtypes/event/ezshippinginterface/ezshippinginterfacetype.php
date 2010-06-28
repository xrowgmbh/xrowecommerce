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
        $handling_fee = $ini->variable( "Settings", "HandlingFeeAmount" );
        $handling_fee_include = $ini->variable( "Settings", "HandlingFeeInclude" );
        $add_handling_fee = $ini->variable( "Settings", "HandlingFee" );
        $handling_fee_name = $ini->variable( "Settings", "HandlingFeeName" );
        $free_shippingitem_reduce = $ini->variable( "Settings", "FreeShippingitemReduce" );
        
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
            
            if ( in_array( $item->attribute( 'contentobject_id' ), $FreeShippingProducts ) )
            {
                if ( $item->ItemCount >= 2 )
                {
                    $freeshippingproduct = true;
                }
            }
            
            // Fetch object
            $co = eZContentObject::fetch( $item->attribute( 'contentobject_id' ) );
            
            // Fetch object datamap
            $dm = $co->dataMap();
            
            // FreeShipping Item check
            if ( array_key_exists( 'freeshipping', $dm ) and $dm['freeshipping']->DataInt == 1 )
            {
                $freeshippingproduct = true;
            }
            else
            {
                $freeshippingproduct = false;
            }
            
            // FreeHandling Item check
            if ( array_key_exists( 'freehandling', $dm ) and $dm["freehandling"]->DataInt == 1 )
            {
                $freehandlingproduct = true;
            }
            else
            {
                $freehandlingproduct = false;
            }
            
            // Hazardous Item check
            if ( array_key_exists( 'hazardous', $dm ) and $dm["hazardous"]->DataInt == 1 )
            {
                if ( $shipping_country != "USA" and $shipping_country != "CAN" or $shippingtype == 4 or $shippingtype == 5 )
                {
                    $hazardousproducts[] = $item;
                    $item->remove();
                    continue;
                }
            }
            

            /*START ABSTRACTION LAYER NEEDED FOR WEIGHT

            //MX specific datatype remove later when we have abstraction
            $found = false;
            foreach ( $dm as $attribute_name => $attribute )
            {
                if ( $attribute->DataTypeString == 'mxmeasuredata' )
                {
                    $found = true;
                    break;
                }
            }
            if ( $found )
            {
                $content = $dm[$attribute_name]->content();
                
                if ( ! empty( $content->Weight ) )
                {
                    $totalweight += (float) $content->Weight * $item->ItemCount;
                    continue;
                }
                else
                {
                    eZDebug::writeDebug( $co->name() . " #" . $co->ID, "Zero Weight Product  in attribute mxmeasuredata" );
                }
            }

            $found = false;
            foreach ( $dm as $attribute_name => $attribute )
            {
                if ( $attribute->DataTypeString == eZOption2Type::OPTION2 )
                {
                    $found = true;
                    break;
                }
            }
            //Variation with option 2
            if ( ! empty( $option ) and $found )
            {
                $optionID = $option->OptionItemID;
                
                if ( array_key_exists( $attribute_name, $dm ) )
                {
                    $content = $dm[$attribute_name]->content();
                    if ( ! empty( $content->Options[$option->OptionItemID]["weight"] ) )
                    {
                        $totalweight += (float) $content->Options[$option->OptionItemID]["weight"] * $item->ItemCount;
                        continue;
                    }
                    else
                    {
                        eZDebug::writeDebug( $co->name() . " #" . $co->ID, "Zero Weight Product in attribute option" );
                    }
                }
            }
            

            // Conditional, if weight is defined in datamap array
            if ( array_key_exists( 'weight', $dm ) )
            {
                // Fetch weight
                $weight = $dm['weight']->content();
                if ( ! empty( $contentoptselect["weight"] ) )
                {
                    $totalweight += $weight * $item->ItemCount;
                    continue;
                }
                else
                {
                    eZDebug::writeDebug( $co->name() . " #" . $co->ID, "Zero Weight Product in attribute weight" );
                }
            
            }
            
        END ABSTRACTION LAYER NEEDED FOR WEIGHT */
        }
        // Order product total weight calculation
                $ini = eZINI::instance( "xrowecommerce.ini" );
            
            // ABSTRACTION LAYER FOR WEIGHT
            // Also builds Packagelist
            $totalweight = 0;
            $boxweight = 0;
            if ( $ini->hasVariable( 'ShippingInterfaceSettings', 'ShippingInterface' ) and class_exists( $ini->variable( 'ShippingInterfaceSettings', 'ShippingInterface' ) ) )
            {

                $interfaceName = $ini->variable( 'ShippingInterfaceSettings', 'ShippingInterface' );

                    $impl = new $interfaceName( );
                    $boxes = $impl->getBoxes( $order );
                    foreach ( $boxes as $box )
                    {
                        $boxweight += $box->totalWeight();
                    }
                    eZDebug::writeDebug( $boxweight, "Weight of Packages" );
                    $products = $impl->getProducts( $order );
                    $packlist = $impl->compute( $boxes, $products );
                    $totalweight = 0;
                    foreach ( $packlist as $package )
                    {
                        $totalweight += $package->totalWeight();
                    }
                    $totalboxweight = $totalweight - $boxweight;
                    eZDebug::writeDebug( $totalboxweight, "Weight of Products" );
                    eZDebug::writeDebug( $totalweight, "Weight of Packages with Products" );
                    $xmlstring = $order->attribute( 'data_text_1' );
                    if ( $xmlstring != null )
                    {
                        $doc = new DOMDocument( );
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
                throw new Exception( "Shipping Interface not set. xrowecommerce.ini[ShippingInterfaceSettings][ShippingInterface] ");
            }

        // @TODO show template that hazardous items got removed
        /*
        include_once( 'kernel/common/template.php' );
        $tpl = eZTemplate::factory();
        $tpl->setVariable( "hazardous", $hazardousproducts );
        */
        
        #### SHIPPING COST CALCULATION
        $shippingerror = false;

        $gateway = xrowShippingInterface::instanceByMethod( $shippingtype );

        if ( $gateway )
        {
            try
            {
                $gateway->order = $order;
                if ( $totalweight > 0 && $totalweight < 1 )
                {
                    $totalweight = 1;
                }
                eZDebug::writeDebug( $totalweight, "Order Weight" );
                $gateway->setOrder( $order );
                $gateway->setWeight( $totalweight );
                $gateway->setAddressTo( $shipping_country, $shipping_state, $shipping_zip, $shipping_city );
                $cost = $gateway->getPrice();
                $description = $gateway->description();
                
                if ( $freeshippingproduct )
                {
                    if ( $cost >= $free_shippingitem_reduce )
                        $cost = $cost - $free_shippingitem_reduce;
                    else
                        $cost = 0.00;
                    $description_discounted_shipping = "Discounted Shipping! $shipping_type_name";
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
        /*
        if ( $shippingtype == 3 OR $shippingtype == 4 OR $shippingtype == 5)
        {
            // UPS Service
            $ups = ShippingInterface::instance( "ups" );
            $shippingservicename    = array();
            $shippingservicename[3] = "UPS Ground";
            $shippingservicename[4] = "UPS Next Business Day Air";
            $shippingservicename[5] = "UPS 2nd Business Day Air";
            if ( $totalweight > 0 && $totalweight < 1 )
               $roundedweight = 1;
            else $roundedweight = $totalweight;
               $ups->setWeight( $roundedweight );
               
            if ( $shippingtype == "3" )
                $ups->setService( "03" );
            elseif ( $shippingtype == "4" )
                $ups->setService( "01" );
            elseif ( $shippingtype == "5" )
                $ups->setService( "02" );
            
            
            $shipping_country = $ups->convert_country( $shipping_country, "Alpha3", "Alpha2");
            $ups->setAddressTo( $shipping_country, $shipping_state, $shipping_zip, $shipping_city );
            
            $ups_price = $ups->getPrice();
            // adding 2$ handling fee
            $ups_price->costs->costs = $ups_price->costs->costs;
        
            if($ups_price->error)
            {
                //echo "Error: ".$ups_price->error->description." <br />";
                $shipping_type_name = $shippingservicename[$shippingtype]." for ( ".$totalweight." lbs): ".$ups_price->error->description.": Vendor will call you to calculate the shipping price!";
                $cost = 0.00;
                $shippingerror = true;
            }
            else 
            {
                //echo $ups_price->costs->shipping_type." ".$ups_price->costs->currency_unit." ".$ups_price->costs->costs."<br />";
                $shipping_type_name =$shippingservicename[$shippingtype]." for ( ".$totalweight." lbs)";
                $cost = (double)$ups_price->costs->costs;
                if ( $add_handling_fee == "enabled" AND $handling_fee_include == "enabled" AND $handling_fee > 0 AND !$freehandlingproduct)
                    $cost = $cost + $handling_fee;
            }
            ############# End UPS of calculation #############
            $description = $shipping_type_name;
            
            if (!$shippingerror)
            {

                if ( $freeshippingproduct and $shippingtype == 3)
                {
                    if ( $cost >= $free_shippingitem_reduce )
                        $cost = $cost - $free_shippingitem_reduce;
                    else
                        $cost = 0.00;
                    $description_discounted_shipping = "Discounted Shipping! $shipping_type_name";
                    $description = $description_discounted_shipping;
                }
                
            }
            
        }
        
        
        
        elseif ( $shippingtype == "6" OR $shippingtype == "7" )
        {
            // USPS Service
            $usps = ShippingInterface::instance( "usps" );
            $shippingservicename    = array();
            #$shippingservicename[6] = "Global Express Mail (EMS)";
            $shippingservicename[6] = "Express Mail International (EMS)";
            #$shippingservicename[7] = "Airmail Parcel Post";
            $shippingservicename[7] = "Global Express Guaranteed";
            $usps->setService( $shippingservicename[$shippingtype] );
            
            #Global Express Guaranteed
            #Global Express Guaranteed Non-Document Rectangular
            #Global Express Guaranteed Non-Document Non-Rectangular
            #Express Mail International (EMS)
            #Express Mail International (EMS) Flat Rate Envelope
            #Priority Mail International
            #Priority Mail International Flat Rate Box
            
            
            ############# Start of USPS calculation #############
            $shipping_country = $usps->convert_country( $shipping_country, "Alpha3", "Name");
            $usps->setAddressTo( $shipping_country, $shipping_state, $shipping_zip, $shipping_city );
            $usps->setPounds( "0" );
            $usps->setContainer( "Flat Rate Box" );
            $totalweight_ounces = round( $totalweight * 16 );
            $usps->setOunces ( $totalweight_ounces );
            $usps_price = $usps->getPrice();
            
            if ( $usps_price->error )
            {
                //echo "Error in USPS: ".$usps_price->error->description." <br />";
                $shipping_type_name = "USPS ".$shippingservicename[$shippingtype]." for ( ".$totalweight." lbs): ".$usps_price->error->description.": Vendor will call you to calculate the shipping price!";
                $cost = 0.00;
                $shippingerror = true;
            }
            else 
            {
                $usps_service = $usps->getService($usps_price->list, $shippingservicename[$shippingtype]);

                //echo $usps_service->svcdescription.": $".$usps_service->rate."<br />";
                $shipping_type_name = "USPS ".$shippingservicename[$shippingtype]." for ( ".$totalweight." lbs)";

                if ( is_object( $usps_service ) )
                    $cost = (double)$usps_service->rate;
                else
                    $cost = 0.00;
                if ( $add_handling_fee == "enabled" AND $handling_fee_include == "enabled" AND $handling_fee > 0 AND !$freehandlingproduct)
                    $cost = $cost + $handling_fee;
            }
            ############# End of USPS calculation #############
            $description = $shipping_type_name;
        }
        else
        {
            // Wrong shippingtype ID
            $description = "Sorry, the shipping type you have selected is no longer supported. Vendor will call you to calculate the shipping price!";
            $cost = 0.00;
        }
*/
        // adding Handling_fee to shipping_cost?
        

        // get actual tax value
        $vat_value = eZVATManager::getVAT( false, false );
        /*
        if( $tax_country == "USA" AND $tax_state == "NY" AND $cost > 0 )
                $vat_value =  8.375;
        
        if( $tax_country == "USA" AND $tax_state == "CT" AND $cost > 0 )
            $vat_value =  6.00 ;
          */
        $r = eZOrderItem::fetchListByType( $orderID, 'handlingfee' );
        if ( count( $r ) > 0 )
        {
            foreach ( $r as $item )
            {
                $item->remove();
            }
        }
        if ( $add_handling_fee == "enabled" and $handling_fee_include == "enabled" and $handling_fee > 0 and ! $freehandlingproduct )
        {
            $cost = $cost + $handling_fee;
        }
        else 
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
        // Remove any existing order shipping item before appendeding a new item
        $r = eZOrderItem::fetchListByType( $orderID, 'shippingcost' );
        if ( count( $r ) > 0 )
        {
            foreach ( $r as $item )
            {
                $item->remove();
            }
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
