<div class="productline">
<div class="product_title"><a href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}"><h2>{$node.name|wash()}</h2></a></div>
    <div class="productcategory-img">
        {if $node.data_map.image.has_content}
        <div class="productline-img">
            {attribute_view_gui image_class=small attribute=$node.data_map.image href=$node.url_alias|ezurl()}
        </div>
        {else}
        <div class="nopic"><a href="{$node.url_alias|ezurl('no')}"><img src={'shop/nopic.gif'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" /></a></div>
        {/if}
    </div>
    <div class="productline-text">
    <p>{attribute_view_gui attribute=$node.data_map.short_description}</p>
    </div>
    <div class="attribute-price">
         {if count($node.data_map.options.content.option_list)|eq(1)}
                 {def $allprice=$node.data_map.options.content.option_list.0.additional_price}
         {elseif count($node.data_map.options.content.option_list)|gt(1)}
                 {foreach $node.data_map.options.content.option_list as $var_price}
                     {if or( $var_price.multi_price|lt($partprice), is_set($partprice)|not ) }
                         {def $partprice=$var_price.multi_price}
                     {/if}
                 {/foreach}
         {/if}
        {if or( $partprice|gt(0), $allprice|gt(0) ) }
             {if $partprice|gt(0)}
                 <span class="currentprice"><small>{'starting at'|i18n('extension/xrowecommerce')}</small> {$partprice|l10n( 'currency' )}</span>
             {/if}
             {if $allprice|gt(0)}
                 <span class="currentprice">{$allprice|l10n( 'currency' )}</span>
             {/if}
        {else}
             {$node.data_map.price.content.price|l10n(currency)}
        {/if}
    </div>
    <p class="read_more"><a href="{$node.url_alias|ezurl('no')}" title="{$node.name|wash()}">{'view product'|i18n('extension/xrowecommerce')} »</a></p>
</div>