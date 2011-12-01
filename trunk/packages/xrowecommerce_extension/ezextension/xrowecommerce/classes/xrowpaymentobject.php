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
    static function createNew( $workflowprocessID, $orderID, $paymentType = 'xrowEPayment', $data = array() )
    {
        return new xrowPaymentObject( array( 
            'order_id' => $orderID , 
            'workflowprocess_id'  => $workflowprocessID,
            'payment_string' => $paymentType , 
            'data' => serialize( $data ) 
        ) );
    }

    function approve()
    {
        $this->setAttribute( 'status', self::STATUS_APPROVED );
        $this->store();
    }

    function approved()
    {
        return ( $this->attribute( 'status' ) == self::STATUS_APPROVED );
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
                ) , 
                'workflowprocess_id' => array( 
                    'name' => 'WorkflowProcessID' , 
                    'datatype' => 'integer' , 
                    'default' => 0 , 
                    'required' => true , 
                    'foreign_class' => 'eZWorkflowProcess' , 
                    'foreign_attribute' => 'id' , 
                    'multiplicity' => '1..*' 
                ) , 
                'data' => array( 
                    'name' => 'Data' , 
                    'datatype' => 'string' , 
                    'default' => '' , 
                    'required' => false 
                ) 
            ) , 
            'keys' => array( 
                'id' 
            ) , 
            'increment_key' => 'id' , 
            'class_name' => 'xrowPaymentObject' , 
            'name' => 'xrowpaymentobject' , 
            'function_attributes' => array( 
                'automatic_status' => 'automaticPaymentStatus' , 
                'data_array' => 'dataArray' 
            ) 
        );
    }

    public function setDataArray( $array )
    {
        $this->Data = serialize( $array );
    }

    public function dataArray()
    {
        $return = unserialize( $this->Data );
        if ( ! is_array( $return ) )
        {
            return array();
        }
        else
        {
            return $return;
        }
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

    /**
     * xrowPaymentObject by 'id' of eZOrder.
     * 
     * @static
     * @return xrowPaymentObject by 'id' of eZOrder.
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
        //@TODO rewrite
        return eZPersistentObject::fetchObject( xrowPaymentObject::definition(), null, array( 
            'workflowprocess_id' => $workflowprocessID 
        ) );
    }

    /*!
     \static
    Continues workflow after approvement.
    */
    static function continueWorkflow( $workflowProcessID )
    {
        $operationResult = null;
        $theProcess = eZWorkflowProcess::fetch( $workflowProcessID );
        if ( $theProcess != null )
        {
            //restore memento and run it
            $bodyMemento = eZOperationMemento::fetchChild( $theProcess->attribute( 'memento_key' ) );
            if ( $bodyMemento === null )
            {
                eZDebug::writeError( $bodyMemento, "Empty body memento in workflow.php" );
                return $operationResult;
            }
            $bodyMementoData = $bodyMemento->data();
            $mainMemento = $bodyMemento->attribute( 'main_memento' );
            if ( ! $mainMemento )
            {
                return $operationResult;
            }
            
            $mementoData = $bodyMemento->data();
            $mainMementoData = $mainMemento->data();
            $mementoData['main_memento'] = $mainMemento;
            $mementoData['skip_trigger'] = false;
            $mementoData['memento_key'] = $theProcess->attribute( 'memento_key' );
            $bodyMemento->remove();
            
            $operationParameters = array();
            if ( isset( $mementoData['parameters'] ) )
                $operationParameters = $mementoData['parameters'];
            
            $operationResult = eZOperationHandler::execute( $mementoData['module_name'], $mementoData['operation_name'], $operationParameters, $mementoData );
        }
        
        return $operationResult;
    }
}
?>
