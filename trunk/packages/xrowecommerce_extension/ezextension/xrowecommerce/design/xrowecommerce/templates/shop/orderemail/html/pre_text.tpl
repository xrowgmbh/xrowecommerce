<html>
<head>
    <title>{'Order confirmation email from'|i18n( 'extension/xrowecommerce' )} {ezini( 'InvoiceSettings', 'CompanyName', 'xrowecommerce.ini'  )}</title>
{literal}
    <style type="text/css">
        table tr td, table tr th
        {
            text-align: left;
        }
    </style>
</head>
{/literal}

<body>
<p>{'Thank you for ordering from'|i18n( 'extension/xrowecommerce' )} {ezini( 'InvoiceSettings', 'CompanyName', 'xrowecommerce.ini'  )}.</p>huhuhuuhuhhuhuhu
{ezini( 'InvoiceSettings', 'CompanyName', 'xrowecommerce.ini'  )|attribute(show)}

<p>{'The details of the order are below.  If you have a question, please do not reply to this email.'|i18n( 'extension/xrowe"extension/xrowecommerce/design/xrowecommerce/templates/shop/orderemail.tpl"commerce' )}</p>
<p>{'Rather, email'|i18n( 'extension/xrowecommerce' )} {ezini( 'InvoiceSettings', 'CompanyEmail', 'xrowecommerce.ini'  )} {'or call'|i18n( 'extension/xrowecommerce' )} {ezini( 'InvoiceSettings', 'CompanyPhone', 'order.ini'  )}.</p>
<p>{'Thank you again, and have a wonderful day!'|i18n( 'extension/xrowecommerce' )}</p>