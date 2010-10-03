<?php

//
// Definition of xrowAfterSaleType class
//
// Created on: <16-Apr-2002 11:08:14 amos>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2010 eZ Systems AS
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


/*!
  \class eZXrowAfterSaleType xrowaftersaletype.php
  \brief Event type for object node id

  WorkflowEvent storage fields : data_int - object_node_id
*/

class xrowAfterSaleType extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = 'xrowaftersale';

    function xrowAfterSaleType()
    {
        $this->eZWorkflowEventType( xrowAfterSaleType::WORKFLOW_TYPE_STRING, ezpI18n::tr( 'kernel/workflow/event', 'After Sale' ) );
        $this->setTriggerTypes( array(
            'shop' => array(
                'checkout' => array(
                    'after'
                )
            )
        ) );
    }

    function attributeDecoder( $event, $attr )
    {
        switch ( $attr )
        {
            case 'get_object_id':
                {
                    $attributeValue = trim( $event->attribute( 'data_int1' ) );
                    $returnValue = empty( $attributeValue ) ? array() : explode( ',', $attributeValue );
                }
                break;

            default:
                $returnValue = null;
        }
        return $returnValue;
    }

    function execute( $process, $event )
    {
        $http = eZHTTPTool::instance();
        // get order ID
        $processParameters = $process->attribute( 'parameter_list' );
        $order = eZOrder::fetch( $processParameters['order_id'] );
        $errors = array();

        $xmlstring = $order->attribute( 'data_text_1' );
        $classname = false;
        if ( $xmlstring != null )
        {
            $doc = new DOMDocument( );
            $doc->loadXML( $xmlstring );
            $root = $doc->documentElement;
            $paymentmethod = $doc->getElementsByTagName( xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD )->item( 0 )->nodeValue;
            $classname = $paymentmethod . 'Gateway';
            if ( class_exists( $classname ) )
            {
                $Gateway = new $classname( );
            }
        }
        // check if 'after sale list' is allowed
        if ( $classname and $classname::AFTER_SALE )
        {
            if ( $order instanceof eZOrder )
            {
                $order->setAttribute( 'is_temporary', 1 );
                $order->store();

                // get the shelf warmer
                $params = array(
                    'ClassFilterType' => 'include' ,
                    'ClassFilterArray' => array(
                        'xrow_product'
                    ) ,
                    'SortBy' => array(
                        'name' ,
                        'asc'
                    ) ,
                    'IgnoreVisibility' => true
                );
                $parentContentObject = eZContentObjectTreeNode::subTreeByNodeID( $params, $event->DataInt1 );

                if ( $http->hasPostVariable( 'Cancel' ) and $http->postVariable( 'Cancel' ) )
                {
                    return eZWorkflowEventType::STATUS_ACCEPTED;
                }

                if ( $http->hasPostVariable( 'SelectMoreProducts' ) )
                {
                    $itemCountList = $http->hasPostVariable( 'ProductItemCountList' ) ? $http->postVariable( 'ProductItemCountList' ) : false;

                    if ( is_array( $itemCountList ) )
                    {
                        $newproductCollectionArray = array();
                        $total_amount_inc_vat = 0.00;
                        $itemOptionIDList = array();

                        foreach ( $itemCountList as $id => $itemCount )
                        {
                            if ( is_array( $itemCount ) )
                            {
                                foreach ( $itemCount as $optionKey => $optionValue )
                                {
                                    if ( $optionValue > 0 )
                                    {
                                        $itemOptionIDList[$optionKey] = $optionValue;
                                    }
                                }
                            }

                            if ( ( ! is_array( $itemCount ) && $itemCount > 0 ) || ( is_array( $itemOptionIDList ) && count( $itemOptionIDList ) > 0 && is_array( $itemCount ) ) )
                            {
                                // get the productdata
                                $productObject = eZContentObject::fetch( $id );
                                $productDataMap = $productObject->attribute( 'data_map' );

                                $attributes = $productObject->contentObjectAttributes();

                                $inc_vat_price = 0.00;

                                foreach ( $attributes as $attribute )
                                {
	                                $name = $productObject->attribute( 'name' );

						            if( $productObject->is_subscription and $productObject->cycle_unit != XROWRecurringOrderCollection::CYCLE_ONETIME )
						            {
						                $ts_args = array();
						                $ts_args['%startdate%'] = strftime( '%d.%m.%y', $productObject->periodStartDate() );
						                $ts_args['%enddate%'] = strftime( '%d.%m.%y', $productObject->attribute( 'next_date' ) );
						                $name .= ' ' . ezpI18n::tr( 'extension/recurringorders', "(period %startdate% till %enddate%)", false, $ts_args );
						            }

                                    $dataType = $attribute->dataType();

                                    // if a product have more than one option
                                	if ( $dataType->isA() == 'ezoption2' && is_array( $itemOptionIDList ) && count( $itemOptionIDList ) > 0 )
				                    {
				                    	$optionsObj = $attribute->content();
				                    	$object_attribute_id = $attribute->attribute( 'id' );
				                    	$optionList = $optionsObj->attribute( 'option_list' );

				                    	foreach ($optionList as $option)
				                    	{
				                    		if ( $itemOptionIDList['options'][$option['id']] > 0 )
				                    		{
					                    		if ( array_key_exists( $option['id'], $itemOptionIDList['options'] ) )
					                    		{
					                    			$priceDataObj = $this->getAmount( $productDataMap['price'], $itemOptionIDList['options'][$option['id']], $option['multi_price'] );
					                    			$total_amount_inc_vat += $priceDataObj->amount_inc_vat;

					                    			$newproductCollectionArray[$id]['option'][$option['id']]['productcollection_id'] = $order->attribute( 'productcollection_id' );
										    		$newproductCollectionArray[$id]['option'][$option['id']]['contentobject_id'] = $id;
										    		$newproductCollectionArray[$id]['option'][$option['id']]['name'] = $name;
					                    			$newproductCollectionArray[$id]['option'][$option['id']]['item_count'] = $itemOptionIDList['options'][$option['id']];
										            $newproductCollectionArray[$id]['option'][$option['id']]['price'] = $priceDataObj->price;
										            $newproductCollectionArray[$id]['option'][$option['id']]['is_vat_inc'] = $priceDataObj->is_vat_inc;
										            $newproductCollectionArray[$id]['option'][$option['id']]['vat_value'] = $priceDataObj->vat;
										            $newproductCollectionArray[$id]['option'][$option['id']]['discount'] = $priceDataObj->discount;
										            // for the product collection itemOpt
										            $newproductCollectionArray[$id]['option'][$option['id']]['options']['option_item_id'] = $option['id'];
										            $newproductCollectionArray[$id]['option'][$option['id']]['options']['object_attribute_id'] = $object_attribute_id;
										            $newproductCollectionArray[$id]['option'][$option['id']]['options']['name'] = $option['comment'];
										            $newproductCollectionArray[$id]['option'][$option['id']]['options']['value'] = $option['value'];
										            $newproductCollectionArray[$id]['option'][$option['id']]['options']['price'] = $priceDataObj->price;
					                    		}
				                    		}
				                    	}
				                    }
				                    elseif ( eZShopFunctions::isProductDatatype( $dataType->isA() ) && !is_array( $itemCount ))
        							{
                                    	$priceDataObj = $this->getAmount( $productDataMap['price'], $itemCount );
                                    	$total_amount_inc_vat += $priceDataObj->amount_inc_vat;

                                    	$newproductCollectionArray[$id]['productcollection_id'] = $order->attribute( 'productcollection_id' );
							            $newproductCollectionArray[$id]['contentobject_id'] = $id;
							            $newproductCollectionArray[$id]['name'] = $name;
							            $newproductCollectionArray[$id]['item_count'] = $itemCount;
							            $newproductCollectionArray[$id]['price'] = $priceDataObj->price;
							            $newproductCollectionArray[$id]['is_vat_inc'] = $priceDataObj->is_vat_inc;
							            $newproductCollectionArray[$id]['vat_value'] = $priceDataObj->vat;
							            $newproductCollectionArray[$id]['discount'] = $priceDataObj->discount;
        							}
                                }
                            }
                        }
                    }

                    if ( $Gateway->furtherCharge( $order, $total_amount_inc_vat ) )
                    {
                    	$productCollectionID = $order->attribute( 'productcollection_id' );
                        // if payment true, add the new order
                        foreach ( $newproductCollectionArray as $newProductID => $newProduct )
                        {
                        	#print_r($newproductCollectionArray);
                        	if ( is_array( $newProduct['option'] ) )
                            {
 	  	                  		foreach ( $newProduct['option'] as $newProductOptionKey => $newProductOption)
 	  	                  		{
 	  	                  			$itemCollection = eZProductCollectionItem::create( $productCollectionID );
                            		$itemCollection->store();
                            		$itemCollectionID = $itemCollection->attribute( 'id' );
 	  	                  			foreach ($newProductOption as $newProductOptionValueKey => $newProductOptionValue)
 	  	                  			{
 	  	                  				if ( $newProductOptionValueKey != 'options' )
 	  	                  				{
 	  	                  					$itemCollection->setAttribute( $newProductOptionValueKey, $newProductOptionValue );
 	  	                  				}
 	  	                  				else
 	  	                  				{
 	  	                  					// create a new product collection itemOpt
 	  	                  					$itemCollectionOption = eZProductCollectionItemOption::create($itemCollectionID,
 	  	                  																		$newProductOptionValue['option_item_id'],
 	  	                  																		$newProductOptionValue['name'],
 	  	                  																		$newProductOptionValue['value'],
 	  	                  																		$newProductOptionValue['price'],
 	  	                  																		$newProductOptionValue['object_attribute_id'] );
 	  	                  					$itemCollectionOption->store();
 	  	                  				}
 	  	                  			}
 	  	                  			$itemCollection->store();
 	  	                  		}
                            }
                            else
                            {
                            	$itemCollection = eZProductCollectionItem::create( $productCollectionID );
                            	$itemCollection->store();
                            	$itemCollectionID = $itemCollection->attribute( 'id' );
                            	foreach ( $newProduct as $newProductAttributeKey => $newProductAttribute)
                            	{
                            		$itemCollection->setAttribute( $newProductAttributeKey, $newProductAttribute );
                            	}
                            	$itemCollection->store();
                            }
                        }
                        $order->setAttribute( 'is_temporary', 0 );
                        $order->store();
                        return eZWorkflowEventType::STATUS_ACCEPTED;
                    }
                    else
                    {
                    	$errors['aftersale'] = 'The charge could not be explained.';
                        return eZWorkflowEventType::STATUS_WORKFLOW_CANCELLED;
                    }
                }

                if ( $http->hasPostVariable( 'WithoutMoreProducts' ) )
                {
                    $order->setAttribute( 'is_temporary', 0 );
                    $order->store();
                    return eZWorkflowEventType::STATUS_ACCEPTED;
                }

                $process->Template = array();
                $process->Template['templateName'] = 'design:workflow/aftersale.tpl';
                $process->Template['templateVars'] = array(
                    'process' => $process ,
                    'event' => $event ,
                    'product_list' => $parentContentObject ,
                    'errors' => $errors
                );
            }
            return eZWorkflowType::STATUS_FETCH_TEMPLATE_REPEAT;
        }
        else
        {
            return eZWorkflowType::STATUS_WORKFLOW_DONE;
        }
    }

    function fetchHTTPInput( $http, $base, $event )
    {
        if ( $http->hasSessionVariable( 'BrowseParameters' ) )
        {
            $browseParameters = $http->sessionVariable( 'BrowseParameters' );
            if ( isset( $browseParameters['custom_action_data'] ) )
            {
                $customData = $browseParameters['custom_action_data'];
                if ( isset( $customData['event_id'] ) && $customData['event_id'] == $event->attribute( 'id' ) )
                {
                    if ( ! $http->hasPostVariable( 'BrowseCancelButton' ) and $http->hasPostVariable( 'SelectedNodeIDArray' ) )
                    {
                        $objectIDArray = $http->postVariable( 'SelectedNodeIDArray' );
                        if ( is_array( $objectIDArray ) and count( $objectIDArray ) > 0 )
                        {
                            switch ( $customData['browse_action'] )
                            {
                                case 'AddObject':
                                    {
                                        $implodeString = implode( ',', array_unique( array_merge( $this->attributeDecoder( $event, 'get_object_id' ), $objectIDArray ) ) );

                                        $event->setAttribute( 'data_int1', $implodeString );
                                    }
                                    break;

                                default:
                                    break;
                            }
                        }
                        $http->removeSessionVariable( 'BrowseParameters' );
                    }
                }
            }
        }
    }

    function customWorkflowEventHTTPAction( $http, $action, $workflowEvent )
    {
        $eventID = $workflowEvent->attribute( 'id' );
        $module = & $GLOBALS['eZRequestedModule'];

        switch ( $action )
        {
            case 'AddObject':
                {
                    eZContentBrowse::browse( array(
                        'action_name' => 'SelectObjectRelationNode' ,
                        'from_page' => '/workflow/edit/' . $workflowEvent->attribute( 'workflow_id' ) ,
                        'custom_action_data' => array(
                            'event_id' => $eventID ,
                            'browse_action' => $action
                        )
                    ), $module );
                }
                break;

            case 'RemoveObject':
                {
                    if ( $http->hasPostVariable( 'DeleteObjectIDArray_' . $eventID ) )
                    {
                        $workflowEvent->setAttribute( 'data_int1', implode( ',', array_diff( $this->attributeDecoder( $workflowEvent, 'get_object_id' ), $http->postVariable( 'DeleteObjectIDArray_' . $eventID ) ) ) );
                    }
                }
                break;

            default:
                break;
        }
    }

    function cleanupAfterRemoving( $attr = array() )
    {
        foreach ( array_keys( $attr ) as $attrKey )
        {
            switch ( $attrKey )
            {
                case 'DeleteContentObject':
                    {
                        $contentObjectID = (int) $attr[$attrKey];
                        $db = eZDb::instance();
                        $db->query( "UPDATE ezworkflow_event
                                  SET    data_int1 = 0
                                  WHERE  workflow_type_string = '{$this->TypeString}' AND
                                         data_int1 = $contentObjectID" );
                    }
                    break;
            }
        }
    }

    /**
     * Calculate prices and get some important details for the order
     *
     * @param $parentPrice
     * @param $count
     * @param $optionPriceObj
     *
     * @return object $priceDataObj
     */
    function getAmount( $parentPrice, $count, $optionPriceObj = false )
    {
    	$price_option = 0.00;
    	$discount_price_option = 0.00;
    	$totalDiscountPriceProduct = 0.00;

    	// get the parent price object
    	$dataType = $parentPrice->dataType();
    	if ( $dataType->isA() == 'ezmultiprice' )
	    {
			$parentPriceObj = $parentPrice->content();
	    }

	    // get the vat value
		$vat_percent = $parentPriceObj->VATType->getPercentage( false, '' );
	    // get the discount value
		$discount_percent = $parentPriceObj->DiscountPercent;

		if ( is_object( $optionPriceObj ) )
		{
			$price_option = $optionPriceObj->attribute( 'price' );
		}

		// sum the parent price with the option price
		$priceProduct = $parentPriceObj->attribute( 'price' ) + $price_option;
		$discountPriceProduct = round( $priceProduct, 2 ) * ( 100 - $discount_percent ) / 100;

		if ( $parentPriceObj->IsVATIncluded )
		{
			$totalDiscountPriceProduct = round( ($count * round( $discountPriceProduct, 2 ) ), 2 );
		}
		else
		{
			$totalDiscountPriceProduct = round( ($count * round( $discountPriceProduct, 2 ) * ( 100 + $vat_percent ) / 100), 2 );
		}

        $priceDataObj = false;
        $priceDataObj->discount = $discount_percent;
        $priceDataObj->vat = $vat_percent;
        $priceDataObj->is_vat_inc = $parentPriceObj->IsVATIncluded;
        $priceDataObj->price = $priceProduct;
        $priceDataObj->amount_inc_vat = $totalDiscountPriceProduct;

    	return $priceDataObj;
    }
}

eZWorkflowEventType::registerEventType( xrowAfterSaleType::WORKFLOW_TYPE_STRING, 'xrowAfterSaleType' );

?>
