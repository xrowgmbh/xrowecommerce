<script language="JavaScript" type="text/javascript">
{literal}<!--
function resetOptionFields(){ezform=document.Statistics;ezform.StartDay.selectedIndex = '0';ezform.StopDay.selectedIndex = '0';ezform.View.focus()}
-->{/literal}
</script>
<form action={'/shop/statistics'|ezurl} method="post" name="Statistics">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Product statistics [%count]'|i18n( 'design/admin/shop/orderstatistics',, hash( '%count', $statistic_result[0].product_list|count  ) )}&nbsp;<a href="#base" style="color: #ffffff; text-decoration: none;">Base</a></h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>




{section show=$statistic_result[0].product_list}

{def $currency = false()
     $locale = false()
     $symbol = false()
     $quantity_text = ''
     $shipping_text = ''
     $sum_ex_vat_text = ''
     $sum_inc_vat_text = ''
     $sum_shipping_text = ''
     $br_tag = ''}


{set sum_ex_vat_text = ''
     sum_inc_vat_text = ''
     sum_shipping_text = ''
     br_tag = ''}

{foreach $statistic_result[0].total_sum_info as $currency_code => $info}

    {if $currency_code}
        {set currency = fetch( 'shop', 'currency', hash( 'code', $currency_code ) )}
    {else}
        {set currency = false()}
    {/if}
    {if $currency}
        {set locale = $currency.locale
             symbol = $currency.symbol}
    {else}
        {set locale = false()
             symbol = false()}
    {/if}

    {set sum_ex_vat_text = concat($sum_ex_vat_text, $br_tag, $info.sum_ex_vat|l10n( 'currency', $locale, $symbol )) }
    {set sum_inc_vat_text = concat($sum_inc_vat_text, $br_tag, $info.sum_inc_vat|l10n( 'currency', $locale, $symbol )) }
    {* set sum_shipping_text = concat($sum_shipping_text, $br_tag, $info.sum_shipping|l10n( 'currency', $locale, $symbol )) *}

    {set sum_shipping_text = concat($sum_shipping_text, $br_tag, $info.sum_shipping|l10n( 'currency', $locale, $symbol ))}

{*
    {set sum_shipping_text = $info.sum_shipping}

concat($sum_shipping_text, $br_tag, $info.sum_shipping|l10n( 'currency', $locale, $symbol ))}
*}
    {if $br_tag|not()}
        {set br_tag = '<br />'}
    {/if}

{* Debug: the total information *}
{* *<br /> {$info|attribute(show,1)}<hr /> *}

{/foreach}



{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">
<table class="list" cellspacing="0">
<tr>
        <th class="wide" colspan="2">{'Totals'|i18n( 'design/admin/shop/orderstatistics' )}</th>
        <th class="tight" align="right">{'Shipping'|i18n( 'design/admin/shop/orderstatistics' )}</th>
        <th class="tight" align="right">{'Products (ex. VAT)'|i18n( 'design/admin/shop/orderstatistics' )}</th>
        <th class="tight" align="right">{'Products (inc. VAT)'|i18n( 'design/admin/shop/orderstatistics' )}</th>
</tr>
<tr>
    <td class="name" colspan="2"><a name="totals" style="text-decoration: none;">{concat( '<strong>', '&nbsp;'|i18n( 'design/admin/shop/orderstatistics' ), '</strong>' )}</a></td>
    <td class="number" align="right"><strong>{$sum_shipping_text}</strong></td>
    <td class="number" align="right"><strong>{$sum_ex_vat_text}</strong></td>
    <td class="number" align="right"><strong>{$sum_inc_vat_text}</strong></td>
</tr>

<tr>
<th class="" colspan="5">

<select name="StartYear" title="{'Select the year for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {section show=eq($year,0)}selected="selected"{/section}>[{'All years'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=StartYearValue loop=$year_list}
        <option value="{$StartYearValue}" {section show=eq($StartYearValue,$year)}selected="selected"{/section}>{$StartYearValue}</option>
    {/section}
</select>

&nbsp;|&nbsp;

{*
<select name="StopYear" title="{'Select the year for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {section show=eq($year,0)}selected="selected"{/section}>[{'All years'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=StopYearValue loop=$year_list}
        <option value="{$StopYearValue}" {section show=eq($StopYearValue,$year)}selected="selected"{/section}>{$StopYearValue}</option>
    {/section}
</select>
*}

<select name="StartMonth" title="{'Select the start month for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {section show=eq($startMonth,0)}selected="selected"{/section}>[{'All months'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=StartMonthItem loop=$month_list}
        <option value="{$StartMonthItem.value}" {section show=eq($StartMonthItem.value,$startMonth)}selected="selected"{/section}>{$StartMonthItem.name|wash}</option>
    {/section}
</select>

<select name="StartDay" title="{'Select the start day for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {section show=eq($startDay,0)}selected="selected"{/section}>[{'All Days'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=DayItem loop=$day_list}
        <option value="{$DayItem.value}" {section show=eq($DayItem.value,$startDay)}selected="selected"{/section}>{$DayItem.name|wash}</option>
    {/section}
</select>

&nbsp;|&nbsp;

<select name="StopMonth" title="{'Select the stop month for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {section show=eq($stopMonth,0)}selected="selected"{/section}>[{'All months'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=StopMonthItem loop=$month_list}
        <option value="{$StopMonthItem.value}" {section show=eq($StopMonthItem.value,$stopMonth)}selected="selected"{/section}>{$StopMonthItem.name|wash}</option>
    {/section}
</select>

<select name="StopDay" title="{'Select the start day for which you wish to view statistics.'|i18n( 'design/admin/shop/orderstatistics' )}">
    <option value="0" {section show=eq($stopDay,0)}selected="selected"{/section}>[{'All Days'|i18n( 'design/admin/shop/orderstatistics' )}]</option>
    {section var=DayItem loop=$day_list}
        <option value="{$DayItem.value}" {section show=eq($DayItem.value,$stopDay)}selected="selected"{/section}>{$DayItem.name|wash}</option>
    {/section}
</select>

&nbsp;
<input class="button" type="submit" name="View" value="{'Show'|i18n( 'design/admin/shop/orderstatistics' )}" title="{'Update the list using the values specified by the menus on the left.'|i18n( 'design/admin/shop/orderstatistics' )}" />

&nbsp;<a href="javascript:resetOptionFields();">Reset</a>
</th>
</tr>

<tr>
	<th class="wide" colspan="2">{'Product'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th class="tight" align="right">{'Quantity'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th class="tight" align="right">{'Total (ex. VAT)'|i18n( 'design/admin/shop/orderstatistics' )}</th>
	<th class="tight" align="right">{'Total (inc. VAT)'|i18n( 'design/admin/shop/orderstatistics' )}</th>
{*	<th class="tight" align="right">{'Shipping Total'|i18n( 'design/admin/shop/orderstatistics' )}</th> *}
</tr>


{section var=Products loop=$statistic_result[0].product_list sequence=array( bglight, bgdark )}

    {set quantity_text = ''
         sum_ex_vat_text = ''
         sum_inc_vat_text = ''
         sum_shipping_text = ''
         shipping_text = ''
         br_tag = ''}

    {foreach $Products.product_info as $currency_code => $info}
        {if $currency_code}
            {set currency = fetch( 'shop', 'currency', hash( 'code', $currency_code ) )}
        {else}
            {set currency = false()}
        {/if}

        {if $currency}
            {set locale = $currency.locale
                 symbol = $currency.symbol}
        {else}
            {set locale = false()
                 symbol = false()}
        {/if}

        {set quantity_text = concat( $quantity_text, $br_tag, $info.sum_count) }
        {set sum_ex_vat_text = concat($sum_ex_vat_text, $br_tag, $info.sum_ex_vat|l10n( 'currency', $locale, $symbol )) }
        {set sum_inc_vat_text = concat($sum_inc_vat_text, $br_tag, $info.sum_inc_vat|l10n( 'currency', $locale, $symbol ))}
        {set shipping_text = concat( $shipping_text, $br_tag, $info.sum_shipping) }


{* Debug: the product stat information *}

{* $info|attribute(show,1) *}

{*
**<br /> {$info|attribute(show,1)}<hr />
**<br /> {$Products.product|attribute(show,1)}<hr />

** {$info|attribute(show,1)}

    {$Products.product_info|attribute(show,1)}<hr />
    {$Products.product|attribute(show,1)}

    {$statistic_result[0].total_sum_info.sum_inc_vat}<hr />
*}

        {if $br_tag|not()}
            {set br_tag = '<br />'}
        {/if}

    {/foreach}

    <tr class="{$Products.sequence}">
        {if and( $Products.product, $Products.product.main_node )}
            {let node_url=$Products.product.main_node.url_alias}
                <td class="name" colspan="2">{$Products.product.class_identifier|class_icon( small, $Products.product.class_name )}&nbsp;{section show=$node_url}<a href={$node_url|ezurl}>{/section}{$Products.product.name|wash}{section show=$node_url}</a>{/section}</td>
            {/let}
        {else}
            <td class="name" colspan="2">{false()|class_icon( small )}&nbsp;{$Products.name|wash}</td>
        {/if}
        <td class="number" align="right">{$quantity_text}</td>
        <td class="number" align="right">{$sum_ex_vat_text}</td>
        <td class="number" align="right">{$sum_inc_vat_text}</td>
{*        <td class="number" align="right">{$shipping_text}</td> *}
    </tr>

{/section}

</table>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block" align="right">
&nbsp;
{* <a name="base" style="text-decoration: none;">&nbsp;</a><a href="#top">Return to Top</a> *}
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

{undef}

{section-else}
<div class="block">
<p>{'The list is empty.'|i18n( 'design/admin/shop/orderstatistics' )}
</div>
{/section}

{* DESIGN: Content END *}</div></div></div>



</form>
