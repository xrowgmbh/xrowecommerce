<div class="content-view-embed">
	<div class="product_category">
		<h2>{'Did you know about our...'|i18n( 'extension/xrowecommerce')}</h2>
		<div class="attribute-image">
		{attribute_view_gui image_class='product_category_embed' attribute=$object.data_map.image href=$object.main_node.url_alias|ezurl()}
		</div>
		<a class="h2" href={$object.main_node.url_alias|ezurl()}>{$object.name|wash()}</a>
	</div>
</div>