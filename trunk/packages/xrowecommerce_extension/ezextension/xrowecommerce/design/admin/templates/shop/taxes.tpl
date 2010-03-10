<form name="orderstatistics" method="post" action={'orderedit/taxes'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Order statistic'|i18n( 'design/admin/shop/orderlist')} ({$order_array|count()} {if $order_array|count()|eq(1)}{'order'|i18n( 'extension/xrowecommerce')}{else}{'orders'|i18n( 'extension/xrowecommerce')}{/if}) </h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">
<div class="context-attributes">
{def $timestamp=currentdate()}
<p>{$timestamp|l10n( 'datetime' )}</p>
<p>{'Selected period is from'|i18n( 'extension/xrowecommerce')} <b>{$order_result.time.start_stamp|l10n( 'date' )}</b> {'till'|i18n( 'extension/xrowecommerce')} <b>{$order_result.time.stop_stamp|l10n( 'date' )}</b> {'and includes'|i18n( 'extension/xrowecommerce')} {$order_array|count()} {if $order_array|count()|eq(1)}{'order'|i18n( 'extension/xrowecommerce')}{else}{'orders'|i18n( 'extension/xrowecommerce')}{/if}.</p>

<table width="100%" cellspacing="0" cellpadding="0" border="0" class="no_print">
        <td colspan="4">
        <label for="country">{'Country'|i18n( 'extension/xrowecommerce')}</label>
            <select name="country" id="country">
             <option value="">{'Any'|i18n( 'extension/xrowecommerce')}</option>
                {foreach $countries as $country_list_item}
                 <option value="{$country_list_item.Alpha3}" {if and( $country|ne(''), eq( $country, $country_list_item.Alpha3 ))} selected="selected"{/if}>
                    {$country_list_item.Name|wash}
                </option>
                {/foreach}
            </select>
        </td>
        <td colspan="4">
        {if $states}
        <label for="state">{'State'|i18n( 'extension/xrowecommerce')}</label>
            <select name="state" id="state">
             <option value="">{'Any'|i18n( 'extension/xrowecommerce')}</option>
                {foreach $states as $key => $state_list_item}
                 <option value="{$key}" {if and( $state|ne(''), eq( $state, $key ))} selected="selected"{/if}>
                    {$state_list_item|wash}
                </option>
                {/foreach}
            </select>
        {/if}
        </td>
</tr>
    <tr>
        <td>&nbsp;</td>
        <td>{'Year'|i18n( 'extension/xrowecommerce')}</td>
        <td>{'Month'|i18n( 'extension/xrowecommerce')}</td>
        <td>{'Day'|i18n( 'extension/xrowecommerce')}</td>
        <td>&nbsp;</td>
        <td>{'Year'|i18n( 'extension/xrowecommerce')}</td>
        <td>{'Month'|i18n( 'extension/xrowecommerce')}</td>
        <td>{'Day'|i18n( 'extension/xrowecommerce')}</td>
        <td>&nbsp;</td>
    </tr>
<tr>
<td>{'From'|i18n( 'extension/xrowecommerce')}: &nbsp;</td>
<td style="text-align: center;">
<select name="StartYear" title="{'Select the year for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {if eq($year,0)}selected="selected"{/if}>[{'All years'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {foreach $year_list as $StartYearValue}
        <option value="{$StartYearValue}" {if eq($StartYearValue,$startYear)}selected="selected"{/if}>{$StartYearValue}</option>
    {/foreach}
</select>
</td>
<td style="text-align: center;">
<select name="StartMonth" title="{'Select the start month for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {if eq($startMonth,0)}selected="selected"{/if}>[{'Months'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {foreach $month_list as $StartMonthItem}
        <option value="{$StartMonthItem.value}" {if eq($StartMonthItem.value,$startMonth)}selected="selected"{/if}>{$StartMonthItem.name|wash}</option>
    {/foreach}
</select>
</td>
<td style="text-align: center;">
<select name="StartDay" title="{'Select the start day for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {if eq($startDay,0)}selected="selected"{/if}>[{'Days'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {foreach $day_list as $DayItem}
        <option value="{$DayItem.value}" {if eq($DayItem.value,$startDay)}selected="selected"{/if}>{$DayItem.name|wash}</option>
    {/foreach}
</select>
</td>
<td style="text-align: center;">{'till'|i18n( 'extension/xrowecommerce')}: &nbsp;</td>
<td style="text-align: center;">
<select name="StopYear" title="{'Select the year till which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {if eq($stopyear,0)}selected="selected"{/if}>[{'Years'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {foreach $year_list as $StopYearValue}
        <option value="{$StopYearValue}" {if eq($StopYearValue,$stopYear)}selected="selected"{/if}>{$StopYearValue}</option>
    {/foreach}
</select>
</td>
<td style="text-align: center;">
<select name="StopMonth" title="{'Select the stop month for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {if eq($stopMonth,0)}selected="selected"{/if}>[{'Months'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {foreach $month_list as $StopMonthItem}
        <option value="{$StopMonthItem.value}" {if eq($StopMonthItem.value,$stopMonth)}selected="selected"{/if}>{$StopMonthItem.name|wash}</option>
    {/foreach}
</select>
</td>
<td style="text-align: center;">
<select name="StopDay" title="{'Select the start day for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {if eq($stopDay,0)}selected="selected"{/if}>[{'Days'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {foreach $day_list as $DayItem}
        <option value="{$DayItem.value}" {if eq($DayItem.value,$stopDay)}selected="selected"{/if}>{$DayItem.name|wash}</option>
    {/foreach}
</select>
</td>
<td style="text-align: center;">
<input class="button" type="submit" name="View" value="{'Show'|i18n( 'design/admin/shop/orderstatistics' )}" title="{'Update the list using the values specified by the menus on the left.'|i18n( 'design/admin/shop/orderstatistics' )}" />
</td>
</tr>
</table>
<div class="block">
<table class="list" width="100%" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <th class="wide">{'Order'|i18n( 'extension/xrowecommerce')}</th>
        <th class="wide">{'Date'|i18n( 'extension/xrowecommerce')}</th>
        <th class="wide">{'Destination'|i18n( 'extension/xrowecommerce')}</th>
        <th class="number">{'Total (inc Tax)'|i18n( 'extension/xrowecommerce')}</th>
        <th class="number">{'Total (ex Tax)'|i18n( 'extension/xrowecommerce')}</th>
        <th class="number">{'Total Tax'|i18n( 'extension/xrowecommerce')}</th>
        <th class="number">{'Total Products'|i18n( 'extension/xrowecommerce')}</th>
        <th class="number">{'Total Shipping/handling'|i18n( 'extension/xrowecommerce')}</th>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="number"><b>{$order_result.price.total_inc_price|l10n( 'currency' )}</b></td>
        <td class="number"><b>{$order_result.price.total_ex_price|l10n( 'currency' )}</b></td>
        <td class="number"><b>{$order_result.price.total_inc_price|sub($order_result.price.total_ex_price)|l10n( 'currency' )} </b></td>
        <td class="number"><b>{$order_result.price.total_collection_inc_price|l10n( 'currency' )}</b></td>
        <td class="number"><b>{$order_result.price.total_order_inc_price|l10n( 'currency' )}</b></td>
    </tr>


    {if count($order_array)|gt(0)}
    {def $i=0}
    {def $page_count=1}
        {foreach $order_array as $key => $order sequence array( 'bgdark', 'bglight' ) as $style}
        {set $i=inc($i)}
            <tr class="{$style}">
                <td><a href={concat("/shop/orderview/", $key)|ezurl()} target="_blank">{$order.order_nr}</a></td>
                <td>{$order.created|l10n( 'shortdate' )}</td>
                <td>{$destination|wash}</td>
                <td class="number">{$order.total_inc_vat|l10n( 'currency' )}</td>
                <td class="number">{$order.total_ex_vat|l10n( 'currency' )}</td>
                <td class="number">{$order.total_inc_vat|sub($order.total_ex_vat)|l10n( 'currency' )}</td>
                <td class="number">{$order.collectionsum.price_inc_tax|l10n( 'currency' )}</td>
                <td class="number">{$order.ordersum.price_inc_tax|l10n( 'currency' )}</td>
            </tr>
            {if or( and($i|eq(40), $page_count|eq(1)), and($i|eq(50), $page_count|gt(1) ) ) }
            {set $i=0}
            {set $page_count=inc($page_count)}
            <tr style="page-break-before:always;">
                <th>{'Order'|i18n( 'extension/xrowecommerce')}</th>
                <th>{'Date'|i18n( 'extension/xrowecommerce')}</th>
                <th>{'Destination'|i18n( 'extension/xrowecommerce')}</th>
                <th>{'Total (inc Tax)'|i18n( 'extension/xrowecommerce')}</th>
                <th>{'Total (ex Tax)'|i18n( 'extension/xrowecommerce')}</th>
                <th>{'Total Tax'|i18n( 'extension/xrowecommerce')}</th>
                <th>{'Total Products'|i18n( 'extension/xrowecommerce')}</th>
                <th>{'Total Shipping/handling'|i18n( 'extension/xrowecommerce')}</th>
            </tr>

            {/if}
        {/foreach}
    {else}
        <tr class="{$style}">
            <td colspan="11">{'Sorry, no order in the selected Month to show.'|i18n( 'extension/xrowecommerce')}</td>
        </tr>
    {/if}
</table>
</div>
<div class="block">
<div class="break"></div>
</div>
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>
