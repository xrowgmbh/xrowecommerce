<?php

$http = eZHTTPTool::instance();
$Module = $Params['Module'];

include_once( "kernel/common/template.php" );

if ( $http->hasPostVariable( "ActionAddToRecurring" ) )
{
    $result = array();
    if ( $http->hasPostVariable( "AddToBasketList" ) and is_array( $http->postVariable( "AddToBasketList" ) ) )
    {
        
        foreach ( $http->postVariable( "AddToBasketList" ) as $position )
        {
            if ( $position['quantity'] > 0 and $position['object_id'] )
            {
                if ( !is_array( $position['variations'] ) )
                    $position['variations'] =array();
                $result[] = $position;
            }
        }
    }
    elseif ( $http->hasPostVariable( "ContentObjectID" ) and $http->hasPostVariable( "ProductItemCountList" ) )
    {
        $result = array();
        $position = array();
        if ( $http->postVariable( "ProductItemCountList" ) > 0 )
        {
            $position['quantity'] = $http->postVariable( "ProductItemCountList" );
        }
        else
        {
            $position['quantity'] = 1;
        }
        $position['object_id'] = $http->postVariable( "ContentObjectID" );
        $position['variations'] = array();
        $result[] = $position;
    }
    if ( count( $result ) == 0 )
    {
        $module->redirectTo( $_SERVER['HTTP_REFERER'] );
        return;
    }
    $collection  = XROWRecurringOrderCollection::fetchByUser();
    if ( !is_array( $collection ) or count( $collection ) == 0 )
    $collection = XROWRecurringOrderCollection::createNew();
    elseif ( is_array( $collection ) )
    {
        $collection = $collection[0];
    }
    foreach ( $result as $item )
    {
        $collection->add( $item['object_id'], $item['variations'], $item['quantity']);
    }
    $http->setSessionVariable( "RedirectURI", $http->sessionVariable( 'LastAccessesURI' ) );
}
if ( $collection->id )
    return $Module->redirectTo( "recurringorders/list/" . $collection->id );
else
    return $Module->handleError( EZ_ERROR_KERNEL_ACCESS_DENIED, 'kernel' );
?>