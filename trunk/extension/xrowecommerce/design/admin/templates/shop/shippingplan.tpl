
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

<table class="list" width="70%" cellspacing="0" cellpadding="0" border="0" align="right">
<caption>{'Shipping plan'|i18n( 'design/admin/shop/orderview' )}</caption>
<thead>
<tr>
    <th class="tight">{'Package'|i18n( 'extension/xrowecommerce' )}</th>
    <th class="tight">{'Name of package'|i18n( 'extension/xrowecommerce' )}</th>
    <th class="wide">{'Products'|i18n( 'extension/xrowecommerce' )}</th>
</tr>
</thead>
<tbody>
{foreach $package_list as $key => $package sequence sequence array(bglight,bgdark) as $sequence}

<tr>
    <td class="number" align="right">{$key|sum(1)}</td>

    <td align="left">{$package.name}</td>
    <td>
    <ul>
    {foreach $package.content as $product}
<li>{$product.amount} x {$product.name}</li>

{/foreach}
    </ul>
    </td>


</tr>
{/foreach}
</tbody>
</table>
<div class="break" style="clear:both;"></div>