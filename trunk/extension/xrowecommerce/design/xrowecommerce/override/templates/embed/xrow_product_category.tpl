<div class="content-view-embed">
	<div class="product_category">
		<h2>Kennen Sie schon unsere...</h2>
		<div class="attribute-image">
		<a href={$object.main_node.url_alias|ezroot()}>{attribute_view_gui image_class=product_category_embed attribute=$object.data_map.image}</a>
		</div>
		<a class="h2" href={$object.main_node.url_alias|ezroot()}>{$object.name|wash()}</a>
	</div>
</div>