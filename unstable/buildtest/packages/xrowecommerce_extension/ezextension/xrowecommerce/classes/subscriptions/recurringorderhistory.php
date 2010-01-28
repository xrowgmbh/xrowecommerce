<?php

class XROWRecurringOrderHistory extends eZPersistentObject
{
    function XROWRecurringOrderHistory( $row )
    {
        parent::eZPersistentObject( $row );
    }
    static function definition()
    {
        return array( "fields" => array( 
                                         "id" => array( 'name' => "id",
                                                                      'datatype' => 'integer',
                                                                      'default' => 0,
                                                                      'required' => true ),
                                         "collection_id" => array( 'name' => "collection_id",
                                                                   'datatype' => 'integer',
                                                                   'default' => 0,
                                                                   'required' => true ),
                                         "order_id" => array( 'name' => "order_id",
                                                              'datatype' => 'integer',
                                                              'default' => null,
                                                              'required' => true ),
                                         "type" => array( 'name' => "type",
                                                          'datatype' => 'integer',
                                                          'default' => null,
                                                                   'required' => true ),
                                         "date" => array( 'name' => "date",
                                                                   'datatype' => 'integer',
                                                                   'default' => time(),
                                                                   'required' => true ),
                                         "data_text" => array( 'name' => "data_text",
                                                                   'datatype' => 'string',
                                                                   'default' => null,
                                                                   'required' => true )
                                                                   ),
                      "keys" => array( "id" ),
                      "increment_key" => "id",
                      "function_attributes" => array( 
                            "collection" => "collection",
                            "type_text" => "typeText"
                       ),
                      "class_name" => "XROWRecurringOrderHistory",
                      "sort" => array( "date" => "asc" ),
                      "name" => "xrow_recurring_order_history" );
    }
    function typeText()
    {
    	switch ( $this->type )
    	{
    	    case XROWRECURRINGORDER_STATUSTYPE_SUCCESS:
    	    {
    	       return 'SUCCESS';
    	    }break;
    	    case XROWRECURRINGORDER_STATUSTYPE_CREDITCARD_EXPIRES:
    	    {
    	       return 'CREDITCARD EXPIRED';
    	    }break;
    	    case XROWRECURRINGORDER_STATUSTYPE_FAILURE:
    	    default:
    	    {
        	    return 'FAILURE';   
    	    }break;
    	}
    }
    function collection()
    {
    	return XROWRecurringOrderCollection::fetch( $this->collection_id );
    }
    function add( $type = XROWRECURRINGORDER_STATUSTYPE_FAILURE, $collection_id = null, $text = '', $order_id = null )
    {
       	$item = new XROWRecurringOrderHistory( array( 'type' => $type, 'date' => mktime(), 'collection_id' => $collection_id, 'order_id' => $order_id, 'data_text' => $text ) );
        $item->store();
    }
        /*!
     \returns number of messages.
    */
    function historyCount( )
    {
        $db = eZDB::instance();
        $countArray = $db->arrayQuery(  "SELECT count(id) AS count FROM xrow_recurring_order_history" );
        $count = $countArray[0]['count'];
        return $count;
    }
        /*!
     \return the list customers.
    */
    function historyList( $offset, $limit )
    {

        $db_params = array();
        $db_params["offset"] =(int) $offset;
        $db_params["limit"] =(int) $limit;
        return XROWRecurringOrderHistory::fetchObjectList( XROWRecurringOrderHistory::definition(), null, null, array( 'date' => true, 'id' => true ), $db_params );
         
    }
}
?>