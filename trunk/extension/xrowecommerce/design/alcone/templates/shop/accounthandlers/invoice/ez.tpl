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