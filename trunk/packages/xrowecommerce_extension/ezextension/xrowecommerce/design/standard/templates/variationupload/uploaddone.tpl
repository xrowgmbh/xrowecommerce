{set scope=global persistent_variable=hash('title', 'Upload new'|i18n('design/standard/ezoe'),
                                           'css', array()
                                           )}
   
<div class="upload-view">
<div class="panel_wrapper" style="height: 360px;">
        <div class="panel">
            <p>Upload done...</p>
            {*$newObject.main_node.data_map.image.content.small.url|attribute(show)*}
            {attribute_view_gui image_class=small attribute=$newObject.current.data_map.image}
            <p>
            <input type="button" name="okbutton" value="OK" onClick="javascript:updateobject();"/>
            </p>
		</div>
	</div>
</div>
<script type="text/javascript">
function updateobject() 
{ldelim}
	opener.document.getElementById('variation_image_id_{$variationName}_{$variationID}').value='{$newObject.id}';
	var d = opener.document.getElementById('variation_image_div_{$variationName}_{$variationID}');
	if( d.hasChildNodes() && opener.document.getElementById('variation_image_link_{$variationName}_{$variationID}') != null )
	{ldelim}
    	var olddiv = opener.document.getElementById('variation_image_link_{$variationName}_{$variationID}');
        d.removeChild(olddiv);
    {rdelim}
    else
    {ldelim}
    	var olddiv = opener.document.getElementById('variation_noimage_div_{$variationName}_{$variationID}');
       	d.removeChild(olddiv);
    {rdelim}
	var newdiv = opener.document.createElement('a');
  newdiv.setAttribute('id','variation_image_link_{$variationName}_{$variationID}');
  newdiv.setAttribute('href','{$newObject.main_node.url_alias|ezurl('no')}');
  newdiv.setAttribute('target','_blank');
  newdiv.innerHTML = '<img id="variation_image_{$variationName}_{$variationID}" style="border: 0px none;" alt="{$newObject.name|wash()}" title="{$newObject.name|wash()}" width="{$newObject.main_node.data_map.image.content.small.width}" height="{$newObject.main_node.data_map.image.content.small.height}"/>';
  d.appendChild(newdiv);
  var newimage = opener.document.getElementById('variation_image_{$variationName}_{$variationID}');
  newimage.setAttribute('src','{$newObject.main_node.data_map.image.content.small.url|ezroot(no)}');
  window.close();
{rdelim}
</script>
{*
variation_image_link_{$attribute.id}
variation_image_id_{$attribute.id}
<a id="variation_image_link_{$attribute.id}" href={$Options.item.image.main_node.url_alias|ezurl()} target="_blank">
{attribute_view_gui image_class=small attribute=$Options.item.image.current.data_map.image}
</a>
*}
