{def
            $node_cat=$node.parent.node_id
            $node_subcat1=$node.node_id
    }
{def    $node_cat=$node.parent.node_id
        $node_subcat1=$node.node_id 
        $cats1=fetch( 'content', 'list', hash(	'parent_node_id', $node_cat,
                  							  	'sort_by', array( array( 'priority' ) ),
                  							  	'class_filter_type',  'include',
                  							  	'class_filter_array', array( 'manufacturer' ),
                  							  	'limit', 20 ) ) 
{if gt($cats1|count,0)}
    <ul>
       {foreach $cats1 as $cat}
           {if eq( $cat.node_id, $node_subcat1)}
                  <li><span style="color: #f57900; font-weight: bold;">{$cat.name}</span></li>
              {else}
                  <li><a href={$cat.url_alias|ezurl}>{$cat.name}</a></li>
              {/if}
       {/foreach}
    </ul>
{/if}
