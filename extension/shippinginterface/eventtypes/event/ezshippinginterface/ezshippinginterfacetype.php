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
    function eZShippingInterfaceType()
    {
        $this->eZWorkflowEventType( eZShippingInterfaceType::WORKFLOW_TYPE_STRING, ezi18n( 'kernel/workflow/event', "Shipping Interface" ) );
        $this->setTriggerTypes( array( 
                                'shop' => array( 'confirmorder' => array ( 'before' ) ),
                                'recurringorders' => array( 'checkout' => array ( 'before' ) ) 
                            
        ) );
    }
    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        switch( $attr )
        {
            case 'methods' :
            {
                return xrowShippingInterface::fetchAll();
            }break;
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
        else if ( $http->hasPostVariable( $VariableFlag ) and !$http->hasPostVariable( $Variable ) )
        {
        	$event->setAttribute( 'data_text3', serialize( array() ) );
        	$event->store();
        }
        
    }
    function attributes()
    {
        return array_merge( array( 'methods' ),
                            eZWorkflowEventType::attributes() );
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
        $totalweight = 0;
        // Fetch Workflow Settings
        $ini = eZINI::instance( 'workflow.ini' );
        
        // Setting to control calculations (Product Option Attribute Processing)
        $settingUseeZoption2ProductVariations = ( $ini->variable( "ShippingInterface", "eZoption2ProductVariations" ) == 'Enabled' );
        $fastlash_id = $ini->variable( "ShippingInterface", "fastlash_id" );
        $handling_fee = $ini->variable( "ShippingInterface", "handling_fee" );
        $handling_fee_include = $ini->variable( "ShippingInterface", "handling_fee_include" );
        $add_handling_fee = $ini->variable( "ShippingInterface", "add_handling_fee" );
        $handling_fee_name = $ini->variable( "ShippingInterface", "handling_fee_name" );
        $free_shippingitem_reduce = $ini->variable( "ShippingInterface", "free_shippingitem_reduce" );

        // Process parameters
        $parameters = $process->attribute( 'parameter_list' );
        $orderID = $parameters['order_id'];

        // Fetch order
        $order = eZOrder::fetch( $orderID );

        // If order class was fetched
        if ( get_class( $order ) == 'eZOrder' )
        {
            // Fetch order ezxml document
            $xml = new eZXML();
            $xmlDoc = $order->attribute( 'data_text_1' );

            // If document is not empty
            if( $xmlDoc != null )
            {
                // get the dom tree of elements
                $dom = $xml->domTree( $xmlDoc );

                // Fetch order state
                if ($statedom = $dom->elementsByName( "state" ))
                    $state = $statedom[0]->textContent();

                // Fetch order shipping address checkbox
                if ($shippingdom = $dom->elementsByName( "shipping" ))
                    $shipping = $shippingdom[0]->textContent();

                // Fetch order shipping type
                if ($shippingtypedom = $dom->elementsByName( "shippingtype" ))
                    $shippingtype = $shippingtypedom[0]->textContent();

                // Fetch order country
                if ($shippingcountrydom = $dom->elementsByName( "country" ))
                    $shipping_country = $shippingcountrydom[0]->textContent();

                // Fetch order shipping address country
                if ($shippingcountrydom = $dom->elementsByName( "s_country" ))
                    $shipping_s_country = $shippingcountrydom[0]->textContent();
                    
                    
                // Fetch order city
                if ($shippingcitydom = $dom->elementsByName( "city" ))
                    $shipping_city = $shippingcitydom[0]->textContent();

                // Fetch order shipping address country
                if ($shippingcitydom = $dom->elementsByName( "s_city" ))
                    $shipping_s_city = $shippingcitydom[0]->textContent();
                    
                // Fetch order zip
                if ($shippingzipdom = $dom->elementsByName( "zip" ))
                    $shipping_zip = $shippingzipdom[0]->textContent();

                // Fetch order shipping address country
                if ($shippingzipdom = $dom->elementsByName( "s_zip" ))
                    $shipping_s_zip = $shippingzipdom[0]->textContent();
                    
                // Fetch order state
                if ($shippingstatedom = $dom->elementsByName( "state" ))
                    $shipping_state = $shippingstatedom[0]->textContent();

                // Fetch order shipping address country
                if ($shippingstatedom = $dom->elementsByName( "s_state" ))
                    $shipping_s_state = $shippingstatedom[0]->textContent();

                    
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
        $freeshippingproduct=false;
        $freehandlingproduct=false;
        
        $hazardousproducts=array();
        foreach ( $items as $item )
        {
            // fetch order item option
            $option = eZProductCollectionItemOption::fetchList( $item->attribute( "id" ) );

            if ( is_array( $option ) and array_key_exists( 0 , $option ) )
                $option = $option[0];

            // START added by Soeren. if there are more than 2 times the Fast Eye lash in cart freeshippingproduct is set to true.
            if ( $item->attribute( 'contentobject_id' ) === $fastlash_id)
            {
                if ( $item->ItemCount >= 2 )
                    $freeshippingproduct=true;
            }
            // STOP added by Soeren. if there are more than 2 times the Fast Eye lash in cart freeshippingproduct is set to true.
            
            // Fetch object
            $co = eZContentObject::fetch( $item->attribute( 'contentobject_id' ) );

            // Fetch object datamap
            $dm = $co->dataMap();
            
            // FreeShipping Item check
            if ( $dm['freeshipping']->DataInt == 1 )
               $freeshippingproduct=true;
           // FreeShipping Item check
           
           // FreeHandling Item check
            if ( is_object( $dm['freehandling'] ) )
            {
                if ( $dm["freehandling"]->DataInt == 1 )
                    $freehandlingproduct = true;
            }
               
           // FreeHandling Item check
           
           // Hazardous Item check
            if ( is_object( $dm['hazardous'] ) )
            {
                if ( $dm["hazardous"]->DataInt == 1 )
                {
                    if ($shipping_country != "USA" AND $shipping_country != "CAN" OR $shippingtype == 4 OR $shippingtype == 5)
                    {
                        $hazardousproducts[] = $item;
                    $item->remove();
                    continue;
                    }
                }
            }
               
           // FreeHandling Item check
            
            if ( $settingUseeZoption2ProductVariations == 'Enabled' )
            {
                if (!empty($option) )
                {
                    $optionID=$option->OptionItemID;


                    /*
                     Variation
                    */
                    if ( $dm['variation'] )
                    {
                        $content = $dm['variation']->content();
                        $contentopt = $content->Options;
                        $contentoptselect = $contentopt[$optionID];

                        $weight = $contentoptselect["weight"] * $item->ItemCount;
                        $totalweight = $totalweight + $weight;

                        if ( !is_object( $content ) )
                            continue;
                    }
                }
                else
                {
                    // Conditional, if weight is defined in datamap array
                    if ( $dm['weight'] )
                    {
                        // Fetch weight
                        $count = $item->ItemCount;
                        $weight = $dm['weight']->content();
                        $subtotalweight = $weight * $count;
                        $totalweight = $totalweight + $subtotalweight;
                    }
                }
            }
            else
            {
                // Conditional, if weight is defined in datamap array
                if ( $dm['weight'] )
                {
                    // Fetch weight
                    $count = $item->ItemCount;
                    $weight = $dm['weight']->content();
                    $totalweight = $weight * $count;
                }
            }
        } // End: Order product total weight calculation


        // @TODO show template that hazardous items got removed
        /*
        include_once( 'kernel/common/template.php' );
        $tpl = templateInit();
        $tpl->setVariable( "hazardous", $hazardousproducts );
		*/
        
        #### SHIPPING COST CALCULATION
        $shippingerror = false;

        $gateway  = xrowShippingInterface::instanceByMethod( $shippingtype );

        if( $gateway )
        {
        	try
        	{
        		$gateway->method = $shippingtype;
        		if ( $totalweight > 0 && $totalweight < 1 )
        		{
        			$totalweight = 1;
        		}
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
        		eZDebug::writeError( 'Gateway error('.$shippingtype.'): ' . $e->getMessage(), 'eZShippingInterfaceType::execute()' );
        		$description = "An error occurred. Vendor will contact you to calculate the shipping price.";
        		$cost = 0.00;
        	}
        }
        else
        {
        	$description = "Sorry, the shipping type you have selected is no longer supported. Vendor will call you to calculate the shipping price!";
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
        $vat_value = 0;
        $vat_value = eZVATManager::getVAT();
        /*
        if( $tax_country == "USA" AND $tax_state == "NY" AND $cost > 0 )
                $vat_value =  8.375;
        
        if( $tax_country == "USA" AND $tax_state == "CT" AND $cost > 0 )
            $vat_value =  6.00 ;
          */      
                $r = eZOrderItem::fetchListByType( $orderID, 'handlingfee' );
            if( count( $r ) > 0 )
            {
                foreach ( $r as $item )
                {
                    $item->remove();
                }
	        }
            if ( $add_handling_fee == "enabled" AND $handling_fee_include == "enabled" AND $handling_fee > 0 AND !$freehandlingproduct )
        	{
                    $cost = $cost + $handling_fee;
        	}
        	else if( !$freehandlingproduct AND $add_handling_fee == "enabled" AND $handling_fee_include != "enabled" )
        	{
                $HandlingItem = new eZOrderItem( array( 'order_id' => $orderID,
                                                 'description' => $handling_fee_name,
                                                 'price' => $handling_fee,
                                                 'vat_value' => $vat_value,
                                                 'type' => 'handlingfee')
                                          );
                $HandlingItem->store();
        	}
            // Remove any existing order shipping item before appendeding a new item
            $r = eZOrderItem::fetchListByType( $orderID, 'shippingcost' );
            if( count( $r ) > 0 )
            {
                foreach ( $r as $item )
                {
                    $item->remove();
                }
	        }

             $orderItem = new eZOrderItem( array( 'order_id' => $orderID,
                                                 'description' => $description,
                                                 'price' => $cost,
                                                 'vat_value' => $vat_value,
                                                 'type' => 'shippingcost' )
                                          );
            $orderItem->store();
        return eZWorkflowType::STATUS_ACCEPTED;
    }
}

eZWorkflowEventType::registerEventType( eZShippingInterfaceType::WORKFLOW_TYPE_STRING, "ezshippinginterfacetype" );

?>