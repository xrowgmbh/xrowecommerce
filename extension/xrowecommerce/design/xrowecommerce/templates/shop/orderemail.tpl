{set-block scope=root variable=subject}{ezini( 'InvoiceSettings', 'CompanyName', 'order.ini'  )} {"Order"|i18n("extension/xrowecommerce")} #{$order.order_nr}{/set-block}
{'Thank you for ordering from'|i18n( 'extension/xrowecommerce' )} {ezini( 'InvoiceSettings', 'CompanyName', 'order.ini'  )}.

{'The details of the order are below.  If you have a question, please do not reply to this email.'|i18n( 'extension/xrowecommerce' )}
{'Rather, email'|i18n( 'extension/xrowecommerce' )} {ezini( 'InvoiceSettings', 'CompanyEmail', 'order.ini'  )} {'or call'|i18n( 'extension/xrowecommerce' )} {ezini( 'InvoiceSettings', 'CompanyPhone', 'order.ini'  )}.
{'Thank you again, and have a wonderful day!'|i18n( 'extension/xrowecommerce' )}
----------------------------------------------

{'Order ID'|i18n( 'extension/xrowecommerce' )}: {$order.order_nr}
{def $timestamp=currentdate()}

{'Date'|i18n( 'extension/xrowecommerce' )}: {$timestamp|l10n( 'datetime' )}


{shop_account_view_gui view=ascii order=$order}



{"Product items"|i18n("extension/xrowecommerce")}
----------------------------------------------


{def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
         $locale = false()
         $symbol = false()}

{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{/if}

{foreach $order.product_items as $ProductItem}
{$ProductItem.item_count} x {$ProductItem.object_name} ({$ProductItem.price_inc_vat|l10n( 'currency', $locale, $symbol )})

{def $vary=$ProductItem.item_object.contentobject.data_map.variation.content.option_list[$ProductItem.item_object.option_list.0.option_item_id]}
--{$vary.value}--  {$vary.comment}
{$ProductItem:item.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}


{/foreach}

----------------------------------------------
{'Total'|i18n( 'extension/xrowecommerce' )}:  {$order.product_total_inc_vat|l10n( 'currency', $locale, $symbol )}
{foreach $order.order_items as $OrderItem}

{$OrderItem.description}:  {$OrderItem.price_inc_vat|l10n( 'currency', $locale, $symbol )}

{/foreach}
{'Total'|i18n( 'extension/xrowecommerce' )}: {$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}
{undef $currency $locale $symbol}

----------------------------------------------






{'The Staff at'|i18n( 'extension/xrowecommerce' )} {ezini( 'InvoiceSettings', 'CompanyName', 'order.ini'  )}

----------------------------
{'Contact Us'|i18n( 'extension/xrowecommerce' )}:
{'Phone'|i18n( 'extension/xrowecommerce' )}: {ezini( 'InvoiceSettings', 'CompanyPhone', 'order.ini'  )}
{'Shop'|i18n( 'extension/xrowecommerce' )}: {ezini( 'InvoiceSettings', 'CompanyWebsite', 'order.ini'  )}
----------------------------
