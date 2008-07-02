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
{undef $var_price}
                {undef $allprice}
                {undef $partprice}
                {if count($object.data_map.variation.content.option_list)|eq(1)}
                        {def $allprice=$object.data_map.variation.content.option_list.0.additional_price}
                {elseif count($object.data_map.variation.content.option_list)|gt(1)}
                        {foreach $object.data_map.variation.content.option_list as $var_price}
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
           {attribute_view_gui attribute=$object.data_map.price}
           {/if}

    	</div>
    </div>
</div>