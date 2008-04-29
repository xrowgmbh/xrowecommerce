<form method="post" action={"xrowecommerce/multiadd"|ezurl}>
<input type="submit" class="flat-right" name="ActionAddToBasket" value="{"Add to basket"|i18n("design/ezwebin/full/product")}" />
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />
<input type="hidden" name="RedirectURI" value="{$node.url_alias}" />
{def $products=fetch( content, tree,
        hash( parent_node_id, $node.node_id,
        class_filter_type, include,
        class_filter_array, array( 'xrow_product' )
        ) )}
{if $products|count|gt(0)}        
<table class="quickorder_products">
		<tr>
    		<th>Item No.</th>
    		<th>Item Name</th>
    		<th>Option</th>
    		<th>Qty</th>
    		<th>Price</th>
		</tr>
		{def $i=0}
		{foreach $products as $key => $child}
			{if $child.data_map.variation.content.option_list|count|gt(0)}
                {foreach $child.data_map.variation.content.option_list as $option}
			        <tr>
			            <td>{attribute_view_gui attribute=$child.data_map.product_id}{$option.value|wash()}</td>
			            <td>{$child.name|wash()}</td>
			            <td>{$option.comment|wash()}</td>
			            <td class="quickbasket">
				            <input type="hidden" name="AddToBasketList[{$i}][object_id]" value="{$child.object.id}" />
				            <input type="hidden" name="AddToBasketList[{$i}][variations][{$child.data_map.variation.id}]" value="{$option.id}">
				            <input type="text" name="AddToBasketList[{$i}][quantity]" value="0" />
			            </td>
			            <td>{$child.data_map.price.data_float|sum($option.additional_price)|l10n(currency)}
			            </td>
			        </tr>
			        {set $i=$i|sum(1)}
                {/foreach}
            {else}
		        <tr>
		            <td>{attribute_view_gui attribute=$child.data_map.product_id}{$child.data_map.variation.content.name|wash()}</td>
		            <td>{$child.name|wash()}</td>
		            <td>-</td>
		            <td class="quickbasket">
			            <input type="hidden" name="AddToBasketList[{$i}][object_id]" value="{$child.object.id}" />
			            <input type="text" name="AddToBasketList[{$i}][quantity]" value="0">
		            </td>
		            <td>{$child.data_map.price.data_float|l10n(currency)}</td>
		        </tr>
		        {set $i=$i|sum(1)}
            {/if}
        {/foreach}
        {undef $i}
</table>

{/if}
<input type="submit" class="flat-right" name="ActionAddToBasket" value="{"Add to basket"|i18n("design/ezwebin/full/product")}" />
</form>

{*$products.1|attribute(show)*}

{undef}

