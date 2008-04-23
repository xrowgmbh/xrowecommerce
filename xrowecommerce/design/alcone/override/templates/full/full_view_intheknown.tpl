{* Product - Full view *}

<div id="product_full">
        <h2>{$node.name|wash()}</h2>
        <br />
        <div id="intheknown_left">
        {if is_set($view_parameters.question)}
        {def $question=fetch( 'content', 'node', hash( 'node_id', $view_parameters.question ) )}
        	<span class="headingintheknownleft">{$question.name|wash()}</span><br />
		  	{attribute_view_gui attribute=$question.data_map.answer}
  	  		<br />
  	  		<br />

                        <a href="javascript:history.go(-1)">Back...&nbsp;<img src={"images/arrow.gif"|ezdesign()}></a>
         	  	{* <a href={"/know_it_all"|ezurl()}>Back...&nbsp;<img src={"images/arrow.gif"|ezdesign()}></a> *}
  	  		<br />
        {else}
            {def
            $page_limit=40
            $list_count=0
            $all=fetch( 'content', 'list', hash(	'parent_node_id', 119,
              							  	'sort_by', array( array( 'priority' ) ),
              							  	'limit', $page_limit,
              							  	'offset', $view_parameters.offset,
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'in_the_known' ),
              							  	'sort_by', array('published', false() ) ) )
           $list_count=fetch( 'content', 'list_count', hash(	'parent_node_id', 119,
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'in_the_known' ) ) )

            }
			<span class="latestquestion">Latest Questions:</span><br /><br />
			<table border="0">
			{foreach $all as $latest}
    		  	<tr><td valign="top" width="30"><img src={"images/arrow.gif"|ezdesign()} /></td><td><a href={concat("/know_it_all/(question)/",$latest.node_id)|ezurl()}><span class="headingintheknownleft">{$latest.name|wash()}</span></a></td></tr>
    		  	<tr><td colspan="2"><div id="dottedline_long"></div></td></tr>
		  	{/foreach}
		  	</table>
		  	{include name=navigator
                     uri='design:navigator/google.tpl'
                     page_uri=$node.url_alias
                     item_count=$list_count
                     view_parameters=$view_parameters
                     item_limit=$page_limit}
        {/if}
		</div>

		<div id="intheknown_right">
		<span class="headingcommon">Common questions this month</span><br /><br />
		{def $commons=fetch( 'content', 'list', hash(	'parent_node_id', 119,
              							  	'sort_by', array('published', false() ),
              							  	'limit',  9,
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'in_the_known' ),
              							  	'attribute_filter', array( array( 'in_the_known/common',
                                                '=',
                                                true() ) )
              							  	 ) )}
           {foreach $commons as $common}
		  	<a href={concat($node.url_alias, "/(question)/",$common.node_id)|ezurl()}>{$common.name|wash()}</a><br />
  	  		<br />
		  	{/foreach}
		</div>
   </div>
