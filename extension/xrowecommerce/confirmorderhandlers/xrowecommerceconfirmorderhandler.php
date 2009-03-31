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
            $email = $params['email'];
            
            require_once ( "kernel/common/template.php" );
            $tpl = templateInit();
            $tpl->setVariable( 'order', $order );
            $templateResult = $tpl->fetch( 'design:shop/orderemail.tpl' );
            
            $subject = $tpl->variable( 'subject' );
            
            //include_once( 'lib/ezutils/classes/ezmail.php' );
            //include_once( 'lib/ezutils/classes/ezmailtransport.php' );
            $mail = new eZMail( );
            $emailSender = eZINI::instance( 'xrowecommerce.ini' )->variable( 'MailSettings', 'Email' );
            $htmlMode = eZINI::instance( 'xrowecommerce.ini' )->variable( 'MailSettings', 'HTMLEmail' );
            if ( ! $emailSender )
            {
                $emailSender = $ini->variable( 'MailSettings', 'EmailSender' );
            }
            if ( ! $emailSender )
            {
                $emailSender = $ini->variable( "MailSettings", "AdminEmail" );
            }
            
            $mail->setReceiver( $email );
            $mail->setSender( $emailSender );
            $mail->setSubject( $subject );
            $mail->setBody( $templateResult );
            if ( $htmlMode == 'enabled' )
            {
                $mail->setContentType( 'text/html' );
            }
            $mailResult = eZMailTransport::send( $mail );
            
            $email = eZINI::instance( 'xrowecommerce.ini' )->variable( 'MailSettings', 'Email' );
            if ( ! $email )
            {
                $email = $ini->variable( "MailSettings", "AdminEmail" );
            }
            $mail = new eZMail( );
            
            $mail->setReceiver( $email );
            $mail->setSender( $emailSender );
            $mail->setSubject( $subject );
            $mail->setBody( $templateResult );
            if ( $htmlMode == 'enabled' )
            {
                $mail->setContentType( 'text/html' );
            }
            $mailResult = eZMailTransport::send( $mail );
        }
    }
}

?>
