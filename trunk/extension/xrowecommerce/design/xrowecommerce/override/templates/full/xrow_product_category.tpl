<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

<div class="content-view-full">
    <div class="class-{$node.object.class_identifier}">

    <div class="attribute-header">
        <h1>{$node.name|wash()}</h1>
    </div>
{if eq(ezpreference( 'quick_order' ), 1)}
    {include uri='design:quickorder.tpl' node_id=$node.node_id}
{else}
                
    {def $name_pattern = $node.object.content_class.contentobject_name|explode('>')|implode(',')
         $name_pattern_array = array('enable_comments', 'enable_tipafriend', 'show_children', 'image', 'show_children_exclude', 'show_children_pr_page')}
    {set $name_pattern  = $name_pattern|explode('|')|implode(',')}
    {set $name_pattern  = $name_pattern|explode('<')|implode(',')}
    {set $name_pattern  = $name_pattern|explode(',')}
    {foreach $name_pattern  as $name_pattern_string}
        {set $name_pattern_array = $name_pattern_array|append( $name_pattern_string|trim() )}
    {/foreach}
    {foreach $node.object.contentobject_attributes as $attribute}
        {if $name_pattern_array|contains($attribute.contentclass_attribute_identifier)|not()}
        {if and($attribute.contentclass_attribute_identifier|eq('image'),$attribute.is_valid|not)}
        {else}
            <div class="attribute-{$attribute.contentclass_attribute_identifier}">
                {attribute_view_gui attribute=$attribute}
            </div>
            {/if}
        {/if}
    {/foreach}
                {def $page_limit = first_set($node.data_map.show_children_pr_page.data_int, 16)
                     $children = array()
                     $children_count = ''}
	            {if $node.children.0.class_identifier|eq( xrow_product_category )}
	                {def $classes = array( 'xrow_product_category' ) }
	            {elseif $node.children.0.class_identifier|eq( xrow_product )}
	                {def $classes = array( 'xrow_product' ) }
	            {else}
	            {/if}
                
                
                     
                {if is_set( $node.data_map.show_children_exclude )}
                    {set $classes = $node.data_map.show_children_exclude.content|explode(',')}
                {/if}

                {set $children=fetch( 'content', 'tree', hash( 'parent_node_id', $node.node_id,
                                                              'offset', $view_parameters.offset,
                                                              'sort_by', $node.sort_array,
                                                              'class_filter_type', 'include',
                                                              'class_filter_array', $classes,
                                                              'limit', $page_limit ) )
                     $children_count=fetch( 'content', 'tree_count', hash( 'parent_node_id', $node.node_id,
                                                                          'class_filter_type', 'include',
                                                                          'class_filter_array', $classes ) )}
                {include name=navigator
                         uri='design:navigator/google.tpl'
                         page_uri=$node.url_alias
                         item_count=$children_count
                         view_parameters=$view_parameters
                         item_limit=$page_limit}
                <div class="content-view-children">
                    {foreach $children as $child }
                        {node_view_gui view='line' content_node=$child}
                    {/foreach}
                </div>

                {include name=navigator
                         uri='design:navigator/google.tpl'
                         page_uri=$node.url_alias
                         item_count=$children_count
                         view_parameters=$view_parameters
                         item_limit=$page_limit}
       {/if}
    </div>
</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>