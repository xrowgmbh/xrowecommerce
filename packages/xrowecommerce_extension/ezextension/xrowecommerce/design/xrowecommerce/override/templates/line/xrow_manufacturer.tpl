<div class="manufacturer-line">
<div class="product_title"><a href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}"><h2>{$node.name|wash()}</h2></a></div>
    <div class="manufacturer-image">
        {if $node.data_map.logo.has_content}
	        <div class="content-image">
	            {attribute_view_gui image_class=small attribute=$node.data_map.logo href=$node.url_alias|ezurl()}
	        </div>
        {else}
	        <div class="nopic">
	            <a href="{$node.url_alias|ezurl('no')}"><img src={'shop/nopic.gif'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" /></a>
	        </div>
        {/if}
    </div>
    <div class="text">
	    {attribute_view_gui attribute=$node.data_map.description}
    </div>
    <p class="read_more"><a href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}">{'view products'|i18n('extension/xrowecommerce')} »</a></p>
</div>