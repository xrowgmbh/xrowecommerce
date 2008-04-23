<div class="shop-basket">
    <form method="post" action={"/shop/confirmorder/"|ezurl}>
    <h2>{"Confirm order"|i18n("design/base/shop")}</h2>
    <br />
    <div style="width: 100%;  color: #565969; font-size: 12px; font-weight: bold; background-color: #EBEEEF; float: left; padding-top: 5px; padding-bottom: 5px; padding-left: 5px;">
    <div style="float: left;">1. Cart</div>
    <div style="float: left; padding-left: 10px;">2. Billing, Shipping and Coupons</div>
    <div style="float: left; padding-left: 30px; color: #308d9d;">3. Confirmation</div>
    <div style="float: left; padding-left: 30px;">4. Payment info</div>
    <div style="float: left; padding-left: 30px;">5. Order completed</div>
    <div style="float: left; padding-left: 30px;">6. Review reciept</div>
    </div>
    <div class="break"></div>

    <div class="buttonblock">
        <table border="0" width="100%">
        <tr>
        <td align="left">
        {*<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/base/shop')}" /> &nbsp;*}
        <input type="image" type="submit" name="CancelButton" src={"images/cancel_small.gif"|ezdesign()} value="Cancel"> &nbsp;</td>
        <td align="right">
        {*<input class="button" type="submit" name="ConfirmOrderButton" value="{'Confirm'|i18n('design/base/shop')}" /> &nbsp;*}
        {if count( $order.product_items )|gt(0)}<input type="image" type="submit" name="ConfirmOrderButton" src={"images/continue_small.gif"|ezdesign()} value="Confirm"> &nbsp;{else}&nbsp;{/if}</td>
        </tr>
        </table>
    </div>
    <br />
    {shop_account_view_gui view=html order=$order}

    {def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
         $locale = false()
         $symbol = false()}

    {if $currency}
        {set locale = $currency.locale
             symbol = $currency.symbol}
    {/if}

    {if count($hazardous)|gt(0)}
    <br />
    <p>
    <b style="color: red;">Hazardous item(s) found in your cart</b><br />
        Dear Customer, <br />
        We've removed the following hazardous items from your shopping cart since we are NOT allowed to ship these items to your destination. For further questions please contact AlconeCO.<br />
        <ul>
            {foreach $hazardous as $item}
            <li>{$item.item_count} x <a href={concat("/content/view/full/", $item.contentobject.main_node_id)|ezurl()}>{$item.name}</a></li>
            {/foreach}
        </ul>
    </p>
    <br />
    {/if}
    {if count( $order.product_items )|gt(0)}
    <h3>{"Product items"|i18n("design/base/shop")}</h3>

    <div class="content-basket">
    <table cellspacing="1">
    <tr>
	    <th>
        {"Quantity"|i18n("design/base/shop")}
        </th>
        <th>
        {"Item"|i18n("design/base/shop")}
        </th>
        <th>
        {"Tax"|i18n("design/base/shop")}
        </th>
        <th>
    	{"Unit Price"|i18n("design/base/shop")}
        </th>
        {*<th>
	    {"Discount"|i18n("design/base/shop")}
        </th>*}
        <th align="right">
     	{"Total Price"|i18n("design/base/shop")}
        </th>
    </tr>
    {section var=product_item loop=$order.product_items sequence=array(bglight,bgdark)}
    <tr>
        <td class="{$product_item.sequence} product-name" align="center"  valign="top">
           <b>{$product_item.item_count}</b>
    	</td>
    	<td class="{$product_item.sequence} product-name" valign="top">
    	{def $prod=fetch( 'content', 'node', hash( 'node_id', $product_item.node_id ) )}
    	<div style="float: left; border: 1px solid grey; margin-right: 5px;">
        	{if $prod.data_map.image_link.content.current.contentobject_attributes.2.content.is_valid}

        	       {attribute_view_gui alignment=center image_class=productthumbnail attribute=$prod.data_map.image_link.content.current.contentobject_attributes.2}

        	{elseif $prod.data_map.image.content.is_valid}

        	   {attribute_view_gui alignment=center image_class=productthumbnail attribute=$prod.data_map.image}

            {else}
                    <img src={"images/no_pic.jpg"|ezdesign} width="100" align="middle">
                {/if}
         </div>
        <a href={concat("/content/view/full/",$product_item.node_id,"/")|ezurl}>{$product_item.object_name}</a>
        {section show=$product_item.item.item_object.option_list}

         <table class="shop-option_list">
         {section var=option_item loop=$product_item.item_object.option_list}
         <tr>
         {*<td class="shop-option_name">{$option_item.name}<br/>*}
             <td class="shop-option_value">
             {def $vary=$product_item.item_object.contentobject.data_map.variation.content.option_list[$product_item.item_object.option_list.0.option_item_id]}
             {$option_item.value}<br />
             <b>{$vary.comment}</b><br />
             {if or(ne($vary.weight, false()), ne($vary.weight, "0"))}Weight:{$vary.weight} lbs</b><br />{/if}
             {*$vary|attribute(show)*}</td>
             {*<td class="shop-option_price">{section show=$option_item.price|ne( 0 )}{$option_item.price|l10n( 'currency', $locale, $symbol )}{/section}</td>*}
         </tr>

     </table>
     {/section}
     {section-else}
          <table class="shop-option_list">
             <tr>
                 <td class="shop-option_value">{$prod.data_map.product_id.content}</td>
             </tr>
         </table>
     {/section}
        </td>
        <td class="{$product_item.sequence} product-name" align="right" valign="top">
           {$product_item.vat_value} %
    	</td>
        <td class="{$product_item.sequence} product-name" align="right" valign="top">
           {$product_item.price_ex_vat|l10n( 'currency', $locale, $symbol )}
    	</td>
    	{*<td class="{$product_item.sequence} product-name"  align="right" valign="top">
        {$product_item.discount_percent}%
        </td>*}
	    <td class="{$product_item.sequence} product-name" align="right" valign="top">
        {$product_item.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}
	    </td>
     </tr>

     {delimiter}
     	<tr><td colspan="5"><hr /></tr>
     {/delimiter}
     {/section}
     {if gt(count($order.product_items),0)}
     	<tr><td colspan="5"><hr /></tr>
     {/if}
     <tr>
         <td colspan='5' align="right">
         {"Subtotal Ex. TAX"|i18n("design/base/shop")}:
         <strong>{$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}</strong>
         </td>
     </tr>
     <tr><td colspan="5"><hr /></tr>
     </table>
     </div>

<h3>{"Order summary"|i18n("design/base/shop")}:</h3>
<table cellspacing="0" cellpadding="0" border="0">
<tr>
    <td class="bgdark">
    {"Subtotal of items ex. Tax"|i18n("design/base/shop")}:
    </td>
    <td class="bgdark" align="right">
    {$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}
    </td>
</tr>

{section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}
<tr>
	<td class="{$OrderItem:sequence}">
	{$OrderItem:item.description}:
	</td>
	<td class="{$OrderItem:sequence}" align="right">
	{$OrderItem:item.price_ex_vat|l10n( 'currency', $locale, $symbol )}
	</td>
</tr>
{/section}
{def $taxpercent = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)
     $percentage = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)|l10n('number') }
{if $taxpercent|eq(0)|not}
<tr>
	<td colspan ="2"><hr /></td>
</tr>
<tr>
	<td class="{$OrderItem:sequence}">
	{* Tax ( {$percentage} % ):*}
	Tax:
	</td>
	<td class="{$OrderItem:sequence}" align="right">
	{sub($order.total_inc_vat, $order.total_ex_vat)|l10n( 'currency', $locale, $symbol )}
	</td>
</tr>
{/if}
<tr>
	<td colspan ="2"><hr /></td>
</tr>

<tr>
    <td class="bgdark">
    <b>{"Order total"|i18n("design/base/shop")}:</b>
    </td>
    <td class="bgdark" align="right">
    <b>{$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}</b>
    </td>
</tr>
</table>
{else} {* If the shopping cart is empty after removing hazardous items... *}
<h3>Sorry, there are no items left in your cart.</h3>
{/if}
{*
    <div class="buttonblock">
<table border="0" width="100%">
<tr>
<td align="left">
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/base/shop')}" /> &nbsp;
<input type="image" type="submit" name="CancelButton" src={"images/cancel_small.gif"|ezdesign()} value="Cancel"> &nbsp;</td>
<td align="right">
<input class="button" type="submit" name="ConfirmOrderButton" value="{'Confirm'|i18n('design/base/shop')}" /> &nbsp;
<input type="image" type="submit" name="ConfirmOrderButton" src={"images/continue_small.gif"|ezdesign()} value="Confirm"> &nbsp;</td>
</tr>
</table>
    </div>
*}

    </form>

</div>
