+{set-block scope=root variable=subject}Alcone Company {"Order"|i18n("design/standard/shop")} #{$order.order_nr}{/set-block}
Thank you for ordering from Alcone, professional makeup artists' go-to spot for the best cosmetics.

The details of the order are below.  If you have a question, please do not reply to this email.
Rather, email info@alconeco.com or call 800-466-7446.
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






The Staff at Alcone Company

----------------------------
Contact Us:
Phone: 800-466-7446
Shop: http://www.alconeco.com
----------------------------
