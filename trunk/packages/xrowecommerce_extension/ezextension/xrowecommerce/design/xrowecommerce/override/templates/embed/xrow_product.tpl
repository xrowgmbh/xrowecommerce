{def $price_display=ezini( 'Settings', 'ShowPriceAs', 'xrowecommerce.ini' )}
<div class="content-view-embed">
    <div class="class-xrow-product">
    <div class="product-image">
        {if $object.data_map.image.has_content}
            {attribute_view_gui image_class=product_medium attribute=$object.data_map.image href=$object.main_node.url_alias|ezurl()}
        {else}
           <div class="nopic"><a href={$object.main_node.url_alias|ezurl}><img src={'nopic_95.gif'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" /></a></div>
        {/if}
    </div>
        <p><a href={$object.main_node.url_alias|ezurl}>{$object.name|wash()|shorten( 40 )}</a></p>
        <div class="attribute-short">
            {$object.data_map.short_description.content.output.output_text|striptags|shorten( 70 )}
        </div>
        <div class="attribute-price">
            {if count($object.data_map.options.content.option_list)|gt( 0 )}
                {foreach $object.data_map.options.content.option_list as $Options}
                    {if or( $Options.multi_price.$price_display|lt( $partprice ), is_set( $partprice )|not ) }
	                    {def $partprice=get_multiprice( $object.data_map.price, $Options.multi_price, $price_display )}
	                {/if}
                {/foreach}
            {/if}
            {if $partprice|gt( 0 ) }
                <span class="currentprice"><small>{'starting at'|i18n('extension/xrowecommerce')}</small> {$partprice|l10n( currency )}</span>
           {else}
                {$object.data_map.price.content.$price_display|l10n( currency )}
           {/if}
        </div>
    </div>
</div>
{undef $price_display $partprice}