<?php

class xrowSubscription
{
    const STATUS_UNDEFINED = 0;
    const STATUS_TRIAL = 1;
    const STATUS_ACTIVE = 2;
	const STATUS_PENDING = 3;
	const STATUS_SUSPENDED = 4;
	const STATUS_OVERDUE = 5;
	const STATUS_CANCELED = 6;
	const STATUS_DELETED = 7;
	const STATUS_NOT_SUBSCRIPED = 8;
	const STATUS_INIT_ACTIVE = 9;
    
    function getHandlerArray()
    {
        if ( count( $GLOBALS['XROWSubscriptionHandlerArray'] ) == 0 )
        {
            $ini = eZINI::instance( 'recurringorders.ini' );
            $subscriptionArray = $ini->variable( 'SubscriptionSettings', 'SubscriptionHandlerArray' );
            $repositoryArray = $ini->variable( 'SubscriptionSettings', 'SubscriptionHandlerRepository' );

            foreach ( $subscriptionArray as $subscription )
            {
                if ( !xrowSubscription::findHandler( $subscription, $repositoryArray ) )
                    eZDebug::writeError( $subscription . ': No file for inclusion found.',
                                         'xrowSubcription::getHandlerArray' );
            }
        }
        return $GLOBALS['XROWSubscriptionHandlerArray'];
    }

    function findHandler( $subscription, $repositoryArray )
    {
        foreach ( $repositoryArray as $repository )
        {
            $fileName = eZExtension::baseDirectory() . "/$repository/classes/subscription_handler/" .
                        strtolower( $subscription ) . 'subscriptionhandler.php';
            if ( file_exists( $fileName ) )
            {
                include_once( $fileName );
                $GLOBALS['XROWSubscriptionHandlerArray'][$subscription] = $subscription;
                return true;
            }
        }
        return false;
    }

    function getHandler( $item )
    {
        if ( in_array( $item->subscription_handler, xrowSubscription::getHandlerArray() ) )
        {
            $className = $item->subscription_handler . 'SubscriptionHandler';
            $newObject = new $className( );
            $newObject->item = $item;
            $newObject->info = $newObject->createArrayfromXML( $item->attribute( 'data_text' ) );
            return $newObject;
        }
        else
            return false;
    }
}
?>