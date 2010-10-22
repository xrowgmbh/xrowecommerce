<?php

$Module = $Params['Module'];

$tpl = eZTemplate::factory();
$user = eZUser::currentUser();
$http = eZHTTPTool::instance();

if ( isset( $Params['UserParameters'] ) )
{
    $viewParameters = $Params['UserParameters'];
}
else
{
    $viewParameters = array();
}
$offset = (int)$viewParameters['offset'];

$messages = array();

$limit = 10;

if ( $Module->isCurrentAction( 'Update' ) and $Module->hasActionParameter( 'ItemArray' ) )
{
    foreach ( $Module->actionParameter( 'ItemArray' ) as $item_id => $attributes )
    {
        $item = XROWRecurringOrderItem::fetch( $item_id );
        if ( $attributes['status'] != $item->attribute('status') )
        {
            if ( $attributes['status'] )
            {
                $item->setAttribute( 'status', XROWRecurringOrderCollection::STATUS_ACTIVE );
                if( $attributes['refund'] )
                {
                    $item->setAttribute( 'last_success', $item->previousDate() );
                }
            }
            else
            {
                $item->setAttribute( 'status', XROWRecurringOrderCollection::STATUS_DEACTIVATED );
            }
            $item->store();
        }
    }
    $messages[] = array( 'type' => 'feedback', 'text' => ezpI18n::tr( 'extension/recurringorders', 'Your input has been stored.' ) ) ;

}
if ( $Module->isCurrentAction( 'Cancel' ) )
{
    return $Module->redirectTo( $http->sessionVariable( "RedirectURI" ));
}
$items = XROWRecurringOrderItem::fetchAll( $offset, $limit);

$tpl->setVariable( '$messages', $messages );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'limit', $limit );
$tpl->setVariable( 'items', $items );
$tpl->setVariable( 'item_count', XROWRecurringOrderItem::count() );
$Result = array();
$Result['left_menu'] = "design:parts/xrowrecurringorders/menu.tpl";
$Result['content'] = $tpl->fetch( "design:recurringorders/listitems.tpl" );
$Result['path'] = array( array( 'url' => false,
                        'text' => 'Automatic Delivery' ) );
?>
