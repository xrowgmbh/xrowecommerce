<?php

class xrowECommerce
{
    const ACCOUNT_KEY_PAYMENTMETHOD = 'paymentmethod';
    const ACCOUNT_KEY_COUPON = 'coupon';
    /* tax identification number / Umsatzsteuer-Identifikationsnummer */
    const ACCOUNT_KEY_TAXID = 'taxid';

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
            
            if ( $currentContentObjectID != $contentObjectID && $itemCount != 1 )
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
                'SERVER_BUSY' => 'The service cannot process your request. Try again later.' 
            );
            throw new Exception( $faults[$ret] );
        }
        return $ret->valid;
    }
}

?>