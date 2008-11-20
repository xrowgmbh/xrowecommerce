	<tr>
		<td class="width_50">
		<table class="product" summary="This table contains the specific product images, product id's, product names and links to the full view of the product">
            <tr>
                <td rowspan="2">
	                <div class="searchline-img">
		                {def $node_name=$node.main_node.name $node_url=$node.main_node.url_alias}
		                {if $node.data_map.image.has_content}
		                    <div class="search-productline-img">
		                        {attribute_view_gui image_class=product_line attribute=$node.data_map.image}
		                    </div>
		                {else}
		                    <div class="nopic"><p><a href="{$node.url_alias|ezurl('no')}">{'no Image'|i18n('design/base/shop')}</a></p></div>
		                {/if}
	                </div>
                </td>
                <td><p class="product_title"><a href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}">{$node.name|wash()}</a></p></td>
            </tr>
            <tr>
                <td><p>Artikelnummer: 000000000</p></td>
            </tr>
        </table>
	    </td>
		<td>
			<p class="quantity">
                        <input type="hidden" name="AddToBasketList[{$i}][object_id]" value="{$child.object.id}" />
                        <input class="halfbox" type="text" name="AddToBasketList[{$i}][quantity]" value="0" />
		   </p>
	   </td>
		<td class="details_light">
			<p class="price">
                {'3,00 €'|wash()}
            </p>
            <p><input type="submit" class="details" name="ActionAddToBasket" value="{"Basket"|i18n("design/ezwebin/full/product")}" /></p>
		</td>
		<td class="details"><p><a class="details" href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}">{'Details'|i18n('design/ezwebin/full/product')}</a></p></td>
	</tr>