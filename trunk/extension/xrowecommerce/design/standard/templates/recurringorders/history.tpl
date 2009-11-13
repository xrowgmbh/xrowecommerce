<form action={concat( '/shop/customerlist' )|ezurl} method="post">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Messages [%history]'|i18n( 'design/admin/shop/customerlist',, hash( '%history', $history_list_count ) )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-bl"><div class="box-br"><div class="box-content">

{if $history_list_count|gt(0)}
<table class="list" cellspacing="0">
<tr>
	<th class="wide">{'Text'|i18n( 'design/admin/shop/customerlist' )}</th>
	<th class="tight">{'Type'|i18n( 'design/admin/shop/customerlist' )}</th>
	<th class="tight">{'Date'|i18n( 'design/admin/shop/customerlist' )}</th>
	<th class="tight">{'Order ID'|i18n( 'design/admin/shop/customerlist' )}</th>
	<th class="tight">{'Collection ID'|i18n( 'design/admin/shop/customerlist' )}</th>
	<th class="tight">{'User ID'|i18n( 'design/admin/shop/customerlist' )}</th>
</tr>
    {foreach $history_list as $item sequence array( bglight, bgdark ) as $sequence }

    <tr class="{$sequence}">
        <td class="name" align="right">{$item.data_text}</td>
        <td class="name" align="right">{$item.type_text}</td>
        <td class="name" align="right">{$item.date|l10n( 'shortdate' )}</td>
        <td class="number" align="right">
             <a href={concat( '/shop/orderview/', $item.order_id )|ezurl}>{$item.order_id}</a>
        </td>
    	<td class="number" align="right">
    	    <a href={concat( '/recurringorders/list/', $item.collection_id )|ezurl}>{$item.collection_id}</a>
    	</td>
    	<td class="number" align="right">
    	{if is_set($item.collection.user_id)}<a href={concat( '/shop/customerorderview/', $item.collection.user_id )|ezurl}>{$item.collection.user_id}</a>{/if}
    	</td>
    </tr>

    {/foreach}



{undef}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/recurringorders/history'
         item_count=$history_list_count
         view_parameters=$view_parameters
         item_limit=$limit}
</div>

{else}
<div class="block">
<p>{'The message list is empty.'|i18n( 'design/admin/shop/customerlist' )}</p>
</div>
{/if}

{* DESIGN: Content END *}</div></div></div></div></div></div>

</div>

</form>
