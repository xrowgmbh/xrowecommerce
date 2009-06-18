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
            $emailBCCArray = array();
            if ( $xrowINI->hasVariable( 'MailSettings', 'EmailBCCReceiver' ) )
            {
                $emailBCCArray = $xrowINI->variable( 'MailSettings', 'EmailBCCReceiver' );
            }

            if ( ! $emailSender )
            {
                $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
            }
            if ( ! $emailSender )
            {
                $emailSender = $ini->variable( "MailSettings", "AdminEmail" );
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
            	eZDebug::writeDebug( 'Order emails were sent to ' . $clientEmail, 'xrowECommerceConfirmOrderHandler' );
            }
            else
            {
            	eZDebug::writeError( 'Order emails were not sent to ' . $clientEmail, 'xrowECommerceConfirmOrderHandler' );
            }

            $mail = new eZMail();
            $mail->setReceiver( $emailSender );
            $mail->setSender( $clientEmail );
            $mail->setSubject( $subject );
            $mail->setBody( $templateResult );

            if ( count( $emailBCCArray ) > 0 )
            {
                foreach( $emailBCCArray as $item )
                {
                    $mail->addBcc( $item );
                }
            }
            $mailResult = eZMailTransport::send( $mail );

            if ( $mailResult )
            {
                eZDebug::writeDebug( 'Order emails were sent to ' . $emailSender . ', ' . implode( ', ', $emailBCCArray ), 'xrowECommerceConfirmOrderHandler' );
            }
            else
            {
                eZDebug::writeError( 'Order emails were not sent to ' . $emailSender . ', ' . implode( ', ', $emailBCCArray ), 'xrowECommerceConfirmOrderHandler' );
            }
        }
    }
}

?>