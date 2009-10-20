{def $related_purchase=fetch( 'shop', 'related_purchase', hash( 'contentobject_id', $node.object.id, 'limit', 2 ) )}
{if $related_purchase}
    <div class="related-xrow-products">
        <h2>{'People who bought this also bought'|i18n( 'extension/xrowecommerce' )}</h2>
        {foreach $related_purchase as $product}
            <span>{content_view_gui view=text_linked content_object=$product}</span>
            <p class="price">{$product.data_map.price.content.price|l10n(currency)}</p>
        {/foreach}
    </div>
{/if}
{undef $related_purchase}