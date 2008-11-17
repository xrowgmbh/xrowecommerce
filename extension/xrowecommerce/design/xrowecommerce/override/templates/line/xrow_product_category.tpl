<div class="productline">
    <div class="product_title">
        <a href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}">{$node.name|wash()}</a>
    </div>
	<div class="productcategory-img">
	{def $node_name=$node.main_node.name node_url=$node.main_node.url_alias}
		{if $node.data_map.image.has_content}
            <div class="productline-img">
				{attribute_view_gui image_class=product_line attribute=$node.data_map.image href=$node.url_alias|ezurl('no')}
			</div>
        {else}
            <a href={$node.url_alias|ezurl('no')}><div class="nopic"><p>{'no Image'|i18n('design/base/shop')}</p></div></a>
		{/if}
	</div>
	<div class="productline-text">
		<p>{$node.data_map.description.data_text|striptags|shorten( 110 )}</p>
		<p class="read_more"><a class="read_more" href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}">{'read more'|i18n('design/base/shop')} »</a></p>
	</div>
</div>