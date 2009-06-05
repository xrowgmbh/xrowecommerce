<?php

class xrowOrderStatusDefault
{
    public $statusID;

    function __construct( $statusID )
    {
        $this->statusID = $statusID;
    }

    function disallowedStatusList()
    {
        if ( eZINI::instance( 'xrowecommerce.ini' )->hasVariable( 'StatusSettings', 'StatusDisallowList-' . $this->statusID ) )
        {
            $list = eZINI::instance( 'xrowecommerce.ini' )->variable( 'StatusSettings', 'StatusDisallowList-' . $this->statusID );
            if ( is_array( $list ) )
            {
                return $list;
            }
            else
            {
                return array();
            }
        }
        else
        {
            return array();
        }
    }

    function canChangeStatus( xrowOrderStatusDefault $status )
    {
        if ( ! in_array( $status->statusID, self::disallowedStatusList() ) )
        {
            $allow = true;
        }
        return $allow;
    }

    function changeStatus( eZOrder $order, xrowOrderStatusDefault $status )
    {
        if ( $this->canChangeStatus( $status ) )
        {
            if ( $status->run( $order, $this ) )
            {
                $order->modifyStatus( $status->statusID );
            }
        }
    }

    function run( eZOrder $order, xrowOrderStatusDefault $old_status )
    {
        return true;
    }

}
?>