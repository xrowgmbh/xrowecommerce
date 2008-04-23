
 {* //////////////////////////////////////////////////////////////////////////////// *}

 {def $cats=fetch( 'content', 'list', hash(	'parent_node_id', $parent_node  ,
              								'sort_by', array( array( 'priority' ) ),
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'category' ),
              							  	'limit', $limit ) )}
  {def $child_count=fetch('content','list_count',hash('parent_node_id',$parent_node ))}

  {* //////////////////////////////////////////////////////////////////////////////// *}

  <h4><a href={$url|ezurl()} style="text-decoration:none;">{$title}</a></h4>
  <ul>
  {foreach $cats as $subcat}
    <li class="frntmenu"><a href={$subcat.url_alias|ezurl()} class="subitem">{$subcat.name|downcase()}</a></li>
    {def $parent_node_url_alias=$subcat.parent.url_alias}
  {/foreach}

  {if $child_count|ge($limit)}
 	 <li><a href={$parent_node_url_alias|ezurl()} class="more">more...</a></li>
  </ul>
  {else}
  </ul>
  {/if}

  {* //////////////////////////////////////////////////////////////////////////////// *}
