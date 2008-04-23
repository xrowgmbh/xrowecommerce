<?php
//
// Definition of eZSimpleShippingType class
//
// Created on: <09-���-2002 14:42:23 sp>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ publish
// SOFTWARE RELEASE: 3.8.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2006 eZ systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
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

/*! \file ezproductcounttype.php
*/

/*!
  \class eZSimpleShippingType ezsimpleshippingtype.php
  \brief The class eZSimpleshippingType handles adding shipping cost to an order

*/
include_once( 'kernel/classes/ezbasket.php' );


define( 'EZ_WORKFLOW_TYPE_PRODUCTCOUNT_ID', 'ezproductcount' );

class eZProductCountType extends eZWorkflowEventType
{
    /*!
     Constructor
    */
    function eZProductCountType()
    {
        $this->eZWorkflowEventType( EZ_WORKFLOW_TYPE_PRODUCTCOUNT_ID, ezi18n( 'kernel/workflow/event', "Product Count" ) );
        $this->setTriggerTypes( array( 'shop' => array( 'addtobasket' => array ( 'after' ) ) ) );
    }

    function execute( &$process, &$event )
    {
    	include_once( 'kernel/classes/ezbasket.php' );
        $basket = eZBasket::currentBasket();
        $id= $basket->attribute( 'productcollection_id');
        
        return EZ_WORKFLOW_TYPE_STATUS_ACCEPTED;
    }
}

eZWorkflowEventType::registerType( EZ_WORKFLOW_TYPE_PRODUCTCOUNT_ID, "ezproductcounttype" );

?>
