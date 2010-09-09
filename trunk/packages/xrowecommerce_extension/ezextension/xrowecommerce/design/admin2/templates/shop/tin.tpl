<form name="list" method="post" action={concat( 'xrowecommerce/tin', '/(offset)/', $view_parameters.offset, '/(limit)/',$view_parameters.limit )|ezurl}>

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Tax identification numbers'|i18n( 'extension/xrowecommerce')} ({$list_count}) </h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">
<div class="context-attributes">
{if $list_count|gt(0)}
<table class="list">
    <tr>
        <th>{'Name'|i18n( 'extension/xrowecommerce')}</th>
        <th class="tight">{'Tax ID'|i18n( 'extension/xrowecommerce')}</th>
        <th>{'Company name'|i18n( 'extension/xrowecommerce')}</th>
        <th class="tight">{'Status'|i18n( 'extension/xrowecommerce')}</th>
        <th class="tight">&nbsp;</th>
    </tr>
   {foreach $list as $object}
       <tr>
        <td><a href={$object.main_node.url_alias|ezurl}>{$object.name|wash}</a></td>
        <td><input name="ContentObject[{$object.id}][tax_id]" value="{$object.data_map.tax_id.content|wash}" /></td>
        <td>{$object.data_map.company_name.content|wash}</td>
        <td>
        <select size="1" name="ContentObject[{$object.id}][status]">
<option value="0"{if $object.data_map.tax_id.data_int|eq('0')} selected{/if}>{'not validated'|i18n( 'extension/xrowtin' )}</option>
<option value="1"{if $object.data_map.tax_id.data_int|eq('1')} selected{/if} disabled>{'validated'|i18n( 'extension/xrowtin' )}</option>
<option value="2"{if $object.data_map.tax_id.data_int|eq('2')} selected{/if}>{'validated by administration'|i18n( 'extension/xrowtin' )}</option>
</select>
        </td>
        <td>
<img src={'edit.gif'|ezimage} onclick="document.forms['edit-{$object.id}'].submit();" />
</td>
    </tr>
   {/foreach}
</table>
{/if}

{if $list_count|eq(0)}
<div class="block">
<p>{'The order list is empty.'|i18n( 'design/admin/shop/orderlist' )}</p>
</div>
{/if}

</div>


<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/xrowecommerce/tin'
         item_count=$list_count
         view_parameters=$view_parameters
         item_limit=$limit}
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<div class="button-right">
    <input class="button" type="submit" name="Save" value="{'Apply changes'|i18n( 'design/admin/shop/orderlist' )}" title="{'Click this button to store changes if you have modified any of the fields above.'|i18n( 'design/admin/shop/orderlist' )}" />
</div>
<div class="break"></div>
</div>

{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</form>

{foreach $list as $object}
        <form name="edit-{$object.id}" method="post" action={concat( 'content/action' )|ezurl}>
        <input type="hidden" name="ContentObjectID" value="{$object.id}" />
        <input type="hidden" name="RedirectURIAfterPublish" value="xrowecommerce/tin/(offset)/{$view_parameters.offset}/(limit)/{$view_parameters.limit}" />

        <input type="hidden" value="yes" name="EditButton" />
        </form>
{/foreach}