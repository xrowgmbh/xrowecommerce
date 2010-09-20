{def $first=true()
     $country_array=fetch( 'shop', 'currency_list' )
     $i=0
     $j=0
     $price_item=array()
}
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="xrowpricetable">
<tbody id="XrowProductVariation_{$attribute.id}_{$line}_{$data.template_item.attribute.identifier}_to">
{foreach $content as $amount => $price_array}
{set $first=true()}
<tr class="{if $i|eq(0)}{set $i=1}bglight{else}{set $i=0}bgdark{/if}">
    <td>
<table cellpadding="0" cellspacing="0" border="0" width="100%" class="xrowpricetable">
<tbody>

{foreach $country_array as $country => $currency}
{if is_set( $price_array[$country] )}{set $price_item=$price_array[$country]}{else}{set $price_item=hash( 'country', $country,
                                                                                                          'price', '' )}{/if}
<tr>
    <td>{if and($amount|ne(1),$first)}<img src={"trash-icon-16x16.gif"|ezimage} alt="{"Delete line"|i18n( 'extension/xrowecommerce/productvariation' )|wash}"  width="16" height="16" onclick="return this.parentNode.parentNode.parentNode.parentNode.removeChild( this.parentNode.parentNode.parentNode );" />{else}<img src={"1x1.gif"|ezimage} width="16" height="16" alt="" />{/if}</td>

{if $data.template_item.sliding}
    <td>{if $first}<span title="{"Amount"|i18n('extension/xrowecommerce/productvariation')|wash}">#</span>{else}&nbsp;{/if}</td>
    <td>{if $first}<input name="XrowProductVariation[{$attribute.id}][{$line}][{$data.template_item.attribute.identifier}][amount][{$j|wash}]" class="xrowproductpriceamount" type="text" size="5" maxlength="15" value="{$amount|wash}" />{if is_set( $error[$line][$data.template_item.attribute.identifier]['amount'][$amount] )}<span class="warning">*</span>{/if}{else}&nbsp;{/if}</td>
{else}
    <td>{if $first}<span title="{"Amount"|i18n('extension/xrowecommerce/productvariation')|wash}">#</span>{else}&nbsp;{/if}</td>
    <td>{if $first}<input name="XrowProductVariation[{$attribute.id}][{$line}][{$data.template_item.attribute.identifier}][amount][{$j|wash}]" class="xrowproductpriceamount" type="text" size="5" maxlength="15" value="{$amount|wash}" {if $amount|eq(1)}readonly="readonly" {/if} />{if is_set( $error[$line][$data.template_item.attribute.identifier]['amount'][$amount] )}<span class="warning">*</span>{/if}{else}&nbsp;{/if}</td>
{/if}
    <td>{$price_item.country|wash}:</td>
    <td>{$country_array[$price_item.country].symbol|wash} <input class="xrowproductprice" type="text" size="5" maxlength="15" id="XrowProductVariation_{$attribute.id}_{$line}_{$data.template_item.attribute.identifier}_{$price_item.country|wash}_{$amount}" name="XrowProductVariation[{$attribute.id}][{$line}][{$data.template_item.attribute.identifier}][{$price_item.country|wash}][{$j|wash}]" value="{if or( $price_item.price|eq(''), is_set( $error[$line][$data.template_item.attribute.identifier]['price'][$j][$price_item.country] ) )}{$price_item.price|wash}{else}{$price_item.price|l10n('number')}{/if}" />{if is_set( $error[$line][$data.template_item.attribute.identifier]['price'][$j][$price_item.country] )}<span class="warning">*</span>{/if}</td>
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

{if is_set( $error[$line][$data.template_item.attribute.identifier]['required'] )}
<div class="warning">{"Input required."|i18n('extension/xrowecommerce/productvariation')}</div>
{/if}

{if is_set( $error[$line][$data.template_item.attribute.identifier]['not_valid'] )}
<div class="warning">{"Please enter valid prices. Wrong prices are marked with a red *"|i18n('extension/xrowecommerce/productvariation')}</div>
{/if}

{if is_set( $error[$line][$data.template_item.attribute.identifier]['amount_1'] )}
<div class="warning">{"Please enter a price for 1 item."|i18n('extension/xrowecommerce/productvariation')}</div>
{/if}

{if is_set( $error[$line][$data.template_item.attribute.identifier]['amount'] )}
<div class="warning">{"Please correct the amount marked with a red *"|i18n('extension/xrowecommerce/productvariation')}</div>
{/if}


{if $data.template_item.sliding}
    <div><input type="button" class="button" name="PriceButton{$attribute.id}_{$line}_{$data.template_item.attribute.identifier}" value="{"Add price"|i18n('extension/xrowecommerce/productvariation')}" onclick="return xrowaddpriceline( 'XrowProductVariation_{$attribute.id}_price_{$data.template_item.attribute.identifier}_from', 'XrowProductVariation_{$attribute.id}_{$line}_{$data.template_item.attribute.identifier}_to', {$line} );" /></div>
{/if}
{undef}