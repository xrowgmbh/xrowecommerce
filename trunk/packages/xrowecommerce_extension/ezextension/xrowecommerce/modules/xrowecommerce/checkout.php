<?php

$http = eZHTTPTool::instance();
$Module = $Params['Module'];

$orderID = $http->sessionVariable( 'MyTemporaryOrderID' );
$order = eZOrder::fetch( $orderID );

if ( $order instanceof eZOrder )
{
    if ( $order->attribute( 'is_temporary' ) )
    {

        $paymentObj = xrowPaymentObject::fetchByOrderID( $orderID );
        if ( $paymentObj != null )
        {
            $startTime = time();
            while ( ( time() - $startTime ) < 25 )
            {
                eZDebug::writeDebug( "next iteration", "checkout" );
                $checkoutError = true;
                if ( $order->attribute( 'is_temporary' ) == 0 )
                {
                    $checkoutError = false;
                    break;
                }
                else
                {
                    sleep( 2 );
                }
            }
            # if no answer or wrong answer from payment gateway
            if ( $checkoutError )
            {
                // Got no receipt or callback from the payment server.
                $http->removeSessionVariable( "CheckoutAttempt" );

                $Result = array();

                $tpl = eZTemplate::factory();
                $tpl->setVariable ("ErrorCode", "NO_CALLBACK");
                $tpl->setVariable ("OrderID", $orderID);

                $Result['content'] = $tpl->fetch( "design:shop/cancelcheckout.tpl" ) ;
                $Result['path'] = array( array( 'url' => false,
                'text' => ezpI18n::tr( 'kernel/shop', 'Checkout' ) ) );
                return;
            }
        }
        if ( $order->attribute( 'is_temporary' ) == 1 && $paymentObj == null )
        {
            $email = $order->accountEmail();
            $order->setAttribute( 'email', $email );
            $order->store();

            $http->setSessionVariable( "UserOrderID", $order->attribute( 'id' ) );

            $operationResult = eZOperationHandler::execute( 'shop', 'checkout', array(
                'order_id' => $order->attribute( 'id' )
            ) );
            switch ( $operationResult['status'] )
            {
                case eZModuleOperationInfo::STATUS_REPEAT:
                case eZModuleOperationInfo::STATUS_HALTED:
                    {
                        if ( isset( $operationResult['redirect_url'] ) )
                        {
                            $Module->redirectTo( $operationResult['redirect_url'] );
                            return;
                        }
                        else
                            if ( isset( $operationResult['result'] ) )
                            {
                                $result = $operationResult['result'];
                                $resultContent = false;
                                if ( is_array( $result ) )
                                {
                                    if ( isset( $result['content'] ) )
                                    {
                                        $resultContent = $result['content'];
                                    }
                                    if ( isset( $result['path'] ) )
                                    {
                                        $Result['path'] = $result['path'];
                                    }
                                }
                                else
                                    $resultContent = $result;
                                $Result['content'] = $resultContent;

                                if ( isset( $Params['UserParameters'] ) )
                                {
                                    $UserParameters = $Params['UserParameters'];
                                }
                                else
                                {
                                    $UserParameters = array();
                                }
                                $viewParameters = array();
                                $viewParameters = array_merge( $viewParameters, $UserParameters );

                                #USER REGISTER START

                                $xini = eZINI::instance('xrowecommerce.ini');
                                if ( $xini->variable( "Settings", "ForceUserRegBeforeCheckout" ) == "true" )
                                {
                                    $xml = simplexml_load_string( $order->DataText1 );
                                    $json = json_encode( $xml );
                                    $order_data = json_decode( $json, TRUE );

                                    if ( eZUser::fetchByEmail( $order_data["email"] ) == null )
                                    {
                                        $alreadyRegistered = eZUser::fetchByEmail( $order_data["email"] );
                                    }
                                    else
                                    {
                                        $tmpuser = eZUser::currentUser();
                                        $userlogin = eZUser::isUserLoggedIn( $tmpuser->ContentObjectID );
                                        unset( $tmpuser );
                                    }

                                    if ( $userlogin != 1 && ! $alreadyRegistered )
                                    {

                                        $EditVersion = 1;
                                        $db = eZDB::instance();
                                        $db->begin();

                                        if ( $http->hasSessionVariable( 'StartedRegistration' ) )
                                        {
                                            eZDebug::writeWarning( 'Cancel module run to protect against multiple form submits', 'user/register' );
                                            $http->removeSessionVariable( "RegisterUserID" );
                                            $http->removeSessionVariable( 'StartedRegistration' );
                                            $db->commit();
                                            return eZModule::HOOK_STATUS_CANCEL_RUN;
                                        }
                                        else
                                            if ( $http->hasPostVariable( 'PublishButton' ) or $http->hasPostVariable( 'CancelButton' ) )
                                            {
                                                $http->setSessionVariable( 'StartedRegistration', 1 );
                                            }

                                        $ini = eZINI::instance();
                                        $errMsg = '';
                                        $checkErrNodeId = false;

                                        $defaultUserPlacement = (int) $ini->variable( "UserSettings", "DefaultUserPlacement" );

                                        $sql = "SELECT count(*) as count FROM ezcontentobject_tree WHERE node_id = $defaultUserPlacement";
                                        $rows = $db->arrayQuery( $sql );
                                        $count = $rows[0]['count'];
                                        if ( $count < 1 )
                                        {
                                            $errMsg = ezpI18n::tr( 'design/standard/user', 'The node (%1) specified in [UserSettings].DefaultUserPlacement setting in site.ini does not exist!', null, array(
                                                $defaultUserPlacement
                                            ) );
                                            $checkErrNodeId = true;
                                            eZDebug::writeError( "$errMsg" );
                                            $tpl->setVariable( 'errMsg', $errMsg );
                                            $tpl->setVariable( 'checkErrNodeId', $checkErrNodeId );
                                        }
                                        $userClassID = $ini->variable( "UserSettings", "UserClassID" );
                                        $class = eZContentClass::fetch( $userClassID );

                                        $userCreatorID = $ini->variable( "UserSettings", "UserCreatorID" );
                                        $defaultSectionID = $ini->variable( "UserSettings", "DefaultSectionID" );
                                        // Create object by user 14 in section 1
                                        $contentObject = $class->instantiate( $userCreatorID, $defaultSectionID );
                                        $objectID = $contentObject->attribute( 'id' );
                                        // Store the ID in session variable
                                        $http->setSessionVariable( "RegisterUserID", $objectID );

                                        $userID = $objectID;

                                        $nodeAssignment = eZNodeAssignment::create( array(
                                            'contentobject_id' => $contentObject->attribute( 'id' ) ,
                                            'contentobject_version' => 1 ,
                                            'parent_node' => $defaultUserPlacement ,
                                            'is_main' => 1
                                        ) );
                                        $nodeAssignment->store();

                                        $Params['ObjectID'] = $userID;
                                        $Module->addHook( 'post_publish', 'registerSearchObject', 1, false );
                                        $object = eZContentObject::fetch( $userID );
                                        $dm = $object->attribute( 'data_map' );
                                        $version = eZContentObjectVersion::fetchVersion( $object->attribute( 'current_version' ), $object->attribute( 'id' ) );

                                        if ( $dm['tax_id'] != null )
                                        {
                                            $dm['tax_id']->setAttribute( 'data_text', $order_data["tax_id"] );
                                            $dm['tax_id']->setAttribute( 'sort_key_string', strtolower( $order_data["tax_id"] ) );
                                            $dm['tax_id']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'tax_id' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['company_name'] != null )
                                        {
                                            $dm['company_name']->setAttribute( 'data_text', $order_data["company_name"] );
                                            $dm['company_name']->setAttribute( 'sort_key_string', strtolower( $order_data["company_name"] ) );
                                            $dm['company_name']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'company_name' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['company_additional'] != null )
                                        {
                                            $dm['company_additional']->setAttribute( 'data_text', $order_data["company_additional"] );
                                            $dm['company_additional']->setAttribute( 'sort_key_string', strtolower( $order_data["company_additional"] ) );
                                            $dm['company_additional']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'company_additional' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['title'] != null )
                                        {
                                            $dm['title']->setAttribute( 'data_text', $order_data["title"] );
                                            $dm['title']->setAttribute( 'sort_key_string', strtolower( $order_data["title"] ) );
                                            $dm['title']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'title' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['first_name'] != null )
                                        {
                                            $dm['first_name']->setAttribute( 'data_text', $order_data["first_name"] );
                                            $dm['first_name']->setAttribute( 'sort_key_string', strtolower( $order_data["first_name"] ) );
                                            $dm['first_name']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'tfirst_nameitle' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['mi'] != null )
                                        {
                                            $dm['mi']->setAttribute( 'data_text', $order_data["mi"] );
                                            $dm['mi']->setAttribute( 'sort_key_string', strtolower( $order_data["mi"] ) );
                                            $dm['mi']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'mi' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['last_name'] != null )
                                        {
                                            $dm['last_name']->setAttribute( 'data_text', $order_data["last_name"] );
                                            $dm['last_name']->setAttribute( 'sort_key_string', strtolower( $order_data["last_name"] ) );
                                            $dm['last_name']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'last_name' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['user_account'] != null )
                                        {
                                            $dm['user_account']->setAttribute( 'is_valid', (int) 1 );

                                            $dm['user_account']->Content = "";
                                            $dm['user_account']->setAttribute( 'content_class_attribute_can_translate', 0 );
                                            $dm['user_account']->setAttribute( 'content_class_attribute_name', "User Account" );
                                            $dm['user_account']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'user_account' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['address1'] != null )
                                        {
                                            $dm['address1']->setAttribute( 'data_text', $order_data["address1"] );
                                            $dm['address1']->setAttribute( 'sort_key_string', strtolower( $order_data["address1"] ) );
                                            $dm['address1']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'address1' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['address2'] != null )
                                        {
                                            $dm['address2']->setAttribute( 'data_text', $order_data["address2"] );
                                            $dm['address2']->setAttribute( 'sort_key_string', strtolower( $order_data["address2"] ) );
                                            $dm['address2']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'address2' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['zip_code'] != null )
                                        {
                                            $dm['zip_code']->setAttribute( 'data_text', $order_data["zip"] );
                                            $dm['zip_code']->setAttribute( 'sort_key_string', strtolower( $order_data["zip"] ) );
                                            $dm['zip_code']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'zip_code' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['city'] != null )
                                        {
                                            $dm['city']->setAttribute( 'data_text', $order_data["city"] );
                                            $dm['city']->setAttribute( 'sort_key_string', strtolower( $order_data["city"] ) );
                                            $dm['city']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'city' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['phone'] != null )
                                        {
                                            $dm['phone']->setAttribute( 'data_text', $order_data["phone"] );
                                            $dm['phone']->setAttribute( 'sort_key_string', strtolower( $order_data["phone"] ) );
                                            $dm['phone']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'phone' missing in class 'client'", __METHOD__ );
                                        }
                                        if ( $dm['country'] != null )
                                        {
                                            $dm['country']->setAttribute( 'data_text', $order_data["country"] );
                                            $dm['country']->setAttribute( 'sort_key_string', strtolower( $order_data["country"] ) );
                                            $dm['country']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'country' missing in class 'client'", __METHOD__ );
                                        }
                                        if ( $dm['fax'] != null )
                                        {
                                            $dm['fax']->setAttribute( 'data_text', $order_data["fax"] );
                                            $dm['fax']->setAttribute( 'sort_key_string', strtolower( $order_data["fax"] ) );
                                            $dm['fax']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'fax' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['state'] != null )
                                        {
                                            $dm['state']->setAttribute( 'data_text', $order_data["state"] );
                                            $dm['state']->setAttribute( 'sort_key_string', strtolower( $order_data["state"] ) );
                                            $dm['state']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'state' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['email'] != null )
                                        {
                                            $dm['email']->setAttribute( 'data_text', $order_data["email"] );
                                            $dm['email']->setAttribute( 'sort_key_string', strtolower( $order_data["email"] ) );
                                            $dm['email']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'email' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['shippingaddress'] != null )
                                        {
                                            $dm['shippingaddress']->setAttribute( 'data_int', $order_data["shipping"] );
                                            $dm['shippingaddress']->setAttribute( 'is_valid', $order_data["shipping"] );
                                            $dm['shippingaddress']->setAttribute( 'sort_key_int', strtolower( $order_data["shipping"] ) );
                                            $dm['shippingaddress']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'shippingaddress' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['s_country'] != null )
                                        {
                                            $dm['s_country']->setAttribute( 'data_text', $order_data["s_country"] );
                                            $dm['s_country']->setAttribute( 'sort_key_string', strtolower( $order_data["s_country"] ) );
                                            $dm['s_country']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 's_country' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['s_company_name'] != null )
                                        {
                                            $dm['s_company_name']->setAttribute( 'data_text', $order_data["s_company_name"] );
                                            $dm['s_company_name']->setAttribute( 'sort_key_string', strtolower( $order_data["s_company_name"] ) );
                                            $dm['s_company_name']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 's_company_name' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['s_company_additional'] != null )
                                        {
                                            $dm['s_company_additional']->setAttribute( 'data_text', $order_data["s_company_additional"] );
                                            $dm['s_company_additional']->setAttribute( 'sort_key_string', strtolower( $order_data["s_company_additional"] ) );
                                            $dm['s_company_additional']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 's_company_additional' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['s_first_name'] != null )
                                        {
                                            $dm['s_first_name']->setAttribute( 'data_text', $order_data["s_first_name"] );
                                            $dm['s_first_name']->setAttribute( 'sort_key_string', strtolower( $order_data["s_first_name"] ) );
                                            $dm['s_first_name']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 's_first_name' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['s_mi'] != null )
                                        {
                                            $dm['s_mi']->setAttribute( 'data_text', $order_data["s_mi"] );
                                            $dm['s_mi']->setAttribute( 'sort_key_string', strtolower( $order_data["s_mi"] ) );
                                            $dm['s_mi']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 's_mi' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['s_last_name'] != null )
                                        {
                                            $dm['s_last_name']->setAttribute( 'data_text', $order_data["s_last_name"] );
                                            $dm['s_last_name']->setAttribute( 'sort_key_string', strtolower( $order_data["s_last_name"] ) );
                                            $dm['s_last_name']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 's_last_name' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['s_address1'] != null )
                                        {
                                            $dm['s_address1']->setAttribute( 'data_text', $order_data["s_address1"] );
                                            $dm['s_address1']->setAttribute( 'sort_key_string', strtolower( $order_data["s_address1"] ) );
                                            $dm['s_address1']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 's_address1' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['s_address2'] != null )
                                        {
                                            $dm['s_address2']->setAttribute( 'data_text', $order_data["s_address2"] );
                                            $dm['s_address2']->setAttribute( 'sort_key_string', strtolower( $order_data["s_address2"] ) );
                                            $dm['s_address2']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 's_address2' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['s_zip_code'] != null )
                                        {
                                            $dm['s_zip_code']->setAttribute( 'data_text', $order_data["s_zip"] );
                                            $dm['s_zip_code']->setAttribute( 'sort_key_string', strtolower( $order_data["s_zip"] ) );
                                            $dm['s_zip_code']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 's_zip_code' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['s_city'] != null )
                                        {
                                            $dm['s_city']->setAttribute( 'data_text', $order_data["s_city"] );
                                            $dm['s_city']->setAttribute( 'sort_key_string', strtolower( $order_data["s_city"] ) );
                                            $dm['s_city']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 's_city' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['s_phone'] != null )
                                        {
                                            $dm['s_phone']->setAttribute( 'data_text', $order_data["s_phone"] );
                                            $dm['s_phone']->setAttribute( 'sort_key_string', strtolower( $order_data["s_phone"] ) );
                                            $dm['s_phone']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 's_phone' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['s_fax'] != null )
                                        {
                                            $dm['s_fax']->setAttribute( 'data_text', $order_data["s_fax"] );
                                            $dm['s_fax']->setAttribute( 'sort_key_string', strtolower( $order_data["s_fax"] ) );
                                            $dm['s_fax']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 's_fax' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['s_email'] != null )
                                        {
                                            $dm['s_email']->setAttribute( 'data_text', $order_data["s_email"] );
                                            $dm['s_email']->setAttribute( 'sort_key_string', strtolower( $order_data["s_email"] ) );
                                            $dm['s_email']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 's_email' missing in class 'client'", __METHOD__ );
                                        }

                                        if ( $dm['newsletter'] != null )
                                        {
                                            $dm['newsletter']->setAttribute( 'data_int', $order_data["newsletter"] );
                                            $dm['newsletter']->setAttribute( 'is_valid', $order_data["newsletter"] );
                                            $dm['newsletter']->setAttribute( 'sort_key_int', strtolower( $order_data["newsletter"] ) );
                                            $dm['newsletter']->store();
                                        }
                                        else
                                        {
                                            eZDebug::writeError( "Attribute 'newsletter' missing in class 'client'", __METHOD__ );
                                        }

                                        $operationResult = eZOperationHandler::execute( 'content', 'publish', array(
                                            'object_id' => $object->attribute( 'id' ) ,
                                            'version' => $version->attribute( 'version' )
                                        ) );

                                        $newuser = eZUser::create( $object->attribute( 'id' ) );

                                        $newuser->setAttribute( 'login', $order_data["email"] );
                                        $newuser->setAttribute( 'email', $order_data["email"] );
                                        $newuser->setAttribute( 'password_hash', $order_data["password"] );
                                        $newuser->setAttribute( 'password_hash_type', 2 );
                                        $newuser->store();

                                        $user = eZUser::currentUser();
                                        $object = eZContentObject::fetch( $object->attribute( 'id' ) );
                                        unset( $user );
                                        $user = eZUser::fetch( $object->attribute( 'id' ) );
                                        $user->loginCurrent();

                                        $http->removeSessionVariable( "GeneratedPassword" );
                                        $http->removeSessionVariable( "RegisterUserID" );
                                        $http->removeSessionVariable( 'StartedRegistration' );
                                        $Module->addHook( 'action_check', 'checkContentActions' );

                                        $OmitSectionSetting = true;

                                        $db->commit();

                                    }
                                }
# USER REGISTER END
                                return;
                            }
                    }
                    break;
                case eZModuleOperationInfo::STATUS_CANCELLED:
                    {
                        $Result = array();

                        $tpl = eZTemplate::factory();
                        $tpl->setVariable( 'operation_result', $operationResult );

                        $Result['content'] = $tpl->fetch( "design:shop/cancelcheckout.tpl" );
                        $Result['path'] = array(
                            array(
                                'url' => false ,
                                'text' => ezpI18n::tr( 'kernel/shop', 'Checkout' )
                            )
                        );

                        return;
                    }
            }
        }
        else
        {
            if ( $order->attribute( 'is_temporary' ) == 0 )
            {
                $http->removeSessionVariable( "CheckoutAttempt" );
                $Module->redirectTo( '/xrowecommerce/orderview/' . $orderID );
                return;
            }
            else
            {
                // Get the attempt number and the order.
                $attempt = $http->sessionVariable( "CheckoutAttempt", 0 );

                // This attempt is for another order. So reset the attempt.
                if ( $attempt != 0 )
                {
                    $attempt = 0;
                }
                $http->setSessionVariable( "CheckoutAttempt", ++ $attempt );
                $http->setSessionVariable( "CheckoutAttemptOrderID", $orderID );

                if ( $attempt < 4 )
                {
                    $Result = array();

                    $tpl = eZTemplate::factory();
                    $tpl->setVariable( 'attempt', $attempt );
                    $tpl->setVariable( 'orderID', $orderID );
                    $Result['path'] = array(
                        array(
                            'url' => false ,
                            'text' => ezpI18n::tr( 'kernel/shop', 'Checkout' )
                        )
                    );
                    return;
                }
                else
                {
                    // Got no receipt or callback from the payment server.
                    $http->removeSessionVariable( "CheckoutAttempt" );

                    $Result = array();

                    $tpl = eZTemplate::factory();
                    $tpl->setVariable( "ErrorCode", "NO_CALLBACK" );
                    $tpl->setVariable( "OrderID", $orderID );

                    $Result['content'] = $tpl->fetch( "design:shop/cancelcheckout.tpl" );
                    $Result['path'] = array(
                        array(
                            'url' => false ,
                            'text' => ezpI18n::tr( 'kernel/shop', 'Checkout' )
                        )
                    );
                    return;
                }
            }
        }
    }
    $Module->redirectTo('/xrowecommerce/orderview/' . $orderID);
    return;

}
