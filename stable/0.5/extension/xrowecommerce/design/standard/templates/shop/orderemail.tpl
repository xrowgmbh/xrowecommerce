+{set-block scope=root variable=subject}{ezini( 'InvoiceSettings', 'CompanyName', 'order.ini'  )} {"Order"|i18n("design/standard/shop")} #{$order.order_nr}{/set-block}
Thank you for ordering from {ezini( 'InvoiceSettings', 'CompanyName', 'order.ini'  )}.

The details of the order are below.  If you have a question, please do not reply to this email.
Rather, email {ezini( 'InvoiceSettings', 'CompanyEmail', 'order.ini'  )} or call {ezini( 'InvoiceSettings', 'CompanyPhone', 'order.ini'  )}.
Thank you again, and have a wonderful day!
----------------------------------------------

Order ID: {$order.order_nr}
{def $timestamp=currentdate()}

Date: {$timestamp|l10n( 'datetime' )}


{shop_account_view_gui view=ascii order=$order}



{"Product items"|i18n("design/standard/shop")}
----------------------------------------------


{def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
         $locale = false()
         $symbol = false()}

{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{/if}

{section name=ProductItem loop=$order.product_items show=$order.product_items sequence=array(bglight,bgdark)}
{$ProductItem:item.item_count} x {$ProductItem:item.object_name} ({$ProductItem:item.price_inc_vat|l10n( 'currency', $locale, $symbol )})

{def $vary=$ProductItem:item.item_object.contentobject.data_map.variation.content.option_list[$ProductItem:item.item_object.option_list.0.option_item_id]}
--{$vary.value}--  {$vary.comment}
{$ProductItem:item.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}


{/section}

----------------------------------------------
Total:  {$order.product_total_inc_vat|l10n( 'currency', $locale, $symbol )}
{section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}

{$OrderItem:item.description}:  {$OrderItem:item.price_inc_vat|l10n( 'currency', $locale, $symbol )}

{/section}
Grand Total: {$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}
{undef $currency $locale $symbol}

----------------------------------------------






The Staff at {ezini( 'InvoiceSettings', 'CompanyName', 'order.ini'  )}

----------------------------
Contact Us:
Phone: {ezini( 'InvoiceSettings', 'CompanyPhone', 'order.ini'  )}
Shop: {ezini( 'InvoiceSettings', 'CompanyWebsite', 'order.ini'  )}
----------------------------
