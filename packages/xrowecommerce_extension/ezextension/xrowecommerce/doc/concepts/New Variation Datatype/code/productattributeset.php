<?php
class ProductAttributeSet1 extends BaseProductAttributeSet 
{
    public $required = true;
    /*
     * @return Array of Attributes
     */
    function attributeList()
    {
        return array( new attrtibute1(), new attrtibute1() );
    }
    function name() {
    	return " 2x Attribute 1";
    }
    function identifier() {
        return "attribute1x2";
    }
}
?>