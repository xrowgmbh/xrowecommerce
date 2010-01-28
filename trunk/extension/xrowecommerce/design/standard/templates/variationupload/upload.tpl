{set scope=global persistent_variable=hash('title', 'Upload new'|i18n('design/standard/ezoe'),
                                           'css', array()
                                           )}
   
<div class="upload-view">
<form action={concat('variationupload/upload/', $object_id, '/', $object_version, '/auto/', $variationName, '/', $variationID )|ezurl} method="post" name="EmbedForm" id="EmbedForm" enctype="multipart/form-data">
{*<input type="button" name="Aktualisieren" value="aktualisieren" onClick="javascript:opener.document.getElementById('variation_weight_128939').value='7'"/>*}
       
<div class="panel_wrapper" style="height: 360px;">
        <div class="panel">
        <p>
        Please select an image for your variation:
        </p>
            <table class="properties">
                <tr>
                    <td class="column1"><label id="srclabel" for="src">{'File'|i18n('design/standard/ezoe')}</label></td>
                    <td colspan="2"><input name="fileName" type="file" id="fileName" value="" /></td>
                </tr>
                <tr id="embedlistsrcrow">
                    <td class="column1"><label for="location">{'Location'|i18n('design/standard/ezoe')}</label></td>
                    <td colspan="2" id="embedlistsrccontainer">
                      <select name="location" id="location">
                        <option value="auto">{'Automatic'|i18n('design/standard/ezoe')}</option>

                        {if $object.published}
                            <option value="{$object.main_node_id}">{$object.name} (this)</option>
                        {/if}

                        {def $root_node_value = ezini( 'LocationSettings', 'RootNode', 'upload.ini' )
                             $root_node = cond( $root_node_value|is_numeric, fetch( 'content', 'node', hash( 'node_id', $root_node_value ) ),
                                             fetch( 'content', 'node', hash( 'node_path', $root_node_value ) ) )
                             $selection_list = fetch( 'content', 'tree',
                                                     hash( 'parent_node_id', $root_node.node_id,
                                                           'class_filter_type', include,
                                                           'class_filter_array', ezini( 'LocationSettings', 'ClassList', 'upload.ini' ),
                                                           'depth', ezini( 'LocationSettings', 'MaxDepth', 'upload.ini' ),
                                                           'depth_operator', 'lt',
                                                           'load_data_map', false(),
                                                           'limit', ezini( 'LocationSettings', 'MaxItems', 'upload.ini' ) ) )}
                        {foreach $selection_list as $item}
                        {if $item.can_create}
                            <option value="{$item.node_id}">{'&nbsp;'|repeat( sub( $item.depth, $root_node.depth, 1 ) )}{$item.name|wash}</option>
                        {/if}
                        {/foreach}

                      </select>
                    </td>
                </tr>
                <tr> 
                    <td class="column1"><label id="titlelabel" for="title">{'Name'|i18n('design/standard/ezoe')}</label></td> 
                    <td colspan="2"><input id="objectName" name="objectName" type="text" value="" /></td> 
                </tr>
                <tr>
                {if $content_type|eq('image')}
                    <td class="column1"><label id="titlelabel" for="title">{'Caption'|i18n('design/standard/ezoe')}</label></td> 
                    <td colspan="2"><input id="objectText" name="ContentObjectAttribute_caption" type="text" value="" size="32" /></td>
                {else}
                    <td class="column1"><label id="titlelabel" for="title">{'Description'|i18n('design/standard/ezoe')}</label></td> 
                    <td colspan="2"><input id="objectText" name="ContentObjectAttribute_description" type="text" value="" size="32" /></td>
                {/if} 
                </tr>
                <tr> 
                    <td colspan="3">
                    <input id="uploadButton" name="uploadButton" type="submit" value="{'Upload local file'|i18n('design/standard/ezoe')}" />
                    <span id="upload_in_progress" style="display: none; color: #666; background: #fff url({"stylesheets/skins/default/img/progress.gif"|ezdesign('single')}) no-repeat top left scroll; padding-left: 32px;">{'Upload is in progress, it may take a few seconds...'|i18n('design/standard/ezoe')}</span>
                    </td> 
                </tr>
            </table>
        </div>
		</div>
     </form>
</div>
