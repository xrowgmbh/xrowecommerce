{set-block scope=root variable=subject}{ezini( 'InvoiceSettings', 'CompanyName', 'xrowecommerce.ini'  )} {"Order"|i18n("extension/xrowecommerce")} #{$order.order_nr}{/set-block}
{include uri="design:shop/orderemail/text/pre_text.tpl"}
----------------------------------------------

{'Your Purchase Order'|i18n( 'extension/harley' )} #{$order.order_nr}  (date {$order.created|l10n( 'datetime' )})

............................................................................................
+ + {'Items'|i18n( 'extension/harley' )} + +
............................................................................................

{def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
         $locale = false()
         $symbol = false()}
{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{/if}
{foreach $order.product_items as $product_item}

{include uri="design:shop/orderemail/text/product_cell_view.tpl"}

{/foreach}

............................................................................................
{'Total'|i18n( 'extension/harley' )}: {$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}

{foreach $order.order_items as $OrderItem}
............................................................................................
{$OrderItem.description}:  {$OrderItem.price_inc_vat|l10n( 'currency', $locale, $symbol )}
 
{/foreach}
{def $taxpercent = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)
     $percentage = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)|l10n('number') }
{if $taxpercent|eq(0)|not}
............................................................................................
{'Tax'|i18n('extension/harley')}: {sub($order.total_inc_vat, $order.total_ex_vat)|l10n( 'currency', $locale, $symbol )}
 
{/if}
............................................................................................
{'Total'|i18n( 'extension/harley' )}: {$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}
............................................................................................

{shop_account_view_gui view=ascii order=$order}

{include uri="design:shop/orderemail/text/post_text.tpl"}

{undef $currency $locale $symbol}
