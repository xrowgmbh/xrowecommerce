<?php

class xrowPaymentGatewayType extends eZWorkflowEventType
{
    const WORKFLOW_TYPE_STRING = 'xrowpaymentgateway';
    const GATEWAY_NOT_SELECTED = 0;
    const GATEWAY_SELECTED = 1;

    /*!
    Constructor.
    */
    
    function xrowPaymentGatewayType()
    {
        $this->logger = eZPaymentLogger::CreateForAdd( "var/log/eZPaymentGatewayType.log" );
        
        $this->eZWorkflowEventType( xrowPaymentGatewayType::WORKFLOW_TYPE_STRING, ezi18n( 'kernel/workflow/event', "xrow Ecommerce Payment Gateway" ) );
        $this->loadAndRegisterGateways();
    }

    /*!
    Creates necessary gateway and delegate execution to it.
    If there are multiple gateways in eZPaymentGatewayType, fetches
    template with list of 'selected'(see. 'attributes' section)
    gateways and asks user to choose one.
    */
    
    function execute( $process, $event )
    {
        eZDebug::writeDebug( 'Started payment workflow', 'xrowPaymentGatewayType::execute()' );
        $GLOBALS['xrowPaymentGatewayFailedAttempt'] = true;
        $this->logger->writeTimedString( 'execute' );
        // Captcha check begin
        $parameters = $process->attribute( 'parameters' );
        $parameters = unserialize( $parameters );
        $order = eZOrder::fetch( $parameters["order_id"] );
        $xml = new eZXML( );
        $xmlDoc = $order->attribute( 'data_text_1' );
        if ( $xmlDoc != null )
        {
            $dom = $xml->domTree( $xmlDoc );
            $recaptchaNode = $dom->elementsByName( "captcha" );
            $recaptcha = false;
            if ( isset( $recaptchaNode[0] ) )
            {
                $recaptcha = $recaptchaNode[0]->textContent();
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
        // Recaptcha check end
        

        if ( $process->attribute( 'event_state' ) == xrowPaymentGatewayType::GATEWAY_NOT_SELECTED )
        {
            $this->logger->writeTimedString( 'execute: eZPaymentGatewayType::GATEWAY_NOT_SELECTED' );
            
            $process->setAttribute( 'event_state', xrowPaymentGatewayType::GATEWAY_SELECTED );
            if ( ! $this->selectGateway( $event ) )
            {
                $process->Template = array();
                $process->Template['templateName'] = 'design:workflow/selectgateway.tpl';
                $process->Template['templateVars'] = array( 
                    'event' => $event 
                );
                
                return eZWorkflowType::STATUS_FETCH_TEMPLATE_REPEAT;
            }
        }
        
        $theGateway = $this->getCurrentGateway( $event );
        if ( $theGateway != null )
        {
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
                if ( $_SESSION['xrowPaymentGatewayFailedAttemptCount'] > 5 )
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
        
        $this->logger->writeTimedString( 'execute: something wrong' );
        return eZWorkflowType::STATUS_REJECTED;
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
                    return explode( ',', $event->attribute( 'data_text1' ) );
                }
                break;
            
            case 'selected_gateways':
                {
                    $selectedGatewaysTypes = explode( ',', $event->attribute( 'data_text1' ) );
                    return $this->getGateways( $selectedGatewaysTypes );
                }
                break;
            
            case 'current_gateway':
                {
                    return $event->attribute( 'data_text2' );
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
            'current_gateway' 
        );
    }

    function attributes()
    {
        return array_merge( array( 
            'available_gateways' 
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
            case 'available_gateways':
                {
                    return $this->getGateways( array( 
                        - 1 
                    ) );
                }
                break;
        }
        return eZWorkflowEventType::attribute( $attr );
    }

    /*!
     \static
    Searches 'available' gateways( built-in or as extensions ).
    */
    
    function loadAndRegisterGateways()
    {
        xrowPaymentGatewayType::loadAndRegisterBuiltInGateways();
        xrowPaymentGatewayType::loadAndRegisterExtensionGateways();
    }

    /*!
      \static
    */
    function loadAndRegisterBuiltInGateways()
    {
        $gatewaysINI = eZINI::instance( 'paymentgateways.ini' );
        $gatewaysTypes = $gatewaysINI->variable( 'GatewaysSettings', 'AvailableGateways' );
        $gatewaysDir = false;
        
        // GatewaysDirectories was spelt as GatewaysDerictories, which is
        // confusing for people writing ini files - it's a typo.
        if ( $gatewaysINI->hasVariable( 'GatewaysSettings', 'GatewaysDerictories' ) )
            $gatewaysDir = $gatewaysINI->variable( 'GatewaysSettings', 'GatewaysDerictories' );
        else
            $gatewaysDir = $gatewaysINI->variable( 'GatewaysSettings', 'GatewaysDirectories' );
        
        if ( is_array( $gatewaysDir ) && is_array( $gatewaysTypes ) )
        {
            foreach ( $gatewaysTypes as $gateway )
            {
                foreach ( $gatewaysDir as $dir )
                {
                    
                    $gatewayPath = "$dir/$gateway/classes/" . $gateway . 'gateway.php';
                    if ( file_exists( $gatewayPath ) )
                    {
                        include_once ( $gatewayPath );
                    }
                }
            }
        }
    }

    /*!
      \static
    */
    function loadAndRegisterExtensionGateways()
    {
        $gatewaysINI = eZINI::instance( 'paymentgateways.ini' );
        $siteINI = eZINI::instance( 'site.ini' );
        $extensionDirectory = $siteINI->variable( 'ExtensionSettings', 'ExtensionDirectory' );
        $activeExtensions = eZExtension::activeExtensions();
        
        foreach ( $activeExtensions as $extension )
        {
            $gatewayPath = "$extensionDirectory/$extension/classes/" . $extension . 'gateway.php';
            if ( file_exists( $gatewayPath ) )
            {
                include_once ( $gatewayPath );
            }
        }
        
        foreach ( $gatewaysINI->variable( 'GatewaysSettings', 'AvailableGateways' ) as $name )
        {
            $found = false;
            foreach ( $gatewaysINI->variable( 'GatewaysSettings', 'GatewaysDirectories' ) as $dir )
            {
                $gatewayPath = $dir . '/' . $name . 'gateway.php';
                if ( file_exists( $gatewayPath ) )
                {
                    $found = true;
                    include_once ( $gatewayPath );
                }
            }
            if ( $found !== true )
            {
                eZDebug::writeError( "Gateway '$name' not found" );
            }
        }
    }

    /*!
    Each gateway must call this function to become 'available'.
    */
    
    function registerGateway( $gateway, $class_name, $description )
    {
        $gateways = & $GLOBALS["eZPaymentGateways"];
        if ( ! is_array( $gateways ) )
        {
            $gateways = array();
        }
        
        if ( isset( $gateways[$gateway] ) )
        {
            eZDebug::writeError( "Gateway already registered: $gateway", "eZPaymentGatewayType::registerGateway" );
        }
        else
        {
            $gateways[$gateway] = array( 
                "class_name" => $class_name , 
                "description" => $description 
            );
        }
    }

    /*!
    Returns an array of gateways difinitions( class_name, description ) by
    'gatewaysTypes'( array of 'gateway' values that were passed to
    'registerGateway' function).
    */
    function getGateways( $gatewaysTypes )
    {
        $gateways = array();
        $availableGateways = $GLOBALS['eZPaymentGateways'];
        if ( ! is_array( $availableGateways ) )
        {
            return $gateways;
        }
        
        if ( in_array( '-1', $gatewaysTypes ) )
        {
            $gatewaysTypes = array_keys( $availableGateways );
        }
        
        foreach ( $gatewaysTypes as $key )
        {
            $gateway = $availableGateways[$key];
            
            $gateway['Name'] = $gateway['description'];
            $gateway['value'] = $key;
            $gateways[] = $gateway;
        }
        
        return $gateways;
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
    
    function getCurrentGateway( $event )
    {
        $theGateway = null;
        $gatewayType = $this->getCurrentGatewayType( $event );
        
        if ( $gatewayType != null )
        {
            $theGateway = $this->createGateway( $gatewayType );
        }
        
        return $theGateway;
    }

    /*!
    Returns 'current' gatewaytype.
    */
    
    function getCurrentGatewayType( $event )
    {
        $gateway = null;
        $http = eZHTTPTool::instance();
        
        if ( $http->hasPostVariable( 'SelectButton' ) && $http->hasPostVariable( 'SelectedGateway' ) )
        {
            $gateway = $http->postVariable( 'SelectedGateway' );
            $event->setAttribute( 'data_text2', $gateway );
            $event->store();
        }
        else 
            if ( $http->hasPostVariable( 'CancelButton' ) )
            {
                $gateway = null;
            }
            else
            {
                $gateway = $event->attribute( 'current_gateway' );
            }
        
        return $gateway;
    }

    /*!
    Sets 'current' gateway from 'selected' gateways. If 'selected' is just one,
    it becomes 'current'. Else user have to choose some( appropriate template
    will be shown).
    */
    
    function selectGateway( $event )
    {
        $selectedGatewaysTypes = explode( ',', $event->attribute( 'data_text1' ) );
        
        if ( count( $selectedGatewaysTypes ) == 1 && $selectedGatewaysTypes[0] != - 1 )
        {
            $event->setAttribute( 'data_text2', $selectedGatewaysTypes[0] );
            $event->store();
            
            $this->logger->writeTimedString( $selectedGatewaysTypes[0], 'selectGateway' );
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
        $theGateway = $this->getCurrentGateway( $event );
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
            $event->setAttribute( "data_text1", $gatewaysString );
        }
    }
    
    public $logger;
}

eZWorkflowEventType::registerEventType( xrowPaymentGatewayType::WORKFLOW_TYPE_STRING, 'xrowpaymentgatewaytype' );
?>