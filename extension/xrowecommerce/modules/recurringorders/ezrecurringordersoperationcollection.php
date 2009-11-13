<?php
include_once( "kernel/shop/ezshopoperationcollection.php" );

class eZRecurringOrdersOperationCollection
{
    /*!
     Constructor
    */
    function eZRecurringOrdersOperationCollection()
    {
    }
    function handleUserCountry( $orderID )
    {
        return eZShopOperationCollection::handleUserCountry( $orderID );
    }
    function activateOrder( $orderID )
    {
        return eZShopOperationCollection::activateOrder( $orderID );
    }
    function sendOrderEmails( $orderID )
    {
        return eZShopOperationCollection::sendOrderEmails( $orderID );
    }
    function handleShipping( $orderID )
    {
        return eZShopOperationCollection::handleShipping( $orderID );
    }
}

?>