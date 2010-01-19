<input class="xrowproductdate" type="text" size="10" maxlength="10" id="XrowProductVariation_{$attribute.id}_{$line}_{$data.template_item.attribute.identifier}" name="XrowProductVariation[{$attribute.id}][{$line}][{$data.template_item.attribute.identifier}]" value="{cond( $content.is_valid, concat( $content.year, '-', $content.month, '-', $content.day ), $content.content )}" />
{if is_set( $error[$line][$data.template_item.attribute.identifier]['required'] )}
<div>{"Input required."|i18n('extension/xrowecommerce/productvariation')}</div>
{/if}

{if is_set( $error[$line][$data.template_item.attribute.identifier]['not_valid'] )}
<div>{"Please enter a valid date."|i18n('extension/xrowecommerce/productvariation')}</div>
{/if}

