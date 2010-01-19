<?php
if ( $Params['Parameters'][0] )
{
switch ( $Params['Parameters'][0] )
{
	case 'getshipping':
	$return = xrowECommerceJSON::getShipping( $_GET['country'] );
	break;
}
    $json = json_encode( $return );
    print_r( $json );
}


eZExecution::cleanExit();
/** first idea of an abstract JOSN server
if ( class_exists( $Params['Parameters'][0] ) )
{
    $classname = $Params['Parameters'][0];
    
    if ( ! empty( $classname ) && class_exists( $classname ) )
    {
        $object = new $classname( );
        if ( $object instanceof eZJSON && method_exists( $object ) )
        {
            $method = $Params['Parameters'][1];
            if ( count( $_GET ) > 0 )
            {
                $return = call_user_func( array( 
                    $classname , 
                    $method 
                ), $_GET );
            }
            elseif ( count( $_POST ) )
            {
                $return = call_user_func( array( 
                    $classname , 
                    $method 
                ), $_POST );
            }
            else
            {
                $return = call_user_func( array( 
                    $classname , 
                    $method 
                ) );
            }
        }
    }
    $json = json_encode( $return );
    print_r( $json );
}
eZExecution::cleanExit();

*/
?>