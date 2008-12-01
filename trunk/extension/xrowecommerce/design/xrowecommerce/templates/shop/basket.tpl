<script type="text/javascript">
<!--
// by Nannette Thacker
// http://www.shiningstar.net
// This script checks and unchecks boxes on a form
// Checks and unchecks unlimited number in the group...
// Pass the Checkbox group name...
// call buttons as so:
// <input type=button name="CheckAll"   value="Check All"
// onClick="checkAll(document.myform.list)">
// <input type=button name="UnCheckAll" value="Uncheck All"
// onClick="uncheckAll(document.myform.list)">
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
        <h1>{"Shopping Cart"|i18n("design/base/shop")}</h1>

{include uri="design:shop/basket_navigator.tpl" step='1'}

        {if $removed_items}
            <div class="warning">
            <h2>{"The following items were removed from your cart, because the products were changed"|i18n("design/base/shop",,)}</h2>
                <ul>
                    {foreach $removed_items as $item}
                    <li>
                        <a href={concat("/content/view/full/",$item.contentobject.main_node_id,"/")|ezurl}>
                            {$item.contentobject.name|wash}
                        </a>
                    </li>
                    {/foreach}
                </ul>
            </div>
         {/if}

    {if not( $vat_is_known )}
        <div class="message-warning">
            <h2>{'Tax is unknown'|i18n('design/base/shop')}</h2>
            {'Tax percentage is not yet known for some of the items being purchased.'|i18n( 'design/base/shop' )}
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
    {if $basket.items}
    <div class="buttonblock">
        <input type="submit" class="left-arrow2" name="ContinueShoppingButton" value="Continue" title="Use this button to return to the last page you visited before the shopping cart." />
        <input type="submit" class="right-arrow" name="StoreChangesButton" value="Update" title="Use this button to update your shopping cart." />
        <input type="submit" class="right-arrow2" name="CheckoutButton" value="Checkout" title="Use this button to place your order." />
    </div>
    <div class="break"></div>
    {def $currency = fetch( 'shop', 'currency', hash( 'code', $basket.productcollection.currency_code ) )
         $locale = false()
         $symbol = false()}
    {if $currency}
        {set locale = $currency.locale
             symbol = $currency.symbol}
    {/if}
    
    <div class="content-basket">
    <div class="buttonblock">
        <input type="submit" class="remove-button flat-right2" name="RemoveProductItemButton" value="Delete" title="Use this button to remove items from your shopping cart."/>
    </div>
    <div class="break"></div>
    <table class="order">
        <tr class="lightbg">
            <th>
                {"Quantity"|i18n("design/base/shop")}
            </th>
            <th>
                {"Item"|i18n("design/base/shop")}
            </th>
          {if eq(ezini( 'BasketInformation', 'DisplayTax', 'xrowecommerce.ini' ), 'enabled' )}
            <th align="right">
                {"Tax"|i18n("design/base/shop")}
            </th>
          {/if}
            <th align="right">
                {"Unit Price"|i18n("design/base/shop")}
            </th>
            <th align="right">
                {"Total Price"|i18n("design/base/shop")}
            </th>
            <th align="right">
                {"Remove"|i18n("design/base/shop")}
            </th>
        </tr>
        {section var=product_item loop=$basket.items sequence=array(bglight,bgdark)}
        <tr>
            <td class="basketspace quantity">
                <input type="hidden" name="ProductItemIDList[]" value="{$product_item.id}" />
                <input class="quantity" type="text" name="ProductItemCountList[]" value="{$product_item.item_count}" size="3"/>
            </td>
            <td class="cart_item basketspace">
{include uri="design:shop/product_cell_view.tpl"}
        </td>
        <td class="basketspace">
        
            {if ne( $product_item.vat_value, -1 )}
                {$product_item.vat_value} %
            {else}
                {'unknown'|i18n( 'extension/xrowecommerce' )}
            {/if}
        </td>
        {/if}
        <td class="basketspace">
            {$product_item.price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
        <td class="basketspace">
            {$product_item.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
        <td class="basketspace">
            <input class="shopping_cart_checkbox" type="checkbox" name="RemoveProductItemDeleteList[]" value="{$product_item.item.id}" />
        </td>
     </tr>
     {/section}
    <tr>
         <td colspan="6" class="align_right line">
         {"Subtotal Ex. Tax"|i18n("extension/xrowecommerce")}:
         <b class="price">{$basket.total_ex_vat|l10n( 'currency', $locale, $symbol )}</b>
         </td>
    </tr>
    <tr>
        <td  colspan="6" class="align_right line2">
            {"Estimated Tax"|i18n("extension/xrowecommerce")}:
            {$basket.items_info.total_price_info.price_vat|l10n( 'currency', $locale, $symbol )}
        </td>
    </tr>
    <tr>
        <td  colspan="6" class="align_right">   
            {"Estimated Shipping and Handling"|i18n("extension/xrowecommerce")}:
            {$basket.items_info.additional_info.shipping_total.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}
        </td>
    </tr>
    <tr>
        <td colspan="6" class="align_right line3">
            <b class="price">
            {'Total'|i18n( 'design/ezwebin/shop/basket' )}:
            {$basket.total_inc_vat|l10n( 'currency', $locale, $symbol )}
            </b>
        </td>
    </tr>
{def $taxpercent = mul( div(sub($basket.total_inc_vat, $basket.total_ex_vat), $basket.total_ex_vat), 100)
     $percentage = mul( div(sub($basket.total_inc_vat, $basket.total_ex_vat), $basket.total_ex_vat), 100)|l10n('number') }

    {if is_set( $shipping_info )}
    <tr>
        <td class="product-subtotal" colspan="5"><a href={$shipping_info.management_link|ezurl}>{'Shipping'|i18n( 'extension/xrowecommerce' )}{if $shipping_info.description} ({$shipping_info.description}){/if}</a>:
            {$shipping_info.cost|l10n( 'currency', $locale, $symbol )}
        </td>
        <td class="product-subtotal">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td class="product-subtotal" colspan="5"><b>{'Order total'|i18n( 'extension/xrowecommerce' )}</b>:
            {$total_inc_shipping_inc_vat|l10n( 'currency', $locale, $symbol )}
        </td>
        <td class="product-subtotal">
            &nbsp;
        </td>
    </tr>
    {/if}
    </table>
   </div>
 </form>

{* ####### Login Box ########

 {def $user=fetch( 'user', 'current_user' )}
 {if $user.contentobject.id|eq(10)}
            <div>
                <div class="loginbox">
                    <p>{'Already a user?"|i18n("design/standard/user",'User name')}</p>
                    <form method="post" action={"user/login"|ezurl}">
                    <div class="loginbox_wrap">
                        <label for="id1">{"Username"|i18n("extension/xrowecommerce",'User name')}</label><div class="labelbreak"></div>
                        <input type="text" name="Login" id="id1" value="{$User:login|wash}" tabindex="1" />
                    </div>
                    <div class="loginbox_wrap">
                        <label for="id2">{"Password"|i18n("extension/xrowecommerce")}</label><div class="labelbreak"></div>
                        <input type="password" name="Password" id="id2" value="" tabindex="1" />
                    </div>
                    <div>
                        <input class="standard" type="submit" name="LoginButton" value="{'Login'|i18n('design/standard/user','Button')}" tabindex="1">
                    </div>
                    <input type="hidden" name="RedirectURI" value={"shop/userregister"|ezurl} />
                </div>
            </div>
 </form>
{/if*}
    {undef $currency $locale $symbol}
    {else}
</form>
    <div class="feedback">
        <form method="post" name="basket" action={"/shop/basket/"|ezurl}>
        <div>
            <table>
                <tr>
                    <td>
                        <a href={'/'|ezurl()}><input type="submit" class="standard" name="ContinueShoppingButton" value="Continue" src={"images/continue_shopping.gif"|ezdesign()}/></a>
                    </td>
                    <td>
                        <b>{"You have no products in your cart"|i18n("extension/xrowecommerce")}</b>               
                    </td>
                </tr>
            </table>
        </div>
        </form>
        <div></div>
    </div>
    {/if}
</div>