{def $content=$attribute.content
     $id=$attribute.id
     $error=$content.error
     $template=$content.template
     $data=false()
     $show_hidden_fields=cond( ezini( 'PriceSettings', 'ShowHiddenFields', 'xrowproduct.ini' )|eq( 'true' ), true(), false() )
     $column_name_array=$content.column_name_array
     $column_desc_array=$content.column_desc_array
}
{if $content.data|count|gt(0)}

<div class="block">
{if $content.template_id|gt(0)}
<label>{'Product template'|i18n('extension/xrowecommerce/productvariation')}:</label>

{$template.name|wash}
{/if}

</div>

<table cellpadding="0" cellspacing="0" border="0" width="100%" class="list">
<tbody id="variationbody_{$id}">
<tr class="bgdark">
{foreach $content.template.attribute_list as $key => $template_item}

{if or( $show_hidden_fields, $template_item.frontend )}
    <th title="{cond( is_set( $column_desc_array[$template_item.attribute.identifier] ), $column_desc_array[$template_item.attribute.identifier]|wash, $template_item.column_desc|wash )}">{cond( is_set( $column_name_array[$template_item.attribute.identifier] ), $column_name_array[$template_item.attribute.identifier]|wash, $template_item.column_name|wash )}</th>
{/if}

{/foreach}

</tr>

{foreach $content.data as $line => $data_array}

<tr id="variationtemplate_{$id}_{$line}">

{foreach $content.template.attribute_list as $key => $template_item}
{set $data=$data_array[$template_item.attribute.identifier]}

{if or( $show_hidden_fields, $template_item.frontend )}

    <td valign="top" id="variationtemplate_td_{$id}_{$line}_{$template_item.attribute.identifier}">

            {include uri=concat( "design:productvariation/variation/view/", $data.template_item.attribute.data_type, ".tpl" )
                     attribute=$attribute
                     error=$error
                     data=$data
                     content=$data.content
                     line=$line
                     column=$template_item.attribute.identifier}
    </td>
{/if}
{/foreach}

</tr>

{/foreach}

</tbody>
</table>

{else}

    <p>{"No product variations entered."|i18n('extension/xrowecommerce/productvariation')}</p>

{/if}

