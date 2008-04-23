<div class="productline">
	<div class="productline-img">
	{default node_name=$object.main_node.name node_url=$object.main_node.url_alias}
	
		
		{if $object.data_map.image.content}
		<div class="productline-img">
			{attribute_view_gui image_class=product_line attribute=$object.data_map.image href=$object.main_node.url_alias|ezurl}
		</div>
		{/if}
	</div>
	<div class="productline-text">
		{section show=$node_url}
			<a href={$node_url|ezurl} class="bold">{/section}{$node_name|wash}{section show=$node_url}</a>
		{/section}
	{/default}
	{attribute_view_gui attribute=$object.data_map.short_description}
		<div class="price">{attribute_view_gui attribute=$object.data_map.price}</div>
	</div>
</div>