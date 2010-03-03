<?php

class xrowECommerce
{
    const ACCOUNT_KEY_PAYMENTMETHOD = 'paymentmethod';
    const ACCOUNT_KEY_COUPON = 'coupon';
    /* tax identification number / Umsatzsteuer-Identifikationsnummer */
    const ACCOUNT_KEY_TAXID = 'taxid';
    /* Credit card related keys */
    const ACCOUNT_KEY_ECNAME = 'ecname';
    const ACCOUNT_KEY_ACCOUNTNUMBER = 'accountnumber';
    const ACCOUNT_KEY_BANKCODE = 'bankcode';
    const ACCOUNT_KEY_TYPE = 'type';
    const ACCOUNT_KEY_NUMBER = 'number';
    const ACCOUNT_KEY_SECURITYCODE = 'securitycode';
    const ACCOUNT_KEY_MONTH = 'month';
    const ACCOUNT_KEY_YEAR = 'year';
    const ACCOUNT_KEY_NAME = 'name';
    const ACCOUNT_KEY_CREDITCARD = 'creditcard';
    const ACCOUNT_KEY_TRANSACTIONID = 'transactionid';
    const ACCOUNT_KEY_PACKAGES = 'packages';

    /**
     * [MerchantLocations]
     * Locations[]=USA
     * Locations[]=GER
     * USA[]=CT
     * USA[]=NY
     * 
     * @access static
     * @return array First element country, Second state
     */
    static function merchantsLocations()
    {
        $ini = eZINI::instance( 'xrowecommerce.ini' );
        $LocationArray = array();
        if ( is_array( $ini->variable( 'MerchantLocations', 'Location' ) ) )
        {
            foreach ( $ini->variable( 'MerchantLocations', 'Location' ) as $location )
            {
                if ( $ini->hasVariable( 'MerchantLocations', $location ) )
                {
                    $LocationArray[] = array( 
                        $location , 
                        $ini->variable( 'MerchantLocations', $location ) 
                    );
                }
                else
                {
                    $LocationArray[] = array( 
                        $location 
                    );
                }
            }
        }
        return $LocationArray;
    }

    static function getPackageListArray( eZOrder $order )
    {
        $return = array();
        $xmlstring = $order->attribute( 'data_text_1' );

        $xml = new SimpleXMLElement( $xmlstring );
        if ( $xml )
        {
            
            foreach ( $xml->xpath( '//' . xrowECommerce::ACCOUNT_KEY_PACKAGES . '/package' ) as $key => $package )
            {
                $p = array();
                $p['name'] = (string) $package['name'];
                $p['id'] = (string) $package['id'];
                $p['content'] = array();
                
                foreach ( $package->product as $product )
                {
                    $pro = array();
                    $pro['id'] = (string) $product['id'];
                    $pro['name'] = (string) $product['name'];
                    $pro['amount'] = (string) $product['amount'];
                    $p['content'][] = $pro;
                }
                $return[] = $p;
            }
        }
        return $return;
    }

    /**
     * @access static
     * @return xrowOrderStatusDefault
     */
    static function instanceStatus( $statusID )
    {
        $list = eZINI::instance( 'xrowecommerce.ini' )->variable( 'StatusSettings', 'StatusTypeList' );
        if ( array_key_exists( $statusID, $list ) and class_exists( $list[$statusID] ) )
        {
            $classname = $list[$statusID];
            $object = new $classname( $statusID );
            if ( ! $object instanceof xrowOrderStatusDefault )
            {
                throw new Exception( "Status is not of class 'xrowOrderStatusDefault'" );
            }
            return $object;
        }
        else
        {
            return new xrowOrderStatusDefault( $statusID );
        }
    }

    /**
     * @access static
     * @return array List of countries
     */
    static function merchantsCountries( $type = 'Alpha2' )
    {
        $countries = array();
        $LocationArray = self::merchantsLocations();
        foreach ( $LocationArray as $Location )
        {
            $country = eZCountryType::fetchCountry( $Location[0], 'Alpha3' );
            $countries[] = $country[$type];
        }
        return $countries;
    }

    /*
     * @param $country Alpha2 code of the country to validate
     * @param $tax_id Tax Identification Number
     * @param $errors Resulting Errors
     * @access static
     * @return boolean
     */
    static function validateTIN( $country, $tax_id, &$errors )
    {
        $matches = array();
        switch ( $country )
        {
            case 'DE':
                
                $regexp = '/^([0-9]{3}\\/[0-9]{3}\\/[0-9]{5}|[0-9]{3}\\/[0-9]{4}\\/[0-9]{4}|[0-9]{2}[\\40\\/][0-9]{3}[\\40\\/][0-9]{4}\\/?[0-9]|0[0-9]{2}\\40[0-9]{3}\\40[0-9]{5}|[0-9]{5}\\/[0-9]{5})$/i';
                /* test cases
        		var_dump( preg_match( $regexp, '93815/08152', $matches ) );
        		var_dump( preg_match( $regexp, '181/815/08155', $matches ) );
        		var_dump( preg_match( $regexp, '75 815 08152', $matches ) );
        		var_dump( preg_match( $regexp, '22/815/0815/4', $matches ) );
        		var_dump( preg_match( $regexp, '22444/815/0815/4', $matches ) ); # invalid
                */
                if ( preg_match( "/^([0-9]{13})/i", $tax_id, $matches ) or preg_match( $regexp, $tax_id, $matches ) )
                {
                
                }
                else
                {
                    $errors[] = ezpI18n::tr( 'extension/xrowecommerce', 'A tax identification number in the Federal Republic of Germany consists of 10 or 11 digits, depending on the "Bundesland" (State). These are divided into groups of 2 - 5 by forward slashes or blanks (e.g. "181/815/08155"). A unified German tax identification number consists of 13 digits like "2893081508152".' );
                    return false;
                }
                break;
            default:
                return true;
                break;
        }
        return true;
    }

    /*!
     \return the number of active orders
    */
    static function orderStatistics( $year = false, $startMonth = false, $stopMonth = false, $startDay = false, $stopDay = false )
    {
        if ( $year == false )
        {
            $startDate = 0;
            $stopDate = mktime( 0, 0, 0, 12, 31, 2037 );
        }
        else 
            if ( $year != false and $startMonth == false and $startDay == false and $stopDay == false )
            {
                $nextYear = $year + 1;
                $startDate = mktime( 0, 0, 0, 1, 1, $year );
                $stopDate = mktime( 0, 0, 0, 1, 1, $nextYear );
            }
            else 
                if ( $year != false and $startMonth != false and $startDay == false and $stopDay == false )
                {
                    $nextMonth = $startMonth + 1;
                    $startDate = mktime( 0, 0, 0, $startMonth, 1, $year );
                    $stopDate = mktime( 23, 59, 59, $nextMonth, 0, $year );
                }
                else 
                    if ( $year != false and $startMonth != false and $startDay != false and $stopDay != false )
                    {
                        $startDate = mktime( 0, 0, 0, $startMonth, $startDay, $year );
                        $stopDate = mktime( 23, 59, 59, $stopMonth, $stopDay, $year );
                    }
        
        $db = eZDB::instance();
        $productArray = $db->arrayQuery( "SELECT ezproductcollection_item.*, ignore_vat, ezorder.created, ezorder_item.price as order_shipping_price, currency_code FROM ezorder, ezorder_item, ezproductcollection_item, ezproductcollection
                                                WHERE ezproductcollection.id=ezproductcollection_item.productcollection_id
                                                  AND ezorder_item.order_id = ezorder.id
                                                  AND ezproductcollection_item.productcollection_id=ezorder.productcollection_id
                                                  AND is_temporary='0'
                                                  AND ezorder.created >= '$startDate' AND ezorder.created < '$stopDate'
                                             ORDER BY contentobject_id, currency_code" );
        $shippingSumSQL = "SELECT sum(ezorder_item.price) as order_shipping_price FROM ezorder, ezorder_item WHERE ezorder_item.order_id = ezorder.id AND ezorder.is_temporary = 0 AND ezorder.created >= '$startDate' AND ezorder.created < '$stopDate'";
        $shippingSum = $db->arrayQuery( $shippingSumSQL );
        $currentContentObjectID = 0;
        $productItemArray = array();
        $statisticArray = array();
        $productObject = null;
        $itemCount = 0;
        $totalSumIncVAT = array();
        $totalSumExVAT = array();
        $name = false;
        $productCount = count( $productArray );
        $productInfo = array();
        $totalSumInfo = array();
        foreach ( $productArray as $productItem )
        {
            $itemCount ++;
            $contentObjectID = $productItem['contentobject_id'];
            
            if ( $productObject == null )
            {
                $productObject = eZContentObject::fetch( $contentObjectID );
                $currentContentObjectID = $contentObjectID;
            }
            
            if ( $currentContentObjectID != $contentObjectID and $itemCount != 1 )
            {
                $productItemArray[] = array( 
                    'name' => $name , 
                    'product' => $productObject , 
                    'product_info' => $productInfo 
                );
                $productInfo = array();
                unset( $productObject );
                $name = $productItem['name'];
                $currentContentObjectID = $contentObjectID;
                $productObject = eZContentObject::fetch( $currentContentObjectID );
            }
            
            $currencyCode = $productItem['currency_code'];
            if ( $currencyCode == '' )
            {
                $currencyCode = eZOrder::fetchLocaleCurrencyCode();
            }
            
            if ( ! isset( $productInfo[$currencyCode] ) )
            {
                $productInfo[$currencyCode] = array( 
                    'sum_count' => 0 , 
                    'sum_ex_vat' => 0 , 
                    'sum_inc_vat' => 0 , 
                    'sum_shipping' => 0 
                );
            }
            if ( ! isset( $totalSumInfo[$currencyCode] ) )
            {
                $totalSumInfo[$currencyCode] = array( 
                    'sum_ex_vat' => 0 , 
                    'sum_inc_vat' => 0 , 
                    'sum_shipping' => 0 
                );
            }
            
            if ( ! isset( $totalSumIncVAT[$currencyCode] ) )
                $totalSumIncVAT[$currencyCode] = 0;
            
            if ( ! isset( $totalSumExVAT[$currencyCode] ) )
                $totalSumExVAT[$currencyCode] = 0;
            
            if ( ! isset( $totalSumShipping[$currencyCode] ) )
                $totalSumShipping[$currencyCode] = 0;
            
            if ( $productItem['ignore_vat'] == true )
            {
                $vatValue = 0;
            }
            else
            {
                $vatValue = $productItem['vat_value'];
            }
            
            $count = $productItem['item_count'];
            $discountPercent = $productItem['discount'];
            
            $isVATIncluded = $productItem['is_vat_inc'];
            $price = $productItem['price'];
            
            if ( $isVATIncluded )
            {
                $priceExVAT = $price / ( 100 + $vatValue ) * 100;
                $priceIncVAT = $price;
                $totalPriceExVAT = $count * $priceExVAT * ( 100 - $discountPercent ) / 100;
                $totalPriceIncVAT = $count * $priceIncVAT * ( 100 - $discountPercent ) / 100;
                $totalPriceExVAT = round( $totalPriceExVAT, 2 );
                $totalPriceIncVAT = round( $totalPriceIncVAT, 2 );
                $totalSumInfo[$currencyCode]['sum_ex_vat'] += $totalPriceExVAT;
                $totalSumInfo[$currencyCode]['sum_inc_vat'] += $totalPriceIncVAT;
            }
            else
            {
                $priceExVAT = $price;
                $priceIncVAT = $price * ( 100 + $vatValue ) / 100;
                $totalPriceExVAT = $count * $priceExVAT * ( 100 - $discountPercent ) / 100;
                $totalPriceIncVAT = $count * $priceIncVAT * ( 100 - $discountPercent ) / 100;
                $totalPriceExVAT = round( $totalPriceExVAT, 2 );
                $totalPriceIncVAT = round( $totalPriceIncVAT, 2 );
                $totalSumInfo[$currencyCode]['sum_ex_vat'] += $totalPriceExVAT;
                $totalSumInfo[$currencyCode]['sum_inc_vat'] += $totalPriceIncVAT;
            }
            
            $productInfo[$currencyCode]['sum_count'] += $count;
            $productInfo[$currencyCode]['sum_ex_vat'] += $totalPriceExVAT;
            $productInfo[$currencyCode]['sum_inc_vat'] += $totalPriceIncVAT;
        }
        $shippingSumNumeric = $shippingSum;
        $shippingSumNumeric = $shippingSumNumeric[0]['order_shipping_price'];
        $totalSumInfo[$currencyCode]['sum_shipping'] = $shippingSumNumeric;
        
        // add last product info
        if ( $productCount != 0 )
            $productItemArray[] = array( 
                'name' => $name , 
                'product' => $productObject , 
                'product_info' => $productInfo 
            );
        
        $statisticArray[] = array( 
            'product_list' => $productItemArray , 
            'total_sum_info' => $totalSumInfo 
        );
        return $statisticArray;
    }

    static function paymentLimitationList()
    {
        
        $list = xrowEPayment::getGateways( array( 
            - 1 
        ) );
        $paymentArray = array();
        foreach ( $list as $item )
        {
            $paymentArray[] = array( 
                'name' => $item['Name'] , 
                'id' => $item['value'] 
            );
        }
        
        return $paymentArray;
    }

    /**
     * Invoke the VIES service to check an EU VAT number
     *
     * @param string $cc Country Code
     * @param string $vat VAT number
     * @return mixed
     */
    static function checkVat( $cc, $vat )
    {
        $wsdl = 'extension/xrowecommerce/WDSL/checkVatPort.xml';
        
        $vies = new SoapClient( $wsdl );
        
        $nii = new checkVat( $cc, $vat );
        
        try
        {
            $ret = $vies->checkVat( $nii );
        }
        catch ( SoapFault $e )
        {
            $ret = $e->faultstring;
            $regex = '/\{ \'([A-Z_]*)\' \}/';
            $n = preg_match( $regex, $ret, $matches );
            $ret = $matches[1];
            $faults = array( 
                'INVALID_INPUT' => 'The provided CountryCode is invalid or the VAT number is empty' , 
                'SERVICE_UNAVAILABLE' => 'The SOAP service is unavailable, try again later' , 
                'MS_UNAVAILABLE' => 'The Member State service is unavailable, try again later or with another Member State' , 
                'TIMEOUT' => 'The Member State service could not be reached in time, try again later or with another Member State' , 
                'SERVER_BUSY' => 'Server Busy. The service cannot process your request. Try again later.' 
            );
            throw new Exception( $faults[$ret] );
        }
        return $ret->valid;
    }

    /**
     * @param string $xmlDoc XML data
     * @return array
     */
    static function createArrayfromXML( $xmlDoc )
    {
        return self::createArrayfromDOMNODE( simplexml_load_string( $xmlDoc ) );
    }

    /**
     * @param SimpleXMLElement $xml
     * @return array
     */
    static function createArrayfromDOMNODE( $xml )
    {
        
        if ( $xml instanceof SimpleXMLElement )
        {
            $children = $xml->children();
            $return = null;
        }
        
        foreach ( $children as $element => $value )
        {
            if ( $value instanceof SimpleXMLElement )
            {
                $values = (array) $value->children();
                
                if ( count( $values ) > 0 )
                {
                    if ( is_array( $return[$element] ) )
                    {
                        //hook
                        foreach ( $return[$element] as $k => $v )
                        {
                            if ( ! is_int( $k ) )
                            {
                                $return[$element][0][$k] = $v;
                                unset( $return[$element][$k] );
                            }
                        }
                        $return[$element][] = self::createArrayfromDOMNODE( $value );
                    }
                    else
                    {
                        $return[$element] = self::createArrayfromDOMNODE( $value );
                    }
                }
                else
                {
                    if ( ! isset( $return[$element] ) )
                    {
                        $return[$element] = (string) $value;
                    }
                    else
                    {
                        if ( ! is_array( $return[$element] ) )
                        {
                            $return[$element] = array( 
                                $return[$element] , 
                                (string) $value 
                            );
                        }
                        else
                        {
                            $return[$element][] = (string) $value;
                        }
                    }
                }
            }
        }
        
        if ( is_array( $return ) )
        {
            return $return;
        }
        else
        {
            return false;
        }
    }
}

?>