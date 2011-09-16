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
         $name_pattern_array = array('enable_comments', 'enable_tipafriend', 'show_children', 'show_children_exclude', 'show_children_pr_page')}
    {set $name_pattern  = $name_pattern|explode('|')|implode(',')}
    {set $name_pattern  = $name_pattern|explode('<')|implode(',')}
    {set $name_pattern  = $name_pattern|explode(',')}
    {foreach $name_pattern  as $name_pattern_string}
        {set $name_pattern_array = $name_pattern_array|append( $name_pattern_string|trim() )}
    {/foreach}
            {if $node.data_map.description.object.embedded_contentobject_array.0.current.data_map.image.content.is_valid|eq('1')}
            {elseif $node.data_map.image.has_content}
                <div class="attribute-image">
                   <a href="javascript:;" onclick="return enlargeImage('/{$node.data_map.image.content.original.full_path}',{$node.data_map.image.content.original.width},{$node.data_map.image.content.original.height},'{$node.data_map.image.content.original.text|wash(javascript)}');" title="{$node.data_map.image.content.original.text|wash} | {"A click on the image enlarges the image in a popup"|i18n( 'extension/xrowecommerce')}">
                       {attribute_view_gui attribute=$node.data_map.image image_class=medium show_alt=false()}
                   </a>
               </div>
            {else}
                <div class="nopic attribute-image"><img src={'nopic.gif'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" /></div>
            {/if}
            <div class="attribute-description">
                {attribute_view_gui attribute=$node.data_map.description}
            </div>
                {def $page_limit = first_set($node.data_map.show_children_pr_page.data_int, 16)
                     $children = array()
                     $children_count = ''}
                   {def $classes = array( 'xrow_product_category', 'xrow_product' ) }

                {if is_set( $node.data_map.show_children_exclude )}
                    {set $classes = $node.data_map.show_children_exclude.content|explode(',')}
                {/if}

                {set $children=fetch( 'content', 'list', hash( 'parent_node_id', $node.node_id,
                                                              'offset', $view_parameters.offset,
                                                              'sort_by', $node.sort_array,
                                                              'class_filter_type', 'include',
                                                              'class_filter_array', $classes,
                                                              'limit', $page_limit ) )
                     $children_count=fetch( 'content', 'list_count', hash( 'parent_node_id', $node.node_id,
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