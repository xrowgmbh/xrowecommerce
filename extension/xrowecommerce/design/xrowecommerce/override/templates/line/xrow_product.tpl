<div class="productline">
	<div class="productline-img">
	{def $node_name=$node.main_node.name node_url=$node.main_node.url_alias}
		{if $node.data_map.image.has_content}
		<div class="productline-img">
			{attribute_view_gui image_class=product_line attribute=$node.data_map.image href=$node.url_alias|ezurl('no')}
		</div>
		{else}
		<a href={$object.main_node.url_alias|ezurl}><div class="nopic">&nbsp;</div></a>
		{/if}
	</div>
	<div class="productline-text">
	<a href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}">{$node.name|wash()}</a>
	{attribute_view_gui attribute=$node.data_map.short_description}
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
                    <span class="currentprice">from {$partprice|l10n( 'currency' )}</span>
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