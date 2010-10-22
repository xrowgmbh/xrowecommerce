<?php

$Module = $Params['Module'];
$http = eZHTTPTool::instance();
$db = eZDB::instance();
if( $http->hasPostVariable( 'E-mail' ) )
{
    $email = $db->escapeString( $http->Variable( 'E-mail' ) );
}
else
{
    return $Module->handleError( EZ_ERROR_KERNEL_NOT_AVAILABLE, 'kernel' );
}

eZDebug::writeDebug( "Starting module", "Customersearch");
/*
SELECT e.user_id, e.email FROM ezorder e
WHERE e.is_archived ="0" AND e.is_temporary ="0"
AND email = "amy@harmonydayspa.com"
ORDER BY e.email;
*/



if ( is_numeric( $email ) )
{
    $userarray = $db->arrayQuery('SELECT distinct e.user_id, e.email FROM ezorder e
    WHERE e.is_archived ="0" AND e.is_temporary ="0"
    AND order_nr = "'.$email.'"
    ORDER BY e.email;');
}
else
{
$userarray = $db->arrayQuery('SELECT distinct e.user_id, e.email FROM ezorder e
    WHERE e.is_archived ="0" AND e.is_temporary ="0"
    AND email = "'.$email.'"
    ORDER BY e.email;');
}
if ( count($userarray) == 1)
{
    $user_id=$userarray[0]["user_id"];
    $Module->redirectTo( "/shop/customerorderview/$user_id/".$userarray[0]["email"] );
}
elseif ( count($userarray) >= 2 )
{
    $Module->redirectTo( "/customersearch/multiple/".$email );
}

elseif ( count($userarray) == 0 )
{
    $Module->redirectTo( "/customersearch/multiple/".$email );
}


$Result = array();
$Result['content'] = "";
?>