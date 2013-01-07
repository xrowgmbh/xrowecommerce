{def $sender_mail = ezini( 'MailSettings', 'EmailSender', 'site.ini' )}
{if or($sender_mail|is_null(), $sender_mail|trim()|eq(''))}
    {set $sender_mail = ezini( 'MailSettings', 'AdminEmail', 'site.ini' )}
{/if}
{def $sender_name = ezini( 'InvoiceSettings', 'CompanyName', 'xrowecommerce.ini' )}

{set-block scope=root variable=sender-mail}{$sender_mail}{/set-block}
{set-block scope=root variable=sender-name}{$sender_name}{/set-block}
{set-block scope=root variable=receiver-name}{$order.first_name} {$order.last_name}{/set-block}
{set-block scope=root variable=receiver-mail}{$email}{/set-block}
{set-block scope=root variable=subject}We hope you were satisfied{/set-block}
Dear {$order.first_name} {$order.last_name},

We hope the order from {$date|ezdate()} satisfied you.
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.

Best Regards,
Your Company Team