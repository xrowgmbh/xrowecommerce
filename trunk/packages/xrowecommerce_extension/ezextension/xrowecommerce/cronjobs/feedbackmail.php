<?php

$cli = eZCLI::instance();
$ini = eZINI::instance( "site.ini" );
$xrowini = eZINI::instance('xrowecommerce.ini');
$sys = eZSys::instance();
$first_orders = array();
$db = eZDB::instance();
$script = eZScript::instance( array( 
    'description' => ( "xrowecommerce cronjob\n" . "Feedback-Email\n" . "\n" . "./extension/xrowecommerce/crobjobs/feedbackmail.php" ) , 
    'use-session' => true , 
    'use-modules' => true , 
    'use-extensions' => true 
) );
$script->startup();
$options = $script->getOptions( "", "", array() );
$script->initialize();
$delay = (int)$xrowini->variable( 'MailSettings', 'FirstOrderDelay' );
$delay *= 86400;
$timestamp_start = mktime(0,0,0,date('m'),date('d'),date('Y')) - $delay;
$timestamp_end = mktime(23,59,59,date('m'),date('d'),date('Y')) - $delay;
$timestamp_start = 1;
$timestamp_end = 9999999999999;
$sql = "SELECT created, email, data_text_1 FROM ezorder WHERE order_nr IN (SELECT order_nr FROM ezorder where is_temporary = 0 group by email) and created between '" . $timestamp_start . "' and '" . $timestamp_end . "';";
$first_orders = $db->arrayQuery( $sql );

$mailConnection = array();
$mailConnection['Transport'] = $ini->variable( "MailSettings", "Transport" );
$mailConnection['Server'] = $ini->variable( "MailSettings", "TransportServer" );
$mailConnection['Port'] = $ini->variable( "MailSettings", "TransportPort" );
$mailConnection['User'] = $ini->variable( "MailSettings", "TransportUser" );
$mailConnection['Password'] = $ini->variable( "MailSettings", "TransportPassword" );

if( $mailConnection['Transport'] == "SMTP" )
{
    $options = new ezcMailSmtpTransportOptions();
    $options->connectionType = ezcMailSmtpTransport::CONNECTION_PLAIN;
    $transport = new ezcMailSmtpTransport( $mailConnection['Server'], $mailConnection['User'], $mailConnection['Password'], $mailConnection['Port'], $options );
    $cli->output( "using SMTP" );
}
else if( $mailConnection['Transport'] == "sendmail" )
{
    $transport = new ezcMailMtaTransport();
    $cli->output( "using sendmail" );
}
else
{
    $cli->output( "wrong mail transport settings" );
}

foreach ( $first_orders as $order )
{
    $tpl = eZTemplate::factory();
    $tpl->setVariable( 'date', $order["created"] );
    $tpl->setVariable( 'email', $order["email"] );
    $xml  = simplexml_load_string( $order["data_text_1"] );
    $json = json_encode( $xml );
    $order = json_decode( $json,TRUE );
    $tpl->setVariable( 'order', $order );
    $templateResult = $tpl->fetch( 'design:xrowecommerce/feedbackmail.tpl' );
    $subject = $tpl->variable( 'subject' );
    $sender_name = $tpl->variable( 'sender-name' );
    $sender_mail = $tpl->variable( 'sender-mail' );
    $receiver_name = $tpl->variable( 'receiver-name' );
    $receiver_mail = $tpl->variable( 'receiver-mail' );
    $cli->output( "sending to " . $receiver_mail );
    $mail = new ezcMailComposer();
    $mail->from = new ezcMailAddress( $sender_mail, $sender_name );
    $mail->addTo( new ezcMailAddress( $receiver_mail, $receiver_name ) );
    $mail->subject = $subject;
    $mail->plainText = $templateResult;
    $mail->build();
    $transport->send( $mail );
}