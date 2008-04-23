{* Product - List embed view *}
<div class="content-view-embed">
    <div class="class-xrow-product">
    	{if $object.data_map.image.has_content}
    		{attribute_view_gui image_class=listitem attribute=$object.data_map.image href=$object.main_node.url_alias|ezurl}
    	{/if}
    	<p><a href={$object.main_node.url_alias|ezurl}>{$object.name|wash}</a></p>
    	<div class="attribute-short">
    		{attribute_view_gui attribute=$object.data_map.short_description}
    	</div>
    	<div class="attribute-price">
    		{attribute_view_gui attribute=$object.data_map.price}
    	</div>
    </div>
</div>