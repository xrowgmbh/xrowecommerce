{if eq($DesignKeys:used.depth,3)}
    {def $node_cat=$node.node_id}
{/if}
{if eq($DesignKeys:used.depth,4)}
        {def
            $node_cat=$node.parent.node_id
            $node_subcat1=$node.node_id
    }
{/if}

{if eq($DesignKeys:used.depth,5)}
        {def
            $node_cat=$node.parent.parent.node_id
            $node_subcat1=$node.parent.node_id
            $node_subcat2=$node.node_id
    }
{/if}
{if eq($DesignKeys:used.depth,6)}
        {def
            $node_cat=$node.parent.parent..parent.node_id
            $node_subcat1=$node.parent.parent.node_id
            $node_subcat2=$node.parent.node_id
    }
{/if}
        {def 
         $cats1=fetch( 'content', 'list', hash(	'parent_node_id', $node_cat,
              							  	'sort_by', array( array( 'priority' ) ),
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'category' ),
              							  	'limit', 20 ) ) 
              							  	
	    $cats2=fetch( 'content', 'list', hash(	'parent_node_id', $node_subcat1,
              							  	'sort_by', array( array( 'priority' ) ),
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'category' ),
              							  	'limit', 20 ) )
              							  	 }
              							  	
	   

	   {if gt($cats1|count,0)}
	       <ul>
	           {foreach $cats1 as $cat}
	              
	               {if eq( $cat.node_id, $node_subcat1)}
		                  <li><span style="color: #f57900; font-weight: bold;"><a href={$cat.url_alias|ezurl}>{$cat.name}</span></li>
		                  
		                  	   {if gt($cats2|count(),0)}
    	       {foreach $cats2 as $subcat}
    	           <li class="subnav">
    	               {if eq( $subcat.node_id, $node_subcat2)}
    		              <span style="color: #f57900; font-weight: bold;">{$subcat.name}</span>
    		           {else}
    		              <a href={$subcat.url_alias|ezurl}>{$subcat.name}</a>
    	               {/if}
    	           </li>
    	       {/foreach}
                            {/if}
		                  
		              {else}
		                  <li><a href={$cat.url_alias|ezurl}>{$cat.name}</a></li>
		              {/if}
	               
	           {/foreach}
	       </ul>
	   {/if}
