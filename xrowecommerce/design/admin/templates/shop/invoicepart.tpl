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
<p style="margin-top:2em"></p>
<img src={"alcone_logo.gif"|ezimage} style="width:30%;float:left;" alt="" />

<div style="width:30%;float: right;">
<p>
Alcone Co.<br />
5-45 49th Avenue<br />
Long Island City, NY 11101<br />
1-800-466-7446<br />
</p>
</div>

<div class="break" style="clear:both;"></div>
<div style="width:30%;float: right;">
<table  class="list" width="100%" cellspacing="0" cellpadding="0" border="0" align="right">
<caption style="font-size: 12pt;"><b>
{if $packingslip}
Packing slip
{else}
Invoice
{/if}
</b></caption>
<tr>
    <th>{'Order ID'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.order_nr}</td>
</tr>
<tr>
    <th>{'Order date'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.created|l10n( shortdate )}</td>
</tr>
<tr>
	<th>{'Shipping method'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>
    
    {if eq($order.account_information.shippingtype,0)}Standard Shipping
    {elseif eq($order.account_information.shippingtype,1)}Next Day Service
    {elseif eq($order.account_information.shippingtype,2)}2nd Day Service
    {elseif eq($order.account_information.shippingtype,3)}UPS Ground (USA only)
    {elseif eq($order.account_information.shippingtype,4)}UPS Next Business Day Air (USA only)
    {elseif eq($order.account_information.shippingtype,5)}UPS 2nd Business Day Air (USA only)
    {elseif eq($order.account_information.shippingtype,6)}USPS Express Mail International (EMS)
    {elseif eq($order.account_information.shippingtype,7)}USPS Global Express Guaranteed
    {else}Unknown Shipping
    {/if}
    </td>
</tr>
    
</table>
</div>
<p style="margin-top:2em;">
&nbsp;
</p>
<div class="break" style="clear:both;"></div>
<div style="width:40%;float: left;">
<table  class="list" width="100%" cellspacing="0" cellpadding="0" border="0" align="right">
<caption style="font-size: 12pt;"><b>Bill to</b></caption>
<tr>
    <th class="tight">{'Name'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.first_name} {$order.account_information.mi} {$order.account_information.last_name}</td>
</tr>
<tr>
    <th>{'Address'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.address1}
     {if $order.account_information.address2}<br />{$order.account_information.address2}{/if}
     <br />{$order.account_information.city}, {$order.account_information.state} {$order.account_information.zip}<br />
     {$order.account_information.country}
     
     </td>
</tr>
<tr>
    <th>{'Phone'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.phone}</td>
</tr>
<tr>
	<th>{'E-mail'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>
        {$order.account_information.email}
    </td>
</tr>
</table>
</div>

<div style="width:40%;float: right;">

{if $order.account_information.shipping|not}

<table  class="list" width="100%" cellspacing="0" cellpadding="0" border="0" align="right">
<caption style="font-size: 12pt;"><b>Ship to</b></caption>
<tr>
    <th class="tight">{'Name'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.s_first_name} {$order.account_information.s_mi} {$order.account_information.s_last_name}</td>
</tr>
<tr>
    <th>{'Address'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.s_address1}
     {if $order.account_information.s_address2}<br />{$order.account_information.s_address2}{/if}
     <br />{$order.account_information.s_city}, {$order.account_information.s_state} {$order.account_information.s_zip}<br />
     {$order.account_information.s_country}
     
     </td>
</tr>
<tr>
    <th>{'Phone'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.s_phone}</td>
</tr>
<tr>
	<th>{'E-mail'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>
        {$order.account_information.s_email}
    </td>
</tr>
</table>




{else}

<table  class="list" width="100%" cellspacing="0" cellpadding="0" border="0" align="right">
<caption style="font-size: 12pt;"><b>Ship to</b></caption>
<tr>
    <th class="tight">{'Name'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.first_name} {$order.account_information.mi} {$order.account_information.last_name}</td>
</tr>
<tr>
    <th>{'Address'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.address1}
     {if $order.account_information.address2}<br />{$order.account_information.address2}{/if}
     <br />{$order.account_information.city}, {$order.account_information.state} {$order.account_information.zip}<br />
     {$order.account_information.country}
     
     </td>
</tr>
<tr>
    <th>{'Phone'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.phone}</td>
</tr>
<tr>
	<th>{'E-mail'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>
        {$order.account_information.email}
    </td>
</tr>
</table>

{/if} 
</div>
<div class="break" style="clear:both;"></div>
<p style="margin-top:2em;">
<b>{'Product items'|i18n( 'design/admin/shop/orderview' )}</b>
</p>
<table class="list" width="70%" cellspacing="0" cellpadding="0" border="0" align="right">
<thead>
<tr>
    <th class="tight">{'Quantity'|i18n( 'design/admin/shop/orderview' )}</th>
    <th class="tight">{'SKU'|i18n( 'design/admin/shop/orderview' )}</th>
	<th>{'Product'|i18n( 'design/admin/shop/orderview' )}</th>
{if $packingslip|not}
	<th class="tight">{'Unit price'|i18n( 'design/admin/shop/orderview' )}</th>
	<th class="tight">{'Extended price'|i18n( 'design/admin/shop/orderview' )}</th>
{/if}
</tr>
</thead>
<tbody>

{section name=ProductItem loop=$order.product_items show=$order.product_items sequence=array(bglight,bgdark)}
<tr>
    <td class="number" align="right">{$ProductItem:item.item_count}</td>
    {section show=$ProductItem:item.item_object.option_list}
    	{section var=Options loop=$ProductItem:item.item_object.option_list}
		<td align="left">{$:Options.item.value|wash}</td>
		<td>{$ProductItem:item.item_object.name|wash}
		{def $vary=$ProductItem:item.item_object.contentobject.data_map.variation.content.option_list[$ProductItem:item.item_object.option_list.0.option_item_id]}
		{if $vary.comment}
			<br />Variation: {$vary.comment}
		{/if}
		</td>
    	{/section}
    {section-else}
	<td align="left">{$ProductItem:item.item_object.contentobject.data_map.product_id.content}</td>
	<td>{$ProductItem:item.item_object.name|wash}</td>
    {/section}
    {if $packingslip|not}
	<td class="number" align="right">{$ProductItem:item.price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
	<td class="number" align="left">{$ProductItem:item.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
    {/if}
</tr>
{/section}
{if $packingslip|not}
<tr>
<td colspan="9">&nbsp;</td>
</tr>
<tr>
    <th colspan="4">{'Subtotal Ex. Tax'|i18n( 'design/admin/shop/orderview' )}:</th>
    <td class="number" align="right">{$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
</tr>


{section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}
<tr>
	<th colspan="4">{$OrderItem:item.description}:</th>
	<td class="number" align="right">{$OrderItem:item.price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
</tr>
{/section}
<tr class="shop-table-row-tax">
    <th colspan="4">{'Tax'|i18n( 'design/admin/shop/orderview' )}:</th>
    <td class="number" align="right">{$order.total_inc_vat|sub($order.total_ex_vat)|l10n( 'currency', $locale, $symbol )}</td>
</tr>
{def $taxpercent = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)
     $percentage = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)|l10n('number') }
<tr>
    <th colspan="4"><b>{'Order total'|i18n( 'design/admin/shop/orderview' )}</b></th>
    <td class="number" align="right"><b>{$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}</b></td>
</tr>
{/if}
</tbody>
</table>

{def $node = fetch( 'content', 'node', hash( 'node_id', 3124 ) )}
{if and($node.data_map.description.content.is_empty|not, $packingslip )}
<div style="clear:both;border: 1px dotted grey;padding: 1em;">

{attribute_view_gui attribute=$node.data_map.description}

</div>
{/if}
