<textarea class="xrowproducttext" rows="1" cols="20" id="XrowProductVariation_{$attribute.id}_{$line}_{$data.template_item.attribute.identifier}" name="XrowProductVariation[{$attribute.id}][{$line}][{$data.template_item.attribute.identifier}]">{$content|wash}</textarea>
{if is_set( $error[$line][$data.template_item.attribute.identifier]['required'] )}
<div>{"Please enter text."|i18n('extension/xrowecommerce/productvariation')}</div>
{/if}