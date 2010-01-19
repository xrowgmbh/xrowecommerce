{if is_set($packingslip)|not}
    {def $packingslip=false()}
{/if}

<p style="margin-top:2em"></p>
    {include uri="design:order/invoiceheader.tpl"}
<div class="break" style="clear:both;"></div>
    {shop_account_view_gui view=invoice order=$order packingslip=$packingslip}
<div class="break" style="clear:both;"></div>
<p style="margin-top:2em;">
<b>{'Product items'|i18n( 'extension/xrowecommerce' )}</b>
</p>
<table class="list" width="70%" cellspacing="0" cellpadding="0" border="0" align="right">
<thead>
<tr>
    <th class="tight">{'Quantity'|i18n( 'extension/xrowecommerce' )}</th>
    <th class="tight">{'SKU'|i18n( 'extension/xrowecommerce' )}</th>
	<th>{'Product'|i18n( 'extension/xrowecommerce' )}</th>
{if $packingslip|not}
	<th class="tight">{'Unit price'|i18n( 'extension/xrowecommerce' )}</th>
	<th class="tight">{'Extended price'|i18n( 'extension/xrowecommerce' )}</th>
{/if}
</tr>
</thead>
<tbody>

{foreach $order.product_items as $ProductItem}
    <tr>
        <td class="number" align="right">{$ProductItem.item_count}</td>
    {section show=$ProductItem.item_object.option_list}
        {section var=Options loop=$ProductItem.item_object.option_list}
            <td align="left">{$order|attribute(show)}</td>
            <td>{$ProductItem.item_object.name|wash}</td>
        {/section}
    {section-else}
    	<td align="left">{$ProductItem.item_object.contentobject.data_map.product_id.content}</td>
    	<td>{$ProductItem.item_object.name|wash}</td>
    {/section}
        {if $packingslip|not}
    	<td class="number" align="right">{$ProductItem.price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    	<td class="number" align="left">{$ProductItem.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
        {/if}
    </tr>
{/foreach}

{if $packingslip|not}
    <tr>
    <td colspan="9">&nbsp;</td>
    </tr>
    <tr>
        <th colspan="4">{'Subtotal Ex. Tax'|i18n( 'extension/xrowecommerce' )}:</th>
        <td class="number" align="right">{$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    </tr>
    {section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}
    <tr>
    	<th colspan="4">{$OrderItem:item.description}:</th>
    	<td class="number" align="right">{$OrderItem:item.price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    </tr>
    {/section}
    {def $taxpercent = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)
         $percentage = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)|l10n('number') }
    <tr>
        <th colspan="4">{'Tax'|i18n( 'extension/xrowecommerce' )}:</th>
        <td class="number" align="right">{$order.product_total_inc_vat|sub($order.product_total_ex_vat)|l10n( 'currency', $locale, $symbol )}</td>
    </tr>
    <tr>
        <th colspan="4"><b>{'Order total'|i18n( 'extension/xrowecommerce' )}</b></th>
        <td class="number" align="right"><b>{$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}</b></td>
    </tr>
{/if}
</tbody>
</table>
{if eq( ezini('OrderSettings','showFooter','order.ini'), true)}
{include uri="design:order/invoicefooter.tpl" packingslip=$packingslip}
{/if}