{def $shiplist=fetch( 'shipping', 'list_all_methods' )}
{def $gateways=fetch( 'xrowecommerce', 'list_all_gateways' )}
<div style="width:30%;float: right;">
<table  class="list" width="100%" cellspacing="0" cellpadding="0" border="0" align="right">
<caption style="font-size: 12pt;"><b>
{if $packingslip}
{'Packing slip'|i18n( 'design/admin/shop/orderview' )}
{else}
{'Invoice'|i18n( 'design/admin/shop/orderview' )}
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
                {foreach $shiplist as $method}
                    {if $method.identifier|eq($order.account_information.shippingtype)}
                       {$method.name|wash}
                    {/if}
                {/foreach}
    </td>
</tr>
    
</table>
</div>
<p style="margin-top:2em;">
&nbsp;
</p>
<div class="break" style="clear:both;"></div>
<div style="width:45%;float: left;">
<table  class="list" width="100%" cellspacing="0" cellpadding="0" border="0" align="right">
<caption style="font-size: 12pt;"><b>{'Bill to'|i18n( 'design/admin/shop/orderview' )}</b></caption>
{if is_set($order.account_information.company_name)}
    <tr>
        <th class="tight">{'Company'|i18n( 'design/admin/shop/orderview' )}</th>
        <td>{$order.account_information.company_name|wash()} {$order.account_information.company_additional|wash()}</td>
    </tr>
{/if}
<tr>
    <th class="tight">{'Name'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.first_name|wash} {$order.account_information.mi|wash} {$order.account_information.last_name|wash}</td>
</tr>
<tr>
    <th>{'Address'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.address1|wash}
     {if $order.account_information.address2}<br />{$order.account_information.address2|wash}{/if}
     <br />{$order.account_information.city|wash}, {$order.account_information.state|wash} {$order.account_information.zip|wash}<br />
     {$order.account_information.country|wash}
     
     </td>
</tr>
<tr>
    <th>{'Phone'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.phone|wash}</td>
</tr>
<tr>
    <th>{'E-mail'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>
        {$order.account_information.email|wash}
    </td>
</tr>
</table>
</div>


<div style="width:45%;float: right;">

{if $order.account_information.shippingaddress|not}
<table  class="list" width="100%" cellspacing="0" cellpadding="0" border="0" align="right">
<caption style="font-size: 12pt;"><b>Ship to</b></caption>
{if is_set($order.account_information.s_company_name)}
    <tr>
        <th class="tight">{'Company'|i18n( 'design/admin/shop/orderview' )}</th>
        <td>{$order.account_information.s_company_name|wash()} {$order.account_information.s_company_additional|wash()}</td>
    </tr>
{/if}
<tr>
    <th class="tight">{'Name'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.s_first_name|wash} {$order.account_information.s_mi|wash} {$order.account_information.s_last_name|wash}</td>
</tr>
<tr>
    <th>{'Address'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.s_address1|wash}
     {if $order.account_information.s_address2}<br />{$order.account_information.s_address2|wash}{/if}
     <br />{$order.account_information.s_city|wash}, {$order.account_information.s_state|wash} {$order.account_information.s_zip|wash}<br />
     {$order.account_information.s_country|wash}
     </td>
</tr>
<tr>
    <th>{'Phone'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.s_phone|wash}</td>
</tr>
<tr>
    <th>{'E-mail'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>
        {$order.account_information.s_email|wash}
    </td>
</tr>
</table>
{else}

<table  class="list" width="100%" cellspacing="0" cellpadding="0" border="0" align="right">
<caption style="font-size: 12pt;"><b>{'Ship to'|i18n( 'design/admin/shop/orderview' )}</b></caption>
{if is_set($order.account_information.company_name)}
    <tr>
        <th class="tight">{'Company'|i18n( 'design/admin/shop/orderview' )}</th>
        <td>{$order.account_information.company_name|wash()} {$order.account_information.company_additional|wash()}</td>
    </tr>
{/if}
<tr>
    <th class="tight">{'Name'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.first_name|wash} {$order.account_information.mi|wash} {$order.account_information.last_name|wash}</td>
</tr>
<tr>
    <th>{'Address'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.address1|wash}
     {if $order.account_information.address2}<br />{$order.account_information.address2|wash}{/if}
     <br />{$order.account_information.city|wash}, {$order.account_information.state|wash} {$order.account_information.zip|wash}<br />
     {$order.account_information.country|wash}
     
     </td>
</tr>
<tr>
    <th>{'Phone'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.phone|wash}</td>
</tr>
<tr>
    <th>{'E-mail'|i18n( 'design/admin/shop/orderview' )}</th>
    <td>{$order.account_information.email|wash}</td>
</tr>
</table>

{/if} 
</div>