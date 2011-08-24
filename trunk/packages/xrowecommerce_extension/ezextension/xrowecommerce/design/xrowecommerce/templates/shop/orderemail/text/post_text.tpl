............................................................................................
+ + {'Cancellation Policy'|i18n( 'extension/harley' )} + +
............................................................................................

+ + {'Right of Revocation'|i18n( 'extension/harley' )} + +
{'You may declare the revocation of your contractual statement in text form (e.g. letter, email) or by returning the merchandise within two weeks. The revocation does not have to contain any explanation. The revocation period commences the day following the receipt of merchandise and this revocation instruction in text form. The time-limit shall be deemed to be observed by the timely dispatch of the declaration of revocation or the returning of the shipment.The revocation is to be addressed to:'|i18n( 'extension/harley' )}

{ezini( 'InvoiceSettings', 'CompanyName', 'xrowecommerce.ini'  )}
{foreach ezini( 'InvoiceSettings', 'CompanyAddress', 'xrowecommerce.ini'  ) as $item}
    {$item}
{/foreach}

+ + {'Consequences of Revocation'|i18n( 'extension/harley' )} + +
{'In case of a valid revocation, all mutually received performances as well as emoluments taken (e.g. interest), if applicable, are to be restituted by either side. If you are unable or partially unable to restitute the merchandise to us or can only restitute it in a deteriorated condition, you have to insofar compensate for its value where applicable. This does not apply if the deterioration is exclusively due to examining the merchandise – as for instance in a retail store – or putting the merchandise to its intended use. Things that can be shipped by parcel are to be returned on our risk. Things that cannot be shipped by parcel will be picked up. You are obliged to bear the costs of the return shipment, if the merchandise delivered corresponds to the merchandise ordered, and if the price of the merchandise to be sent back does not exceed an amount of forty euros or if, where the price is higher, you have at the date of the revocation not yet rendered consideration or given a part payment. In all other cases, the returning of the shipment for you is free of charge. All reimbursement obligations must be fulfilled within 30 days of the declaration of revocation.'|i18n( 'extension/harley' )}
