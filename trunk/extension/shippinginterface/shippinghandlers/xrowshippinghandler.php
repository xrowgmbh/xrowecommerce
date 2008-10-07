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

/*! \file ezsampleshippinghandler.php

Sample shipping handler.
Uses external database table to store shipping informaion
associated with product collections. The table is managed
via eZSampleShipping class.

*/

include_once( 'lib/ezutils/classes/ezextension.php' );
ext_activate( 'shippinginterface', 'classes/xrowshipping.php' );

class XROWShippingHandler
{
    /*!
     Return shipping information associated with the given product collection.

     \public
     \static
     */
    function getShippingInfo( $productCollectionID )
    {
        $shippingInfo = XROWShipping::getInfo( $productCollectionID );

        /* Generate summary of chosen shipping options
         * and return it as 'description'.
         */
        $desc = "delivered by " . $shippingInfo['delivered_by'];
        if ( $shippingInfo['urgently'] )
            $desc .= ', urgently';

        return array(
                'description'     => $desc,
                'cost'            => $shippingInfo['cost'],
                'management_link' => '/shipping/changeoptions/' . $productCollectionID
                );
    }

    /*!
     Purge shipping information associated with the given product collection.

     \public
     \static
     */
    function purgeShippingInfo( $productCollectionID )
    {
        XROWShipping::purgeInfo( $productCollectionID );
    }

    /*!
     Purge shipping information associated with the given product collection.
     This method is usually called when shopping basket contents get changed.

     \public
     \static
     */
    function updateShippingInfo( $productCollectionID )
    {
        XROWShipping::updateCost( $productCollectionID );
    }
}

?>
