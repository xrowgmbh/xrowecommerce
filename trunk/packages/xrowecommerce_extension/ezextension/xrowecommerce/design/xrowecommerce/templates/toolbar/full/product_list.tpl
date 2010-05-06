{def $price_display=ezini( 'Settings', 'ShowPriceAs', 'xrowecommerce.ini' )}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc">

<div class="toolbar-item {$placement}">
    {if or($show_subtree|count_chars()|eq(0),
                     fetch(content, node, hash( node_id, $module_result.node_id ) ).path_string|contains( concat( '/', $show_subtree, '/' ) ),
                     $requested_uri_string|begins_with( $show_subtree ))}
    {def $current_user=fetch('user','current_user')}
    {cache-block keys=array($tool_id, $current_user.role_id_list|implode( ',' ), $current_user.limited_assignment_value_list|implode( ',' ))}

    {if $limit|count|eq(0)}{def $limit='5'}{/if}
    {if $sort_by|count|eq(0)}{def $sort_by='published'}{/if}

    {def $node_list=cond( $treelist_check|eq( 'yes' ),
                             fetch( content, tree, hash( parent_node_id, $parent_node,
                                    limit, $limit,
                                    class_filter_type, include,
                                    class_filter_array, array( 'xrow_product' ),
                                    sort_by, array( $sort_by, false() ) ) ),
                         fetch( content, list, hash( parent_node_id, $parent_node,
                                limit, $limit,
                                class_filter_type, include,
                                class_filter_array, array( 'xrow_product' ),
                                sort_by, array( $sort_by, false() ) ) ) )}
    <div class="toollist">
        <div class="toollist-design">
	        <h2>{$title}</h2>
	        <div class="content-view-children">
		        {foreach $node_list as $node_object}
		        <div class="child">
			        <table>
				        <tr>
					        <td class="attribute-image">{attribute_view_gui attribute=$node_object.data_map.image image_class="product_related" href=$node_object.path_with_names|ezurl()}</td>
					        <td>
					            <a class="attribute-name" href={$node_object.path_with_names|ezurl()}>
					               {$node_object.name|wash()}
					            </a>
			                    <div class="attribute-price">
                                    {if count($node_object.data_map.options.content.option_list)|gt( 0 )}
                                        {foreach $node_object.data_map.options.content.option_list as $Options}
                                            {if or( $Options.multi_price.$price_display|lt( $partprice ), is_set( $partprice )|not ) }
	                                            {def $partprice=get_multiprice( $node_object.data_map.price, $Options.multi_price, $price_display )}
	                                        {/if}
                                        {/foreach}
                                    {/if}
                                    {if $partprice|gt( 0 ) }
                                        <span class="currentprice"><small>{'starting at'|i18n('extension/xrowecommerce')}</small> {$partprice|l10n( currency )}</span>
                                    {else}
                                        {$node_object.data_map.price.content.$price_display|l10n( currency )}
                                    {/if}
                                    {undef $partprice}
			                    </div>
					        </td>
				        </tr>
			        </table>
		        </div>
		        {/foreach}
	        </div>
        </div>
    </div>
    {/cache-block}
    {/if}
</div>
{undef $price_display $node_list $current_user}
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>