<?php

class recurringordersFunctionCollection
{
    /*!
     Constructor
    */
    function recurringordersFunctionCollection()
    {
    }
    function hasSubscription( $object_id )
    {
        $result = array( 'result' => XROWRecurringOrderCollection::hasSubscription( $object_id ) );
        return $result;
    }
    function fetchTextAdjectiveArray( )
    {
    	$recurringordercollection = new XROWRecurringOrderCollection( array() );
        $result = array( 'result' => $recurringordercollection->getBillingCycleTextArray() );
        return $result;
    }
    function fetchGMNow( )
    {
        $result = array( 'result' => XROWRecurringOrderCollection::now() );
        return $result;
    }
}

?>
