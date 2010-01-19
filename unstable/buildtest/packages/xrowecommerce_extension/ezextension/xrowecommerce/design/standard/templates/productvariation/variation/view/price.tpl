{def $first=true()
     $country_array=ezini( 'PriceSettings', 'CountryArray', 'xrowproduct.ini' )
     $i=0
     $j=0
     $price_item=array()
}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="xrowpricetable">
<tbody id="XrowProductVariation_{$attribute.id}_{$line}_{$data.template_item.attribute.identifier}_to">
{foreach $content as $amount => $price_array}
{set $first=true()}
<tr class="{if $i|eq(0)}{set $i=1}bglight{else}{set $i=0}bgdark{/if}"><td>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="xrowpricetable">
<tbody>

{foreach $country_array as $country => $currency}
{if is_set( $price_array[$country] )}{set $price_item=$price_array[$country]}{else}{set $price_item=hash( 'country', $country,
                                                                                                          'price', '' )}{/if}
<tr>

    <td>{if $first}<span title="{"Amount"|i18n('extension/xrowecommerce/productvariation')|wash}">#</span>{else}&nbsp;{/if}</td>
    <td>{if $first}{$amount|wash}{/if}</td>

    <td>{$price_item.country|wash}:</td>
    <td>{if $price_item.price|ne('')}{$price_item.price|l10n('number')}{/if}</td>
</tr>
{set $first=false()}
{/foreach}
</tbody>
</table>
</td>
</tr>
{set $j=$j|inc()}
{/foreach}
</tbody>
</table>

