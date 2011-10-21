<?php

class xrowEPaymentGateway extends eZPaymentGateway
{
    const AUTOMATIC_STATUS = true;
    // if a list of "after sale products" is not allowed set AFTER_SALE to false in your gateway
    const AFTER_SALE = true;
	const GATEWAY_COSTS = 'gatewaycosts';
    
    private $td;
    public $description;
	public $costs;
	
	function name()
	{
	    return null;
	}
    function costs()
	{
	    return 0.00;
	}
    /* Capture a payment
     * 
     * @returns boolean true on success
     * @throws xrowPaymentErrorException
     */
    function capture( eZOrder $order )
    {
        return true;
    }

    /* Cancel a payment
     * 
     * @returns boolean true on success
     * @throws xrowPaymentErrorException
     */
    function cancel( eZOrder $order )
    {
        return true;
    }

    /*
     * Needs an existing order to be executed charges an additional amount toward the first transaction
     *  
     */
    function furtherCharge( eZOrder $order, $amount = 0.00 )
    {
        return true;
    }

    static function encryptData( $planeTextData )
    {
    	if ( $planeTextData != '' || is_object( $planeTextData ) || is_array( $planeTextData ) )
    	{
	    	$xrowini = eZINI::instance( 'xrowecommerce.ini' );
	        $key = $xrowini->variable( 'EncryptionSettings', 'Key' );
	        $algorithm = $xrowini->variable( 'EncryptionSettings', 'Algorithm' );
	        $mode = $xrowini->variable( 'EncryptionSettings', 'Mode' );
	        if ( is_object( $planeTextData ) || is_array( $planeTextData ) )
	        {
		        foreach ( $planeTextData as $planeTextItem )
		        {
		            $planeText = $planeTextItem;
		        }
	        }
	        else
	        {
	        	$planeText = $planeTextData;
	        }
	        $td = mcrypt_module_open( $algorithm, '', $mode, '' );
	        $iv = mcrypt_create_iv( mcrypt_enc_get_iv_size( $td ), MCRYPT_RAND );
	        $okey = substr( md5( $key . rand( 0, 9 ) ), 0, mcrypt_enc_get_key_size( $td ) );
	        mcrypt_generic_init( $td, $okey, $iv );
	        $encrypted = mcrypt_generic( $td, $planeText . chr( 194 ) );
	        $encryptedString = $encrypted . $iv;
	        
	        return base64_encode( $encryptedString );
    	}
    	break;
    }

    static function decryptData( $encryptedStringData, $limit = 0 )
    {
    	if ( $encryptedStringData != '' || is_object( $encryptedStringData ) || is_array( $encryptedStringData ) )
    	{
	    	$xrowini = eZINI::instance( 'xrowecommerce.ini' );
	        $key = $xrowini->variable( 'EncryptionSettings', 'Key' );
	        $algorithm = $xrowini->variable( 'EncryptionSettings', 'Algorithm' );
	        $mode = $xrowini->variable( 'EncryptionSettings', 'Mode' );
	        
	        if ( is_object( $encryptedStringData ) || is_array( $encryptedStringData ) )
	        {
		        foreach ( $encryptedStringData as $encryptedStringDataItem )
		        {
		            $encryptedString = base64_decode( $encryptedStringDataItem );
		        }
	        }
	        else
	        {
	        	$encryptedString = base64_decode( $encryptedStringData );
	        }
	        
	        $td = mcrypt_module_open( $algorithm, '', $mode, '' );
	        $iv = substr( $encryptedString, - 8 );
	        $encrypted = substr( $encryptedString, 0, - 8 );
	        for ( $i = 0; $i < 10; $i ++ )
	        {
	            $okey = substr( md5( $key . $i ), 0, mcrypt_enc_get_key_size( $td ) );
	            mcrypt_generic_init( $td, $okey, $iv );
	            $decrypted = trim( mdecrypt_generic( $td, $encrypted ) );
	            mcrypt_generic_deinit( $td );
	            $planeText = substr( $decrypted, 0, - 1 );
	            if ( ord( substr( $decrypted, - 1 ) ) == 194  )
	                break;
	        }
	        mcrypt_module_close( $td );
	        // cut the result, if a limit is greater than 0
	    	if ( $limit > 0 )
	        {
	            $planeText = strrev( substr( strrev( $planeText ), 0, 4 ) );
	        } 
	        
	        return $planeText;
    	}
    	break;
    }
}

?>