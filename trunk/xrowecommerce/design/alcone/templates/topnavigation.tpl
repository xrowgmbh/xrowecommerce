{literal}
<style type="text/css">

<!--

#nav *
{
    list-style: none;
    z-index: 6000;
    margin: 0px;
}
ul#nav
{
    padding-top: 2px;
}
#nav li
{
                position: relative;
                text-align: left;
                cursor: none;
}

li ul
  	{
        margin: 5px 0px 5px 0px;
        white-space:nowrap;
        padding-top: 3px;
        font-size: 11px;

        display: none;
		position: absolute;
		left: 0;
		top: 12px;
		background-color: white;
		border: 1px solid #a0a3b4;
		text-decoration: none;
		text-transform: lowercase;

	}

#nav li:hover ul, li.over ul
{
		display: block;
}

#nav li ul li a:hover
{
       color: #aac708 !important;
}

#nav li ul li
{
    /* a is overriden somewhere else we could drop this. */
       margin: 0px 10px 0px 10px !important;
       padding: 0px 0px 0px 0px !important;
       display: block;
       border: 1px solid #fff;
}
#nav li ul li a
{
    /* a is overriden somewhere else we could drop this. */
       margin: 0px !important;
       padding: 0px 3px 0px 3px !important;
    display: block;


}
-->
</style>

{/literal}
{def $cats=fetch( 'content', 'list', hash(	'parent_node_id', '61',
              							  	'sort_by', array( array( 'priority' ) ),
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'category','newsletter_promo' ),
              							  	'limit', 20 ) )}


<div id="toolbar-top">
	<div class="toolbar-design1">
		<ul id="nav">
		{def $subcats=''}
		{foreach $cats as $index => $cat}
         	<LI class=
				{if eq($index,'0')}"toolbar-item first"
				{elseif eq($cats|count|sub(1), $index)}"toolbar-item last"
				{else}"toolbar-item"
				{/if}
				{if eq($module_result.path.2.node_id,$cat.node_id)}
    				style="color: #F57900;"
				{/if}
				>

         		<a href={$cat.url_alias|ezurl()}>{$cat.name|wash}</a>
       				{set $subcats=fetch( 'content', 'list', hash(	'parent_node_id', $cat.node_id,
              							  	'sort_by', array( array( 'priority' ) ),
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'category' ),
              							  	'limit', '20' ) )}
              							  	{if gt( count($subcats), 0)}
              	<ul>
              		{foreach $subcats as $subcat}
              	   		<LI><a href={$subcat.url_alias|ezurl()} class="subitem">{$subcat.name|downcase()|wash}</a></LI>
              	   		{/foreach}
         		</ul>

         		{/if}
         		</LI>
        {/foreach}
		</ul>
	</div>
	<div class="break"></div>
</div>
