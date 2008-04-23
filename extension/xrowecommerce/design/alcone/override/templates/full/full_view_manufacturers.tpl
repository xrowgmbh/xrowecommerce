<div id="col_left">
    {def $cats=fetch( 'content', 'list', hash(	'parent_node_id', 61,
         				  	'sort_by', array( array( 'name' ) ),
            			  	'class_filter_type',  'include',
                         	'class_filter_array', array( 'category' ) ) )
    }
    {if gt($cats|count,0)}
	       <ul>
	           {foreach $cats as $cat}
	               <li>
	                  {if eq( $cat.node_id, $node_subcat1)}
		                  <span style="color: #f57900; font-weight: bold;">{$cat.name}</span>
		              {else}
		                  <a href={$cat.url_alias|ezurl}>{$cat.name}</a>
		              {/if}
	               </li>
	           {/foreach}
	       </ul>
	   {/if}
</div>


    {def  $prods=fetch( 'content', 'list', hash('parent_node_id', '128',
            					'sort_by', array( array( 'name' ) ),
              					'class_filter_type',  'include',
              					'class_filter_array', array( 'manufacturer' ) ) )
    }

    <div id="col_middle">
      <h2>{$node.name|upcase()}</h2>
    <div>

<br />
<div style="width: 50%; float: left;">
        {if gt($prods|count(),0)}
	       {foreach $prods as $index => $prod}
	          <p><img src={"images/arrow.gif"|ezdesign()}> <a href={$prod.url_alias|ezurl()}>{$prod.name}</a><br /><br /></p>
          {delimiter modulo=sum(div($prods|count(),2),1)}</div><div style="width: 50%; float: right;">{/delimiter}
          {/foreach}
	    {/if}
</div>
</div>

   <div class="break"></div>
</div>



{* Sidebar - Right *}
<div id="col_right">



 <h2>In the know</h2>
 <span class="headingitk">Common Makeup Questions</span><br />
 <br />
 {def $intheknown=fetch( 'content', 'tree', hash( 'parent_node_id', 119,
              					  'sort_by', array( array( 'priority' ) ),
              				   	  'class_filter_type',  'include',
              					  'class_filter_array', array( 'in_the_known' ),
              					  'limit', 9 ) )}
 {foreach $intheknown as $known}<a href={concat("/know_it_all/(question)/",$known.node_id)|ezurl()}>{$known.name}</a><br /><br />{/foreach}
 <br />
</div>
