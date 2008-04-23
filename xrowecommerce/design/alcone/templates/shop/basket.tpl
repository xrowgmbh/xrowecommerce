<SCRIPT LANGUAGE="JavaScript">
<!--
// by Nannette Thacker
// http://www.shiningstar.net
// This script checks and unchecks boxes on a form
// Checks and unchecks unlimited number in the group...
// Pass the Checkbox group name...
// call buttons as so:
// <input type=button name="CheckAll"   value="Check All"
        //onClick="checkAll(document.myform.list)">
// <input type=button name="UnCheckAll" value="Uncheck All"
        //onClick="uncheckAll(document.myform.list)">
// -->

<!-- Begin
{literal}
removecheck_togglestate = 0;
function toggleAll()
{
	var checkref = document.basket.elements["RemoveProductItemDeleteList[]"];
	if (!removecheck_togglestate)
	{
		for (i = 0; i < checkref.length; i++)
		{
			checkref[i].checked = true ;
		}
		removecheck_togglestate = 1;
	} else
	{
		for (i = 0; i < checkref.length; i++)
		{
			checkref[i].checked = false;
		}
		removecheck_togglestate = 0;
	}
}

function uncheckAll()
{
for (i = 0; i < field.length; i++)
        field[i].checked = false ;
}
{/literal}
//  End -->
</script>

<div class="shop-basket">
    <form method="post" name="basket" action={"/shop/basket/"|ezurl}>
        <div style="float: right;">
            <img src={"images/freeshipping50.gif"|ezdesign} alt="Alcone Beauty Company free shipping when you spend fifty dollars or more. Limited to the first 5lbs." title="Alcone Beauty Company free shipping when you spend fifty dollars or more. Limited to the first 5lbs."/>
        </div>
        <h2>{"Basket"|i18n("design/base/shop")}</h2>
    <br />
    <br />
    <div class="shopping_cart_path">
    <div style="float: left; color: #308d9d;">1. Cart</div>
    <div style="float: left; padding-left: 10px;">2. Billing, Shipping and Coupons</div>
    <div style="float: left; padding-left: 30px;">3. Confirmation</div>
    <div style="float: left; padding-left: 30px;">4. Payment info</div>
    <div style="float: left; padding-left: 30px;">5. Order completed</div>
    <div style="float: left; padding-left: 30px;">6. Review reciept</div>
    </div>
    <div class="break"></div>
    {* <br />
 <br /><br /> *}

    {section show=$removed_items}
    <div class="warning">
        <h2>{"The following items were removed from your basket, because the products were changed"|i18n("design/base/shop",,)}</h2>
        <ul>
        {section name=RemovedItem loop=$removed_items}
            <li> <a href={concat("/content/view/full/",$RemovedItem:item.contentobject.main_node_id,"/")|ezurl}>{$RemovedItem:item.contentobject.name|wash}</a></li>
        {/section}
        </ul>
    </div>
    {/section}

    {if not( $vat_is_known )}
    <div class="message-warning">
    <h2>{'Tax is unknown'|i18n( 'design/base/shop' )}</h2>
    {'Tax percentage is not yet known for some of the items being purchased.'|i18n( 'design/base/shop' )}<br/>
    {'This probably means that some information about you is not yet available and will be obtained during checkout.'|i18n( 'design/base/shop' )}
    </div>
    {/if}

    {section show=$error}
    <div class="error">
    {section show=$error|eq(1)}
    <h2>{"Attempted to add object without price to basket."|i18n("design/base/shop",,)}</h2>
    {/section}
    </div>
    {/section}

    {section show=$error}
    <div class="error">
    {section show=eq($error, "aborted")}
    <h2>{"Your payment was aborted."|i18n("design/base/shop",,)}</h2>
    {/section}
    </div>
    {/section}

    {section show=$basket.items}
 <table width="100%" border="0">

 <tr>
 <td align="left">
 <input type="image" name="ContinueShoppingButton" value="Continue" src={"images/continue_shopping.gif"|ezdesign()}/>
 </td>
 <td align="right">
 <input type="image" name="StoreChangesButton" value="Store" src={"images/updatecart.gif"|ezdesign()} />
 </td>
 <td align="right">
 <input type="image" name="CheckoutButton" value="Checkout" src={"images/proceedcheckout.gif"|ezdesign()} />
 </td>
 </tr>
 </table>

    {def $currency = fetch( 'shop', 'currency', hash( 'code', $basket.productcollection.currency_code ) )
         $locale = false()
         $symbol = false()}
    {if $currency}
        {set locale = $currency.locale
             symbol = $currency.symbol}
    {/if}

    <div class="content-basket">
    <div style="width: 100%; text-align: right; padding-bottom: 10px;">
        <input type="image" name="RemoveProductItemButton" value="Delete" src={"images/remove_grey.gif"|ezdesign()} />
    </div>

    <table cellspacing="0">
    <tr>
        <th>
        {"Quantity"|i18n("design/base/shop")}
        </th>
        <th>
        {"Item"|i18n("design/base/shop")}
        </th>
        <th align="right">
        {"Tax"|i18n("design/base/shop")}
        </th>
        <th align="right">
    	{"Unit Price"|i18n("design/base/shop")}
        </th>
        {*<th align="right">
	    {"Discount"|i18n("design/base/shop")}
        </th>*}
        <th align="right">
     	{"Total Price"|i18n("design/base/shop")}
        </th>
        <th align="center">
        <a href="#" onClick="toggleAll(  )">Remove</a>
        </th>
    </tr>
    {section var=product_item loop=$basket.items sequence=array(bglight,bgdark)}
    <tr>
        <td valign="top">
            <input type="hidden" name="ProductItemIDList[]" value="{$product_item.id}" />
            <input type="text" name="ProductItemCountList[]" value="{$product_item.item_count}" size="3" style="border: 1px solid #d3d5d6; padding: 2px; text-align: right;" />
    	</td>
    	<td>
    	{def $prod=fetch( 'content', 'node', hash( 'node_id', $product_item.node_id ) )}

    	<div style="float: left; border: 1px solid grey; margin-right: 5px; width: 142px; text-align: center;">
        	{if and( is_set($prod.data_map.image_link.content.current.contentobject_attributes.2.content.is_valid), $prod.data_map.image_link.content.current.contentobject_attributes.2.content.is_valid )}

        	       {attribute_view_gui alignment=center image_class=productthumbnail attribute=$prod.data_map.image_link.content.current.contentobject_attributes.2}

        	{elseif $prod.data_map.image.content.is_valid}

        	   {attribute_view_gui alignment=center image_class=productthumbnail attribute=$prod.data_map.image}

            {else}
                    <img src={"images/no_pic.jpg"|ezdesign} width="100" align="middle">
                {/if}
         </div>
         <a href={concat("/content/view/full/",$product_item.node_id,"/")|ezurl}>{$product_item.object_name}</a>
        {section show=$product_item.item.item_object.option_list}
        <table border="0">
     <tr>
         <td class="{$product_item.sequence}">
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
         {/section}
         </table>

         </td>
     </tr>
     </table>
     {section-else}
        <table border="0">
     <tr>
         <td class="{$product_item.sequence}">
         <table class="shop-option_list">
         <tr>
             {def $prod=fetch( 'content', 'node', hash( 'node_id', $product_item.node_id ) )}
                 <td class="shop-option_value">{$prod.data_map.product_id.content}</td>
         </tr>
         </table>
         </td>
     </tr>
     </table>
     {/section}
        </td>
        <td align="right" valign="top">
        {if ne( $product_item.vat_value, -1 )}
            {$product_item.vat_value} %
        {else}
            {'unknown'|i18n( 'design/base/shop' )}
        {/if}
    	</td>
        <td align="right" valign="top">
        {$product_item.price_ex_vat|l10n( 'currency', $locale, $symbol )}
    	</td>
    	{* <td class="{$product_item.sequence} product-details" valign="top">
        {$product_item.discount_percent}%
        </td> *}
        <td align="right" valign="top" class="subtotal">
        {$product_item.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}
	</td>
     	<td align="center" valign="top">
        <input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$product_item.item.id}" />
    	</td>
     </tr>
     <tr><td colspan="6"><hr /></td> </tr>
     {/section}

     <tr>
         <td colspan='6' align="right">
         {"Subtotal Ex. Tax"|i18n("design/base/shop")}:
         <strong>{$basket.total_ex_vat|l10n( 'currency', $locale, $symbol )}</strong>
         </td>
     </tr>
{def $taxpercent = mul( div(sub($basket.total_inc_vat, $basket.total_ex_vat), $basket.total_ex_vat), 100)
     $percentage = mul( div(sub($basket.total_inc_vat, $basket.total_ex_vat), $basket.total_ex_vat), 100)|l10n('number') }
{if $taxpercent|eq(0)|not}
     <tr>
         <td colspan='6' align="right">
         {"Tax"|i18n("design/base/shop")}:
         <strong>{$basket.total_inc_vat|sub($basket.total_ex_vat)|l10n( 'currency', $locale, $symbol )}</strong>
         </td>
     </tr>
{/if}

     <tr><td colspan="6"><hr /></td> </tr>
    {if is_set( $shipping_info )}
    {* Show shipping type/cost. *}
    <tr>
    <td class="product-subtotal" colspan="5"><a href={$shipping_info.management_link|ezurl}>{'Shipping'|i18n( 'design/admin/shop/basket' )}{if $shipping_info.description} ({$shipping_info.description}){/if}</a>:
    {$shipping_info.cost|l10n( 'currency', $locale, $symbol )}
    </td>
    <td class="product-subtotal">
    &nbsp;
    </td>
    </tr>
    {* Show order total *}
    <tr>
    <td class="product-subtotal" colspan="5"><b>{'Order total'|i18n( 'design/admin/shop/basket' )}</b>:
    {$total_inc_shipping_inc_vat|l10n( 'currency', $locale, $symbol )}
    </td>
    <td class="product-subtotal">
    &nbsp;
    </td>
    </tr>
    {/if}
    </table>
   </div>

{* <br /><br />
 <table width="100%" border="0">

 <tr>
 <td align="left">
 <input type="image" name="ContinueShoppingButton" value="Continue" src={"images/continue_shopping.gif"|ezdesign()}/>
 </td>
 <td align="right">
 <input type="image" name="StoreChangesButton" value="Store" src={"images/updatecart.gif"|ezdesign()} />
 </td>
 <td align="right">
 <input type="image" name="CheckoutButton" value="Checkout" src={"images/proceedcheckout.gif"|ezdesign()} />
 </td>
 </tr>
 </table> *}

 </form>

{* <br />*}
 {def $user=fetch( 'user', 'current_user' )}
 {if $user.contentobject.id|eq(10)}
{* <br /> *}

             <div style="float: right; width: 200px; margin-right: 20px; border: 1px solid lightgrey;">
             <div style="text-align: center;">
             <p>
             Already a member?
             </p>
             <form method="post" action="https://www.alconeco.com/user/login/">
                 <div class="block">
                    <label for="id1">{"Username"|i18n("design/standard/user",'User name')}</label><div class="labelbreak"></div>
                    <input type="text" name="Login" id="id1" value="{$User:login|wash}" tabindex="1" />
                    </div>
                    <div class="block">
                    <label for="id2">{"Password"|i18n("design/standard/user")}</label><div class="labelbreak"></div>
                    <input type="password"  name="Password" id="id2" value="" tabindex="1" />
            </div>
            <div class="buttonblock" style="text-align: right;">
            <input type="image" src={"images/continue.gif"|ezdesign()} name="LoginButton" value="{'Login'|i18n('design/standard/user','Button')}" tabindex="1">
            </div>

            <input type="hidden" name="RedirectURI" value="https://www.alconeco.com/alcone/userregister" />
            </div>
    </div>

 </form>
 <br />
 <br />
 {/if}
 {*
  <input class="shopbutton" type="submit" name="CheckoutButton" value="{'Checkout'|i18n('design/base/shop')}" /> &nbsp;
  </div>

     <div class="buttonblock">
         <input class="shopbutton" type="submit" name="ContinueShoppingButton" value="{'Continue shopping'|i18n('design/base/shop')}" />

         <input class="shopbutton" type="submit" name="StoreChangesButton" value="{'Store quantities'|i18n('design/base/shop')}" />
         <input class="shopbutton" type="submit" name="RemoveProductItemButton" value="Delete selected" />

     </div>*}
    {undef $currency $locale $symbol}

    {section-else}
</form>
    <div class="feedback">
        <form method="post" name="basket" action={"/shop/basket/"|ezurl}>
        <br />
        <div><table border="0" align="left"><tr align="left"><td width="186"><input type="image" name="ContinueShoppingButton" value="Continue" src={"images/continue_shopping.gif"|ezdesign()}/></td><td><b>{"You have no products in your basket"|i18n("design/base/shop")}</b></td></tr></table></div>
        </form>
        <div style="width: 10px; height: 450px;"></div>
    </div>

    {/section}


</div>
