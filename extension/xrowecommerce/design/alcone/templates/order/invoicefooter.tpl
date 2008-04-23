{def $node = fetch( 'content', 'node', hash( 'node_id', 3124 ) )}
{if and($node.data_map.description.content.is_empty|not, $packingslip )}
    <div style="clear:both;border: 1px dotted grey;padding: 1em;">
        {attribute_view_gui attribute=$node.data_map.description}
    </div>
{/if}