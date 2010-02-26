<?php
$http = eZHTTPTool::instance();
$Module = $Params['Module'];

if ( $http->hasPostVariable( "RedirectURI" ) )
{
	$http->setSessionVariable( 'RedirectURI', $http->postVariable( "RedirectURI" ) );
}
$Module->redirectTo( 'user/register' );
?>