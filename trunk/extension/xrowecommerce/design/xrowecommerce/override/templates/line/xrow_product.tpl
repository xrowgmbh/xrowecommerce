<div class="productline">
<div class="product_title"><a href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}">{$node.name|wash()}</a></div>
	<div class="productline-img">
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
	
	<p>{$node.data_map.short_description.data_text|striptags|shorten( 90 )}</p>
	<p class="read_more"><a href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}">{'read more'|i18n('design/base/shop')} »</a></p>
	
		<div class="price">
                {undef $var_price}
                {undef $allprice}
                {undef $partprice}
                {if count($node.data_map.variation.content.option_list)|eq(1)}
                        {def $allprice=$node.data_map.variation.content.option_list.0.additional_price}
                {elseif count($node.data_map.variation.content.option_list)|gt(1)}
                        {foreach $node.data_map.variation.content.option_list as $var_price}
                            {if or( $var_price.additional_price|lt($partprice), is_set($partprice)|not ) }
                                {def $partprice=$var_price.additional_price}
                            {/if}
                        {/foreach}
                {/if}
           {if or( $partprice|gt(0), $allprice|gt(0) ) }
                {if $partprice|gt(0)}
                    <span class="currentprice">{'starting at '|i18n('design/base/shop')}{$partprice|l10n( 'currency' )}</span>
                {/if}
                {if $allprice|gt(0)}
                    <span class="currentprice">{$allprice|l10n( 'currency' )}</span>
                {/if}
           {else}
           {attribute_view_gui attribute=$node.data_map.price}
           {/if}
		</div>
	</div>
</div>