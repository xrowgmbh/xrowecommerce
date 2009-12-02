<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc">

<div class="toolbar-item {$placement}">
    {section show=or($show_subtree|count_chars()|eq(0),
                     fetch(content, node, hash( node_id, $module_result.node_id ) ).path_string|contains( concat( '/', $show_subtree, '/' ) ),
                     $requested_uri_string|begins_with( $show_subtree ))}
    {default current_user=fetch('user','current_user')}
    {cache-block keys=array($tool_id, $current_user.role_id_list|implode( ',' ), $current_user.limited_assignment_value_list|implode( ',' ))}
    {default limit=5}
    {section show=$sort_by|count|eq( 0 )}{set sort_by='published'}{/section}
    {let node_list=cond( $treelist_check|eq( 'yes' ),
                             fetch( content, tree, hash( parent_node_id, $parent_node,
                                    limit, $limit,
                                    class_filter_type, exclude,
                                    class_filter_array, array( 'folder' ),
                                    sort_by, array( $sort_by, false() ) ) ),
                         fetch( content, list, hash( parent_node_id, $parent_node,
                                limit, $limit,
                                class_filter_type, exclude,
                                class_filter_array, array( 'folder' ),
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
			                        {undef $var_price}
			                        {undef $allprice}
			                        {undef $partprice}
			                        {if count($node_object.data_map.options.content.option_list)|eq(1)}
			                            {def $allprice=$node_object.data_map.options.content.option_list.0.additional_price}
			                        {elseif count($node_object.data_map.options.content.option_list)|gt(1)}
			                            {foreach $node_object.data_map.options.content.option_list as $var_price}
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
			                            {$node_object.data_map.price.content.price|l10n(currency)}
			                       {/if}
			                    </div>
					        </td>
				        </tr>
			        </table>
		        </div>
		        {/foreach}
	        </div>
        </div>
    </div>
    {*$node_list.0|attribute(show)*}

    {/let}
    {/default}
    {/cache-block}
    {/default}
    {/section}
</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>