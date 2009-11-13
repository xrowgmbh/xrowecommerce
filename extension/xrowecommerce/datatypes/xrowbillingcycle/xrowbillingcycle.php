<?php

class xrowBillingCycle
{
    function xrowBillingCycle( $period = 0, $quantity = 0 )
    {
        $this->Period               = $period;
        $this->Quantity             = $quantity;
        $this->PeriodTextArray      = XROWRecurringOrderCollection::getBillingCycleTextArray();
        $this->PeriodAdjTextArray   = XROWRecurringOrderCollection::getBillingCycleTextAdjectiveArray();
        $this->PeriodText           = XROWRecurringOrderCollection::getBillingCycleText( $period, $quantity );
        if ( isset( $this->PeriodAdjTextArray[$period] ) )
            $this->PeriodAdjText = $this->PeriodAdjTextArray[$period];
        else
        {
            $keys = array_keys( $this->PeriodAdjTextArray );
            $this->Period = $keys[0];
            $this->PeriodAdjText = $this->PeriodAdjTextArray[ $keys[0] ];
            $this->PeriodText = XROWRecurringOrderCollection::getBillingCycleText( $this->Period, $quantity );
        }
     }

    function attributes()
    {
        return array( 'period',
                      'quantity',
                      'text',
                      'text_array',
                      'text_adj_array',
                      'text_adj',
                      'has_content' );
    }

    function hasAttribute( $name )
    {
        return in_array( $name, $this->attributes() );
    }

   function attribute( $name )
    {
        switch ( $name )
        {
            case "period" :
            {
                return $this->Period;
            }break;
            case "quantity" :
            {
                return $this->Quantity;
            }break;
            case "text_array" :
            {
                return $this->PeriodTextArray;
            }break;
            case "text_adj_array" :
            {
                return $this->PeriodAdjTextArray;
            }break;
            case "text_adj" :
            {
                return $this->PeriodAdjText;
            }break;
            case "text" :
            {
                return $this->PeriodText;
            }break;
            case "has_content" :
            {
                if ( $this->Quantity > 0 )
                    return true;
            }break;
            default:
            {
                eZDebug::writeError( "Attribute '$name' does not exist", 'xrowBillingCycle::attribute' );
                $retValue = null;
                return $retValue;
            }break;
        }
    }

    /// \privatesection
    var $Period;
    var $Quantity;
    var $PeriodTextArray;
    var $PeriodAdjTextArray;
    var $PeriodText;
    var $PeriodAdjText;
}

?>