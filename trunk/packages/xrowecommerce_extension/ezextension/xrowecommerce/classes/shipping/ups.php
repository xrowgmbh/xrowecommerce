<?php

class UPS extends ShippingInterface 
{
    var $XpciVersion = "1.0001";
    var $context = "Default Shipping";
    var $to_residental = true;

    
    function methods() {
        return array( 
            array(
                'identifier' => '3', 
                'name' => 'UPS Ground (USA only)'
            ),
            array(
                'identifier' => '4',
                'name' => 'UPS Next Business Day Air (USA only)'
            ),
            array(
                'identifier' => '5',
                'name' => 'UPS 2nd Business Day Air (USA only)'
            ),
            array(
                'identifier' => 'ups_ground',
                'is_air' => false,
                'name' => 'UPS Ground (USA only)'
            ),
            array(
                'identifier' => 'ups_air_nextday',
                'is_air' => true,
                'name' => 'UPS Next Business Day Air (USA only)'
            ),
            array(
                'identifier' => 'ups_air_2ndday',
                'is_air' => true,
                'name' => 'UPS 2nd Business Day Air (USA only)'
            )
        );
    }

    function loadConfiguration()
    {
        $upsini = eZINI::instance( 'ups.ini' );
        $this->setLicense($upsini->variable( "Account", "AccessLicenseNumber" ));
        $this->ShipperNumber = $upsini->variable( "Account", "ShipperNumber" );
        $this->setUserID($upsini->variable( "Account", "Userid" ));
        $this->setPass($upsini->variable( "Account", "Password" ));
        $this->setServer($upsini->variable( "Connection", "URL" ));
        $this->setServices($upsini->variable( "Services", "Service_code" ));
        $this->setAvailableFor($upsini->variable( "AvailableSettings", "AvailableFor" ));
        $this->setAddressFrom( $upsini->variable( "ShipperSettings", "Country" ),
                               $upsini->variable( "ShipperSettings", "State" ),
                               $upsini->variable( "ShipperSettings", "Zip" ),
                               $upsini->variable( "ShipperSettings", "City" )  );
        $this->length_unit = "IN";
        $this->weight_unit = "LBS";
        $this->PackagingType = "02";
        $this->PickupType = "01";
    }
    
    function setAddressTo( $shipping_country, $shipping_state, $shipping_zip, $shipping_city )
    {
        $shipping_country = $this->convert_country( $shipping_country, "Alpha3", "Alpha2");
        parent::setAddressTo( $shipping_country, $shipping_state, $shipping_zip, $shipping_city );
    }
    
    function setXpciVersion($XpciVersion) {
        $this->XpciVersion = $XpciVersion;
    }
    
    function setContext($context) {
        $this->context = $context;
    }
    
    function settoResidental($to_residental) {
        $this->to_residental = $to_residental;
    }
    
    function convertservices($services)
    {
        if (count($services) > 0 )
        {
            $ups_services=array();
            foreach ($services as $service)
            {
                $parts=explode(":", $service);
                $ups_services[$parts[0]]=$parts[1];
                
            }
            return $ups_services;
        }
        else return false;
        
    }
    function setLicense($license) {
        $this->license = $license;
    }

    function setService($service) {
        $this->service = $service;
    }
    
    function setServices($services) {
         $this->services = $services;
    }

    function getService( $service_list, $service_name )
    {
        if ( $service_list[0]['shipping_type'] == $service_name )
        {
            return $service_list[0];
        }
        else
        {
            return false;
        }
    }
    
    function getPrice( $shippingmethod )
    {
        return (float)$shippingmethod['costs'];
    }
    
    function getDescription( $shippingmethod )
    {
        return $shippingmethod['description'];
    }

    function getShippingDetails()
    {
        if ( $this->method == "3" ||  $this->method == "ups_ground" )
        {
            $this->setService( "03" );
        }
        elseif ( $this->method == "4" ||  $this->method == "ups_air_nextday" )
        {
            $this->setService( "01" );
        }
        elseif ( $this->method == "5" || $this->method == "ups_air_2ndday" )
        {
            $this->setService( "02" );
        }
        $strXML = "<?xml version='1.0'?>
   <AccessRequest xml:lang='en-US'>
      <AccessLicenseNumber>".$this->license."</AccessLicenseNumber>
      <UserId>".$this->userid."</UserId>
      <Password>".$this->pass."</Password>
   </AccessRequest>";
        $strXML.=  "<?xml version=\"1.0\"?>
            <RatingServiceSelectionRequest>
              <Request>
                <TransactionReference>
                  <CustomerContext>".$this->context."</CustomerContext>
                  <XpciVersion>".$this->XpciVersion."</XpciVersion>
                </TransactionReference>
                <RequestAction>Rate</RequestAction>
                <RequestOption>Rate</RequestOption>
              </Request>
              <PickupType>
                <Code>".$this->PickupType."</Code>
              </PickupType>
              <Shipment>
                <Shipper>
                  <ShipperNumber>".$this->ShipperNumber."</ShipperNumber>
                  <Address>
                    <City>".$this->address_from["city"]."</City>
                    <StateProvinceCode>".$this->address_from["state"]."</StateProvinceCode>
                    <PostalCode>".$this->address_from["zip"]."</PostalCode>
                    <CountryCode>".$this->address_from["country"]."</CountryCode>
                  </Address>
                </Shipper>
                <ShipTo>
                  <Address>
                    <City>".$this->address_to["city"]."</City>";
        if ($this->address_to["state"] == "" )
            $strXML.=  "<StateProvinceCode />";
        else
            $strXML.= "<StateProvinceCode>".$this->address_to["state"]."</StateProvinceCode>";

        $strXML.= "<PostalCode>".$this->address_to["zip"]."</PostalCode>
                    <CountryCode>".$this->address_to["country"]."</CountryCode>";
        if ($this->to_residental)
            $strXML.= "<ResidentialAddressIndicator />";

        $strXML.= "</Address>
                </ShipTo>
                <ShipFrom>
                  <Address>
                    <City>".$this->address_from["city"]."</City>
                    <StateProvinceCode>".$this->address_from["state"]."</StateProvinceCode>
                    <PostalCode>".$this->address_from["zip"]."</PostalCode>
                    <CountryCode>".$this->address_from["country"]."</CountryCode>
                  </Address>
                </ShipFrom>
                <Service>
                  <Code>".$this->service."</Code>
                </Service>
                <Package>
                  <PackagingType>
                    <Code>".$this->PackagingType."</Code>
                  </PackagingType>
                  <Dimensions>
                    <UnitOfMeasurement>
                      <Code>".$this->length_unit."</Code>
                    </UnitOfMeasurement>
                    <Length>".$this->length."</Length>
                    <Width>".$this->width."</Width>
                    <Height>".$this->height."</Height>
                  </Dimensions>
                  <PackageWeight>
                    <UnitOfMeasurement>
                      <Code>".$this->weight_unit."</Code>
                    </UnitOfMeasurement>
                    <Weight>".$this->weight."</Weight>
                  </PackageWeight>
                </Package>
                <RateInformation>
                    <NegotiatedRatesIndicator/>
                </RateInformation>
              </Shipment>
            </RatingServiceSelectionRequest>";

        $ch = curl_init(); /// initialize a cURL session 
        curl_setopt ($ch, CURLOPT_URL, $this->server); 
        curl_setopt ($ch, CURLOPT_HEADER, 0); 
        curl_setopt ($ch, CURLOPT_POST, 1); 
        curl_setopt ($ch, CURLOPT_POSTFIELDS, "$strXML");
        eZDebug::writeDebug( $strXML , 'UPS Request' );
        curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
        $response = curl_exec ($ch);
        $curl_error = curl_error($ch);
        $info = curl_getinfo($ch);
        if ( $curl_error or $info['http_code'] != '200' )
        {
            eZDebug::writeError( $curl_error, 'USPS Curl Error Message'  );
            if ( $curl_error ) 
            {
                eZDebug::writeError( $curl_error, 'UPS Curl Error Message'  );
                throw new xrowShippingGatewayException( "Connection error with shipping gateway.", "N/A" );
            }
        }
        curl_close ($ch);
        eZDebug::writeDebug( $response , 'UPS Response' );
        // move XML to array
        $resp_array = xrowECommerce::createArrayfromXML($response);

        $ups_services = $this->convertservices( $this->services );

        // Checking response status code (1=success, 0=Error)
        if ( $resp_array["Response"]["ResponseStatusCode"] == "1")
        {
            /*$ups_price = new ups_price();
            $ups_price->status = $resp_array["Response"]["ResponseStatusCode"];
            $ups_price->description = $resp_array["Response"]["ResponseStatusDescription"];
            $ups_price->shipping_type = $ups_services[$resp_array["RatedShipment"]["Service"]["Code"]];
            $ups_price->costs = $resp_array["RatedShipment"]["TotalCharges"]["MonetaryValue"];
            $ups_price->currency_unit = $resp_array["RatedShipment"]["TotalCharges"]["CurrencyCode"];
            $this->costs = $ups_price;*/
            $price = array();
            $price['status'] = $resp_array["Response"]["ResponseStatusCode"];
            $price['description'] = $ups_services[$resp_array["RatedShipment"]["Service"]["Code"]];
            $price['shipping_type'] = $this->method;
            $price['costs'] = $resp_array["RatedShipment"]["TotalCharges"]["MonetaryValue"];
            $price['currency_unit'] = $resp_array["RatedShipment"]["TotalCharges"]["CurrencyCode"];
            $this->list[] = $price;
        }
        elseif ($resp_array["Response"]["ResponseStatusCode"] == "0")
        {
            eZDebug::writeError( $resp_array["Response"]["Error"]["ErrorDescription"] , __METHOD__ );
            throw new xrowShippingGatewayException( $resp_array["Response"]["Error"]["ErrorDescription"], $resp_array["Response"]["Error"]["ErrorCode"] );
        }
        elseif( $curl_error == '' )
        {
            eZDebug::writeError( "Unknown error." . $ups_error->error_code , __METHOD__ );
            throw new xrowShippingGatewayException( "Unknown error.", $ups_error->error_code );
        }
        return $this;
    } // End of GetPrice
} // CLASS UPS

?> 
