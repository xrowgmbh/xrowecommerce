<?php

class xrowECommerceConfirmOrderHandler
{

    function execute( $params = array() )
    {
        $sendOrderEmail = eZINI::instance( 'xrowecommerce.ini' )->variable( 'MailSettings', 'SendOrderEmail' );
        if ( $sendOrderEmail == 'enabled' )
        {
            $this->sendOrderEmail( $params );
        }
    }

    function sendOrderEmail( $params )
    {
        $ini = eZINI::instance();
        if ( isset( $params['order'] ) and isset( $params['email'] ) )
        {
            $order = $params['order'];
            $clientEmail = $params['email'];

            require_once ( "kernel/common/template.php" );
            $tpl = templateInit();
            $tpl->setVariable( 'order', $order );
            $htmlMode = eZINI::instance( 'xrowecommerce.ini' )->variable( 'MailSettings', 'HTMLEmail' );
            if ( $htmlMode == 'enabled' )
            {
                $templateResult = $tpl->fetch( 'design:shop/orderemail/html/orderemail.tpl' );
            }
            else
            {
                $templateResult = $tpl->fetch( 'design:shop/orderemail/text/orderemail.tpl' );
            }

            $subject = $tpl->variable( 'subject' );

            $mail = new eZMail( );
            $xrowINI = eZINI::instance( 'xrowecommerce.ini' );
            $emailSender = $xrowINI->variable( 'MailSettings', 'Email' );
            $receiverList = array();
            if ( $xrowINI->hasVariable( 'MailSettings', 'EmailReceiverList' ) )
            {
                $receiverList = $xrowINI->variable( 'MailSettings', 'EmailReceiverList' );
            }

            if ( ! $emailSender )
            {
                $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
            }
            if ( ! $emailSender )
            {
                $emailSender = $ini->variable( "MailSettings", "AdminEmail" );
            }

            if ( $xrowINI->hasVariable( 'MailSettings', 'ReplyToMail' ) and
                 $xrowINI->variable( 'MailSettings', 'ReplyToMail' ) != '' )
            {
                $replyToMail = $xrowINI->variable( 'MailSettings', 'ReplyToMail' );
                if ( eZMail::validate( $replyToMail ) )
                {
                    $mail->setReplyTo( $replyToMail );
                }
            }

            $mail->setReceiver( $clientEmail );
            $mail->setSender( $emailSender );
            $mail->setSubject( $subject );
            $mail->setBody( $templateResult );
            if ( $htmlMode == 'enabled' )
            {
                $mail->setContentType( 'text/html' );
            }
            $mailResult = eZMailTransport::send( $mail );
            if ( $mailResult )
            {
            	eZDebug::writeDebug( 'Order email were sent to ' . $clientEmail, 'xrowECommerceConfirmOrderHandler' );
            }
            else
            {
            	eZDebug::writeError( 'Order email were not sent to ' . $clientEmail, 'xrowECommerceConfirmOrderHandler' );
            }

            if ( count( $receiverList ) == 0 )
            {
                $receiverList[] = $emailSender;
            }

            foreach ( $receiverList as $receiver )
            {
                if ( eZMail::validate( $receiver ) )
                {
                    $mail = new eZMail();
                    $mail->setReceiver( $receiver );
                    $mail->setSender( $clientEmail );
                    $mail->setSubject( $subject );
                    $mail->setBody( $templateResult );
            if ( $htmlMode == 'enabled' )
            {
                $mail->setContentType( 'text/html' );
            }
                    $mailResult = eZMailTransport::send( $mail );

                    if ( $mailResult )
                    {
                        eZDebug::writeDebug( 'Order email were sent to ' . $receiver, 'xrowECommerceConfirmOrderHandler' );
                    }
                    else
                    {
                        eZDebug::writeError( 'Order email were not sent to ' . $receiver, 'xrowECommerceConfirmOrderHandler' );
                    }
                }
                else
                {
                    eZDebug::writeError( 'Invalid email address: ' . $receiver, 'xrowECommerceConfirmOrderHandler' );
                }
            }
        }
    }
}

?>
