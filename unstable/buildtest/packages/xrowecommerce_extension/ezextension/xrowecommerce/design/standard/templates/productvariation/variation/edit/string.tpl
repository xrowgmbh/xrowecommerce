<input class="xrowproductstring" type="text" size="15" maxlength="255" id="XrowProductVariation_{$attribute.id}_{$line}_{$data.template_item.attribute.identifier}" name="XrowProductVariation[{$attribute.id}][{$line}][{$data.template_item.attribute.identifier}]" value="{$content|wash}" />
{if is_set( $error[$line][$data.template_item.attribute.identifier]['required'] )}
<div>{"Input required."|i18n('extension/xrowecommerce/productvariation')}</div>
{/if}