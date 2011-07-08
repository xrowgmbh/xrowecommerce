{if ezini( 'InvoiceSettings', 'CompanyName', 'xrowecommmerce.ini'  )|ne('')}<p>{'The Staff at'|i18n( 'extension/xrowecommerce' )} {ezini( 'InvoiceSettings', 'CompanyName', 'xrowecommmerce.ini'  )}</p>{/if}

<hr />
<p>{'Contact Us'|i18n( 'extension/xrowecommerce' )}:</p>
{if ezini( 'InvoiceSettings', 'CompanyPhone', 'xrowecommmerce.ini'  )|ne('')}<p>{'Phone'|i18n( 'extension/xrowecommerce' )}: {ezini( 'InvoiceSettings', 'CompanyPhone', 'xrowecommmerce.ini'  )}</p>{/if}
{if ezini( 'InvoiceSettings', 'CompanyWebsite', 'xrowecommmerce.ini'  )|ne('')}<p>{'Shop'|i18n( 'extension/xrowecommerce' )}: {ezini( 'InvoiceSettings', 'CompanyWebsite', 'xrowecommmerce.ini'  )}</p>{/if}
<hr />
</head>
</html>