<form action={concat( '/recurringorders/listitems' )|ezurl} method="post">
{foreach $messages as $message}
<div class="message-{$message.type}">
    <h2>{$message.text}</h2>
</div>
{/foreach}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Subscriptions'|i18n( 'extension/recurringorders' )}  [{$item_count}]</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{if $item_count}

<table class="list" cellspacing="0">
    <tr>
    <th class="number" align="right">ID</th>
    <th class="name" align="right">Name</th>
    <th class="name" align="right">User</th>
    <th class="name" align="right">Created</th>
    <th class="name" align="right">Next Cycle</th>
    <th class="name" align="right">Type</th>
    <th class="name" align="right">Amount</th>
    
    <th class="name">Status</th>
    </tr>

{foreach $items as $item sequence array( bglight, bgdark ) as $sequence }

    <tr class="{$sequence}">
    <td class="tight">{$item.item_id}</td>
    <td class="wide">{$item.object.name}</td>
    <td class="wide">{$item.user.contentobject.name}</td>
    <td class="tight">
    {$item.created||l10n( 'shortdate' )}
    </td>
    <td class="tight" >
    {$item.next_date||l10n( 'shortdate' )}
    </td>
    <td class="tight">
    {$item.subscription_handler}
    </td>
    <td class="number" align="right">
    {$item.price|l10n( 'currency' )}
    </td>
    <td class="tight" align="right">
    <select name="ItemArray[{$item.item_id}][status]">
    <option value="1" {if $item.status}selected{/if}>Activated</option>
    <option value="0" {if $item.status|not}selected{/if}>Deactivated</option>
    </select>
    {if $item.status|not}<label><input name="ItemArray[{$item.item_id}][refund]" value="1" type="checkbox" />Refund till current cycle</label>{/if}
    </td>

    </tr>

{/foreach}



{undef}
</table>

<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/recurringorders/listitems'
         item_count=$item_count
         view_parameters=$view_parameters
         item_limit=$limit}
</div>
{/if}


{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="Update" value="{'Update'|i18n( 'design/admin/section/edit' )}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>


</div>



</form>
