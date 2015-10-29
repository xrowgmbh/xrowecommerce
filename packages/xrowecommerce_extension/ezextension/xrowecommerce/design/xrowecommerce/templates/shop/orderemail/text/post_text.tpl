{def $cemail = ezini( 'InvoiceSettings', 'CompanyEmail', 'xrowecommerce.ini'  )}{chr(012)}
{'The Staff at'|i18n( 'extension/xrowecommerce' )} {ezini( 'InvoiceSettings', 'CompanyName', 'xrowecommerce.ini'  )}{chr(012)}
{chr(012)}
{chr(012)}
----------------------------{chr(012)}
{'Contact Us'|i18n( 'extension/xrowecommerce' )}:{chr(012)}
{'Phone'|i18n( 'extension/xrowecommerce' )}: {ezini( 'InvoiceSettings', 'CompanyPhone', 'xrowecommerce.ini'  )}{chr(012)}
{'Shop'|i18n( 'extension/xrowecommerce' )}: {ezini( 'InvoiceSettings', 'CompanyWebsite', 'xrowecommerce.ini'  )}{chr(012)}
{if and(is_set($cemail), $cemail|ne(''))}{'Email'|i18n( 'extension/xrowecommerce' )}: {ezini( 'InvoiceSettings', 'CompanyEmail', 'xrowecommerce.ini'  )}{/if}{chr(012)}{chr(012)}
----------------------------{chr(012)}