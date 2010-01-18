<?php
include_once( 'lib/ezutils/classes/ezcli.php' );
include_once( 'lib/ezutils/classes/ezextension.php' );
include_once( 'kernel/classes/ezscript.php' );
include_once( eZExtension::baseDirectory() . '/recurringorders/classes/xrowrecurringorderscommonfunctions.php' );

$cli = eZCLI::instance();
$script = eZScript::instance( array( 'description' => ( "eZ publish recurring orders\n" .
                                                         "php extension/recurringorders/bin/recurringorders.php" ),
                                      'use-session' => false,
                                      'use-modules' => true,
                                      'use-extensions' => true ) );

$script->startup();

$options = $script->getOptions( "",
                                "",
                                array(  ) );
$sys = eZSys::instance();

$script->initialize();
if ( !$isQuiet )
{
    
    $cli->output( 'Using Siteaccess '.$GLOBALS['eZCurrentAccess']['name'] );
    
}

// login as admin
include_once( 'kernel/classes/datatypes/ezuser/ezuser.php' );
$user = eZUser::fetchByName( 'admin' );

if ( is_object( $user ) )
{
	if ( $user->loginCurrent() )
	   $cli->output( "Logged in as 'admin'" );
}
else
{
	$cli->error( 'No admin.' );
    $script->shutdown( 1 );
}

include_once( 'extension/recurringorders/classes/recurringordercollection.php');
$cli->output( "Today is " . strftime( "%d.%m.%Y", XROWRecurringOrderCollection::now() ) );

$list = XROWRecurringOrderCollection::fetchAll();
foreach ( $list as $collection )
{
    $cli->output( "Processing Collection #" . $collection->id );
    $collection->markRun();
    $user = $collection->attribute( 'user' );
    if ( $collection->attribute( 'status' ) == XROWRECURRINGORDER_STATUS_DEACTIVATED )
    {
        $cli->output( "Collection #" . $collection->id . ' deactivated' );
        continue;
    }
    if ( !$collection->canTry() )
    {
        $cli->output( "Collection #" . $collection->id . ' has to wait for the next try');
        continue;
    }
    if ( !$collection->isDue() )    
    {
        $cli->output( "Collection #" . $collection->id . " has no items that are due." );
        continue;
    }
    $cccheck = $collection->checkCreditCard();
    if ( $cccheck !== true )
    {
        $collection->sendMail( 'design:recurringorders/email/check_payment.tpl' );
        $collection->addHistory( XROWRECURRINGORDER_STATUSTYPE_CREDITCARD_EXPIRES, 'Creditcard expires' );
        
        // credit card error
        $cli->output( "Collection #" . $collection->id . ' credit card error' );
        continue;
    }

    include_once( 'kernel/classes/ezshopaccounthandler.php' );

    $accountHandler = eZShopAccountHandler::instance();
    // Do we have all the information we need to start the checkout
    if ( !$accountHandler->verifyAccountInformation() )
    {
        continue;
    }
    $items = $collection->fetchDueList();
    foreach ( $items as $key => $item )
    {
        //@todo check if all items are valid
        if ( !$item->isValid() )
        {
            $collection->sendMail( 'design:recurringorders/email/item_not_available.tpl' );
            $item->remove();
            unset( $items[$key] );
        }
    }
    if ( count( $items ) == 0 )
    {
        $cli->output( "Collection #" . $collection->id . " is empty after checking for validity." );
        continue;
    }
    
    
    $order = $collection->createOrder( $items );

    $userArray = $accountHandler->fillAccountArray( $user );

    $node = XROWRecurringordersCommonFunctions::createDOMTreefromArray( "shop_account", $userArray );
    $doc = new eZDOMDocument( 'account_information' );
    $doc->setRoot( $node );
    $docstring = $doc->toString();
    $shopaccountini = eZINI::instance( "shopaccount.ini" );
    $account_identifier = $shopaccountini->variable( 'AccountSettings', 'Handler' );
    $order->setAttribute( 'data_text_1', $doc->toString() );
    $order->setAttribute( 'account_identifier', $account_identifier );
    $order->setAttribute( 'email', $accountHandler->email( $order ) );
    $order->setAttribute( 'ignore_vat', 1 );
    $order->store();
    eZHTTPTool::setSessionVariable( 'MyTemporaryOrderID', $order->ID );
    
    //set TAX

    $productItems = eZPersistentObject::fetchObjectList( eZProductCollectionItem::definition(), null, array( "productcollection_id" => $order->ProductCollectionID ) );
    $country = eZVATManager::getUserCountry( $user, false );
    foreach ( $productItems as $item )
    {
        $item->setAttribute( 'vat_value', eZVATManager::getVAT( $item->attribute( 'contentobject' ), $country ) );
        $item->store();
    }
    $orderItems = $order->orderItems();
    foreach ( $orderItems as $item )
    {
        $item->setAttribute( 'vat_value', eZVATManager::getVAT( $item['item_object']->attribute( 'contentobject' ), $country ) );
        $item->store();
    }
    $order->setAttribute( 'ignore_vat', 0 );
    $order->store();

    $operationResult = eZOperationHandler::execute( 'recurringorders', 'checkout', array( 'order_id' => $order->attribute( 'id' ) ) );
    switch( $operationResult['status'] )
    {
        case EZ_MODULE_OPERATION_HALTED:
        {
            if (  isset( $operationResult['redirect_url'] ) )
            {
                $collection->addHistory( XROWRECURRINGORDER_STATUSTYPE_FAILURE, "Order has been processed with a strange result.", $order->ID );
                $order->remove();
                continue 2;
            }
            else if ( isset( $operationResult['result'] ) )
            {
                $collection->addHistory( XROWRECURRINGORDER_STATUSTYPE_FAILURE, "Order has been processed with a strange result.", $order->ID );
                $order->remove();
                continue 2;
            }
        }break;
        case EZ_MODULE_OPERATION_CANCELED:
        {
            $collection->addHistory( XROWRECURRINGORDER_STATUSTYPE_FAILURE, "Order has been CANCELED.", $order->ID );
            $order->remove();
            continue 2;
        }break;
        default:
        {

        }break;
    }
    $order = eZOrder::fetch( $order->ID );
    $cli->output( "Order #" . $order->OrderNr . " created." );
    foreach ( $items as $item )
    {
        $item->setAttribute( 'last_success', $item->attribute( 'next_date' ) );
        $item->store();
        $cli->output( "  Item #" . $item->item_id . " next order is on " . strftime( "%d.%m.%Y", $item->attribute( 'next_date' ) ) );
    }
    $collection->addHistory( XROWRECURRINGORDER_STATUSTYPE_SUCCESS, "Order has been completed.", $order->ID ); 
}
$cli->output( "Recurring Orders processed" );

$script->shutdown();
?>
