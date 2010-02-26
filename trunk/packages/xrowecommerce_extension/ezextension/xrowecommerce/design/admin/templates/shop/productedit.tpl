<form name="contentserver" method="post" action={'orderedit/edit'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Products with zero weight'|i18n( 'design/admin/shop/orderlist')} ( {count($products)} Products )</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">
<div class="context-attributes">
<p>
{'Products that match the following properties are listed:'|i18n( 'extension/xrowecommerce')}
</p>
<ul>
<li>{'no weight attribute in the variations and no general weight'|i18n( 'extension/xrowecommerce')}</li>
<li>{'no general weight and zero weight in the variations'|i18n( 'extension/xrowecommerce')}</li>
</ul>

<div class="block">

<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
        <th>{'Contentobject_ID'|i18n( 'design/admin/shop/orderview' )}</th>        
        <th>{'Product name'|i18n( 'design/admin/shop/orderview' )}</th>
</tr>
{if count($products)|gt(0)}
{foreach $products as $product sequence array( 'bgdark', 'bglight' ) as $style}
<tr class="{$style}">
        <td align="left">{$product.contentobject_id}</td>
        <td align="left"><a href ={concat("/content/edit/",$product.contentobject_id)|ezurl()} target="_blank">{$product.name}</a></td>
</tr>
{/foreach}
{else}
<tr>
        <td colspan="2">{'Gratulation. No products left anymore.'|i18n( 'extension/xrowecommerce')}</td>
</tr>
{/if}
</table>


<div class="break"></div>
</div>
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>
