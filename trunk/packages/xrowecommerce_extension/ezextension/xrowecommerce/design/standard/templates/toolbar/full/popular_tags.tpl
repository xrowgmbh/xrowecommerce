{def $node=fetch( 'content', 'node', hash( 'node_id', $parent_node ))}
{def $current_node=fetch( 'content', 'node', hash( 'node_id', $current_node_id ))}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc">
	<div class="content-view-toolbar">
		<div class="content-view-eztagcloud">
			<div class="attribute-header">
			    <h2>Tags</h2>
			</div>
			<div class="attribute-tagcloud">
				{if $current_node.object.class_identifier|eq('xrow_product_category')}
				    {eztagcloud( hash( 'parent_node_id', $current_node.node_id, limit, $limit, sort_by, "count" ))}
				{elseif $current_node.object.class_identifier|eq('xrow_product')}
				    {eztagcloud( hash( 'parent_node_id', $current_node.parent.node_id, limit, $limit, sort_by, "count" ))}
				{else}
				    {eztagcloud( hash( 'parent_node_id', $node.node_id, limit, $limit, sort_by, 'count'))}
				{/if}
				
			</div>
			<p class="read_more"><a href="{concat('content/keyword/(classid)/', $current_node.object.contentclass_id )|ezurl('no')}" title="{$node.name|wash()}">{'View All Tags'|i18n('extension/xrowecommerce')}</a></p>
		</div>
	</div>
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>