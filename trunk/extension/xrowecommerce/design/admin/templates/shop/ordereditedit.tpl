{def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
     $locale = false()
     $symbol = false()}
{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{/if}
<form name="contentserver" method="post" action={'orderedit/edit'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Orderitem'|i18n( 'design/admin/shop/orderlist')} {$ordernr}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<form action={concat("/orderedit/edit")|ezurl} method="post" name="Orderitemedit">
<table class="list" cellspacing="0">
{foreach $orderitems as $orderitem}
<tr class="bglight">
    <th scope="row">{'Order Number'|i18n( 'extension/xrowecommerce' )}:</th>
    <td>
        <input type="hidden" name="orderitemid[]" value="{$orderitem.id}" />
        <b>{$ordernr}</b>
    </td>
</tr>
<tr class="bglight">
    <th scope="row">{'Original description'|i18n( 'extension/xrowecommerce' )}:</th>
    <td>
        {$orderitem.description}
    </td>
</tr>

<tr class="bglight">
    <th scope="row">{'Description'|i18n( 'extension/xrowecommerce' )}:</th>
    <td>
        <input type="text" name="description_{$orderitem.id}" value="{$orderitem.description}" size="130" />
    </td>
</tr>
<tr class="bglight">
    <th scope="row">{'Original price'|i18n( 'extension/xrowecommerce' )}:</th>
    <td>
        {$orderitem.price|l10n( 'currency', $locale, $symbol )}
    </td>
</tr>

<tr class="bglight">
    <th scope="row">{'Price'|i18n( 'extension/xrowecommerce' )}:</th>
    <td>
        {$symbol} <input type="text" name="price_{$orderitem.id}" value="{$orderitem.price}" size="10" />
    </td>
</tr>

{/foreach}
</table>
</form>
<div class="block">
<div class="button-left">
    <input class="button" type="submit" name="Store" value="{'Store shippingcosts'|i18n( 'design/admin/shop/orderlist' )}" title="Store shippingcosts"/>
    <input class="button" type="submit" name="Cancel" value="{'Cancel'|i18n( 'design/admin/shop/orderlist' )}" title="Cancel this action" />
</div>
<div class="break"></div>

</div>


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>
