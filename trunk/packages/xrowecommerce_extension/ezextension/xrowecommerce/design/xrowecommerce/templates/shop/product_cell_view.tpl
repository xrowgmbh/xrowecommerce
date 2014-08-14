{* Documentation
Variables in this template

$view is used to deftermine from which template this template has been included.
possible values are "basket", "confirmorder" or "orderview"

$product_item is the current product in the cart

*}
<div class="product-cell">
{def $image=""}
{def $type=""}
{def $prod=fetch( 'content', 'node', hash( 'node_id', $product_item.node_id ) )}

{if $product_item.item_object.option_list|count|gt(0)}
    {if $product_item.item_object.contentobject.data_map.options.data_type_string|eq('ezmultioption')}
        <div class="product-image">
            {if $product_item.item_object.contentobject.data_map.image.has_content}
                {set $type=$product_item.item_object.contentobject.data_map.image.data_type_string}
                {switch match=$type}
                    {case match='ezobjectrelationlist'}
                        {set $image = fetch( 'content', 'object', hash( 'object_id', $product_item.item_object.contentobject.data_map.image.content.relation_list.0.contentobject_id ) )}
                        <img src={$image.data_map.image.content['product_tiny'].url|ezroot} width="{$image.data_map.image.content['product_tiny'].width}" height="{$image.data_map.image.content['product_tiny'].height}" alt="{$image.data_map.image.content['product_tiny'].text|wash(xhtml)}" title="{$image.data_map.image.content['product_tiny'].text|wash(xhtml)}" />
                    {/case}
                    {case match='ezimage'}
                        {set $image = $product_item.item_object.contentobject}
                        <img src={$image.data_map.image.content['product_tiny'].url|ezroot} width="{$image.data_map.image.content['product_tiny'].width}" height="{$image.data_map.image.content['product_tiny'].height}" alt="{$image.data_map.image.content['product_tiny'].text|wash(xhtml)}" title="{$image.data_map.image.content['product_tiny'].text|wash(xhtml)}" />
                    {/case}
                    {case}
                        no image<br />
                    {/case}
                {/switch}
            {else}
                <div class="product-image-unavialable">
                    <img src={'shop/nopic_tiny.jpg'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" />
                </div>
            {/if}
        </div>
        <p class="product-link">
            {if $view|eq('basket')}
                <a class="basketlink" href={concat("/content/view/full/",$prod.node_id)|ezurl}>{$prod.name|wash()}</a>
            {else}
                {$prod.name|wash()}
            {/if}
        </p>
        <p class="product-id">
            {'SKU'|i18n("extension/xrowecommerce")}:
            {if and(is_set($prod.data_map.variation.content.name), $prod.data_map.variation.content.name|ne(''))}
                {$prod.data_map.variation.content.name|wash()}
            {else}
                {$prod.data_map.product_id.content|wash()}
            {/if}
        </p>
        <p class="product-description">
            {def $vary=$product_item.item_object.contentobject.data_map.options.content.option_list[$product_item.item_object.option_list.0.option_item_id]}
            {$vary.comment}
        </p>
        {if $prod.data_map.weight}
            <p class="product-weight">
                {'Weight'|i18n("extension/xrowecommerce")}: {attribute_view_gui attribute=$prod.data_map.weight} {ezini( 'Settings', 'WeightUnit', 'shipping.ini' )|wash}
            </p>
        {/if}
        <p class="product-id">
            {'Variation'|i18n("extension/xrowecommerce")}:
            {foreach $product_item.item_object.option_list as $option_item}
                {$option_item.value}
            {/foreach}
        </p>
    {else}
        {foreach $product_item.item_object.option_list as $option_item}
            <div class="product-image">
                {if $product_item.item_object.contentobject.data_map.image.has_content}
                    {set $type=$product_item.item_object.contentobject.data_map.image.data_type_string}
                    {switch match=$type}
                        {case match='ezobjectrelationlist'}
                            {set $image = fetch( 'content', 'object', hash( 'object_id', $product_item.item_object.contentobject.data_map.image.content.relation_list.0.contentobject_id ) )}
                            <img src={$image.data_map.image.content['product_tiny'].url|ezroot} width="{$image.data_map.image.content['product_tiny'].width}" height="{$image.data_map.image.content['product_tiny'].height}" alt="{$image.data_map.image.content['product_tiny'].text|wash(xhtml)}" title="{$image.data_map.image.content['product_tiny'].text|wash()}" />
                        {/case}
                        {case match='ezimage'}
                            {set $image = $product_item.item_object.contentobject}
                            <img src={$image.data_map.image.content['product_tiny'].url|ezroot} width="{$image.data_map.image.content['product_tiny'].width}" height="{$image.data_map.image.content['product_tiny'].height}" alt="{$image.data_map.image.content['product_tiny'].text|wash(xhtml)}" title="{$image.data_map.image.content['product_tiny'].text|wash()}" />
                        {/case}
                        {case}
                            no image<br />
                        {/case}
                    {/switch}
                {else}
                    <div class="product-image-unavialable"><img src={'shop/nopic_tiny.jpg'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" /></div>
                {/if}
            </div>
            <p class="product-link">
                {if $view|eq('basket')}<a class="basketlink" href={concat("/content/view/full/",$prod.node_id)|ezurl}>{$prod.name|wash()}</a>{else}{$prod.name|wash()}{/if}
                 {foreach $prod.data_map.options.content.option_list as $key => $option}
                    {if $product_item.item_object.option_list[$key].option_item_id|eq($option.id)}
                         - {$option.comment|wash()}
                    {/if}
                {/foreach}
            </p>
            <p class="product-id">
                {'SKU'|i18n("extension/xrowecommerce")}:
                {if and(is_set($option_item.value), $option_item.value|ne(''))}
                    {$option_item.value}
                {else}
                    {$product_item.item_object.contentobject.data_map.product_id.content|wash()}
                {/if}
            </p>
            <p class="product-description">
                {foreach $prod.data_map.options.content.option_list as $key => $option}
                    {if $product_item.item_object.option_list[$key].option_item_id|eq($option.id)}
                        {$option.description|wash()}<br />
                    {/if}
                {/foreach}
            </p>
            <p class="product-weight">
                {'Weight'|i18n("extension/xrowecommerce")}:
                    {foreach $prod.data_map.options.content.option_list as $key => $option}
                        {if $product_item.item_object.option_list[$key].option_item_id|eq($option.id)}
                            {$option.weight|l10n()}
                        {/if}
                    {/foreach}
                {ezini( 'Settings', 'WeightUnit', 'shipping.ini' )|wash}
            </p>
        {/foreach}
    {/if}
{else}
    <div class="product-image">
        {if $product_item.item_object.contentobject.data_map.image.has_content}
            {set $type=$product_item.item_object.contentobject.data_map.image.data_type_string}
            {switch match=$type}
                {case match='ezobjectrelationlist'}
                    {set $image = fetch( 'content', 'object', hash( 'object_id', $product_item.item_object.contentobject.data_map.image.content.relation_list.0.contentobject_id ) )}
                    <img src={$image.data_map.image.content['product_tiny'].url|ezroot} width="{$image.data_map.image.content['product_tiny'].width}" height="{$image.data_map.image.content['product_tiny'].height}" alt="{$image.data_map.image.content['product_tiny'].text|wash(xhtml)}" title="{$image.data_map.image.content['product_tiny'].text|wash(xhtml)}" />
                {/case}
                {case match='ezimage'}
                    {set $image = $product_item.item_object.contentobject}
                    <img src={$image.data_map.image.content['product_tiny'].url|ezroot} width="{$image.data_map.image.content['product_tiny'].width}" height="{$image.data_map.image.content['product_tiny'].height}" alt="{$image.data_map.image.content['product_tiny'].text|wash(xhtml)}" title="{$image.data_map.image.content['product_tiny'].text|wash(xhtml)}" />
                {/case}
                {case}
                    no image<br />
                {/case}
            {/switch}
        {else}
            <div class="product-image-unavialable">
                <img src={'shop/nopic_tiny.jpg'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" />
            </div>
        {/if}
    </div>
    <p class="product-link">
        {if $view|eq('basket')}
            <a class="basketlink" href={concat("/content/view/full/",$prod.node_id)|ezurl}>{$prod.name|wash()}</a>
        {else}
            {$prod.name|wash()}
        {/if}
    </p>
    <p class="product-id">
        {'SKU'|i18n("extension/xrowecommerce")}:
        {if and(is_set($prod.data_map.variation.content.name), $prod.data_map.variation.content.name|ne(''))}
            {$prod.data_map.variation.content.name|wash()}
        {elseif is_set($prod.data_map.product_id)}
            {$prod.data_map.product_id.content|wash()}
        {else}
            {$prod.name|wash()}
        {/if}
    </p>
    {if is_set($product_item.item_object.contentobject.data_map.options)}
        <p class="product-description">
            {def $vary=$product_item.item_object.contentobject.data_map.options.content.option_list[$product_item.item_object.option_list.0.option_item_id]}
            {$vary.comment}
        </p>
    {/if}
    {if $prod.data_map.weight}
        <p class="product-weight">
        {'Weight'|i18n("extension/xrowecommerce")}: {attribute_view_gui attribute=$prod.data_map.weight} {ezini( 'Settings', 'WeightUnit', 'shipping.ini' )|wash}
        </p>
    {/if}
{/if}
</div>

