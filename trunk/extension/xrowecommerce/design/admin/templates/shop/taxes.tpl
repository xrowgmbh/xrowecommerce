<form name="orderstatistics" method="post" action={'orderedit/taxes'|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Order statistic'|i18n( 'design/admin/shop/orderlist')} ({$order_array|count()} {if $order_array|count()|eq(1)}{'order'|i18n( 'extension/xrowecommerce')}{else}{'orders'|i18n( 'extension/xrowecommerce')}{/if}) </h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">
<div class="context-attributes">
<p>
{def $timestamp=currentdate()}
{$timestamp|l10n( 'datetime' )}
<br /><br />
{'Selected period is from'|i18n( 'extension/xrowecommerce')} <b>{$order_result.time.start_stamp|l10n( 'date' )}</b> {'till'|i18n( 'extension/xrowecommerce')} <b>{$order_result.time.stop_stamp|l10n( 'date' )}</b> {'and includes'|i18n( 'extension/xrowecommerce')} {$order_array|count()} {if $order_array|count()|eq(1)}{'order'|i18n( 'extension/xrowecommerce')}{else}{'orders'|i18n( 'extension/xrowecommerce')}{/if}.<br />
<br />
{literal}
<style type="text/css">
@media print
{
  #hidebyprint
  {
    display: none;
  }
}
</style>
{/literal}
<table width="100%" cellspacing="0" cellpadding="0" border="0" id="hidebyprint">
    <tr>
        <td style="text-align: center;">&nbsp;</td>
        <td style="text-align: center;">{'Year'|i18n( 'extension/xrowecommerce')}</td>
        <td style="text-align: center;">{'Month'|i18n( 'extension/xrowecommerce')}</td>
        <td style="text-align: center;">{'Day'|i18n( 'extension/xrowecommerce')}</td>
        <td style="text-align: center;">&nbsp;</td>
        <td style="text-align: center;">{'Year'|i18n( 'extension/xrowecommerce')}</td>
        <td style="text-align: center;">{'Month'|i18n( 'extension/xrowecommerce')}</td>
        <td style="text-align: center;">{'Day'|i18n( 'extension/xrowecommerce')}</td>
        <td style="text-align: center;">&nbsp;</td>
    </tr>
<tr>
<td>{'From'|i18n( 'extension/xrowecommerce')}: &nbsp;</td>
<td style="text-align: center;">
<select name="StartYear" title="{'Select the year for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {section show=eq($year,0)}selected="selected"{/section}>[{'All years'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=StartYearValue loop=$year_list}
        <option value="{$StartYearValue}" {section show=eq($StartYearValue,$startYear)}selected="selected"{/section}>{$StartYearValue}</option>
    {/section}
</select>
</td>
<td style="text-align: center;">
<select name="StartMonth" title="{'Select the start month for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {section show=eq($startMonth,0)}selected="selected"{/section}>[{'Months'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=StartMonthItem loop=$month_list}
        <option value="{$StartMonthItem.value}" {section show=eq($StartMonthItem.value,$startMonth)}selected="selected"{/section}>{$StartMonthItem.name|wash}</option>
    {/section}
</select>
</td>
<td style="text-align: center;">
<select name="StartDay" title="{'Select the start day for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {section show=eq($startDay,0)}selected="selected"{/section}>[{'Days'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=DayItem loop=$day_list}
        <option value="{$DayItem.value}" {section show=eq($DayItem.value,$startDay)}selected="selected"{/section}>{$DayItem.name|wash}</option>
    {/section}
</select>
</td>
<td style="text-align: center;">{'till'|i18n( 'extension/xrowecommerce')}: &nbsp;</td>
<td style="text-align: center;">
<select name="StopYear" title="{'Select the year till which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {section show=eq($stopyear,0)}selected="selected"{/section}>[{'Years'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=StopYearValue loop=$year_list}
        <option value="{$StopYearValue}" {section show=eq($StopYearValue,$stopYear)}selected="selected"{/section}>{$StopYearValue}</option>
    {/section}
</select>
</td>
<td style="text-align: center;">
<select name="StopMonth" title="{'Select the stop month for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {section show=eq($stopMonth,0)}selected="selected"{/section}>[{'Months'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=StopMonthItem loop=$month_list}
        <option value="{$StopMonthItem.value}" {section show=eq($StopMonthItem.value,$stopMonth)}selected="selected"{/section}>{$StopMonthItem.name|wash}</option>
    {/section}
</select>
</td>
<td style="text-align: center;">
<select name="StopDay" title="{'Select the start day for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {section show=eq($stopDay,0)}selected="selected"{/section}>[{'Days'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=DayItem loop=$day_list}
        <option value="{$DayItem.value}" {section show=eq($DayItem.value,$stopDay)}selected="selected"{/section}>{$DayItem.name|wash}</option>
    {/section}
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
        <th style="text-align: right;"><b>{'Order'|i18n( 'extension/xrowecommerce')}</b></th>
        <th style="text-align: right;"><b>{'Date'|i18n( 'extension/xrowecommerce')}</b></th>
        <th style="text-align: right;"><b>{'State'|i18n( 'extension/xrowecommerce')}</b></th>
        <th style="text-align: right;"><b>{'Total (inc Tax)'|i18n( 'extension/xrowecommerce')}</b></th>
        <th style="text-align: right;"><b>{'Total (ex Tax)'|i18n( 'extension/xrowecommerce')}</b></th>
        <th style="text-align: right;"><b>{'Total Tax'|i18n( 'extension/xrowecommerce')}</b></th>
        <th style="text-align: right;"><b>{'Total NY Tax'|i18n( 'extension/xrowecommerce')}</b></th>
        <th style="text-align: right;"><b>{'Total CT Tax'|i18n( 'extension/xrowecommerce')}</b></th>
        <th style="text-align: right;"><b>{'Total Products'|i18n( 'extension/xrowecommerce')}</b></th>
        <th style="text-align: right;"><b>{'Total Shipping/handling'|i18n( 'extension/xrowecommerce')}</b></th>
    </tr>
    <tr>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;">&nbsp;</td>
        <td style="text-align: right;"><b>{$order_result.price.total_inc_price|l10n( 'currency' )}</b></td>
        <td style="text-align: right;"><b>{$order_result.price.total_ex_price|l10n( 'currency' )}</b></td>
        <td style="text-align: right;"><b>{$order_result.price.total_inc_price|sub($order_result.price.total_ex_price)|l10n( 'currency' )} </b></td>
        <td style="text-align: right;"><b>{$order_result.price.NY|l10n( 'currency' )}</b></td>
        <td style="text-align: right;"><b>{$order_result.price.CT|l10n( 'currency' )}</b></td>
        <td style="text-align: right;"><b>{$order_result.price.total_collection_inc_price|l10n( 'currency' )}</b></td>
        <td style="text-align: right;"><b>{$order_result.price.total_order_inc_price|l10n( 'currency' )}</b></td>
    </tr>
    <tr>
        <td colspan="11"><hr /></td>
    </tr>

    {if count($order_array)|gt(0)}
    {def $i=0}
    {def $page_count=1}
        {foreach $order_array as $key => $order sequence array( 'bgdark', 'bglight' ) as $style}
        {set $i=inc($i)}
            <tr class="{$style}">
                <td style="text-align: right;"><a href={concat("/shop/orderview/", $key)|ezurl()} target="_blank">{$order.order_nr}</a></td>
                <td style="text-align: right;">{$order.created|l10n( 'shortdate' )}</td>
                <td style="text-align: right;">
                    {if $order.state|eq("NY")}<span style="color:green;"><b>{$order.state}</b></span>{/if}
                    {if $order.state|eq("CT")}<span style="color:red;"><b>{$order.state}</b></span>{/if}
                </td>
                <td style="text-align: right;">{$order.total_inc_vat|l10n( 'currency' )}</td>
                <td style="text-align: right;">{$order.total_ex_vat|l10n( 'currency' )}</td>
                <td style="text-align: right;">{$order.total_inc_vat|sub($order.total_ex_vat)|l10n( 'currency' )}</td>
                <td style="text-align: right;">
                    {if $order.state|eq("NY")}{$order.total_inc_vat|sub($order.total_ex_vat)|l10n( 'currency' )}{else}&nbsp;{/if}
                </td>
                <td style="text-align: right;">
                    {if $order.state|eq("CT")}{$order.total_inc_vat|sub($order.total_ex_vat)|l10n( 'currency' )}{else}&nbsp;{/if}
                </td>
                <td style="text-align: right;">{$order.collectionsum.price_inc_tax|l10n( 'currency' )}</td>
                <td style="text-align: right;">{$order.ordersum.price_inc_tax|l10n( 'currency' )}</td>
            </tr>
            {if or( and($i|eq(40), $page_count|eq(1)), and($i|eq(50), $page_count|gt(1) ) ) }
            {set $i=0}
            {set $page_count=inc($page_count)}
            <tr style="page-break-before:always;">
                <th style="text-align: right;"><b>{'Order'|i18n( 'extension/xrowecommerce')}</b></th>
                <th style="text-align: right;"><b>{'Date'|i18n( 'extension/xrowecommerce')}</b></th>
                <th style="text-align: right;"><b>{'State'|i18n( 'extension/xrowecommerce')}</b></th>
                <th style="text-align: right;"><b>{'Total (inc Tax)'|i18n( 'extension/xrowecommerce')}</b></th>
                <th style="text-align: right;"><b>{'Total (ex Tax)'|i18n( 'extension/xrowecommerce')}</b></th>
                <th style="text-align: right;"><b>{'Total Tax'|i18n( 'extension/xrowecommerce')}</b></th>
                <th style="text-align: right;"><b>{'Total NY Tax'|i18n( 'extension/xrowecommerce')}</b></th>
                <th style="text-align: right;"><b>{'Total CT Tax'|i18n( 'extension/xrowecommerce')}</b></th>
                <th style="text-align: right;"><b>{'Total Products'|i18n( 'extension/xrowecommerce')}</b></th>
                <th style="text-align: right;"><b>{'Total Shipping/handling'|i18n( 'extension/xrowecommerce')}</b></th>
            </tr>
            <tr>
                <td colspan="11"><hr /></td>
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
{*$order_result|attribute(show)*}
{*$order_array|attribute(show)*}
<div class="break"></div>
</div>
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

<div class="block">
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>
