<?php

/**
 */

class getMutliPriceTemplateOperator
{
    
    function getMutliPriceTemplateOperator()
    {
        $this->Operators = array( 
            'get_multiprice'
        );
    }

    function operatorList()
    {
        return $this->Operators;
    }

    function namedParameterPerOperator()
    {
        return true;
    }

	public function namedParameterList()
    {
        return array( 
            'get_multiprice' => array( 
                'price' => array( 
                    'type' => 'object', 
                    'required' => true, 
                    'default' => false 
                ), 
                'option_price' => array( 
                    'type' => 'object', 
                    'required' => true, 
                    'default' => false 
                ),
                'priceparameter' => array(
                	'type' => 'string', 
                    'required' => true, 
                    'default' => false 
                )
            )
        );
    }

    function modify( &$tpl, &$operatorName, &$operatorParameters, &$rootNamespace, &$currentNamespace, &$operatorValue, &$namedParameters )
    {
    	$price = $namedParameters['price'];
        $optionPriceObj = $namedParameters['option_price'];
        $priceparameter = $namedParameters['priceparameter'];

        $parentPriceObj = false;
        $vat_percent = 0;
        $discount_percent = 0;
        $price_parent = 0.00;
        $price_option = 0.00;

        if (is_object( $price ))
        {
            $dataType = $price->dataType();
            if ( $dataType->isA() == 'ezmultiprice' )
            {
                $parentPriceObj = $price->content();
                // get the vat value
                $vat_percent = $parentPriceObj->VATType->getPercentage( false, '' );
                // get the discount value
                $discount_percent = $parentPriceObj->DiscountPercent;
                // get the option price
                $price_option = $optionPriceObj->attribute( 'price' );
               
                switch ( $operatorName )
                {
                    case 'get_multiprice':
                        
                        $price_parent = $parentPriceObj->attribute( $priceparameter );

                        switch ( $priceparameter )
                        {
                            case 'inc_vat_price':
                            case 'discount_price_inc_vat':

                                if ( $vat_percent > 0 && !$parentPriceObj->IsVATIncluded )
                                {
                                    $price_option = $price_option * ( 100 + $vat_percent ) / 100;
                                }
                                
                            break;

                            case 'ex_vat_price':
                            case 'discount_price_ex_vat':
                                if ( $vat_percent > 0 && $parentPriceObj->IsVATIncluded )
                                {
                                    $price_option = $price_option / ( 100 + $vat_percent ) / 100;
                                }
                            break;
                        }
                        if ( $price_option > 0 && $discount_percent > 0 && ( $priceparameter == 'discount_price_inc_vat' || $priceparameter == 'discount_price_ex_vat' ) )
                        {
                            $price_option = $price_option * ( 100 - $discount_percent ) / 100;
                        }
                        $total_price = $price_parent + $price_option;
                        
                        $operatorValue = $total_price;
                    break;
                }
            }
            else
            {
            	eZDebug::writeError( 'Datatype is not a multiprice', __METHOD__ );
            }
        }
    }
}
;

?>