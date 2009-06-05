<?php

class xrowPaymentObject extends eZPersistentObject
{
    const STATUS_NOT_APPROVED = 0;
    const STATUS_APPROVED = 1;

    /*!
    Constructor.
    */
    function xrowPaymentObject( $row )
    {
        $this->eZPersistentObject( $row );
    }

    /*!
     \static
    Creates new object.
    */
    static function createNew( $orderID, $paymentType = 'xrowEPaymentGateway' )
    {
        return new xrowPaymentObject( array( 
            'order_id' => $orderID , 
            'payment_string' => $paymentType 
        ) );
    }

    function modifyStatus( $id = false )
    {
        switch ( $id )
        {
            case self::STATUS_APPROVED:
                $this->setAttribute( 'status', self::STATUS_APPROVED );
                $this->store();
                break;
            case self::STATUS_NOT_APPROVED:
                $this->setAttribute( 'status', self::STATUS_NOT_APPROVED );
                $this->store();
                break;
        }
    
    }

    static function definition()
    {
        return array( 
            'fields' => array( 
                'id' => array( 
                    'name' => 'ID' , 
                    'datatype' => 'integer' , 
                    'default' => 0 , 
                    'required' => true 
                ) , 
                'order_id' => array( 
                    'name' => 'OrderID' , 
                    'datatype' => 'integer' , 
                    'default' => 0 , 
                    'required' => false , 
                    'foreign_class' => 'eZOrder' , 
                    'foreign_attribute' => 'id' , 
                    'multiplicity' => '1..*' 
                ) , 
                'payment_string' => array( 
                    'name' => 'PaymentString' , 
                    'datatype' => 'string' , 
                    'default' => '' , 
                    'required' => false 
                ) , 
                'status' => array( 
                    'name' => 'Status' , 
                    'datatype' => 'integer' , 
                    'default' => 0 , 
                    'required' => true 
                ) 
            ) , 
            'keys' => array( 
                'id' 
            ) , 
            'increment_key' => 'id' , 
            'class_name' => 'xrowPaymentObject' , 
            'name' => 'xrowPaymentObject' , 
            'function_attributes' => array( 
                'automatic_status' => 'automaticPaymentStatus' 
            ) 
        );
    }

    public function automaticPaymentStatus()
    {
        $gateway = xrowEPayment::instanceGateway( $this->PaymentString );
        return constant( get_class( $gateway ) . '::AUTOMATIC_STATUS' );
    }

    /*!
     \static
    Returns xrowPaymentObject by 'id'.
    */
    static function fetchByID( $transactionID )
    {
        return eZPersistentObject::fetchObject( xrowPaymentObject::definition(), null, array( 
            'id' => $transactionID 
        ) );
    }

    /*!
     \static
    Returns xrowPaymentObject by 'id' of eZOrder.
    */
    static function fetchByOrderID( $orderID )
    {
        return eZPersistentObject::fetchObject( xrowPaymentObject::definition(), null, array( 
            'order_id' => $orderID 
        ) );
    }

    /*!
     \static
    Returns xrowPaymentObject by 'id' of eZWorkflowProcess.
    */
    static function fetchByProcessID( $workflowprocessID )
    {
        return eZPersistentObject::fetchObject( xrowPaymentObject::definition(), null, array( 
            'workflowprocess_id' => $workflowprocessID 
        ) );
    }
}
?>
