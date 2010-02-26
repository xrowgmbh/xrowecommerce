<?php
class BaseProductAttributeSet implements ProductAttribute 
{
    public $required = true;
    public $type = 'int';
    /*
     * @return Array of Attributes
     */
    function attributeList()
    {
        return array( );
    }
    function validate() {
    	foreach ( $this->attributeList() as $attr )
    	{
    	    try
    	    {
    	       $attr->validate();
    	    }
    	    catch ( ValiationException $e )
    	    {
    	        array_push( $this->errors[$attr->identifier()], $e->message );
    	    }
    	}
    }
    function name() {
    	return "";
    }
    function identifier() {
    	return "";
    }
}
?>