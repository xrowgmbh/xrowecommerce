<?php

class USPS extends ShippingInterface
{
    // !!! USPS just excepts integer for pounds and ounces !!!
    var $pounds = "1";
    var $ounces = "0";
    var $container = "None";
    var $size = "REGULAR";
    var $machinable;
    var $MailType = "Package";
    
    /*  USPS International Services
        Global Express Guaranteed Document Service
        Global Express Guaranteed Non-Document Service
        Global Express Mail (EMS)
        Global Priority Mail - Flat-rate Envelope (Large)
        Global Priority Mail - Flat-rate Envelope (Small)
        Global Priority Mail - Variable Weight (Single)
        Airmail Letter Post
        Airmail Parcel Post
        Economy (Surface) Letter Post
        Economy (Surface) Parcel Post
    */
    function methods() {
    	return array( 
	    	array(
	    		'identifier' => '6',
	    		'name' => 'USPS Express Mail International (EMS) (Intl. only)'
	    	),
    	    array(
	    		'identifier' => '7',
	    		'name' => 'USPS Global Express Guaranteed (Intl. only)'
    		),array(
	    		'identifier' => 'usps_international',
	    		'name' => 'USPS Express Mail International (EMS) (Intl. only)'
    		),
    	    array(
	    		'identifier' => 'usps_international_guaranteed',
	    		'name' => 'USPS Global Express Guaranteed (Intl. only)'
    		)
		);
    }
    function loadConfiguration()
    {
    	$uspsini = eZINI::instance( 'usps.ini' );
        $this->setServer($uspsini->variable( "Connection", "URL" ));
        $this->setAvailableFor($uspsini->variable( "AvailableSettings", "AvailableFor" ));
        $this->setUserID ($uspsini->variable( "Account", "Userid" ));
        $this->setAddressFrom( $uspsini->variable( "ShipperSettings", "Country" ),
                               $uspsini->variable( "ShipperSettings", "State" ),
                               $uspsini->variable( "ShipperSettings", "Zip" ),
                               $uspsini->variable( "ShipperSettings", "City" )  );
    }

    function countryConvert( $value )
    {
      $country = eZCountryType::fetchCountry( $value, 'Alpha3' );
      if ( !$country )
      {
          return $value;
      }
      $code = strtoupper( $country['Alpha2'] );
      $list = array('AF' => 'Afghanistan',
                    'AL' => 'Albania',
                    'DZ' => 'Algeria',
                    'AD' => 'Andorra',
                    'AO' => 'Angola',
                    'AI' => 'Anguilla',
                    'AG' => 'Antigua and Barbuda',
                    'AR' => 'Argentina',
                    'AM' => 'Armenia',
                    'AW' => 'Aruba',
                    'AU' => 'Australia',
                    'AT' => 'Austria',
                    'AZ' => 'Azerbaijan',
                    'BS' => 'Bahamas',
                    'BH' => 'Bahrain',
                    'BD' => 'Bangladesh',
                    'BB' => 'Barbados',
                    'BY' => 'Belarus',
                    'BE' => 'Belgium',
                    'BZ' => 'Belize',
                    'BJ' => 'Benin',
                    'BM' => 'Bermuda',
                    'BT' => 'Bhutan',
                    'BO' => 'Bolivia',
                    'BA' => 'Bosnia-Herzegovina',
                    'BW' => 'Botswana',
                    'BR' => 'Brazil',
                    'VG' => 'British Virgin Islands',
                    'BN' => 'Brunei Darussalam',
                    'BG' => 'Bulgaria',
                    'BF' => 'Burkina Faso',
                    'MM' => 'Burma',
                    'BI' => 'Burundi',
                    'KH' => 'Cambodia',
                    'CM' => 'Cameroon',
                    'CA' => 'Canada',
                    'CV' => 'Cape Verde',
                    'KY' => 'Cayman Islands',
                    'CF' => 'Central African Republic',
                    'TD' => 'Chad',
                    'CL' => 'Chile',
                    'CN' => 'China',
                    'CX' => 'Christmas Island (Australia)',
                    'CC' => 'Cocos Island (Australia)',
                    'CO' => 'Colombia',
                    'KM' => 'Comoros',
                    'CG' => 'Congo (Brazzaville),Republic of the',
                    'ZR' => 'Congo, Democratic Republic of the',
                    'CK' => 'Cook Islands (New Zealand)',
                    'CR' => 'Costa Rica',
                    'CI' => 'Cote d\'Ivoire (Ivory Coast)',
                    'HR' => 'Croatia',
                    'CU' => 'Cuba',
                    'CY' => 'Cyprus',
                    'CZ' => 'Czech Republic',
                    'DK' => 'Denmark',
                    'DJ' => 'Djibouti',
                    'DM' => 'Dominica',
                    'DO' => 'Dominican Republic',
                    'TP' => 'East Timor (Indonesia)',
                    'EC' => 'Ecuador',
                    'EG' => 'Egypt',
                    'SV' => 'El Salvador',
                    'GQ' => 'Equatorial Guinea',
                    'ER' => 'Eritrea',
                    'EE' => 'Estonia',
                    'ET' => 'Ethiopia',
                    'FK' => 'Falkland Islands',
                    'FO' => 'Faroe Islands',
                    'FJ' => 'Fiji',
                    'FI' => 'Finland',
                    'FR' => 'France',
                    'GF' => 'French Guiana',
                    'PF' => 'French Polynesia',
                    'GA' => 'Gabon',
                    'GM' => 'Gambia',
                    'GE' => 'Georgia, Republic of',
                    'DE' => 'Germany',
                    'GH' => 'Ghana',
                    'GI' => 'Gibraltar',
                    'GB' => 'Great Britain and Northern Ireland',
                    'GR' => 'Greece',
                    'GL' => 'Greenland',
                    'GD' => 'Grenada',
                    'GP' => 'Guadeloupe',
                    'GT' => 'Guatemala',
                    'GN' => 'Guinea',
                    'GW' => 'Guinea-Bissau',
                    'GY' => 'Guyana',
                    'HT' => 'Haiti',
                    'HN' => 'Honduras',
                    'HK' => 'Hong Kong',
                    'HU' => 'Hungary',
                    'IS' => 'Iceland',
                    'IN' => 'India',
                    'ID' => 'Indonesia',
                    'IR' => 'Iran',
                    'IQ' => 'Iraq',
                    'IE' => 'Ireland',
                    'IL' => 'Israel',
                    'IT' => 'Italy',
                    'JM' => 'Jamaica',
                    'JP' => 'Japan',
                    'JO' => 'Jordan',
                    'KZ' => 'Kazakhstan',
                    'KE' => 'Kenya',
                    'KI' => 'Kiribati',
                    'KW' => 'Kuwait',
                    'KG' => 'Kyrgyzstan',
                    'LA' => 'Laos',
                    'LV' => 'Latvia',
                    'LB' => 'Lebanon',
                    'LS' => 'Lesotho',
                    'LR' => 'Liberia',
                    'LY' => 'Libya',
                    'LI' => 'Liechtenstein',
                    'LT' => 'Lithuania',
                    'LU' => 'Luxembourg',
                    'MO' => 'Macao',
                    'MK' => 'Macedonia, Republic of',
                    'MG' => 'Madagascar',
                    'MW' => 'Malawi',
                    'MY' => 'Malaysia',
                    'MV' => 'Maldives',
                    'ML' => 'Mali',
                    'MT' => 'Malta',
                    'MQ' => 'Martinique',
                    'MR' => 'Mauritania',
                    'MU' => 'Mauritius',
                    'YT' => 'Mayotte (France)',
                    'MX' => 'Mexico',
                    'MD' => 'Moldova',
                    'MC' => 'Monaco (France)',
                    'MN' => 'Mongolia',
                    'MS' => 'Montserrat',
                    'MA' => 'Morocco',
                    'MZ' => 'Mozambique',
                    'NA' => 'Namibia',
                    'NR' => 'Nauru',
                    'NP' => 'Nepal',
                    'NL' => 'Netherlands',
                    'AN' => 'Netherlands Antilles',
                    'NC' => 'New Caledonia',
                    'NZ' => 'New Zealand',
                    'NI' => 'Nicaragua',
                    'NE' => 'Niger',
                    'NG' => 'Nigeria',
                    'KP' => 'North Korea (Korea, Democratic People\'s Republic of)',
                    'NO' => 'Norway',
                    'OM' => 'Oman',
                    'PK' => 'Pakistan',
                    'PA' => 'Panama',
                    'PG' => 'Papua New Guinea',
                    'PY' => 'Paraguay',
                    'PE' => 'Peru',
                    'PH' => 'Philippines',
                    'PN' => 'Pitcairn Island',
                    'PL' => 'Poland',
                    'PT' => 'Portugal',
                    'QA' => 'Qatar',
                    'RE' => 'Reunion',
                    'RO' => 'Romania',
                    'RU' => 'Russia',
                    'RW' => 'Rwanda',
                    'SH' => 'Saint Helena',
                    'KN' => 'Saint Kitts (St. Christopher and Nevis)',
                    'LC' => 'Saint Lucia',
                    'PM' => 'Saint Pierre and Miquelon',
                    'VC' => 'Saint Vincent and the Grenadines',
                    'SM' => 'San Marino',
                    'ST' => 'Sao Tome and Principe',
                    'SA' => 'Saudi Arabia',
                    'SN' => 'Senegal',
                    'YU' => 'Serbia-Montenegro',
                    'SC' => 'Seychelles',
                    'SL' => 'Sierra Leone',
                    'SG' => 'Singapore',
                    'SK' => 'Slovak Republic',
                    'SI' => 'Slovenia',
                    'SB' => 'Solomon Islands',
                    'SO' => 'Somalia',
                    'ZA' => 'South Africa',
                    'GS' => 'South Georgia (Falkland Islands)',
                    'KR' => 'South Korea (Korea, Republic of)',
                    'ES' => 'Spain',
                    'LK' => 'Sri Lanka',
                    'SD' => 'Sudan',
                    'SR' => 'Suriname',
                    'SZ' => 'Swaziland',
                    'SE' => 'Sweden',
                    'CH' => 'Switzerland',
                    'SY' => 'Syrian Arab Republic',
                    'TW' => 'Taiwan',
                    'TJ' => 'Tajikistan',
                    'TZ' => 'Tanzania',
                    'TH' => 'Thailand',
                    'TG' => 'Togo',
                    'TK' => 'Tokelau (Union) Group (Western Samoa)',
                    'TO' => 'Tonga',
                    'TT' => 'Trinidad and Tobago',
                    'TN' => 'Tunisia',
                    'TR' => 'Turkey',
                    'TM' => 'Turkmenistan',
                    'TC' => 'Turks and Caicos Islands',
                    'TV' => 'Tuvalu',
                    'UG' => 'Uganda',
                    'UA' => 'Ukraine',
                    'AE' => 'United Arab Emirates',
                    'UY' => 'Uruguay',
                    'UZ' => 'Uzbekistan',
                    'VU' => 'Vanuatu',
                    'VA' => 'Vatican City',
                    'VE' => 'Venezuela',
                    'VN' => 'Vietnam',
                    'WF' => 'Wallis and Futuna Islands',
                    'WS' => 'Western Samoa',
                    'YE' => 'Yemen',
                    'ZM' => 'Zambia',
                    'ZW' => 'Zimbabwe');

      if ( isset( $list[$code] ) )
          return $list[$code];
      else
          return $value;
    }

    function setWeight( $totalweight ) {
		$this->setPounds( "0" );
        $this->setContainer( "Flat Rate Box" );
        $totalweight_ounces = round( $totalweight * 16 );
        $this->setOunces ( $totalweight_ounces );
	}
	
    function setContainer($cont) {
        $this->container = $cont;
    }
    
    function setMailType($mailtype) {
        $this->MailType = $mailtype;
    }

    function setSize($size) {
        $this->size = $size;
    }
    
    function setPounds($pounds) {
        $this->pounds = $pounds;
    }
    
    function setOunces($ounces) {
        $this->ounces = $ounces;
    }
    
    function setMachinable($mach) {
        /* Required for Parcel Post only, set to True or False */
        $this->machinable = $mach;
    }
    function setAddressTo( $shipping_country, $shipping_state, $shipping_zip, $shipping_city )
    {
    	$shipping_country = $usps->convert_country( $shipping_country, "Alpha3", "Name");
        parent::setAddressTo( $shipping_country, $shipping_state, $shipping_zip, $shipping_city );
    }
    
    function getPrice() {
        if($this->address_to["country"]=="United States"){
            // may need to urlencode xml portion
            $str = $this->server. "?API=RateV2&XML=<RateV2Request%20USERID=\"";
            $str .= $this->userid . "\"%20PASSWORD=\"" . $this->pass . "\"><Package%20ID=\"0\"><Service>";
            $str .= "PRIORITY</Service><ZipOrigination>" . $this->address_from["zip"] . "</ZipOrigination>";
            $str .= "<ZipDestination>" . $this->address_to["zip"] . "</ZipDestination>";
            $str .= "<Pounds>" . $this->pounds . "</Pounds><Ounces>" . $this->ounces . "</Ounces>";
            $str .= "<Container>" . urlencode($this->container) . "</Container><Size>" . $this->size . "</Size>";
            $str .= "<Machinable>" . $this->machinable . "</Machinable></Package></RateV2Request>";
        }
        else {
            $str = $this->server. "?API=IntlRate&XML=<IntlRateRequest%20USERID=\"";
            $str .= $this->userid . "\"%20PASSWORD=\"" . $this->pass . "\"><Package%20ID=\"0\">";
            $str .= "<Pounds>" . $this->pounds . "</Pounds><Ounces>" . $this->ounces . "</Ounces>";
            $str .= "<MailType>".$this->MailType."</MailType><Country>".urlencode($this->countryConvert( $this->address_to["country"] ) )."</Country></Package></IntlRateRequest>";
        }
        ezDebug::writeDebug( $str, 'USPS Request'  );
        $ch = curl_init();
        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_URL, $str);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false );

        // grab URL and pass it to the browser
        $ats = curl_exec($ch);
        $curl_error = curl_error($ch);
        $info = curl_getinfo($ch);
        if ( $curl_error or $info['http_code'] != '200' )
        {
        	eZDebug::writeError( $curl_error, 'USPS Curl Error Message'  );
        	throw new xrowShippingGatewayException( "Connection error with shipping gateway.", "N/A" );

        }
        // close curl resource, and free up system resources
        curl_close($ch);
        $xmlParser = new xmlparser();
        $array = $xmlParser->GetXMLTree($ats);
        //$xmlParser->printa($array);
        ezDebug::writeDebug( $array, 'USPS Response'  );
        if(count($array['ERROR'])) { // If it is error
        	throw new xrowShippingGatewayException( $array['ERROR'][0]['DESCRIPTION'][0]['VALUE'], $array['ERROR'][0]['NUMBER'][0]['VALUE'] );
            /*
            $error = new error();
            $error->number = $array['ERROR'][0]['NUMBER'][0]['VALUE'];
            $error->source = $array['ERROR'][0]['SOURCE'][0]['VALUE'];
            $error->description = $array['ERROR'][0]['DESCRIPTION'][0]['VALUE'];
            $error->helpcontext = $array['ERROR'][0]['HELPCONTEXT'][0]['VALUE'];
            $error->helpfile = $array['ERROR'][0]['HELPFILE'][0]['VALUE'];
            $this->error = $error;
            */
        } else if(count($array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ERROR'])) {
        	throw new xrowShippingGatewayException( $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['DESCRIPTION'][0]['VALUE'], $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['NUMBER'][0]['VALUE'] );
        	/*
            $error = new error();
            $error->number = $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['NUMBER'][0]['VALUE'];
            $error->source = $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['SOURCE'][0]['VALUE'];
            $error->description = $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['DESCRIPTION'][0]['VALUE'];
            $error->helpcontext = $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['HELPCONTEXT'][0]['VALUE'];
            $error->helpfile = $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['HELPFILE'][0]['VALUE'];
            $this->error = $error;
			*/        
        } else if(count($array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR'])){ //if it is international shipping error
        	throw new xrowShippingGatewayException( $array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['DESCRIPTION'][0]['VALUE'], $array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['NUMBER'][0]['VALUE'] );
        	/*
            $error = new error($array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR']);
            $error->number = $array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['NUMBER'][0]['VALUE'];
            $error->source = $array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['SOURCE'][0]['VALUE'];
            $error->description = $array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['DESCRIPTION'][0]['VALUE'];
            $error->helpcontext = $array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['HELPCONTEXT'][0]['VALUE'];
            $error->helpfile = $array['INTLRATERESPONSE'][0]['PACKAGE'][0]['ERROR'][0]['HELPFILE'][0]['VALUE'];
            $this->error = $error;
			*/
        } else if(count($array['RATEV2RESPONSE'])){ // if everything OK
            //print_r($array['RATEV2RESPONSE']);
            $this->zone = $array['RATEV2RESPONSE'][0]['PACKAGE'][0]['ZONE'][0]['VALUE'];
            foreach ($array['RATEV2RESPONSE'][0]['PACKAGE'][0]['POSTAGE'] as $value){
                $price = new price();
                $price->mailservice = $value['MAILSERVICE'][0]['VALUE'];
                $price->rate = $value['RATE'][0]['VALUE'];
                $this->list[] = $price;
            }
        } else if (count($array['INTLRATERESPONSE'][0]['PACKAGE'][0]['SERVICE'])) { // if it is international shipping and it is OK
            foreach($array['INTLRATERESPONSE'][0]['PACKAGE'][0]['SERVICE'] as $value) {
                $price = new intPrice();
                $price->id = $value['ATTRIBUTES']['ID'];
                $price->pounds = $value['POUNDS'][0]['VALUE'];
                $price->ounces = $value['OUNCES'][0]['VALUE'];
                $price->mailtype = $value['MAILTYPE'][0]['VALUE'];
                $price->country = $value['COUNTRY'][0]['VALUE'];
                $price->rate = $value['POSTAGE'][0]['VALUE'];
                $price->svccommitments = $value['SVCCOMMITMENTS'][0]['VALUE'];
                $price->svcdescription = $value['SVCDESCRIPTION'][0]['VALUE'];
                $price->maxdimensions = $value['MAXDIMENSIONS'][0]['VALUE'];
                $price->maxweight = $value['MAXWEIGHT'][0]['VALUE'];
                $this->list[] = $price;
            }
        }
        
        return $this;
    }
    
    function getService( $service_list, $service_name )
    {
        $return = false;
    	foreach ($service_list as $service)
    	{
    	    if ( $service->svcdescription == $service_name )
    	       $return = $service;
    	}
    	return $return;
    }
}
class price
{
    var $mailservice;
    var $rate;
}
class intPrice
{
    var $id;
    var $rate;
}
?> 
