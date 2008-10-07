<?php
//
// Definition of eZSampleShippingHandler class
//
// Created on: <29-Mar-2006 18:04:01 vs>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

require_once( 'kernel/classes/ezproductcollection.php' );
require_once( 'kernel/classes/ezproductcollectionitem.php' );
require_once( 'kernel/classes/ezcontentobject.php' );
require_once( 'lib/ezdb/classes/ezdb.php' );

/*
shipping info = shipping options + shipping cost
*/

class XROWShipping
{
    /*!
     Determine shipping cost according to the chosen shipping options.

     \public
     \static
     */
    function calculateCost( $productCollectionID, $shippingOptions )
    {
        $collection = eZProductCollection::fetch( $productCollectionID );
        $items = $collection->itemList();

        // Determine cost of delivery.
        switch ( $shippingOptions['delivered_by'] )
        {
            case 'Courier':
                $deliveryCostFactor = 2;
                break;
            case 'FedEx':
                $deliveryCostFactor = 1;
                break;
            default:
                $deliveryCostFactor = 0;
        }

        // Determine total weight.
        $totalWeight = 0;
        foreach ( $items as $item )
        {
            $productID = $item->attribute( 'contentobject_id' );
            if ( !$productID )
                continue;
            $product = eZContentObject::fetch( $item->attribute( 'contentobject_id' ) );
            if ( !is_object( $product ) )
                continue;

            $productWeight = XROWShipping::_getProductWeight( $product );
            $productCount  = $item->attribute( 'item_count' );

            $totalWeight += ( $productCount * $productWeight );
        }

        // Determine urgency cost.
        if ( $shippingOptions['urgently'] )
            $urgencyCost = 10;
        else
            $urgencyCost = 0;

        // Calculate total cost.
        $totalCost = $totalWeight * $deliveryCostFactor + $urgencyCost;

        eZDebug::writeDebug( "Calculated shipping cost = $totalWeight * $deliveryCostFactor + $urgencyCost = $totalCost",
                             'eZSampleShipping::calculateCost()' );

        return $totalCost;
    }

    /*!
     Save shipping options for the given product collection.

     \public
     \static
     */
    function setOptions( $productCollectionID, $shippingOptions )
    {
        $shippingInfo = XROWShipping::_fetch( $productCollectionID );

        $db = eZDB::instance();

        $deliveredBy =       $db->escapeString( $shippingOptions['delivered_by'] );
        $urgently    = (int) $db->escapeString( $shippingOptions['urgently'] );
        $cost        =       XROWShipping::calculateCost( $productCollectionID, $shippingOptions );

        if ( $shippingInfo  )
            $query = "UPDATE ezsampleshippinginfo " .
                     "SET delivered_by='$deliveredBy', urgently=$urgently, cost=$cost " .
                     "WHERE productcollection_id=$productCollectionID";
        else
            $query = "INSERT INTO ezsampleshippinginfo (productcollection_id,delivered_by,urgently,cost) " .
                     "VALUES($productCollectionID, '$deliveredBy', $urgently, $cost)";

        $db->query( $query );

        return $cost;
    }

    /*!
     Return shipping information (options, calculated cost) for the given
     product collection.

     \public
     \static
     */
    function getInfo( $productCollectionID )
    {
        $shippingInfo = XROWShipping::_fetch( $productCollectionID );

        /* If there's no info stored for the given product collection yet
         * then store default shipping options
         * and calculate shipping cost acorrding to them.
         */
        if ( !$shippingInfo )
        {
            $defaultShippingOptions = array( 'delivered_by' => 'FedEx',
                                             'urgently'     => false );
            $cost = XROWShipping::setOptions( $productCollectionID, $defaultShippingOptions );

            $shippingInfo = $defaultShippingOptions;
            $shippingInfo['cost'] = $cost;
        }

        return $shippingInfo;
    }

    /*!
     Purge shipping info for the given product collection.

     This method is usually called when a product collection is removed.

     \public
     \static
     */
    function purgeInfo( $productCollectionID )
    {
        eZDebug::writeDebug( "Purging shipping info for product collection $productCollectionID",
                             'eZSampleShippingInfo::purgeInfo()' );

        $db = eZDB::instance();
        $query = "DELETE FROM ezsampleshippinginfo WHERE productcollection_id=$productCollectionID";
        $db->query( $query );
    }

    /*!
     Update shipping cost for the specified product collection.

     This method is usually called when shopping basket is modified
     (e.g. more products are added to it).

     \public
     \static
     */
    function updateCost( $productCollectionID )
    {
        $shippingInfo = XROWShipping::_fetch( $productCollectionID );

        if ( !$shippingInfo )
            return;

        eZDebug::writeDebug( "Updating shipping cost for product collection $productCollectionID",
                             'eZSampleShipping::updateCost()' );

        $cost = eZSampleShipping::calculateCost( $productCollectionID, $shippingInfo );
        $query = "UPDATE ezsampleshippinginfo SET cost=$cost " .
                 "WHERE productcollection_id=$productCollectionID";
        $db = eZDB::instance();
        $db->query( $query );
    }

    /*!
     Fetches shipping information for the specified product collection from database.

     \return false if no information found.
     \private
     \static
     */
    function _fetch( $productCollectionID )
    {
        require_once( 'lib/ezdb/classes/ezdb.php' );
        $db = eZDB::instance();
        $rslt = $db->arrayQuery( "SELECT * FROM ezsampleshippinginfo WHERE productcollection_id=$productCollectionID" );

        if ( count( $rslt ) > 0 )
            return $rslt[0];
        else
            return false;
    }

    /*!
     Returns weight of the specified product.

     \return weight of the specified product, or null on error.
     \private
     \static
     */
    function _getProductWeight( $product )
    {
        $productDataMap  = $product->attribute( 'data_map' );
        if ( !isset( $productDataMap['weight'] ) )
        {
            eZDebug::writeError( "Cannot find attribute 'weight' in the product '" . $product->attribute( 'name' ) . "'" );
            return null;
        }

        $weightAttribute = $productDataMap['weight'];
        $productWeight   = $weightAttribute->attribute( 'content' );

        return $productWeight;
    }
}
?>
