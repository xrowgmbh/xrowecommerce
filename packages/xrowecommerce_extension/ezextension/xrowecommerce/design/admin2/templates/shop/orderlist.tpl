{def $show_payment_status=cond( and( ezini_hasvariable( 'StatusSettings', 'ShowPaymentStatus', 'xrowecommerce.ini' ), ezini( 'StatusSettings', 'ShowPaymentStatus', 'xrowecommerce.ini' )|eq( 'disabled' ) ), false(), true() )}
<!--
<link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/2.8.1/build/container/assets/skins/sam/container.css" />
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/yahoo-dom-event/yahoo-dom-event.js"></script>
<script type="text/javascript" src="http://yui.yahooapis.com/2.8.1/build/container/container-min.js"></script>
{if $order_list}
    {foreach $order_list as $order}
        {if $order.status_modification_list|count|gt( 0 )}
            {def $order_states = $order.status_modification_list}
            {break}
        {/if}
    {/foreach}
{/if}

{literal}

<style type="text/css">
.yui-overlay
{
    position:absolute;
    background:#fff;
    border:1px dotted black;
    padding:5px;
    margin:10px;
}
</style>
{/literal}

 -->

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h1 class="context-title">{'Customer search'|i18n( 'extension/xrowecommerce')}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="context-toolbar">

<div class="block">

<div class="break"></div>
{if and(is_set($emails), $emails|eq(2)) }
    <p>{'Multiple occurencies!'|i18n( 'extension/xrowecommerce')}</p>
{elseif and(is_set($emails), $emails|eq(0)) }
    <p>{'No match'|i18n( 'extension/xrowecommerce')}</p>
{else}
    <p>{'Search for customer in the system via e-mail address'|i18n( 'extension/xrowecommerce')}</p>
{/if}
</div>
</div>

<table class="list">
  <tr class="bglight">
  <th scope="row">{'Edit shippingcosts with this order number:'|i18n( 'extension/xrowecommerce')}</th>
    <td>
        <form name="orderedit" method="post" action={'orderedit/edit'|ezurl}>
            <input name="order_id" type="text" value="" size="50" />
            <input class="button" name="Edit" type="submit" value="Edit" />
        </form>
    </td>
  </tr>

    <tr>
        <th scope="row">{'E-mail address or Order No'|i18n( 'extension/xrowecommerce')}</th>
        <td>
            <form name="contentserver" method="post" action={'customersearch/search'|ezurl}>
                <input name="E-mail" type="text" {if is_set($email)}value="{$email}"{else}value=""{/if} size="50" />
                <input class="button" name="Search" type="submit" value="Search" />
            </form>
        </td>
  </tr>
</table>
{if and(is_set($emails),$emails|eq(2))}
<h3>Please select one of the following users:</h3>
<form name="contentserver" method="post" action={'customersearch/search'|ezurl}>
<table class="list" cellspacing="0">
        <tr>
            <th class="wide">{'Link'|i18n( 'extension/xrowecommerce')}</th>
            <th class="wide">{'ID'|i18n( 'extension/xrowecommerce')}</th>
            <th class="wide">{'E-mail'|i18n( 'extension/xrowecommerce')}</th>
        </tr>
        {foreach $userid as $email_item}
            <tr><td><a href="/shop/customerorderview/{$email_item.user_id}/{$email}">{'Show user'|i18n( 'extension/xrowecommerce')}</a></td><td>{if $email_item.user_id|eq(10)}(anonymous){else}{$email_item.user_id}{/if}</td><td>{$email}</td></tr>
        {/foreach}

</table>
</form>
{/if}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>
</div>

{if is_set($email)|not}
{let can_apply=false()}
<form name="orderlist" method="post" action={concat( '/xrowecommerce/orderlist', $view_parameters.offset|gt(0)|choose( '', concat( '/(offset)/', $view_parameters.offset ) ) )|ezurl}>
    <div class="context-block">

    {* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

    <h1 class="context-title">{'Orders [%count]'|i18n( 'design/admin/shop/orderlist',, hash( '%count', $order_list|count ) )}</h1>

    {* DESIGN: Mainline *}<div class="header-mainline"></div>

    {* DESIGN: Header END *}</div></div></div></div></div></div>

    {* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

    {if $order_list}
    <div class="context-toolbar">
    <div class="block">
    <div class="left">
    <p>
    {if eq( ezpreference( 'admin_orderlist_sortfield' ), 'user_name' )}
        <a href={'/user/preferences/set/admin_orderlist_sortfield/time/shop/orderlist/'|ezurl}>{'Time'|i18n( 'design/admin/shop/orderlist' )}</a>
        <span class="current">{'Customer'|i18n( 'design/admin/shop/orderlist' )}</span>
    {else}
        <span class="current">{'Time'|i18n( 'design/admin/shop/orderlist' )}</span>
        <a href={'/user/preferences/set/admin_orderlist_sortfield/user_name/shop/orderlist/'|ezurl}>{'Customer'|i18n( 'design/admin/shop/orderlist' )}</a>
    {/if}
    </p>
    </div>
    <div class="right">
    <p>
    {if eq( ezpreference( 'admin_orderlist_sortorder' ), 'desc' )}
        <a href={'/user/preferences/set/admin_orderlist_sortorder/asc/shop/orderlist/'|ezurl}>{'Ascending'|i18n( 'design/admin/shop/orderlist' )}</a>
        <span class="current">{'Descending'|i18n( 'design/admin/shop/orderlist' )}</span>
    {else}
        <span class="current">{'Ascending'|i18n( 'design/admin/shop/orderlist' )}</span>
        <a href={'/user/preferences/set/admin_orderlist_sortorder/desc/shop/orderlist/'|ezurl}>{'Descending'|i18n( 'design/admin/shop/orderlist' )}</a>
    {/if}
    </p>
    </div>

    <div class="break"></div>
    {if is_set($order_states)}
        <span class="button" id="MarkOrdersButton">Mark all pending orders</span>
        <select id="MarkOrdersSelection">
            {foreach $order_states as $Status}
                <option value="{$Status.status_id}" {if eq( $Status.status_id, '2' )} selected="selected"{/if}>
                    {$Status.name|wash}
                </option>
            {/foreach}
        </select>
    {/if}
    </div>
    </div>

    {def $currency = false()
         $locale = false()
         $symbol = false()}

    <table class="list" cellspacing="0">
    <tr>
        <th class="tight"><img src={'toggle-button-16x16.gif'|ezimage} alt="{'Invert selection.'|i18n( 'design/admin/shop/orderlist' )}" title="{'Invert selection.'|i18n( 'design/admin/shop/orderlist' )}" onclick="ezjs_toggleCheckboxes( document.orderlist, 'OrderIDArray[]' ); return false;" /></th>
        <th class="tight">{'ID'|i18n( 'design/admin/shop/orderlist' )}</th>
        <th class="wide">{'Customer'|i18n( 'design/admin/shop/orderlist' )}</th>
        <th class="tight">{'Total (ex. VAT)'|i18n( 'design/admin/shop/orderlist' )}</th>
        <th class="tight">{'Total (inc. VAT)'|i18n( 'design/admin/shop/orderlist' )}</th>
        <th class="wide">{'Time'|i18n( 'design/admin/shop/orderlist' )}</th>
        <th class="wide">{'Order status'|i18n( 'design/admin/shop/orderlist' )}</th>
    {if $show_payment_status}
        <th class="wide">{'Payment status'|i18n( 'design/admin/shop/orderlist' )}</th>
    {/if}
        <th class="wide">{'Actions'|i18n( 'design/admin/shop/orderlist' )}</th>
    </tr>
    {foreach $order_list as $order sequence array( bglight, bgdark ) as $seq}

    {set $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )}
    {if $currency}
        {set locale = $currency.locale
             symbol = $currency.symbol}
    {else}
        {set locale = false()
             symbol = false()}
    {/if}

    <tr class="{$seq}">
        <td><input type="checkbox" name="OrderIDArray[]" value="{$order.id}" title="{'Select order for removal.'|i18n( 'design/admin/shop/orderlist' )}" /></td>
        <td><a href={concat( '/shop/orderview/', $order.id, '/' )|ezurl}>{$order.order_nr}</a></td>
        <td>
        {if is_null($order.account_name)}
            <s><i>{'( removed )'|i18n( 'design/admin/shop/orderlist' )}</i></s>
        {else}
            <a href={concat( '/shop/customerorderview/', $order.user_id, '/', $order.account_email )|ezurl}>{$order.account_name}</a>
        {/if}
        </td>

        {* NOTE: These two attribute calls are slow, they cause the system to generate lots of SQLs.
                 The reason is that their values are not cached in the order tables *}
        <td class="number" align="right">{$order.total_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
        <td class="number" align="right">{$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}</td>

        <td>{$order.created|l10n( shortdatetime )}</td>
        <td>

        {if $order.status_modification_list|count|gt( 0 )}
            {set can_apply=true()}
            <select name="StatusList[{$order.id}]">
            {foreach $order.status_modification_list as $Status}
                <option value="{$Status.status_id}"
                    {if and(ezini_hasvariable( 'StatusSettings', concat( 'StatusDisallowList-', $order.status_id) ,'xrowecommerce.ini' ), ezini( 'StatusSettings', concat( 'StatusDisallowList-', $order.status_id) ,'xrowecommerce.ini' )|contains($Status.status_id) )} disabled="disabled"{/if}{if eq( $Status.status_id, $order.status_id )} selected="selected"{/if}>
                    {$Status.name|wash}</option>
            {/foreach}
            </select>
        {else}
            {* Lets just show the name if we don't have access to change the status *}
            {$order.status_name|wash}
        {/if}

        </td>
           {if $show_payment_status}
           <td>
            {def $stati = hash( '0', 'unpaid'|i18n( 'design/admin/shop/orderlist' ),  '1', 'paid'|i18n( 'design/admin/shop/orderlist' ) )}
            {def $payment = fetch( 'xrowecommerce', 'payment_status', hash( 'id', $order.id ) )}
            <select {if or( $payment.status|eq('1'), $payment.automatic_status )} disabled="disabled"{/if} name="PaymentStatusList[{$order.id}]" title="Payment via {$payment.payment_string}">
            {foreach $stati as $key => $status}
                <option value="{$key}"
                    {if or($payment.automatic_status|eq(true) ,eq( $key, $payment.status ))}selected="selected"{/if}>
                    {$status|wash}</option>
            {/foreach}
            </select>
            {if and(is_set($payment.data_array.errors), $payment.data_array.errors|count|gt(0))}
<!--
    <script type="text/javascript">
            YAHOO.namespace("example.container");
            function init() {ldelim}
                YAHOO.example.container.orderoverlay{$order.id} = new YAHOO.widget.Overlay("orderoverlay{$order.id}", {ldelim} fixedcenter:true,
                                                                                          visible:false,
                                                                                          width:"300px" {rdelim} );
                YAHOO.example.container.orderoverlay{$order.id}.render();
                YAHOO.util.Event.addListener("order-show-{$order.id}", "click", YAHOO.example.container.orderoverlay{$order.id}.show, YAHOO.example.container.orderoverlay{$order.id}, true);
                YAHOO.util.Event.addListener("order-hide-{$order.id}", "click", YAHOO.example.container.orderoverlay{$order.id}.hide, YAHOO.example.container.orderoverlay{$order.id}, true);
            {rdelim}
            YAHOO.util.Event.addListener(window, "load", init);
    </script>
 -->
        <input id="order-show-{$order.id}" type="button" value="!" />

    <div id="orderoverlay{$order.id}" style="visibility:hidden">

        <ul>
            {foreach $payment.data_array.errors as $error}
            <li>{$error|wash}</li>
            {/foreach}
        </ul>
        <input id="order-hide-{$order.id}" type="button" value="{'Close'|i18n( 'design/admin/shop/orderlist' )}" />
    </div>
            {/if}
            {undef $payment $stati}
        </td>
        {/if}
        <td>
        <a href={concat( 'xrowecommerce/invoiceprint/', $order.id )|ezurl} target="_blank"><img src={'printer.png'|ezimage} height="28" width="28" alt="" title="{'Print invoice and packaging slip'|i18n( 'design/admin/shop/orderlist' )}" /></a>
        <a href={concat( 'xrowecommerce/shippingplanprint/', $order.id )|ezurl} target="_blank"><img src={'shipping_plan.png'|ezimage} height="28" width="28" alt="" title="{'Print shipping plan'|i18n( 'design/admin/shop/orderlist' )}" /></a>
        <a href={concat( 'orderedit/edit/', $order.order_nr)|ezurl}><img src={'images/txt2.png'|ezdesign}  alt="Edit shippingcosts" title="Edit shippingcosts" /></a>
        </td>
    </tr>
    {/foreach}
    </table>
    {undef $currency $locale $symbol}
    {else}
    <div class="block">
    <p>{'The order list is empty.'|i18n( 'design/admin/shop/orderlist' )}</p>
    </div>
    {/if}

    <div class="context-toolbar">
    {include name=navigator
             uri='design:navigator/google.tpl'
             page_uri='/shop/orderlist'
             item_count=$order_list_count
             view_parameters=$view_parameters
             item_limit=$limit}
    </div>

    {* DESIGN: Content END *}</div></div></div>

    <div class="controlbar">
    {* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">

    <div class="block">
    <div class="button-left">
    {if $order_list}
        <input class="button" type="submit" name="ArchiveButton" value="{'Archive selected'|i18n( 'design/admin/shop/orderlist' )}" title="{'Archive selected orders.'|i18n( 'design/admin/shop/orderlist' )}" />
        <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/shop/orderlist' )}" title="{'Remove selected orders.'|i18n( 'design/admin/shop/orderlist' )}" />
    {else}
        <input class="button-disabled" type="submit" name="ArchiveButton" value="{'Archive selected'|i18n( 'design/admin/shop/orderlist' )}" disabled="disabled" />
        <input class="button-disabled" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'design/admin/shop/orderlist' )}" disabled="disabled" />
    {/if}
    </div>
    <div class="button-right">
        {if and( $order_list|count|gt( 0 ), $can_apply )}
        <input class="button" type="submit" name="SaveOrderStatusButton" value="{'Apply changes'|i18n( 'design/admin/shop/orderlist' )}" title="{'Click this button to store changes if you have modified any of the fields above.'|i18n( 'design/admin/shop/orderlist' )}" />
        {else}
        <input class="button-disabled" type="submit" name="SaveOrderStatusButton" value="{'Apply changes'|i18n( 'design/admin/shop/orderlist' )}" disabled="disabled" />
        {/if}
    </div>
    <div class="break"></div>

    </div>

    {* DESIGN: Control bar END *}</div></div></div></div></div></div>
    </div>

</form>
<div class="block">
    <form id="printmarkedinvoices" action="/xrowecommerce/printinvoices" target="_blank" method="post">
        <input type="submit" class="button" id="PrintMarkedOrdersButton" value="{'Print selected'|i18n( 'design/admin/shop/orderlist' )}" />
        <span class="button" id="SetToButton">{'Set selected to'|i18n( 'design/admin/shop/orderlist' )}</span>
        {if is_set($order_states)}
            <select id="SetToSelection">
                {foreach $order_states as $Status}
                    <option value="{$Status.status_id}" {if eq( $Status.status_id, '2' )} selected="selected"{/if}>
                        {$Status.name|wash}
                    </option>
                {/foreach}
            </select>
            {undef $order_states}
        {/if}
        <div id="printidarray"></div>
    </form>
</div>
{/let}
{/if}