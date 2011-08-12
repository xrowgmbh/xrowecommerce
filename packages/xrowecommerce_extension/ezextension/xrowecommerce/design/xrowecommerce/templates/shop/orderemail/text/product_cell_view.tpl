{def $prod=fetch( 'content', 'node', hash( 'node_id', $product_item.node_id ) )}
{$product_item.item_count} x {$product_item.object_name} ({$product_item.price_inc_vat|l10n( 'currency', $locale, $symbol )})
{def $vary=$product_item.item_object.contentobject.data_map.variation.content.option_list[$ProductItem.item_object.option_list.0.option_item_id]}

{if $product_item.item_object.option_list|count|gt(0)}
{'SKU'|i18n("extension/xrowecommerce")}: {$product_item.item_object.contentobject.data_map.product_id.content|wash()}{$option_item.value}
{else}
{'SKU'|i18n("extension/xrowecommerce")}: {$prod.data_map.product_id.content|wash()}{$prod.data_map.variation.content.name|wash()}
{/if}

{"Total Price"|i18n("extension/xrowecommerce")}: {$product_item.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}
----------------------------------------------
