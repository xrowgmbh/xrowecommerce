<?php

/*!
 * class xrowProductPrice
 * Stores price information of a xrowproduct
 */
class xrowProductPriceID extends eZPersistentObject
{

    public function __construct( $row = array() )
    {
        $this->eZPersistentObject( $row );
    }

    public static function definition()
    {
        return array( 
            'fields' => array( 
                'id' => array( 
                    'name' => 'ID' , 
                    'datatype' => 'integer' , 
                    'default' => 0 , 
                    'required' => true 
                ) 
            ) , 
            'function_attributes' => array() , 
            'keys' => array( 
                'id' 
            ) , 
            'sort' => array( 
                'id' => 'asc' 
            ) , 
            'class_name' => 'xrowProductPriceID' , 
            'name' => 'xrowproduct_price_id' 
        );
    }

    public static function create()
    {
        $db = eZDB::instance();
        $sql = "INSERT INTO xrowproduct_price_id () VALUES ()";
        $db->query( $sql );
        $priceID = $db->lastSerialID();
        return $priceID;
    }

    public static function fetch( $id, $asObject = true )
    {
        return eZPersistentObject::fetchObject( self::definition(), null, array( 
            "id" => $id 
        ), $asObject );
    }
}
?>