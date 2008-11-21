<div class="productline">
<div class="product_title"><a href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}">{$node.name|wash()}</a></div>
	<div class="productline-img">
		{if $node.data_map.image.has_content}
		<div class="productline-img">
			{attribute_view_gui image_class=product_line attribute=$node.data_map.image href=$node.url_alias|ezurl()}
		</div>
		{else}
		<div class="nopic"><p><a href="{$node.url_alias|ezurl('no')}">{'no Image'|i18n('design/base/shop')}</a></p></div>
		{/if}
	</div>
	<div class="productline-text">
	<p>{$node.data_map.short_description.data_text|striptags|shorten( 90 )}</p>
	<p class="read_more"><a href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}">{'read more'|i18n('design/base/shop')} »</a></p>
	</div>
</div>