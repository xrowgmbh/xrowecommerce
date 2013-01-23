{def $cemail = ezini( 'InvoiceSettings', 'CompanyEmail', 'xrowecommerce.ini'  )}
{'The Staff at'|i18n( 'extension/xrowecommerce' )} {ezini( 'InvoiceSettings', 'CompanyName', 'xrowecommerce.ini'  )}


----------------------------
{'Contact Us'|i18n( 'extension/xrowecommerce' )}:
{'Phone'|i18n( 'extension/xrowecommerce' )}: {ezini( 'InvoiceSettings', 'CompanyPhone', 'xrowecommerce.ini'  )}
{'Shop'|i18n( 'extension/xrowecommerce' )}: {ezini( 'InvoiceSettings', 'CompanyWebsite', 'xrowecommerce.ini'  )}
{if and(is_set($cemail), $cemail|ne(''))}{'Email'|i18n( 'extension/xrowecommerce' )}: {ezini( 'InvoiceSettings', 'CompanyEmail', 'xrowecommerce.ini'  )}{/if}
----------------------------