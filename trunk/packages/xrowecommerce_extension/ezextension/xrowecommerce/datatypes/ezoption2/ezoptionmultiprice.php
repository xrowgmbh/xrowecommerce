<?

class eZOptionMultiPrice extends eZMultiPrice
{

    function __construct( $PriceList, $contentObjectAttribute = null, $storedPrice = null )
    {
        $discountPercent = 0.0;
        if ( $contentObjectAttribute instanceof eZContentObjectAttribute )
        {
            $object = $contentObjectAttribute->object();
            $this->ContentObject = $object;
            $discountPercent = eZDiscount::discountPercent( eZUser::currentUser(), array( 
                'contentclass_id' => $object->attribute( 'contentclass_id' ) , 
                'contentobject_id' => $object->attribute( 'id' ) , 
                'section_id' => $object->attribute( 'section_id' ) 
            ) );
        }
        $this->setDiscountPercent( $discountPercent );
        
        $this->setVatIncluded( 0 );
        $this->setVatType( self::CALCULATION_TYPE_VAT_EXCLUDE );
        $autoCurrecyList = $this->currencyList();
        
        $this->PriceList = $PriceList;
        foreach ( $autoCurrecyList as $item )
        {
            if ( ! array_key_exists( $item->Code, $this->PriceList ) )
            {
                $price = array( 
                    'type' => eZMultiPriceData::VALUE_TYPE_AUTO , 
                    'value' => '0.00' , 
                    'currency_code' => $item->attribute( 'code' ) 
                );
                $this->PriceList[$item->attribute( 'code' )] = $price;
            }
        }
    
    }

    function removePriceByCurrency( $currencyCode )
    {
        if ( $currencyCode )
        {
            unset( $this->priceList[$currencyCode] );
        }
    }

    function priceList( $type = false )
    {
        if ( ! isset( $this->PriceList ) )
        {
            // @TODO
            if ( is_object( $this->ContentObjectAttribute ) )
            {
                $this->PriceList = self::fetch();
            }
            
            if ( ! $this->PriceList )
            {
                $this->PriceList = array();
            }
        }
        
        $priceList = array();
        if ( $type !== false )
        {
            foreach ( $this->priceList() as $currencyCode => $price )
            {
                if ( $price['type'] == $type )
                {
                    $priceList[$currencyCode] = $price;
                }
            }
        }
        else
        {
            $priceList = $this->PriceList;
        }
        
        return $priceList;
    }

    function fetch( $currencyCode = false, $type = false )
    {
        $priceList = null;
        $rows[] = $this->priceList;
        #$rows[]= array( 'type' => 1, 'currency_code' => 'EUR', 'value' => 12 );
        #$rows[]= array( 'type' => 2, 'currency_code' => 'USD', 'value' => 15 );
        if ( count( $rows ) > 0 )
        {
            foreach ( $rows as $key => $value )
            {
                if ( $type === false and $currencyCode === false )
                {
                    $priceList[$rows[$key]['currency_code']] = $value;
                }
                elseif ( $type === false and $currencyCode == $rows[$key]['currency_code'] )
                {
                    $priceList[$rows[$key]['currency_code']] = $value;
                }
                elseif ( $type == $rows[$key]['type'] and $currencyCode === false )
                {
                    $priceList[$rows[$key]['currency_code']] = $value;
                }
                elseif ( $type == $rows[$key]['type'] and $currencyCode == $rows[$key]['currency_code'] )
                {
                    $priceList[$rows[$key]['currency_code']] = $value;
                }
            }
        }
        return $priceList;
    }

    function calcPriceList( $calculationType, $priceType )
    {
        $priceList = $this->priceList( $priceType );
        
        $calculatedPriceList = array();
        foreach ( $priceList as $key => $price )
        {
            switch ( $calculationType )
            {
                case self::CALCULATION_TYPE_VAT_INCLUDE:
                    {
                        $value = $this->calcIncVATPrice( $price['value'] );
                    }
                    break;
                
                case self::CALCULATION_TYPE_VAT_EXCLUDE:
                    {
                        $value = $this->calcExVATPrice( $price['value'] );
                    }
                    break;
                
                case self::CALCULATION_TYPE_DISCOUNT_INCLUDE:
                    {
                        $value = $this->calcDiscountIncVATPrice( $price['value'] );
                    }
                    break;
                
                case self::CALCULATION_TYPE_DISCOUNT_EXCLUDE:
                    {
                        $value = $this->calcDiscountExVATPrice( $price['value'] );
                    }
                    break;
                
                default:
                    {
                        // do nothing
                    }
                    break;
            }
            
            $calculatedPrice = $price;
            $calculatedPrice['value'] = $value;
            $calculatedPriceList[$key] = $calculatedPrice;
        }
        
        return $calculatedPriceList;
    }

    function price()
    {
        $value = '0.0';
        if ( $currencyCode = $this->preferredCurrencyCode() )
        {
            $price = $this->priceByCurrency( $currencyCode );
            if ( $price )
            {
                $value = $price['value'];
            }
        }
        
        return $value;
    }

    /*!
     functional attribute
    */
    function autoCurrencyList()
    {
        // 'auto currencies' are the currencies used for 'auto' prices.
        // 'auto currencies' = 'all currencies' - 'currencies of custom prices'
        

        $autoCurrecyList = $this->currencyList();
        $customPriceList = $this->customPriceList();
        foreach ( $customPriceList as $price )
        {
            if ( $price )
            {
                $currencyCode = $price['currency_code'];
                unset( $autoCurrecyList[$currencyCode] );
            }
        }
        
        return $autoCurrecyList;
    }

    function priceByCurrency( $currencyCode, $type = false )
    {
        $price = false;
        $priceList = $this->priceList();
        
        if ( isset( $priceList[$currencyCode] ) )
        {
            if ( $type === false || $priceList[$currencyCode]['type'] == $type )
            {
                $price = $priceList[$currencyCode];
            }
        }
        
        return $price;
    }

    function updatePrice( $currencyCode, $value, $type )
    {
        $price = $this->priceByCurrency( $currencyCode );
        if ( $price )
        {
            if ( $value !== false )
            {
                $this->PriceList[$currencyCode]['value'] = $value;
            }
            
            if ( $type !== false )
            {
                $this->PriceList[$currencyCode]['type'] = $type;
            }
        }
        
        return $price;
    }

    function createPrice( $currencyCode, $value, $type )
    {
        if ( is_object( $this->ContentObjectAttribute ) && $this->currencyByCode( $currencyCode ) )
        {
            return array( 
                'currency_code' => $currencyCode , 
                'value' => $value , 
                'type' => $type 
            );
        }
        return false;
    }

    function addPrice( $currencyCode, $value, $type )
    {
        $price = $this->createPrice( $currencyCode, $value, $type );
        if ( $price )
        {
            if ( $value === false )
                $price['value'] = '0.00';
            
            $this->PriceList[$price['currency_code']] = $price;
        
        }
        
        return $price;
    }

    function setPriceByCurrency( $currencyCode, $value, $type )
    {
        if ( ! $this->updatePrice( $currencyCode, $value, $type ) && ! $this->addPrice( $currencyCode, $value, $type ) )
        {
            eZDebug::writeWarning( "Unable to set price in '$currencyCode'", 'eZMultiPrice::setPrice' );
            return false;
        }
        
        return true;
    }

    function updateAutoPriceList()
    {
        //include_once( 'kernel/shop/classes/ezcurrencyconverter.php' );
        $converter = eZCurrencyConverter::instance();
        
        $basePrice = $this->basePrice();
        $basePriceValue = $basePrice ? $basePrice['value'] : 0;
        $baseCurrencyCode = $basePrice ? $basePrice['currency_code'] : false;
        
        $autoCurrencyList = $this->autoCurrencyList();
        foreach ( $autoCurrencyList as $currencyCode => $currency )
        {
            $autoValue = $converter->convert( $baseCurrencyCode, $currencyCode, $basePriceValue );
            $this->setAutoPrice( $currencyCode, $autoValue );
        }
    }

}
