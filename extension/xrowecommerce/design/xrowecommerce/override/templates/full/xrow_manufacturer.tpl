<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">
<div class="content-view-full">
    <div class="class-manufacturer">

    <div class="attribute-header">
        <h1>{$node.name|wash()}</h1>
    </div>
    <div class="attribute-logo">
            {attribute_view_gui image_class=medium attribute=$node.data_map.logo}
    </div>
    <div class="attribute-description">
            {attribute_view_gui attribute=$node.data_map.description}
    </div>
            
{def $related = fetch( 'content', 'reverse_related_objects', hash( 'object_id', $node.object.id, 'all_relations', true() ) )
     $related_count = fetch( 'content', 'reverse_related_objects_count', hash( 'object_id', $node.object.id, 'all_relations', true() ) )
     $offset = $view_parameters.offset
     $page_limit = 15
     $node_line = ''}

                {include name=navigator
                         uri='design:navigator/google.tpl'
                         page_uri=$node.url_alias
                         item_count=$related_count
                         view_parameters=$view_parameters
                         item_limit=$page_limit}
				{foreach $related as $item offset $offset max $page_limit}
				    {set $node_line=fetch( 'content', 'node', hash( 'node_id', $item.main_node.node_id ) )}
				    {node_view_gui view='line' content_node=$node_line}
				{/foreach}
                {include name=navigator
                         uri='design:navigator/google.tpl'
                         page_uri=$node.url_alias
                         item_count=$related_count
                         view_parameters=$view_parameters
                         item_limit=$page_limit}
   
    </div>
</div>
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>