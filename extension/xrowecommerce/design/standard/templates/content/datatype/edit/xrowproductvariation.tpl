{def $content=$attribute.content
     $id=$attribute.id
     $error=$content.error
     $template=$content.template
     $data=false()
     $index=0
}
{if $content.data|count|gt(0)}{set $index=$content.data|count}{/if}

{* $content.data|attribute(show) *}

<script type="text/javascript">
<!--
    var newindex={$index};
// -->
</script>
<div class="block">
<label>{'Product template'|i18n('extension/xrowecommerce/productvariation')}:</label>

<select name="XrowProductTemplate_templateid_{$id}">
{foreach $content.template_list as $item}
    <option value="{$item.id}"{if $content.template_id|eq( $item.id )} selected="selected"{/if}>{$item.name|wash}</option>
{/foreach}
</select>

{if $content.template_id|gt(0)}
<input class="button" type="submit" name="CustomActionButton[{$id}_set-template]" value="{'Change template'|i18n('extension/xrowecommerce/productvariation')}" title="{'Change template'|i18n('extension/xrowecommerce/productvariation')}" onclick="return xrow_confirm( '{'You try to change the template. Please confirm this action.'|i18n('extension/xrowecommerce/productvariation')}', 'editform' );" />
</div>
{else}
    <input class="button" type="submit" name="CustomActionButton[{$id}_set-template]" value="{'Choose template'|i18n('extension/xrowecommerce/productvariation')}" title="{'Choose template'|i18n('extension/xrowecommerce/productvariation')}" />
</div>
{/if}

{if $content.template_id|gt(0)}

<div style="display:none;">
<table cellpadding="0" cellspacing="0" border="0" width="100%">
    <tbody>
        <tr id="variationtemplate_{$id}">
            <td valign="top" class="tight"><img src={"trash-icon-16x16.gif"|ezimage} alt="{"Delete attribute"|i18n( 'extension/xrowecommerce/productvariation' )|wash}"  width="16" height="16" onclick="return this.parentNode.parentNode.parentNode.removeChild( this.parentNode.parentNode );" /></td>
{foreach $content.template.attribute_list as $variation_attribute_id => $variation_attribute}
            <td valign="top" id="variationtemplate_td_{$id}_{$variation_attribute_id}_{$variation_attribute.attribute.data_type}">
            {include uri=concat( "design:productvariation/variation/default/", $variation_attribute.attribute.data_type, ".tpl" )
                     attribute=$attribute
                     error=$error
                     template=$template
                     content=$content
                     variation_attribute=$variation_attribute
                     variation_attribute_id=$variation_attribute_id}
            </td>
{/foreach}
            <td valign="top" class="tight"><img src={"button-move_up.gif"|ezimage} alt="Move up"  width="16" height="16" onclick="return xrow_move(  {ldelim} direction: 'up', tr: this.parentNode.parentNode {rdelim} );" /></td>
        <td valign="top" class="tight"><img src={"button-move_down.gif"|ezimage} alt="Move down" width="16" height="16" title="Move down" onclick="return xrow_move( {ldelim} direction: 'down', tr: this.parentNode.parentNode {rdelim});" /></td>
        </tr>
    </tbody>
</table>
</div>

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="list">
<tbody id="variationbody_{$id}">
<tr class="bgdark">
    <th>&nbsp;</th>
{foreach $content.template.attribute_list as $key => $template_item}

    <th valign="top" title="{$template_item.column_desc|wash}">{$template_item.column_name|wash}</th>

{/foreach}
    <th>&nbsp;</th>
    <th>&nbsp;</th>
</tr>
{if $content.data|count|gt(0)}

{foreach $content.data as $line => $data_array}

<tr id="variationtemplate_{$id}_{$line}">
    <td valign="top" class="tight"><img src={"trash-icon-16x16.gif"|ezimage} alt="{"Delete attribute"|i18n( 'extension/xrowecommerce/productvariation' )|wash}"  width="16" height="16" onclick="return this.parentNode.parentNode.parentNode.removeChild( this.parentNode.parentNode );" /><input type="hidden" name="XrowProductVariation[{$attribute.id}][{$line}][id]" value="{$data_array.id}" /></td>
{foreach $content.template.attribute_list as $key => $template_item}
{set $data=$data_array[$template_item.attribute.identifier]}
    <td valign="top" id="variationtemplate_td_{$id}_{$line}_{$template_item.attribute.identifier}"{if is_set( $error[$line][$template_item.attribute.identifier] )} class="error"{/if}>

            {include uri=concat( "design:productvariation/variation/edit/", $data.template_item.attribute.data_type, ".tpl" )
                     attribute=$attribute
                     error=$error
                     data=$data
                     content=$data.content
                     line=$line
                     column=$template_item.attribute.identifier}
    </td>

{/foreach}

    <td valign="top" class="tight"><img src={"button-move_up.gif"|ezimage} alt="{"Move up"|i18n('extension/xrowecommerce/productvariation')}"  title="{"Move up"|i18n('extension/xrowecommerce/productvariation')}" width="16" height="16" onclick="return xrow_move(  {ldelim} direction: 'up', tr: this.parentNode.parentNode {rdelim} );" /></td>
    <td valign="top" class="tight"><img src={"button-move_down.gif"|ezimage} alt="{"Move down"|i18n('extension/xrowecommerce/productvariation')}" width="16" height="16" title="{"Move down"|i18n('extension/xrowecommerce/productvariation')}" onclick="return xrow_move( {ldelim} direction: 'down', tr: this.parentNode.parentNode {rdelim});" /></td>
</tr>

{/foreach}
{/if}

</tbody>
</table>

<input type="button" class="button" name="AddButton{$id}" value="{'Add line'|i18n('extension/xrowecommerce/productvariation')}" onclick="return addVariation( 'variationtemplate_{$id}', 'variationbody_{$id}' );" />


{/if}