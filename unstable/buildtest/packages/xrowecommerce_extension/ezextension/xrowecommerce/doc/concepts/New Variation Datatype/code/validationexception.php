<?php
class ValiationException extends Exception 
{
    public $message;
    function __construct( $text )
    {
    	$this->message = $text;
    }
}
?>