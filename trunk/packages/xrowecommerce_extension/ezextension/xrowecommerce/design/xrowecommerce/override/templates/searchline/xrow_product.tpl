    <tr>
        <td class="width_50">
        <table class="product" summary="{"This table contains the specific product images, product id's, product names and links to the full view of the product"|i18n( "extension/xrowecommerce")}">
            <tr>
                <td rowspan="2">
                    <div class="searchline-img">
                        {if $node.data_map.image.has_content}
                            <div class="search-productline-img">
                                {attribute_view_gui image_class=product_thumb attribute=$node.data_map.image href=$node.url_alias|ezurl}
                            </div>
                        {else}
                            <div class="nopic"><a href="{$node.url_alias|ezurl('no')}"><img src={'shop/nopic.gif'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" /></a></div>
                        {/if}
                    </div>
                </td>
                <td><p class="product_title"><a href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}">{$node.name|wash()}</a></p></td>
            </tr>
            <tr>
                <td>{*<p>{'Article Number:'|i18n( 'extension/xrowecommerce')} 000000000</p>*}
{if $node.data_map.short_description.has_content}
    {attribute_view_gui image_class=product_thumb attribute=$node.data_map.short_description}
{else}
    {$node.data_map.short_description.data_text|strip_all|wash()|shorten( 50 )}
{/if}

                </td>
            </tr>
        </table>
        </td>
        {*<td>
            <p class="quantity">
                        <input type="hidden" name="AddToBasketList[{$i}][object_id]" value="{$child.object.id}" />
                        <input class="halfbox" type="text" name="AddToBasketList[{$i}][quantity]" value="0" />
           </p>
       </td>
        <td class="details_light">
            <p class="price">{'3,00 €'|wash()}</p>
            <p><input type="submit" class="details" name="ActionAddToBasket" value="{'Basket'|i18n('extension/xrowecommerce')}" /></p>
        </td>*}
        <td class="details"><p><a class="details" href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}">{'Details'|i18n('extension/xrowecommerce')}</a></p></td>
    </tr>