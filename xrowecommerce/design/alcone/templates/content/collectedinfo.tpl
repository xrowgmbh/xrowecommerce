<div id="col_main" >
	<h2>Contact Us</h2>
	<br />
	
	
	
{section loop=$collection.attributes}

<h3>{$:item.contentclass_attribute_name|wash}</h3>

{attribute_result_gui attribute=$:item}

{/section}


</div>