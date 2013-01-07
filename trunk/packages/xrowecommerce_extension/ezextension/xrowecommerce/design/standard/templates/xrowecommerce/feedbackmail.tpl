{set-block scope=root variable=sender-mail}sender@example.com{/set-block}
{set-block scope=root variable=sender-name}Your Company Team{/set-block}
{set-block scope=root variable=receiver-name}{$order.first_name} {$order.last_name}{/set-block}
{set-block scope=root variable=receiver-mail}{$email}{/set-block}
{set-block scope=root variable=subject}We hope you were satisfied{/set-block}
Dear {$order.first_name} {$order.last_name},

We hope the order from {$date|ezdate()} satisfied you.
Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consetetur sadipscing elitr, sed diam nonumy eirmod tempor invidunt ut labore et dolore magna aliquyam erat, sed diam voluptua. At vero eos et accusam et justo duo dolores et ea rebum. Stet clita kasd gubergren, no sea takimata sanctus est Lorem ipsum dolor sit amet.

Best Regards,
Your Company Team