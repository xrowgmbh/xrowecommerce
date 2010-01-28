<?php

class XROWRecurringOrderItemOption extends eZPersistentObject
{
    function XROWRecurringOrderItemOption( $row )
    {
        parent::eZPersistentObject( $row );
    }
    static function definition()
    {
        return array( "fields" => array( 
                                         "item_id" => array( 'name' => "item_id",
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         "variation_id" => array( 'name' => "variation_id",
                                                             'datatype' => 'integer',
                                                             'default' => null,
                                                             'required' => true ),
                                         "option_id" => array( 'name' => "option_id",
                                                                   'datatype' => 'integer',
                                                                   'default' => null,
                                                                   'required' => true ) 
                                                                   ),
                      "keys" => array( "item_id", "variation_id", "option_id" ),
                      "function_attributes" => array(),
                      "class_name" => "XROWRecurringOrderItemOption",
                      "sort" => array( "item_id" => "asc" ),
                      "name" => "xrow_recurring_order_item_option" );
    }
}
?>