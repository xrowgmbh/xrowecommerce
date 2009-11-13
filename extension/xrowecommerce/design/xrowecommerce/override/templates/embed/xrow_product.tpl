{def $var_price = ""
     $allprice = ""
     $partprice = ""}
<div class="content-view-embed">
    <div class="class-xrow-product">
    <div class="product-image">
        {if $object.data_map.image.has_content}
            {attribute_view_gui image_class=product_medium attribute=$object.data_map.image href=$object.main_node.url_alias|ezurl()}
        {else}
           <div class="nopic"><a href={$object.main_node.url_alias|ezurl}><img src={'nopic_95.gif'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" /></a></div>
        {/if}
    </div>
        <p><a href={$object.main_node.url_alias|ezurl}>{$object.name|wash()|shorten(40)}</a></p>
        <div class="attribute-short">
            {$object.data_map.short_description.content.output.output_text|striptags|shorten(70)}
        </div>
        <div class="attribute-price">
                {undef $var_price}
                {undef $allprice}
                {undef $partprice}
                {if count($object.data_map.options.content.option_list)|eq(1)}
                        {def $allprice=$object.data_map.options.content.option_list.0.additional_price}
                {elseif count($object.data_map.options.content.option_list)|gt(1)}
                        {foreach $object.data_map.options.content.option_list as $var_price}
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
                {$object.data_map.price.content.price|l10n(currency)}
           {/if}
        </div>
    </div>
</div>