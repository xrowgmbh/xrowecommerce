{if is_set($packingslip)|not}
    {def $packingslip=false()}
{/if}
<!-- 
{literal}
<style type="text/css">
table tbody td
{
    page-break-inside: avoid;
}
thead {
    display: table-header-group;
}
</style>
{/literal}
 -->

<p style="margin-top:2em"></p>
    {include uri="design:shop/invoice/header.tpl"}
<div class="break" style="clear:both;"></div>
    {shop_account_view_gui view=invoice order=$order packingslip=$packingslip}
<div class="break" style="clear:both;"></div>
<p style="margin-top:2em;">
<b>{'Product items'|i18n( 'design/admin/shop/orderview' )}</b>
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
{def $vary=""}
<tr>
    <td class="number" align="right">{$ProductItem.item_count}</td>
    {if $ProductItem.item_object.option_list}
        {foreach $ProductItem.item_object.option_list as $Options}
            <td align="left">{$Options.value|wash}</td>
            <td>{$ProductItem.item_object.name|wash}
            {foreach $ProductItem.item_object.contentobject.data_map.options.content.option_list as $option}
                {if $option.id|eq($ProductItem.item_object.option_list.0.option_item_id)}
                    {set $vary=$option}
                {/if}
            {/foreach}
            {if and(is_set($vary),is_set($vary.comment))}
                <br />{'Variation'i18n('extension/xrowecommerce')}: {$vary.comment}
            {/if}
            </td>
            {undef $vary}
        {/foreach}
    {else}
    <td align="left">
    {$ProductItem.item_object.contentobject.data_map.product_id.content}
    {$ProductItem.item_object.contentobject.data_map.options.data_text}
    </td>
    <td>{$ProductItem.item_object.name|wash}</td>
    {/if}
    {if $packingslip|not}
    <td class="number" align="right">{$ProductItem.price_ex_vat|l10n( 'currency' )}</td>
    <td class="number" align="left">{$ProductItem.total_price_ex_vat|l10n( 'currency' )}</td>
    {/if}
</tr>
{/foreach}
{if $packingslip|not}
<tr>
<td colspan="9">&nbsp;</td>
</tr>
<tr>
    <th colspan="4">{'Subtotal Ex. Tax'|i18n( 'extension/xrowecommerce' )}:</th>
    <td class="number" align="right">{$order.product_total_ex_vat|l10n( 'currency' )}</td>
</tr>
{foreach $order.order_items as $order_item sequence array(bglight,bgdark) as $sequence}
<tr>
    <th colspan="4">{$order_item.description}:</th>
    <td class="number" align="right">{$order_item.price_ex_vat|l10n( 'currency' )}</td>
</tr>
{/foreach}
{def $taxpercent = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)
     $percentage = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)|l10n('number') }
<tr>
    <th colspan="4">{'Tax'|i18n( 'extension/xrowecommerce' )}:</th>
    <td class="number" align="right">{$order.total_inc_vat|sub($order.total_ex_vat)|l10n( 'currency' )}</td>
</tr>

<tr>
    <th colspan="4"><b>{'Order total'|i18n( 'extension/xrowecommerce' )}</b></th>
    <td class="number" align="right"><b>{$order.total_inc_vat|l10n( 'currency' )}</b></td>
</tr>
{/if}
</tbody>
</table>
{if ezini('InvoiceSettings','ShowFooter','xrowecommerce.ini')|eq('enabled')}
{include uri="design:shop/invoice/footer.tpl" packingslip=$packingslip}
{/if}