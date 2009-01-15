{def $first=true()}
<div id="XrowProductVariation_{$attribute.id}_price_{$variation_attribute.attribute.identifier}_from">
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="xrowpricetable">
<tbody>
{foreach ezini( 'PriceSettings', 'CountryArray', 'xrowproduct.ini' ) as $countryISO => $currency}
<tr>
    <td>{if $first}<img src={"trash-icon-16x16.gif"|ezimage} alt="{"Delete line"|i18n( 'extension/xrowecommerce/productvariation' )|wash}"  width="16" height="16" onclick="return this.parentNode.parentNode.parentNode.parentNode.parentNode.removeChild( this.parentNode.parentNode.parentNode.parentNode );" />{else}<img src={"1x1.gif"|ezimage} width="16" height="16" alt="" />{/if}</td>
{if $variation_attribute.sliding}
    <td>{if $first}<span title="{"Amount"|i18n('extension/xrowecommerce/productvariation')|wash}">#</span>{else}&nbsp;{/if}</td>
    <td>{if $first}<input name="DefaultXrowProductVariation[{$attribute.id}][xxxrownumberxxx][{$variation_attribute.attribute.identifier}][amount][]" class="xrowproductpriceamount" type="text" size="5" maxlength="15" value="" />{else}&nbsp;{/if}</td>
{else}
    <td>{if $first}<span title="{"Amount"|i18n('extension/xrowecommerce/productvariation')|wash}">#</span>{else}&nbsp;{/if}</td>
    <td>{if $first}<input name="DefaultXrowProductVariation[{$attribute.id}][xxxrownumberxxx][{$variation_attribute.attribute.identifier}][amount][]" class="xrowproductpriceamount" type="text" size="5" maxlength="15" value="1" readonly="readonly" />{else}&nbsp;{/if}</td>
{/if}
    <td>{$countryISO|wash}:</td>
    <td><input  name="DefaultXrowProductVariation[{$attribute.id}][xxxrownumberxxx][{$variation_attribute.attribute.identifier}][{$countryISO|wash}][]" class="xrowproductprice" type="text" size="5" maxlength="15" value="" /></td>
    <td>{$currency|wash}</td>
</tr>
{set $first=false()}
{/foreach}
</tbody>
</table>
</div>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="xrowpricetable">
<tbody id="XrowProductVariation_{$attribute.id}_xxxrownumberxxx_{$variation_attribute.attribute.identifier}_to"></tbody>
</table>
<div><input type="button" class="button" name="PriceButton{$attribute.id}_xxxrownumberxxx_{$variation_attribute.attribute.identifier}" value="{"Add price"|i18n('extension/xrowecommerce/productvariation')}" onclick="return xrowaddpriceline( 'XrowProductVariation_{$attribute.id}_price_{$variation_attribute.attribute.identifier}_from', 'XrowProductVariation_{$attribute.id}_xxxrownumberxxx_{$variation_attribute.attribute.identifier}_to', xxxrownumberxxx );" /></div>