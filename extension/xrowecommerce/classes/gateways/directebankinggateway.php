<?php
/* Gateway for a popular german payment provider called Payment network 
 * https://www.payment-network.com aka sofortueberweisung.de
 *
 */
class DIRECTebankingGateway extends xrowEPaymentGateway
{
    const GATEWAY_STRING = "DIRECTebanking";
    const AUTOMATIC_STATUS = false;

    function __construct()
    {
        $this->logger   = eZPaymentLogger::CreateForAdd( "var/log/DIRECTebankingGateway.log" );
        $this->logger->writeTimedString( 'DIRECTebankingGateway::directeBankingGateway()' );
    }

    function createPaymentObject( $processID, $orderID )
    {
        $this->logger->writeTimedString("createPaymentObject");
        return eZPaymentObject::createNew( $processID, $orderID, 'DIRECTebanking' );
    }

    function createRedirectionUrl( $process )
    {
        $parameters = $process->attribute( 'parameter_list' );
        $orderID = $parameters['order_id'];
        $order = eZOrder::fetch( $orderID );
        
        $directebankingINI = eZINI::instance( 'directebanking.ini' );
        
        $data = array(
            'user_id'               => $directebankingINI->variable( 'EbankingSettings', 'UserID' ),
            'project_id'            => $directebankingINI->variable( 'EbankingSettings', 'ProjectID' ),
            'sender_holder'         => $order->attribute( 'account_name' ),
            'sender_account_number' => '',
            'sender_bank_code'      => '',
            'sender_country_id'     => '',
            'amount'                => $order->attribute('total_inc_vat'),
            'currency_id'           => $order->currencyCode(),
            'reason_1'              => ezi18n( 'extension/directebanking/common', 'Order' ) . " " . $order->ID,
            'reason_2'              => '',
            'user_variable_0'       => '',
            'user_variable_1'       => $orderID,
            'user_variable_2'       => '',
            'user_variable_3'       => '',
            'user_variable_4'       => '',
            'user_variable_5'       => '',
            'project_password'      => $directebankingINI->variable( 'EbankingSettings', 'ProjectPWD' )
        );
        
        $data_serial = implode( '|', $data );
        switch( strtolower($directebankingINI->variable( 'EbankingSettings', 'InputCheckType' )) )
        {
            case 'sha1':
            case 'sha256':
            case 'md5':
            {
                $hash = hash(strtolower($directebankingINI->variable( 'EbankingSettings', 'InputCheckType' )), $data_serial );
            }break;
            default:
            {
                $hash = hash('md5', $data_serial );
            }break;
        }
        
        $server_uri = $directebankingINI->variable( 'ServerSettings', 'RequestURL' );
        
        $variables = '?';
        foreach( $data as $key => $value )
        {
            if( trim($value) != '' )
            {
                $variables .= $key.'='.$value.'&';
            }
        }
        $check = 'hash='.$hash;
        return $server_uri.$variables.$check;
    }

    function execute( $process, $event )
    {
        $processParams = $process->attribute( 'parameter_list' );
        $processID = $process->attribute( 'id' );
        $orderID = $processParams['order_id'];
        $order = eZOrder::fetch( $orderID );
        
        $xmlstring = $order->attribute( 'data_text_1' );
        if ( $xmlstring != null )
        {
            $doc = new DOMDocument( );
            $doc->loadXML( $xmlstring );
            $root = $doc->documentElement;
            $invoice = $doc->createElement( xrowECommerce::ACCOUNT_KEY_PAYMENTMETHOD, DIRECTebankingGateway::GATEWAY_STRING );
            $root->appendChild( $invoice );
            $order->setAttribute( 'data_text_1', $doc->saveXML() );
            $order->store();
        }
        return eZWorkflowType::STATUS_ACCEPTED;
    }

}
xrowEPayment::registerGateway( DIRECTebankingGateway::GATEWAY_STRING, "directebankinggateway", ezi18n( 'kernel/classes/datatypes', 'DIRECTebanking' ) );
?>