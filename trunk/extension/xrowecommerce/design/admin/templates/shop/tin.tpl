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
       <tr class="d">
        <td><a href={$object.main_node.url_alias|ezurl}>{$object.name|wash}</a></td>
        <td><span style="white-space: nowrap">{$object.data_map.tax_id.content|wash}</span></td>
        <td>{$object.data_map.company_name.content|wash}</td>
        <td><span style="white-space: nowrap">
{if $object.data_map.tax_id.data_int|eq('0')}{'not validated'|i18n( 'extension/xrowtin' )}{/if}
{if $object.data_map.tax_id.data_int|eq('1')}{'validated'|i18n( 'extension/xrowtin' )}{/if}
{if $object.data_map.tax_id.data_int|eq('2')}{'validated by administration'|i18n( 'extension/xrowtin' )}{/if}
        </span></td>
        <td>
        <form method="post" action={concat( 'content/action' )|ezurl}>
        <input type="hidden" name="ContentObjectID" value="{$object.id}" />
        <input type="hidden" name="RedirectURIAfterPublish" value="xrowecommerce/tin/(offset)/{$view_parameters.offset}/(limit)/{$view_parameters.limit}" />
        <input type="image" src={'edit.gif'|ezimage} name="EditButton" />
        </form>
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
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>
