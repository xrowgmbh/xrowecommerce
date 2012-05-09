<?php

class XROWRecurringOrderCollection extends eZPersistentObject
{
    const CYCLE_ONETIME = 0;
    const CYCLE_DAY = 1;
    const CYCLE_WEEK = 2;
    const CYCLE_MONTH = 3;
    const CYCLE_QUARTER = 4;
    const CYCLE_YEAR = 5;
    
    const STATUS_ACTIVE = 1;
    const STATUS_DEACTIVATED = 0;
    const STATUSTYPE_SUCCESS = 1;
    const STATUSTYPE_CREDITCARD_EXPIRES = 2;
    const STATUSTYPE_FAILURE = 3;
    const ERROR_CREDITCARD_MISSING = 0;

    function XROWRecurringOrderCollection( $row )
    {
        parent::eZPersistentObject( $row );
    }

    static function definition()
    {
        return array( 
            "fields" => array( 
                "user_id" => array( 
                    'name' => "user_id" , 
                    'datatype' => 'integer' , 
                    'default' => 0 , 
                    'required' => true 
                ) , 
                "id" => array( 
                    'name' => "id" , 
                    'datatype' => 'integer' , 
                    'default' => 0 , 
                    'required' => true 
                ) , 
                "status" => array( 
                    'name' => "status" , 
                    'datatype' => 'integer' , 
                    'default' => XROWRecurringOrderCollection::STATUS_ACTIVE , 
                    'required' => true 
                ) , 
                "created" => array( 
                    'name' => "created" , 
                    'datatype' => 'integer' , 
                    'default' => 0 , 
                    'required' => true 
                ) , 
                "last_run" => array( 
                    'name' => "last_run" , 
                    'datatype' => 'integer' , 
                    'default' => null , 
                    'required' => false 
                ) , 
                "next_try" => array( 
                    'name' => "next_try" , 
                    'datatype' => 'integer' , 
                    'default' => 0 , 
                    'required' => true 
                ) 
            ) , 
            "keys" => array( 
                "id" 
            ) , 
            "increment_key" => "id" , 
            "function_attributes" => array( 
                "list" => "fetchList" , 
                'now' => 'now' , 
                "user" => "user" , 
                "internal_next_date" => "internalNextDate" 
            ) , 
            "class_name" => "XROWRecurringOrderCollection" , 
            "sort" => array( 
                "id" => "asc" 
            ) , 
            "name" => "xrow_recurring_order_collection" 
        );
    }

    /**
     * Time function needed for testing mod_fcgid doesn`t restart with a future date.
     *
     * @return int
     */
    static function now()
    {
        #$time = gmmktime( 0,0,0,18,8,2010 );
        $time = gmmktime( 0, 0, 0 );
        return $time;
    }

    function markRun()
    {
        $this->setAttribute( 'last_run', XROWRecurringOrderCollection::now() );
        $this->store();
    }

    function getAllCycleTypes()
    {
        return array( 
            XROWRecurringOrderCollection::CYCLE_ONETIME , 
            XROWRecurringOrderCollection::CYCLE_DAY , 
            XROWRecurringOrderCollection::CYCLE_WEEK , 
            XROWRecurringOrderCollection::CYCLE_MONTH , 
            XROWRecurringOrderCollection::CYCLE_QUARTER , 
            XROWRecurringOrderCollection::CYCLE_YEAR 
        );
    }

    function ListCount()
    {
        return count( $this->fetchList() );
    }

    function checkCreditCard( $monthsToExpiryDate = 3 )
    {
        $user = $this->attribute( 'user' );
        if ( ! is_object( $user ) )
        {
            return false;
        }
        $list = $this->fetchList();
        if ( count( $list ) == 0 )
        {
            return false;
        }
        
        $userco = $user->attribute( 'contentobject' );
        $dm = $userco->attribute( 'data_map' );
        if ( ! array_key_exists( xrowECommerce::ACCOUNT_KEY_CREDITCARD, $dm ) )
        {
            return false;
        }
        $data = $dm[xrowECommerce::ACCOUNT_KEY_CREDITCARD]->attribute( 'content' );
        if ( $data['month'] and $data['year'] )
        {
            $now = new eZDateTime( mktime() );
            $now->setMonth( $now->month() + $monthsToExpiryDate );
            $date = eZDateTime::create( - 1, - 1, - 1, $data['month'], - 1, $data['year'] );
            if ( ! $date->isGreaterThan( $now ) )
            {
                return XROWRecurringOrderCollection::STATUSTYPE_CREDITCARD_EXPIRES;
            }
            else
            {
                return true;
            }
        }
        elseif ( $data[ezcreditcardType::KEY_TYPE] == ezcreditcardType::EUROCARD )
        {
            return true;
        }
        else
        {
            return XROWRecurringOrderCollection::ERROR_CREDITCARD_MISSING;
        }
    }

    function creditCardExpiryDate()
    {
        $user = $this->attribute( 'user' );
        if ( ! is_object( $user ) )
            return false;
        $userco = $user->attribute( 'contentobject' );
        $dm = $userco->attribute( 'data_map' );
        $data = $dm['creditcard']->attribute( 'content' );
        if ( $data['month'] and $data['year'] )
        {
            $date = eZDateTime::create( - 1, - 1, - 1, $data['month'], - 1, $data['year'] );
            return $date;
        }
        else
        {
            return null;
        }
    }

    function hadErrorSince( $days )
    {
        $db = eZDB::instance();
        $date = new eZDateTime( mktime() );
        $date->setDay( $date->day() - $days );
        
        $result = $db->arrayQuery( "SELECT count( id ) as counter FROM xrow_recurring_order_history x WHERE x.date > " . $date->timeStamp() . " and x.collection_id = " . $this->id );
        return ( $result[0]['counter'] > 0 );
    }

    function failuresSinceLastSuccess()
    {
        $db = eZDB::instance();
        $date = $this->last_success;
        if ( ! $date )
            $date = '0';
        $result = $db->arrayQuery( "SELECT count( id ) as counter FROM xrow_recurring_order_history x WHERE x.date > " . $date . " and x.collection_id = " . $this->id );
        return $result[0]['counter'];
    }

    function canTry()
    {
        if ( (int) $this->next_try <= XROWRecurringOrderCollection::now() )
            return true;
        else
            return false;
    }

    function user()
    {
        return eZUser::fetch( $this->user_id );
    }

    function createOrder( $recurringitemlist )
    {
        if ( count( $recurringitemlist ) == 0 )
            return false;
        
     // Make order
        $productCollection = eZProductCollection::create();
        $productCollection->store();
        $productCollectionID = $productCollection->attribute( 'id' );
        
        foreach ( $recurringitemlist as $recurringitem )
        {
            $handler = $recurringitem->attribute( 'handler' );
            $object = $recurringitem->attribute( 'object' );
            
            if ( ! $handler )
            {
                $attributes = $object->contentObjectAttributes();
                $priceFound = false;
                foreach ( $attributes as $attribute )
                {
                    $dataType = $attribute->dataType();
                    if ( eZShopFunctions::isProductDatatype( $dataType->isA() ) )
                    {
                        $priceObj = $attribute->content();
                        $price = $priceObj->attribute( 'price' );
                        $priceFound = true;
                    }
                }
                $name = $object->attribute( 'name' );
            }
            else
            {
                $price = $handler->getPrice();
                $name = $handler->getName();
            }
            if ( $recurringitem->is_subscription and $recurringitem->cycle_unit != XROWRecurringOrderCollection::CYCLE_ONETIME )
            {
                $ts_args = array();
                $ts_args['%startdate%'] = strftime( '%d.%m.%y', $recurringitem->periodStartDate() );
                $ts_args['%enddate%'] = strftime( '%d.%m.%y', $recurringitem->attribute( 'next_date' ) );
                $name .= ' ' . ezpI18n::tr( 'extension/recurringorders', "(period %startdate% till %enddate%)", false, $ts_args );
            }
            $item = eZProductCollectionItem::create( $productCollectionID );
            $item->setAttribute( 'name', $name );
            $item->setAttribute( "contentobject_id", $object->attribute( 'id' ) );
            $item->setAttribute( "item_count", $recurringitem->attribute( 'amount' ) );
            $item->setAttribute( "price", $price );
            
            $item->store();
            if ( ! $handler )
            {
                $optionList = $recurringitem->options();
                foreach ( $optionList as $optionData )
                {
                    if ( $optionData )
                    {
                        $optionData['additional_price'] = eZShopFunctions::convertAdditionalPrice( $currency, $optionData['additional_price'] );
                        $optionItem = eZProductCollectionItemOption::create( $item->attribute( 'id' ), $optionData['id'], $optionData['name'], $optionData['value'], $optionData['additional_price'], $attributeID );
                        $optionItem->store();
                        $price += $optionData['additional_price'];
                    }
                }
                $item->setAttribute( "price", $price );
                $item->store();
            }
        }
        
        $user = $this->attribute( 'user' );
        $userID = $user->attribute( 'contentobject_id' );
        
        include_once ( 'kernel/classes/ezorderstatus.php' );
        $time = XROWRecurringOrderCollection::now();
        $order = new eZOrder( array( 
            'productcollection_id' => $productCollectionID , 
            'user_id' => $userID , 
            'is_temporary' => 1 , 
            'created' => $time , 
            'status_id' => eZOrderStatus::PENDING , 
            'status_modified' => $time , 
            'status_modifier_id' => $userID 
        ) );
        
        $db = eZDB::instance();
        $db->begin();
        $order->store();
        
        $orderID = $order->attribute( 'id' );
        $this->setAttribute( 'order_id', $orderID );
        $this->store();
        $db->commit();
        
        return $order;
    }

    static function fetchByUser( $user_id = null )
    {
        if ( $user_id === null )
            $user_id = eZUser::currentUserID();
        return eZPersistentObject::fetchObjectList( XROWRecurringOrderCollection::definition(), null, array( 
            'user_id' => $user_id 
        ), true );
    
    }

    /**
     *
     * @return array array of XROWRecurringOrderCollection
     */
    static function fetchAll()
    {
        return eZPersistentObject::fetchObjectList( XROWRecurringOrderCollection::definition(), null, null, true );
    
    }

    /**
     *
     * @return XROWRecurringOrderCollection
     */
    static function fetch( $collection_id )
    {
        return eZPersistentObject::fetchObject( XROWRecurringOrderCollection::definition(), null, array( 
            "id" => $collection_id 
        ), true );
    }

    /**
     *
     * @return array array of XROWRecurringOrderCollection
     */
    function fetchDueList()
    {
        $list = eZPersistentObject::fetchObjectList( XROWRecurringOrderItem::definition(), null, array( 
            'collection_id' => $this->id 
        ), true );
        $result = array();
        foreach ( $list as $item )
        {
            if ( $item->isDue() )
            {
                $result[] = $item;
            }
        }
        #var_dump( $result );
        return $result;
    }

    function isDue()
    {
        if ( count( $this->fetchDueList() ) > 0 )
            return true;
        else
            return false;
    }

    /**
     *
     * @return array array of XROWRecurringOrderCollection
     */
    function fetchList()
    {
        return eZPersistentObject::fetchObjectList( XROWRecurringOrderItem::definition(), null, array( 
            'collection_id' => $this->id 
        ), true );
    }

    /**
     *
     * @return XROWRecurringOrderCollection
     */
    static function createNew( $user_id = null )
    {
        if ( $user_id === null )
            $user_id = eZUser::currentUserID();
        $collection = new XROWRecurringOrderCollection( array( 
            'user_id' => $user_id , 
            'status' => XROWRecurringOrderCollection::STATUS_ACTIVE , 
            'created' => XROWRecurringOrderCollection::now() 
        ) );
        $collection->store();
        return $collection;
    }

    function add( $object_id, $variations = null, $amount = 0, $cycle_unit = null, $cycle = 1, $isSubscription = false, $start = 0, $end = 0, $canceled = 0, $data = array(), $subscriptionIdentifier = '', $status = XROWRecurringOrderCollection::STATUS_ACTIVE )
    {
        return XROWRecurringOrderItem::add( $this->id, $object_id, $variations, $amount, $cycle, $cycle_unit, $isSubscription, $start, $end, $canceled, $data, $subscriptionIdentifier, $status );
    }

    /**
     *
     * @return void
     */
    function addHistory( $type = XROWRecurringOrderCollection::STATUSTYPE_SUCCESS, $text = null, $orderid = null )
    {
        $db = eZDB::instance();
        $db->begin();
        $ini = eZINI::instance( 'recurringorders.ini' );
        $row = array( 
            'date' => gmmktime() , 
            'type' => $type , 
            'data_text' => $text , 
            'order_id' => $orderid , 
            'collection_id' => $this->id 
        );
        $item = new XROWRecurringOrderHistory( $row );
        $item->store();
        if ( $type != XROWRecurringOrderCollection::STATUSTYPE_SUCCESS )
        {
            $date = new eZDateTime( XROWRecurringOrderCollection::now() );
            $date->setDay( $date->day() + $ini->variable( 'GeneralSettings', 'DaysAfterRetryOnError' ) );
            $this->setAttribute( 'next_try', $date->timeStamp() );
            if ( ( $ini->variable( 'RecurringOrderSettings', 'FailuresTillPause' ) > 1 ) and $this->failuresSinceLastSuccess() >= $ini->variable( 'RecurringOrderSettings', 'FailuresTillPause' ) )
            {
                $this->setAttribute( 'status', XROWRecurringOrderCollection::STATUS_DEACTIVATED );
                $this->sendMail( 'design:recurringorders/email/manyfailures.tpl' );
            }
            $this->store();
        }
        $db->commit();
    }

    function hasSubscription( $object_id )
    {
        $return = XROWRecurringOrderItem::fetchObject( XROWRecurringOrderItem::definition(), null, array( 
            'contentobject_id' => $object_id , 
            'is_subscription' => '1' 
        ) );
        if ( isset( $return[0] ) )
            return true;
        else
            return false;
    }

    function sendMail( $template, $params = array() )
    {
        $user = $this->attribute( 'user' );
        $userobject = $user->attribute( 'contentobject' );
        $ini = eZINI::instance();
        include_once ( "lib/ezutils/classes/ezmail.php" );
        include_once ( "lib/ezutils/classes/ezmailtransport.php" );
        $mail = new eZMail();
        
        $mail->setSender( $ini->variable( 'MailSettings', 'AdminEmail' ) );
        $mail->setReceiver( $user->attribute( 'email' ), $userobject->attribute( 'name' ) );
        
        // fetch text from mail template
        $mailtpl = eZTemplate::factory();
        foreach ( $params as $key => $value )
        {
            $mailtpl->setVariable( $key, $value );
        }
        $mailtext = $mailtpl->fetch( $template );
        $subject = $mailtpl->variable( 'subject' );
        $mail->setSubject( $subject );
        $mail->setBody( $mailtext );
        
        // mail was sent ok
        if ( eZMailTransport::send( $mail ) )
        {
            return true;
        }
        else
        {
            eZDebug::writeError( "Failed to send mail.", 'Recurring orders' );
            return false;
        }
    }

    /*!
     \static
     fetch text array of available billing cycles
     This can be called like XROWRecurringOrderCollection::getBillingCycleTextArray()
    */
    function getBillingCycleTextArray()
    {
        if ( ! isset( $GLOBALS['xrowBillingCycleTextArray'] ) )
        {
            $GLOBALS['xrowBillingCycleTextArray'] = array( 
                XROWRecurringOrderCollection::CYCLE_ONETIME => ezpI18n::tr( 'kernel/classes/recurringordercollection', "one time" ) , 
                XROWRecurringOrderCollection::CYCLE_DAY => ezpI18n::tr( 'kernel/classes/recurringordercollection', "day(s)" ) , 
                XROWRecurringOrderCollection::CYCLE_WEEK => ezpI18n::tr( 'kernel/classes/recurringordercollection', "weeks(s)" ) , 
                XROWRecurringOrderCollection::CYCLE_MONTH => ezpI18n::tr( 'kernel/classes/recurringordercollection', "month(s)" ) , 
                XROWRecurringOrderCollection::CYCLE_QUARTER => ezpI18n::tr( 'kernel/classes/recurringordercollection', "quarter(s)" ) , 
                XROWRecurringOrderCollection::CYCLE_YEAR => ezpI18n::tr( 'kernel/classes/recurringordercollection', "year(s)" ) 
            );
            $ini = eZINI::instance( 'recurringorders.ini' );
            foreach ( $ini->variable( 'RecurringOrderSettings', 'DisabledCycles' ) as $disabled )
            {
                unset( $GLOBALS['xrowBillingCycleTextArray'][$disabled] );
            }
        }
        return $GLOBALS['xrowBillingCycleTextArray'];
    }

    /*!
     \static
     fetch text array of available billing cycles
     This can be called like XROWRecurringOrderCollection::getBillingCycleTextAdjectiveArray()
    */
    function getBillingCycleTextAdjectiveArray()
    {
        
        if ( ! isset( $GLOBALS['xrowBillingCycleTextAdjectiveArray'] ) )
        {
            $GLOBALS['xrowBillingCycleTextAdjectiveArray'] = array( 
                XROWRecurringOrderCollection::CYCLE_ONETIME => ezpI18n::tr( 'kernel/classes/recurringordercollection', "one time" ) , 
                XROWRecurringOrderCollection::CYCLE_DAY => ezpI18n::tr( 'kernel/classes/recurringordercollection', "daily" ) , 
                XROWRecurringOrderCollection::CYCLE_WEEK => ezpI18n::tr( 'kernel/classes/recurringordercollection', "weekly" ) , 
                XROWRecurringOrderCollection::CYCLE_MONTH => ezpI18n::tr( 'kernel/classes/recurringordercollection', "monthly" ) , 
                XROWRecurringOrderCollection::CYCLE_QUARTER => ezpI18n::tr( 'kernel/classes/recurringordercollection', "quarterly" ) , 
                XROWRecurringOrderCollection::CYCLE_YEAR => ezpI18n::tr( 'kernel/classes/recurringordercollection', "yearly" ) 
            );
            $ini = eZINI::instance( 'recurringorders.ini' );
            foreach ( $ini->variable( 'RecurringOrderSettings', 'DisabledCycles' ) as $disabled )
            {
                unset( $GLOBALS['xrowBillingCycleTextAdjectiveArray'][$disabled] );
            }
        }
        return $GLOBALS['xrowBillingCycleTextAdjectiveArray'];
    }

    /*!
     \static
     fetch description text for the given period
     This can be called like XROWRecurringOrderCollection::getBillingCycleText( $period, $quantity )
    */
    function getBillingCycleText( $period, $quantity = 0 )
    {
        
        if ( ! isset( $GLOBALS['xrowBillingCycleText'] ) )
        {
            $GLOBALS['xrowBillingCycleText'] = array( 
                XROWRecurringOrderCollection::CYCLE_ONETIME => array( 
                    0 => ezpI18n::tr( 'kernel/classes/recurringordercollection', "one time" ) , 
                    1 => ezpI18n::tr( 'kernel/classes/recurringordercollection', "one time" ) 
                ) , 
                XROWRecurringOrderCollection::CYCLE_DAY => array( 
                    0 => ezpI18n::tr( 'kernel/classes/recurringordercollection', "days" ) , 
                    1 => ezpI18n::tr( 'kernel/classes/recurringordercollection', "day" ) 
                ) , 
                XROWRecurringOrderCollection::CYCLE_WEEK => array( 
                    0 => ezpI18n::tr( 'kernel/classes/recurringordercollection', "weeks" ) , 
                    1 => ezpI18n::tr( 'kernel/classes/recurringordercollection', "week" ) 
                ) , 
                XROWRecurringOrderCollection::CYCLE_MONTH => array( 
                    0 => ezpI18n::tr( 'kernel/classes/recurringordercollection', "months" ) , 
                    1 => ezpI18n::tr( 'kernel/classes/recurringordercollection', "month" ) 
                ) , 
                XROWRecurringOrderCollection::CYCLE_QUARTER => array( 
                    0 => ezpI18n::tr( 'kernel/classes/recurringordercollection', "quarters" ) , 
                    1 => ezpI18n::tr( 'kernel/classes/recurringordercollection', "quarter" ) 
                ) , 
                XROWRecurringOrderCollection::CYCLE_YEAR => array( 
                    0 => ezpI18n::tr( 'kernel/classes/recurringordercollection', "years" ) , 
                    1 => ezpI18n::tr( 'kernel/classes/recurringordercollection', "year" ) 
                ) 
            )
            ;
            $ini = eZINI::instance( 'recurringorders.ini' );
            foreach ( $ini->variable( 'RecurringOrderSettings', 'DisabledCycles' ) as $disabled )
            {
                unset( $GLOBALS['xrowBillingCycleText'][$disabled] );
            }
        }
        
        if ( $quantity == 1 )
        {
            if ( isset( $GLOBALS['xrowBillingCycleText'][$period][1] ) )
                return $GLOBALS['xrowBillingCycleText'][$period][1];
        }
        else
        {
            if ( isset( $GLOBALS['xrowBillingCycleText'][$period][0] ) )
                return $GLOBALS['xrowBillingCycleText'][$period][0];
        }
        
        return '';
    
    }
}
?>