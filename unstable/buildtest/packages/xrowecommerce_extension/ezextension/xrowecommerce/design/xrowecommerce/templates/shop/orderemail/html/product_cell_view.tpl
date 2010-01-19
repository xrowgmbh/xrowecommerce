{def $prod=fetch( 'content', 'node', hash( 'node_id', $product_item.node_id ) )}
{$product_item.item_count} x {$product_item.object_name} ({$product_item.price_inc_vat|l10n( 'currency', $locale, $symbol )})
{def $vary=$product_item.item_object.contentobject.data_map.variation.content.option_list[$ProductItem.item_object.option_list.0.option_item_id]}

{if $product_item.item_object.option_list|count|gt(0)}
{'SKU number'|i18n("extension/xrowecommerce")}: {$product_item.item_object.contentobject.data_map.product_id.content|wash()}{$option_item.value}
{else}
{'SKU number'|i18n("extension/xrowecommerce")}: {$prod.data_map.product_id.content|wash()}{$prod.data_map.variation.content.name|wash()}
{/if}

{"Total Price"|i18n("extension/xrowecommerce")}: {$product_item.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}
----------------------------------------------

<table class="product-cell">
{def $image=""}
{def $type=""}
{def $prod=fetch( 'content', 'node', hash( 'node_id', $product_item.node_id ) )}
{if $product_item.item_object.option_list|count|gt(0)}
    {def $vary=$product_item.item_object.contentobject.data_map.variation.content.option_list[$product_item.item_object.option_list.0.option_item_id]}
    {foreach $product_item.item_object.option_list as $option_item}
    
<tr>
<td>
                   {if $vary.image|is_object(true)}
                       {if $vary.image.current.data_map.image.has_content}
                               <img src={$vary.image.current.data_map.image.content['product_tiny'].url|ezroot( 'double' , 'full' )} width="{$image.width}" height="{$image.height}" alt="{$image.text|wash(xhtml)}" title="{$image.text|wash(xhtml)}" />
                       
                       {/if}
                   {else}

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
                       <div class="product-image-unavialable"><img src={'shop/unavailable.png'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" /></div>
                       {/if}
                   {/if}
</td>
</tr>

<tr>
<td>
               <p class="product-link">
                {if $view|eq('basket')}<a class="basketlink" href={concat("/content/view/full/",$prod.node_id)|ezurl}>{$prod.name|wash()}</a>{else}{$prod.name|wash()}{/if}
               </p>
               
               <p class="product-id">
                   {'SKU number'|i18n("extension/xrowecommerce")}<br /> {$product_item.item_object.contentobject.data_map.product_id.content|wash()}{$option_item.value}
               </p>
               <p class="product-description">
                   {$vary.comment}
               </p>
               {if $vary.weight}
               <p class="product-weight">
                   {'Weight'|i18n("extension/xrowecommerce")}: {$vary.weight|wash()} {'lbs'|i18n("extension/xrowecommerce")}
               </p>
               {/if}
               {/foreach}
            {else}
<tr>
<td>
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
               {else}{/if}
</td></tr>

               <p class="product-link">
                {if $view|eq('basket')}<a class="basketlink" href={concat("/content/view/full/",$prod.node_id)|ezurl}>{$prod.name|wash()}</a>{else}{$prod.name|wash()}{/if}
               </p>
            <p class="product-id">
            {'SKU number'|i18n("extension/xrowecommerce")}<br />{$prod.data_map.product_id.content|wash()}{$prod.data_map.variation.content.name|wash()}
            </p>
            <p class="product-description">
               {def $vary=$product_item.item_object.contentobject.data_map.variation.content.option_list[$product_item.item_object.option_list.0.option_item_id]}
               {$vary.comment}                        
               </p>
               {if $prod.data_map.weight}
               <p class="product-weight">
               {'Weight'|i18n("extension/xrowecommerce")}: {attribute_view_gui attribute=$prod.data_map.weight} {'lbs'|i18n("extension/xrowecommerce")}
               </p>
               {/if}
               {/if}
</td>
</tr>
</table>
