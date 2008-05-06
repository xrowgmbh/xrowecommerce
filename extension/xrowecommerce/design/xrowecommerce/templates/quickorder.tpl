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
                {set $i=$i|sum(1)}
			        <tr>
			            <td>{attribute_view_gui attribute=$child.data_map.product_id}{$option.value|wash()}</td>
			            <td><a href="/{$child.path_identification_string}" id="show_auto_tip_{$i}">{$child.name|wash()}</td>
			            <td>{$option.comment|wash()}</td>
			            <td class="quickbasket">
				            <input type="hidden" name="AddToBasketList[{$i}][object_id]" value="{$child.object.id}" />
				            <input type="hidden" name="AddToBasketList[{$i}][variations][{$child.data_map.variation.id}]" value="{$option.id}">
				            <input type="text" name="AddToBasketList[{$i}][quantity]" value="0" />
			            </td>
			            <td>{$child.data_map.price.data_float|sum($option.additional_price)|l10n(currency)}
			            </td>
			        </tr>        
<div class="overlay" id="overlay{$i}" style="visibility:hidden;">
<h3>{$child.name|wash()}</h3>
{attribute_view_gui attribute=$child.data_map.image image_class=tooltip}
<p>{attribute_view_gui attribute=$child.data_map.short_description}</p>
<p>{attribute_view_gui attribute=$child.data_map.description}</p>
<p>Option:</p>
<h4>{$option.comment|wash()}</h4>
<p>{$option.description|wash()}</p>


{*$option|attribute(show, 2)*}
</div>
							<script>
						            YAHOO.namespace("example.container");
						            function init() {ldelim}
						                // Build overlay1 based on markup, initially hidden, fixed to the center of the viewport, and 300px wide
						                YAHOO.example.container.overlay{$i} = new YAHOO.widget.Overlay("overlay{$i}", {ldelim} fixedcenter:false,
						                                                                                          visible:false,
						                                                                                          width:"300px" {rdelim} );
						                YAHOO.example.container.overlay{$i}.render();
						                YAHOO.util.Event.addListener("show_auto_tip_{$i}", "mouseover", YAHOO.example.container.overlay{$i}.show, YAHOO.example.container.overlay{$i}, true);
						                YAHOO.util.Event.addListener("show_auto_tip_{$i}", "mouseout", YAHOO.example.container.overlay{$i}.hide, YAHOO.example.container.overlay{$i}, true);
						            {rdelim}
						            YAHOO.util.Event.addListener(window, "load", init);
						    </script>
						
                {/foreach}
            {else}{set $i=$i|sum(1)}
		        <tr>
		            <td>{attribute_view_gui attribute=$child.data_map.product_id}{$child.data_map.variation.content.name|wash()}</td>
		            <td><a href="/{$child.path_identification_string}" id="show_auto_tip_{$i}">{$child.name|wash()}</a></td>
		            <td>-</td>
		            <td class="quickbasket">
			            <input type="hidden" name="AddToBasketList[{$i}][object_id]" value="{$child.object.id}" />
			            <input type="text" name="AddToBasketList[{$i}][quantity]" value="0">
		            </td>
		            <td>{$child.data_map.price.data_float|l10n(currency)}</td>
		        </tr>
<div class="overlay" id="overlay{$i}" style="visibility:hidden;">
<h3>{$child.name|wash()}</h3>
{attribute_view_gui attribute=$child.data_map.image image_class=tooltip}
<p>{attribute_view_gui attribute=$child.data_map.short_description}</p>
<p>{attribute_view_gui attribute=$child.data_map.description}</p>
<p>(no Options)</p>

{*$option|attribute(show, 2)*}
</div>
                            <script>
                                    YAHOO.namespace("example.container");
                                    function init() {ldelim}
                                        // Build overlay1 based on markup, initially hidden, fixed to the center of the viewport, and 300px wide
                                        YAHOO.example.container.overlay{$i} = new YAHOO.widget.Overlay("overlay{$i}", {ldelim} fixedcenter:false,
                                                                                                                  visible:false,
                                                                                                                  width:"300px" {rdelim} );
                                        YAHOO.example.container.overlay{$i}.render();
                                        YAHOO.util.Event.addListener("show_auto_tip_{$i}", "mouseover", YAHOO.example.container.overlay{$i}.show, YAHOO.example.container.overlay{$i}, true);
                                        YAHOO.util.Event.addListener("show_auto_tip_{$i}", "mouseout", YAHOO.example.container.overlay{$i}.hide, YAHOO.example.container.overlay{$i}, true);
                                    {rdelim}
                                    YAHOO.util.Event.addListener(window, "load", init);
                            </script>
            {/if}
        {/foreach}
        {undef $i}
</table>

{/if}
<input type="submit" class="flat-right" name="ActionAddToBasket" value="{"Add to basket"|i18n("design/ezwebin/full/product")}" />
</form>
{undef}

