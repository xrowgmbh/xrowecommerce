<?php

class xrowPaymentGatewayType extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = 'xrowpaymentgateway';
    const GATEWAY_NOT_SELECTED = 0;
    const GATEWAY_SELECTED = 1;

    /*!
    Constructor.
    */
    
    function __construct()
    {
        $this->logger = eZPaymentLogger::CreateForAdd( "var/log/eZPaymentGatewayType.log" );
        $this->eZWorkflowEventType( xrowPaymentGatewayType::WORKFLOW_TYPE_STRING, ezi18n( 'kernel/workflow/event', "xrow Ecommerce Payment Gateway" ) );
        $this->setTriggerTypes( array( 
            'shop' => array( 
                'checkout' => array( 
                    'before' 
                ) 
            ) 
        ) );
        xrowEPayment::loadAndRegisterGateways();
    }

    /*!
    Creates necessary gateway and delegate execution to it.
    If there are multiple gateways in eZPaymentGatewayType, fetches
    template with list of 'selected'(see. 'attributes' section)
    gateways and asks user to choose one.
    */
    
    function execute( $process, $event )
    {
        $GLOBALS['xrowPaymentGatewayFailedAttempt'] = true;
        $this->logger->writeTimedString( 'execute' );
        // Captcha check begin
        $parameters = $process->attribute( 'parameters' );
        $parameters = unserialize( $parameters );
        $order = eZOrder::fetch( $parameters["order_id"] );
        $xmlString = $order->attribute( 'data_text_1' );
        if ( $xmlString != null )
        {
            $dom = new DOMDocument( '1.0', 'utf-8' );
            $success = $dom->loadXML( $xmlString );
            
            $recaptchaNode = $dom->getElementsByTagName( "captcha" )->item( 0 );
            $recaptcha = false;
            if ( isset( $recaptchaNode ) )
            {
                $recaptcha = $recaptchaNode->textContent;
                if ( $recaptcha == "1" )
                {
                    $recaptcha = true;
                }
            }
        }
        
        $currentUser = eZUser::currentUser();
        $accessAllowed = $currentUser->hasAccessTo( 'xrowecommerce', 'bypass_captcha' );
        if ( eZINI::instance( 'xrowecommerce.ini' )->variable( 'Settings', 'Captcha' ) == 'enabled' and $accessAllowed["accessWord"] != 'yes' and ! $recaptcha and ! eZSys::isShellExecution() )
        {
            return eZWorkflowType::STATUS_REJECTED;
        }
        $http = eZHTTPTool::instance();
        if ( $http->hasPostVariable( 'CancelButton' ) )
        {
            $order->purge( false );
            $process->RedirectUrl = 'shop/basket';
            return eZWorkflowType::STATUS_REDIRECT_REPEAT;
        }
        $theGateway = $this->getCurrentGateway( $process, $event );
        
        if ( $process->attribute( 'event_state' ) == xrowPaymentGatewayType::GATEWAY_NOT_SELECTED and $theGateway instanceof eZPaymentGateway )
        {
            $process->setAttribute( 'event_state', xrowPaymentGatewayType::GATEWAY_SELECTED );
        }
        if ( $process->attribute( 'event_state' ) == xrowPaymentGatewayType::GATEWAY_NOT_SELECTED or ! ( $theGateway instanceof eZPaymentGateway ) )
        {
            $this->logger->writeTimedString( 'execute: eZPaymentGatewayType::GATEWAY_NOT_SELECTED' );
            
            if ( ! $this->selectGateway( $process, $event ) )
            {
                $process->Template = array();
                $process->Template['templateName'] = 'design:workflow/selectgateway.tpl';
                $process->Template['templateVars'] = array( 
                    'event' => $event 
                );
                
                return eZWorkflowType::STATUS_FETCH_TEMPLATE_REPEAT;
            }
        }
        
        $status = $theGateway->execute( $process, $event );
        /* 
             * Fraud detection
             * 
             * Gateway must use  $GLOBALS['xrowPaymentGatewayFailedAttempt'] = true on declient transactions
			 */
        if ( $status == eZWorkflowType::STATUS_FETCH_TEMPLATE_REPEAT and $GLOBALS['xrowPaymentGatewayFailedAttempt'] )
        {
            $oldtime = time() - 60 * 60 * 24;
            if ( ! array_key_exists( 'xrowPaymentGatewayFailedAttemptCount', $_SESSION ) or $oldtime > $_SESSION['xrowPaymentGatewayFailedAttemptCountTime'] )
            {
                $_SESSION['xrowPaymentGatewayFailedAttemptCount'] = 0;
                $_SESSION['xrowPaymentGatewayFailedAttemptCountTime'] = time();
            }
            $_SESSION['xrowPaymentGatewayFailedAttemptCount'] ++;
            if ( $_SESSION['xrowPaymentGatewayFailedAttemptCount'] > 5555 )
            {
                $process->Template = array();
                $process->Template['templateName'] = 'design:workflow/fraud.tpl';
                $process->Template['templateVars'] = array( 
                    'event' => $event 
                );
                return eZWorkflowType::STATUS_FETCH_TEMPLATE_REPEAT;
            }
        }
        return $status;
    }

    /*!
    Attributes. There are three types of gateways in eZPaymentGatewayType.
    'Available' gateways - gateways that were installed in the eZPublish
                   (as extensions, build-in);
    'Selected' gateways  - gateways that were selected for this instance of
                   eZPaymentGatewayType;
    'Current' gateway    - through this gateway payment will be made.
    */
    
    function attributeDecoder( $event, $attr )
    {
        switch ( $attr )
        {
            case 'selected_gateways_types':
                {
                    return explode( ',', $event->attribute( 'data_text3' ) );
                }
                break;
            
            case 'selected_gateways':
                {
                    $selectedGatewaysTypes = explode( ',', $event->attribute( 'data_text3' ) );
                    return xrowEPayment::getGateways( $selectedGatewaysTypes );
                }
                break;
            
            case 'permissions':
                {
                    return $event->attribute( 'data_text4' );
                }
                break;
        
        }
        return null;
    }

    function typeFunctionalAttributes()
    {
        return array( 
            'selected_gateways_types' , 
            'selected_gateways' , 
            'current_gateway' , 
            'permissions' 
        );
    }

    function attributes()
    {
        return array_merge( array( 
            'available_gateways' , 
            'allowed_gateways' 
        ), eZWorkflowEventType::attributes() );
    }

    function hasAttribute( $attr )
    {
        return in_array( $attr, $this->attributes() );
    }

    function attribute( $attr )
    {
        switch ( $attr )
        {
            /*
                        case 'current_gateway':
                {
                    $params = $process->parameterList();
                    return $params['paymentgateway'] ? $params['paymentgateway'] : false;
                }
                break;
*/
            case 'available_gateways':
                {
                    return xrowEPayment::getGateways( array( 
                        - 1 
                    ) );
                }
                break;
            case 'allowed_gateways':
                {
                    return xrowEPayment::allowedGatewaysByUser();
                }
                break;
        }
        return eZWorkflowEventType::attribute( $attr );
    }

    /*!
    Creates and returns object of eZPaymentGateway subclass.
    */
    
    function createGateway( $inGatewayType )
    {
        $gateway_difinition = $GLOBALS['eZPaymentGateways'][$inGatewayType];
        
        $this->logger->writeTimedString( $gateway_difinition, "createGateway. gateway_difinition" );
        
        if ( $gateway_difinition )
        {
            $class_name = $gateway_difinition['class_name'];
            return new $class_name( );
        }
        
        return null;
    }

    /*!
    Returns 'current' gateway.
    */
    
    function getCurrentGateway( $process, $event )
    {
        $theGateway = null;
        $gatewayType = $this->getCurrentGatewayType( $process, $event );
        if ( ! empty( $gatewayType ) )
        {
            $theGateway = $this->createGateway( $gatewayType );
        }
        
        return $theGateway;
    }

    /*!
    Returns 'current' gatewaytype.
    */
    
    function getCurrentGatewayType( $process, $event )
    {
        $gateway = null;
        $http = eZHTTPTool::instance();
        
        if ( $http->hasPostVariable( 'SelectButton' ) && $http->hasPostVariable( 'SelectedGateway' ) )
        {
            $gateway = $http->postVariable( 'SelectedGateway' );
            if ( in_array( $gateway, xrowEPayment::allowedGatewayListByUser() ) )
            {
                #$event->setAttribute( 'data_text2', $gateway );
                #$event->store();
                /* if stored in process */
                $params = $process->parameterList();
                $params['paymentgateway'] = $gateway;
                $process->setParameters( $params );
                $process->store();
            
            }
            else
            {
                throw new Exception( "Gateway '$gateway' not known." );
            }
        }
        else
        {
            $params = $process->parameterList();
            $gateway = $params['paymentgateway'];
        }
        return $gateway;
    }

    /*!
    Sets 'current' gateway from 'selected' gateways. If 'selected' is just one,
    it becomes 'current'. Else user have to choose some( appropriate template
    will be shown).
    */
    
    function selectGateway( $process, $event )
    {
        $selectedGatewaysTypes = explode( ',', $event->attribute( 'data_text3' ) );
        
        if ( count( $selectedGatewaysTypes ) == 1 && $selectedGatewaysTypes[0] != - 1 )
        {
            $params = $process->parameterList();
            $params['paymentgateway'] = $selectedGatewaysTypes[0];
            $process->setParameters( $params );
            $process->store();
            $this->logger->writeTimedString( $selectedGatewaysTypes[0], 'selectGateway' );
            return true;
        }
        elseif ( array_key_exists( 'paymentgateway', $process->parameterList() ) )
        {
            return true;
        }
        
        $this->logger->writeTimedString( 'selectGateways. multiple gateways, let user choose.' );
        return false;
    }

    function needCleanup()
    {
        return true;
    }

    /*!
    Delegate to eZPaymentGateway subclass.
    */
    
    function cleanup( $process, $event )
    {
        $theGateway = $this->getCurrentGateway( $process, $event );
        if ( $theGateway != null and $theGateway->needCleanup() )
        {
            $theGateway->cleanup( $process, $event );
        }
    }

    function initializeEvent( $event )
    {
    }

    /*!
    Sets 'selected' gateways. -1 means 'Any' - all 'available' gateways
    becomes 'selected'.
    */
    
    function fetchHTTPInput( $http, $base, $event )
    {
        $gatewaysVar = $base . "_event_ezpaymentgateway_gateways_" . $event->attribute( "id" );
        if ( $http->hasPostVariable( $gatewaysVar ) )
        {
            $gatewaysArray = $http->postVariable( $gatewaysVar );
            if ( in_array( '-1', $gatewaysArray ) )
            {
                $gatewaysArray = array( 
                    - 1 
                );
            }
            
            $gatewaysString = implode( ',', $gatewaysArray );
            $event->setAttribute( "data_text3", $gatewaysString );
            
            $permissionVar = $base . "_event_ezpaymentgateway_permissions_" . $event->attribute( "id" );
            if ( $http->hasPostVariable( $permissionVar ) )
            {
                
                $event->setAttribute( "data_text4", '1' );
            }
            else
            {
                $event->setAttribute( "data_text4", '0' );
            }
        }
    
    }
    
    public $logger;
}

eZWorkflowEventType::registerEventType( xrowPaymentGatewayType::WORKFLOW_TYPE_STRING, 'xrowpaymentgatewaytype' );
?>
