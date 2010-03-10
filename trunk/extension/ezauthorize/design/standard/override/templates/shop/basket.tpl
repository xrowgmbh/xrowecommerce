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

    <h2>{"Basket"|i18n("design/base/shop")}</h2><br />

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

    {def $currency = fetch( 'shop', 'currency', hash( 'code', $basket.productcollection.currency_code ) )
         $locale = false()
         $symbol = false()}
    {if $currency}
        {set locale = $currency.locale
             symbol = $currency.symbol}
    {/if}

    <div class="content-basket">
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
                    <img src={"images/no_pic.jpg"|ezdesign} width="100" align="middle" />
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
             <td class="shop-option_value">
             {def $vary=$product_item.item_object.contentobject.data_map.variation.content.option_list[$product_item.item_object.option_list.0.option_item_id]}
{* $option_item|attribute(show,1) *}
             {$option_item.name}: {$option_item.value}<br />
             {* <b>{$vary.comment}</b><br /> *}
             {* if or(ne($vary.weight, false()), ne($vary.weight, "0"))}Weight:{$vary.weight} lbs</b><br />{/if *}
             </td>
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
        {$product_item.price_inc_vat|l10n( 'currency', $locale, $symbol )}
    	</td>
    	{*<td class="{$product_item.sequence} product-details" valign="top">
        {$product_item.discount_percent}%
        </td>*}
        <td align="right" valign="top">
        {$product_item.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}
	</td>
     	<td align="center" valign="top">
        <input type="checkbox" name="RemoveProductItemDeleteList[]" value="{$product_item.item.id}" />
    	</td>
     </tr>
     <tr><td colspan="6"><hr /></td> </tr>    
     {/section}
     
     <tr>
         <td colspan='6' align="right">
         {"Subtotal Inc. TAX"|i18n("design/base/shop")}:
         <strong>{$basket.total_inc_vat|l10n( 'currency', $locale, $symbol )}</strong>
         </td>
     </tr>
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

 <br /><br />
 <table width="100%" border="0">
 <tr>
 <td>&nbsp;</td>
 <td align="right">
 <input type="image" name="RemoveProductItemButton" value="Delete" src={"images/remove.gif"|ezdesign()} />
 </td>
 <td>&nbsp;</td>
 </tr>
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

    <div class="feedback">
        <b>{"You have no products in your basket"|i18n("design/base/shop")}</b>
        <div style="width: 10px; height: 450px;"></div>
    </div>

    {/section}

    </form>
</div>
