<form action={concat( '/shop/customerlist' )|ezurl} method="post">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Forecast [%forecast]'|i18n( 'design/admin/shop/customerlist',, hash( '%forecast', $list_count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{if $list_count}

<table class="list" cellspacing="0">

{def $counter=0}
{def $trow=0}
{foreach $list as $item sequence array( bglight, bgdark ) as $sequence }

    <tr class="{$sequence}">
        <th class="wide" colspan="7"><a href={$item.object.main_node.url_alias|ezurl}>{$item.object.name}</a></th>
    </tr>
    <tr class="{$sequence}">
    {set $trow=0}
    {foreach $item.variations as $variation}
    {set $trow=$trow|sum(1)}
    {foreach $variation as $cycle}
    {set $trow=$trow|sum(1)}
    {/foreach}
    {/foreach}
    <td class="tight" rowspan="{$trow}">
    &nbsp;
    </td>
    <th class="number" align="right">
    </th>
    <th class="name" align="right" colspan="5">Variation</th>

    </tr>
    {set $counter=0}
    {foreach $item.variations as $key => $variation}
    {set $counter=$counter|sum(1)}
    <tr class="{$sequence}">

    <th class="number" align="right"  rowspan="{$variation.cycles|count|sum(1)}">
    {$counter}
    </th>

        <td class="name" align="right" rowspan="{$variation.cycles|count|sum(1)}">
        {foreach $variation.options as $option}
        {$option.name} {$option.value} {$option.comment}<br />
        {/foreach}
        </td>
    <th class="name" align="right">Type</th>
    <th class="name" align="right">Amount</th>
    <th class="name" align="right">Price per item</th>
    <th class="name" align="right">Total Price</th>
    </tr>
{foreach $variation.cycles as $ckey => $cycle}
<tr class="{$sequence}">
    <td class="tight">
        {$type_text_array.[$ckey]}
    </td>
    <td class="tight">
        {$cycle.amount}
    </td>
    <td class="tight">
        {$cycle.unit_price|l10n( 'currency' )}
    </td>
        <td class="tight">
        {$cycle.total_price|l10n( 'currency' )}
    </td>
    </tr>
        {/foreach}

    {/foreach}
{/foreach}



{undef}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/recurringorders/forecast'
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$limit}
</div>

</div>

<div class="context-block">

{* DESIGN: Content END *}</div></div></div></div></div></div>

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{'Total recurring forecast on revenue'|i18n( 'design/admin/shop/discountgroupmembershipview')}</h2>

{* DESIGN: Mainline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

<table class="list" cellspacing="0">
    <tr>
        <th class="wide">Type</th>
        <th class="wide">Amount</th>
    </tr>
        {foreach $totals as $key => $total}
    <tr>
        <td class="name" align="right">{$type_text_array.[$key]}</td>
        <td class="number" align="right">{$total|l10n( 'currency' )}</td>
    </tr>
        {/foreach}
</table>

{else}
<div class="block">
<p>{'The forecast list is empty.'|i18n( 'design/admin/shop/customerlist' )}</p>
</div>
{/if}

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

</form>
