<?php

$Module =& $Params['Module'];
include_once( 'kernel/common/template.php' );
$tpl = eZTemplate::factory();
$user = eZUser::currentUser();
$http = eZHTTPTool::instance();
if ( !$Params['CollectionID'] )
{
    $collection = XROWRecurringOrderCollection::fetchByUser();
    if ( !is_array( $collection ) or count( $collection ) == 0 )
        $collection = XROWRecurringOrderCollection::createNew();
    return $Module->redirectTo( "recurringorders/list/" . $collection[0]->id );
}
$messages = array();

$collection = XROWRecurringOrderCollection::fetch( $Params['CollectionID'] );
if ( eZUser::currentUserID() != $collection->user_id and !$user->hasAccessTo( 'recurringorders', 'administrate' ) )
{
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
}
if ( $Module->isCurrentAction( 'Remove' ) and $Module->hasActionParameter( 'RemoveArray' ) )
{
    foreach ( $Module->actionParameter( 'RemoveArray' ) as $item_id )
    {
        $item = XROWRecurringOrderItem::fetch( $item_id );
        if ( is_object( $item ) )
            $item->remove();
    }
    $messages[] = array( 'type' => 'feedback', 'text' => ezpI18n::tr( 'extension/recurringorders', 'Your input has been stored.' ) ) ;
}
if ( $Module->isCurrentAction( 'Update' ) and $Module->hasActionParameter( 'ItemArray' ) )
{
    foreach ( $Module->actionParameter( 'ItemArray' ) as $item_id => $settings )
    {
        $item = XROWRecurringOrderItem::fetch( $item_id );
        list ( $month, $day, $year ) = split( "/", $settings['next_date'] , 3 );
        $nextdate = gmmktime( 0,0,0, $month, $day, $year );
        if ( $nextdate != $item->next_date )
        {
            $datehaschanged = true;
        }
        else
        {
            $datehaschanged = false;
        }
        $item->setAttribute( 'cycle_unit', (int)$settings['cycle_unit'] );
        if( $settings['cycle'] > 0 )
            $item->setAttribute( 'cycle', (int)$settings['cycle'] );
        else
            $item->setAttribute( 'cycle', 1 );
        $item->setAttribute( 'amount', (int)$settings['amount'] );
        
        if ( $datehaschanged )
        {
            $item->setAttribute( 'next_date', (int)$nextdate );
        }
        $item->store();
    }
    $messages[] = array( 'type' => 'feedback', 'text' => ezpI18n::tr( 'extension/recurringorders', 'Your input has been stored.' ) ) ;
    if ( $Module->actionParameter( 'Pause' ) )
    {
        $collection->setAttribute( 'status', XROWRecurringOrderCollection::STATUS_DEACTIVATED );
    }
    else
    {
        $collection->setAttribute( 'status', XROWRecurringOrderCollection::STATUS_ACTIVE );
    }
    $collection->store();
}
if ( $Module->isCurrentAction( 'Cancel' ) )
{
    return $Module->redirectTo( $http->sessionVariable( "RedirectURI" ));
}

if ( $collection->checkCreditCard() !== true )
    $messages[] = array( 'type' => 'error', 'text' => ezpI18n::tr( 'extension/recurringorders', 'We have encountered problems with your credit card. Please update your profile.' ) ) ;
$tpl->setVariable( 'messages', $messages );
$tpl->setVariable( 'collection', $collection );
$Result = array();
$Result['left_menu'] = "design:parts/xrowrecurringorders/menu.tpl";
$Result['content'] = $tpl->fetch( "design:recurringorders/list.tpl" );
$Result['path'] = array( array( 'url' => false,
                        'text' => 'Automatic Delivery' ) );
?>
