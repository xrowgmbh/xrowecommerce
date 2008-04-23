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
  	  		<a href={"/faqs"|ezurl()}>Back...&nbsp;<img src={"images/arrow.gif"|ezdesign()}></a>
  	  		<br />
        {else}
            {def
            $page_limit=3
            $list_count=0
            $all=fetch( 'content', 'list', hash(	'parent_node_id', 119,
              							  	'sort_by', array( array( 'priority' ) ),
              							  	'limit', 3,
              							  	'offset', $view_parameters.offset,
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'faq' ),
              							  	'sort_by', array('published', false() ) ) )
           $list_count=fetch( 'content', 'list_count', hash(	'parent_node_id', 119,
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'faq' ) ) )
            
            }
			<span class="latestquestion">Latest Questions:</span><br /><br />
		  	{foreach $all as $latest}
		  	<a href={concat("/in_the_know/(question)/",$latest.node_id)|ezurl()}><span class="headingintheknownleft">{$latest.name|wash()}</span></a><br />
		  	{attribute_view_gui attribute=$latest.data_map.answer}asd
  	  		<br />
		  	{/foreach}
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
              							  	'class_filter_array', array( 'faq' ),
              							  	'attribute_filter', array( array( 'faq/common',
                                                '=',
                                                true() ) )
              							  	 ) )}
           {foreach $commons as $common}
		  	<a href={concat($node.url_alias, "/(question)/",$common.node_id)|ezurl()}>{$common.name|wash()}</a><br />
		  	{attribute_view_gui attribute=$latest.data_map.common}
  	  		<br />
		  	{/foreach}
		</div>
   </div>