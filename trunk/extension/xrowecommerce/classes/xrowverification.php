<?php

class xrowVerification
{
	
    
    function verify( $http )
    {
    	include_once( "extension/xrowecommerce/classes/recaptchalib.php" );
	    $recaptcha = false;
	    $ini = eZINI::instance( 'recaptcha.ini' );
	    // If PrivateKey is an array try and find a match for the current host
	    $privatekey = $ini->variable( 'Keys', 'PrivateKey' );
	    if ( is_array($privatekey) )
	    {
	      $hostname = eZSys::hostname();
	      if (isset($privatekey[$hostname]))
	        $privatekey = $privatekey[$hostname];
	      else
	        // try our luck with the first entry
	        $privatekey = array_shift($privatekey);
	    }
	    $recaptcha_challenge_field = $http->postVariable('recaptcha_challenge_field');
	    $recaptcha_response_field = $http->postVariable('recaptcha_response_field');
	    $resp = recaptcha_check_answer ($privatekey,
	                                $_SERVER["REMOTE_ADDR"],
	                                $recaptcha_challenge_field,
	                                $recaptcha_response_field);
        return $resp->is_valid;
    }
    
}

?>