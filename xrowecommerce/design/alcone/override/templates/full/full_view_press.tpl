<div id="col_main" >
    <br />
    <h2>{$node.name|wash()}</h2>
	<br />
	{attribute_view_gui attribute=$node.data_map.description}
	<br />
		
	{def $press_re=fetch( 'content', 'list', hash(	'parent_node_id', $node.node_id,
              							  	'sort_by', array( array( 'priority' ) ),
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'press_release' ) ) )}
	
  	{foreach $press_re as $press}
        <div style="width: 100%; border-bottom: 15px;">
            <div style="float: left;  padding: 2px 20px 2px 20px; width: 65px;">
                <img src="{$press.data_map.image.content.current.data_map.image.content.original.url}">
            </div>
            <div style="float: left; width: 400px;">
                {$press.data_map.press.content<br />
                <b>{$press.name}</b>
                {attribute_view_gui attribute=$press.data_map.content}
            </div>
        </div>
        <div class="break"></div>
        <br />
        <div style="height: 5px;width: 100%; background-image: url('/extension/alcone/design/alcone/images/dotted.gif'); background-position: bottom; background-repeat:repeat-x;">
        </div>
        <br />
    {/foreach}
</div>