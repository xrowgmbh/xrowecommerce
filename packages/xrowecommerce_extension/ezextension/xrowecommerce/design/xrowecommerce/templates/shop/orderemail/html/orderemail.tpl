{set-block scope=root variable=subject}{ezini( 'InvoiceSettings', 'CompanyName', 'xrowecommerce.ini'  )} {"Order"|i18n("extension/xrowecommerce")} #{$order.order_nr}{/set-block}
{include uri="design:shop/orderemail/html/pre_text.tpl"}

<hr />

<p>{'Order ID'|i18n( 'extension/xrowecommerce' )}: {$order.order_nr}</p>

<p>{'Date'|i18n( 'extension/xrowecommerce' )}: {$order.created|l10n( 'datetime' )}</p>

{shop_account_view_gui view=html order=$order}

{"Product items"|i18n("extension/xrowecommerce")}
<hr />

{def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
         $locale = false()
         $symbol = false()}
{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{/if}
{foreach $order.product_items as $product_item}

{include uri="design:shop/orderemail/html/product_cell_view.tpl"}

{/foreach}

<hr />
<p>{'Total'|i18n( 'extension/xrowecommerce' )}: {$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}</p>

{foreach $order.order_items as $OrderItem}
<hr />
<p>{$OrderItem.description}:  {$OrderItem.price_inc_vat|l10n( 'currency', $locale, $symbol )}</p>
 
{/foreach}
{def $taxpercent = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)
     $percentage = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)|l10n('number') }
{if $taxpercent|eq(0)|not}

<hr />
<p>{'Tax'|i18n('extension/xrowecommerce')}: {sub($order.total_inc_vat, $order.total_ex_vat)|l10n( 'currency', $locale, $symbol )}</p>
 
{/if}
<hr />
<p>{'Total'|i18n( 'extension/xrowecommerce' )}: {$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}</p>
<hr /> 
{include uri="design:shop/orderemail/html/post_text.tpl"}

{undef $currency $locale $symbol}
