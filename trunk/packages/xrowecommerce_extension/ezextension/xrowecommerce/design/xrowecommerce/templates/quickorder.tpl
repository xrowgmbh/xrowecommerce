{def $node=fetch( 'content', 'node', hash( 'node_id', $node_id ) )}
<form method="post" action={"xrowecommerce/multiadd"|ezurl}>
<input type="submit" class="button flat-right" name="ActionAddToBasket" value="{"Add to cart"|i18n("extension/xrowecommerce")}" />
<input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
<input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
<input type="hidden" name="ViewMode" value="full" />
<input type="hidden" name="RedirectURI" value="{$node.url_alias}" />
{def $page_limit=10
$products=fetch( content, tree,
        hash( 'parent_node_id', $node.node_id,
        'offset', $view_parameters.offset,
        'sort_by', $node.sort_array,
        'limit', $page_limit,
        'class_filter_type', include,
        'class_filter_array', array( 'xrow_product' )
        ) )
        $products_count=fetch( content, tree_count,
        hash( parent_node_id, $node.node_id,
        class_filter_type, include,
        class_filter_array, array( 'xrow_product' )
        ) )
        }
{if $products|count|gt(0)}
{include name=navigator
                         uri='design:navigator/google.tpl'
                         page_uri=$node.url_alias
                         item_count=$products_count
                         view_parameters=$view_parameters
                         item_limit=$page_limit}
<table class="quickorder_products">
        <tr>
            <th>{'Number'|i18n("extension/xrowecommerce")}</th>
            <th>{'Name'|i18n("extension/xrowecommerce")}</th>
            <th>{'Item'|i18n("extension/xrowecommerce")}</th>
            <th>{'Qty'|i18n("extension/xrowecommerce")}</th>
            <th>{'Price'|i18n("extension/xrowecommerce")}</th>
        </tr>
        {def $i=0}
        {foreach $products as $key => $child}
            {if $child.data_map.options.content.option_list|count|gt(0)}
                {foreach $child.data_map.options.content.option_list as $option sequence array( 'quickbasket_light', 'quickbasket_dark' ) as $style}
                {set $i=$i|sum(1)}
                {if mod( $i, 2)|eq(1)}
                    <tr class="quickbasket_light">
                {else}
                    <tr class="quickbasket_dark">
                {/if}
                        <td>{attribute_view_gui attribute=$child.data_map.product_id}{$option.value|wash()} </td>
                        <td><a href={$child.path_with_names|ezurl('double','relative')} id="show_auto_tip_{$i}">{$child.name|wash()}</td>
                        <td>{$option.comment|wash()}<br />{$option.description|wash()}</td>
                        <td class="quickbasket">
                            <input type="hidden" name="AddToBasketList[{$i}][object_id]" value="{$child.object.id}" />
                            <input type="hidden" name="AddToBasketList[{$i}][optionss][{$child.data_map.options.id}]" value="{$option.id}">
                            <input type="text" name="AddToBasketList[{$i}][quantity]" value="0" />
                        </td>
                        <td class="quickprice">{$child.data_map.price.data_float|sum($option.additional_price)|l10n(currency)}
                        </td>
                    </tr>
<div class="overlay" id="overlay{$i}" style="visibility:hidden;">
    <h3>{$child.name|wash()}</h3>
    {if $child.data_map.image.has_content}
       {attribute_view_gui attribute=$child.data_map.image image_class=medium}
    {else}
       <div class="nopic attribute-image"><img src={'shop/nopic.gif'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" /></div>
    {/if}
    <p>{attribute_view_gui attribute=$child.data_map.short_description}</p>
    <p>{attribute_view_gui attribute=$child.data_map.description}</p>
    <p>{'Option:'|i18n("extension/xrowecommerce")}</p>
    <h4>{$option.comment|wash()}</h4>
    <p>{$option.description|wash()}</p>
    <span class="row-hr"><span>{'Weight'|i18n("extension/xrowecommerce")}</span><span>{'Price'|i18n("extension/xrowecommerce")}</span></span>
    <span class="row-td"><span>{$option.weight|wash} {'LBS'|i18n("extension/xrowecommerce")}</span><span>{$child.data_map.price.data_float|sum($option.additional_price)|l10n(currency)}</span></span>
</div>
    <script type="text/javascript">
         YAHOO.namespace("example.container");
         function init() {ldelim}
             // Build overlay1 based on markup, initially hidden, fixed to the center of the viewport, and 300px wide
             YAHOO.example.container.overlay{$i} = new YAHOO.widget.Overlay("overlay{$i}", {ldelim} fixedcenter:false,
                                                                                       visible:false,
                                                                                       width:"300px" {rdelim} );
             YAHOO.example.container.overlay{$i}.render();
             YAHOO.util.Event.addListener("show_auto_tip_{$i}", "mouseover", YAHOO.example.container.overlay{$i}.show, YAHOO.example.container.overlay{$i}, true);
             YAHOO.util.Event.addListener("show_auto_tip_{$i}", "mouseout", YAHOO.example.container.overlay{$i}.hide, YAHOO.example.container.overlay{$i}, true);
         {rdelim}
         YAHOO.util.Event.addListener(window, "load", init);
    </script>
                {/foreach}
            {else}
            {set $i=$i|sum(1)}
                {if mod( $i, 2)|eq(1)}
                    <tr class="quickbasket_light">
                {else}
                    <tr class="quickbasket_dark">
                {/if}
                    <td>{attribute_view_gui attribute=$child.data_map.product_id}{$child.data_map.options.content.name|wash()}</td>
                    <td><a href={$child.path_with_names|ezurl('double','relative')} id="show_auto_tip_{$i}">{$child.name|wash()}</a></td>
                    <td>-</td>
                    <td class="quickbasket">
                        <input type="hidden" name="AddToBasketList[{$i}][object_id]" value="{$child.object.id}" />
                        <input class="qb_halfbox" type="text" name="AddToBasketList[{$i}][quantity]" value="0">
                    </td>
                    <td>{$child.data_map.price.data_float|l10n(currency)}</td>
                </tr>
<div class="overlay" id="overlay{$i}" style="visibility:hidden;">
<h3>{$child.name|wash()}</h3>
{if $child.data_map.image.has_content}
    {attribute_view_gui attribute=$child.data_map.image image_class=medium}
{else}
    <div class="nopic attribute-image"><img src={'shop/nopic.gif'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" /></div>
{/if}
<p>{attribute_view_gui attribute=$child.data_map.short_description}</p>
<p>{attribute_view_gui attribute=$child.data_map.description}</p>
<p><span>{'Weight'|i18n("extension/xrowecommerce")} </span><span>{$child.data_map.weight.data_float|wash} LBS</span></p>
<p><span>{'Price'|i18n("extension/xrowecommerce")} </span><span>{$child.data_map.price.data_float|sum($option.additional_price)|l10n(currency)}</span></p>
</div>
    <script type="text/javascript">
            YAHOO.namespace("example.container");
            function init() {ldelim}
                // Build overlay1 based on markup, initially hidden, fixed to the center of the viewport, and 300px wide
                YAHOO.example.container.overlay{$i} = new YAHOO.widget.Overlay("overlay{$i}", {ldelim} fixedcenter:false,
                visible:false,
                width:"300px" {rdelim} );
                YAHOO.example.container.overlay{$i}.render();
                YAHOO.util.Event.addListener("show_auto_tip_{$i}", "mouseover", YAHOO.example.container.overlay{$i}.show, YAHOO.example.container.overlay{$i}, true);
                YAHOO.util.Event.addListener("show_auto_tip_{$i}", "mouseout", YAHOO.example.container.overlay{$i}.hide, YAHOO.example.container.overlay{$i}, true);
            {rdelim}
            YAHOO.util.Event.addListener(window, "load", init);
    </script>
            {/if}
        {/foreach}
        {undef $i}
</table>
{/if}
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri=$node.url_alias
         item_count=$products_count
         view_parameters=$view_parameters
         item_limit=$page_limit}
<input type="submit" class="flat-right button" name="ActionAddToBasket" value="{"Add to cart"|i18n("extension/xrowecommerce")}" />
</form>
{undef}

