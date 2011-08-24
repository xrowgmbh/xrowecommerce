{'Hello'|i18n( 'extension/harley' )} {$order.account_information.title|wash} {$order.account_information.last_name|wash},
{'many thanks for ordering at'|i18n( 'extension/harley' )} {ezini( 'InvoiceSettings', 'CompanyName', 'xrowecommerce.ini'  )}.
............................................................................................
{ezini( 'InvoiceSettings', 'CompanyName', 'xrowecommerce.ini'  )|attribute(show)}

{'If you have any questions concerning your customer account or your order,'|i18n( 'extension/harley' )}
{'please send an email to'|i18n( 'extension/harley' )} {ezini( 'InvoiceSettings', 'CompanyEmail', 'xrowecommerce.ini'  )}
{'or call us by phone on '|i18n( 'extension/harley' )} {ezini( 'InvoiceSettings', 'CompanyPhone', 'order.ini'  )}.
{'Below you can find your order confirmation, thank you!'|i18n( 'extension/harley' )}