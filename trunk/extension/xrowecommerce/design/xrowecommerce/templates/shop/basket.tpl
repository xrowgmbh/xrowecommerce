{def $cols=7}
{if ezini( 'Settings', 'ShowColumnRemove', 'xrowecommerce.ini')|ne('enabled')}
{set $cols=$cols|sub(1)}
{/if}
{if ezini( 'Settings', 'ShowColumnPosition', 'xrowecommerce.ini')|ne('enabled')}
{set $cols=$cols|sub(1)}
{/if}
{if ezini( 'Settings', 'ShowColumnTax', 'xrowecommerce.ini' )|ne('enabled')}
{set $cols=$cols|sub(1)}
{/if}
{literal}
<script type="text/javascript">
removecheck_togglestate = 0;
function toggleAll( element )
{
    var checkref = document.basket.elements[element];
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
</script>
{/literal}
<div class="shop shop-basket">
{include uri="design:shop/basket_navigator.tpl" step='1'}
<div class="break"></div>
<h1>{"Shopping Cart"|i18n("extension/xrowecommerce")}</h1>
{if ezini( 'Settings', 'Catalogueorder', 'xrowecommerce.ini' )|eq('enabled')}
    {include uri="design:shop/basket_catalogue_order.tpl"}
    <h2>{"Shopping Cart"|i18n("extension/xrowecommerce")}</h2>
{/if}



        {if $removed_items}
            <div class="warning">
            <h2>{"The following items were removed from your cart, because the products were changed"|i18n("extension/xrowecommerce",,)}</h2>
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
            <h2>{'Tax is unknown'|i18n('extension/xrowecommerce')}</h2>
            {'Tax percentage is not yet known for some of the items being purchased.'|i18n( 'extension/xrowecommerce' )}
            {'This probably means that some information about you is not yet available and will be obtained during checkout.'|i18n( 'extension/xrowecommerce' )}
        </div>

    {/if}
    {section show=$error}
        <div class="error">
            {section show=$error|eq(1)}
                <h2>{"Attempted to add object without price to basket."|i18n("extension/xrowecommerce")}</h2>
            {/section}
        </div>
    {/section}
    {section show=$error}
        <div class="error">
            {section show=eq($error, "aborted")}
                <h2>{"Your payment was aborted."|i18n("extension/xrowecommerce")}</h2>
            {/section}
        </div>
    {/section}
    {if $basket.items}
    <form method="post" name="basket" action={"shop/basket/"|ezurl}>
    <input type="submit" class="hide" sytle="display: hide;" name="CheckoutButton" value={'Checkout'|i18n( 'extension/xrowecommerce' )|wash} />
    <div id="buttonblock-top" class="buttonblock">

        <input id="continue-shopping-button" class="right-arrow" type="submit" name="ContinueShoppingButton" value="{'Continue shopping'|i18n("extension/xrowecommerce")}" title="{'Continue shopping'|i18n("extension/xrowecommerce")|wash}"/>
        <input id="empty-cart-button" type="button" onclick="empty_basket.submit();" class="button small-action" name="EmptyShoppingCartButton" value="{'Empty cart'|i18n( 'extension/xrowecommerce' )}" title="{'Use this button to empty your shopping cart.'|i18n( 'extension/xrowecommerce' )|wash}" />

        <input id="store-button" type="submit" class="right-arrow " name="StoreChangesButton" value="{'Update'|i18n( 'extension/xrowecommerce' )|wash}" title="{'Use this button to update your shopping cart.'|i18n( 'extension/xrowecommerce' )|wash}" />
        <input id="checkout-button" type="submit" class="right-arrow2 continue-button" name="CheckoutButton" value={'Checkout'|i18n( 'extension/xrowecommerce' )|wash} title="{'Use this button to place your order.'|i18n( 'extension/xrowecommerce' )|wash}" />
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

    <table class="order">
        <tr class="lightbg">
            {if ezini( 'Settings', 'ShowColumnPosition', 'xrowecommerce.ini' )|eq('enabled')}
                <th class="position">
                <abbr title="{"Position"|i18n("extension/xrowecommerce")}">{"Pos."|i18n("extension/xrowecommerce")}</abbr>
                </th>
            {/if}
            <th>
                {"Quantity"|i18n("extension/xrowecommerce")}
            </th>
            <th>
                {"Item"|i18n("extension/xrowecommerce")}
            </th>
            {if ezini( 'Settings', 'ShowColumnTax', 'xrowecommerce.ini' )|eq('enabled')}
            <th>
                {"Tax"|i18n("extension/xrowecommerce")}
            </th>
            {/if}
            <th>
                {"Unit Price"|i18n("extension/xrowecommerce")}
            </th>
            <th class="totalprice">
                {"Total Price"|i18n("extension/xrowecommerce")}
            </th>
            {if ezini( 'Settings', 'ShowColumnRemove', 'xrowecommerce.ini')|eq('enabled')}
            <th>{*<a class="mark-all" onclick="toggleAll('RemoveProductItemDeleteList[]');">Mark all</a>*}
               <input id="delete-button" class="icon" type="image" name="RemoveProductItemButton" src={'shop/basket-delete-icon.gif'|ezimage} value={'Delete'|i18n( 'extension/xrowecommerce' )} title="{'Use this button to remove items from your shopping cart.'|i18n( 'extension/xrowecommerce' )}" />
            </th>
            {/if}
        </tr>
        {foreach $basket.items as $key => $product_item sequence array(bglight,bgdark) as $sequence}
        <tr class="{$sequence} product-line">
            {if ezini( 'Settings', 'ShowColumnPosition', 'xrowecommerce.ini' )|eq('enabled')}
            <td class="position">
            {$key|sum(1)}
            </td>
            {/if}
            <td class="basketspace quantity">
                <input type="hidden" name="ProductItemIDList[]" value="{$product_item.id}" />
                <input class="quantity" type="text" name="ProductItemCountList[]" value="{$product_item.item_count}" size="4" maxlength="3" />
            </td>
            <td class="cart_item basketspace">
                {include uri="design:shop/product_cell_view.tpl" view="basket"}
            </td>
            {if and( eq(ezini( 'BasketInformation', 'DisplayTax', 'xrowecommerce.ini' ), 'enabled' ), ezini( 'Settings', 'ShowColumnTax', 'xrowecommerce.ini' )|eq('enabled') )}
            <td class="basketspace">

                {if ne( $product_item.vat_value, -1 )}
                    {$product_item.vat_value} %
                {else}
                    {'unknown'|i18n( 'extension/xrowecommerce' )}
                {/if}
            </td>
            {/if}
        <td class="basketspace price">
            {$product_item.price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
        <td class="basketspace totalprice">
            {$product_item.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
        {if ezini( 'Settings', 'ShowColumnRemove', 'xrowecommerce.ini')|eq('enabled')}
        <td class="delete basketspace">
            <input class="shopping_cart_checkbox" type="checkbox" name="RemoveProductItemDeleteList[]" value="{$product_item.id}" />
        </td>
        {/if}
     </tr>
     {/foreach}


{def $taxpercent = mul( div(sub($basket.total_inc_vat, $basket.total_ex_vat), $basket.total_ex_vat), 100)
     $percentage = mul( div(sub($basket.total_inc_vat, $basket.total_ex_vat), $basket.total_ex_vat), 100)|l10n('number') }

{if is_set( $shipping_info )}
    <tr>
        <td class="product-subtotal" colspan="{$cols|sub(1)}"><a href={$shipping_info.management_link|ezurl}>{'Shipping'|i18n( 'extension/xrowecommerce' )}{if $shipping_info.description} ({$shipping_info.description}){/if}</a>:
            {$shipping_info.cost|l10n( 'currency', $locale, $symbol )}
        </td>
        <td class="product-subtotal">
            &nbsp;
        </td>
    </tr>
    <tr>
        <td class="product-subtotal" colspan="{$cols|sub(1)}"><b>{'Order total'|i18n( 'extension/xrowecommerce' )}</b>:
            {$total_inc_shipping_inc_vat|l10n( 'currency', $locale, $symbol )}
        </td>
        <td class="product-subtotal">
            &nbsp;
        </td>
    </tr>
    {/if}
            <tr class="subtotal-line">
                 <td colspan="{$cols|sub(2)}" class="align_right">
                    {"Subtotal Ex. Tax"|i18n("extension/xrowecommerce")}
                 </td>
                 <td class="totalprice">
                     <b class="price">{$basket.total_ex_vat|l10n( 'currency', $locale, $symbol )}</b>
                 </td>
                 <td class="noborder">&nbsp;</td>
            </tr>
            {if eq(ezini( 'BasketInformation', 'DisplayShipping', 'xrowecommerce.ini' ), 'enabled' )}
            <tr class="orderitem-line">
                <td colspan="{$cols|sub(2)}" class="align_right">
                    {"Estimated Shipping and Handling"|i18n("extension/xrowecommerce")}
                </td>
                <td class="price totalprice">
                    {$basket.items_info.additional_info.shipping_total.total_price_inc_vat|l10n( 'currency', $locale, $symbol )}
                </td>
                <td class="noborder">&nbsp;</td>
            </tr>
            {/if}
            {if eq(ezini( 'BasketInformation', 'DisplayTax', 'xrowecommerce.ini' ), 'enabled' )}
            <tr class="tax-line">
                <td colspan="{$cols|sub(2)}" class="align_right">
                    {"Estimated Tax"|i18n("extension/xrowecommerce")}
                </td>
                <td class="price totalprice">
                     {$basket.items_info.total_price_info.price_vat|l10n( 'currency', $locale, $symbol )}
                </td>
                <td class="noborder">&nbsp;</td>
            </tr>
            {/if}
            <tr class="grandtotal-line">
                <td colspan="{$cols|sub(2)}" class="align_right">
                    <strong>
                        {'Total'|i18n( 'extension/xrowecommerce' )}
                    </strong>
                </td>
                <td class="price totalprice">
                    {$basket.total_inc_vat|l10n( 'currency', $locale, $symbol )}
                </td>
                <td class="noborder">&nbsp;</td>
            </tr>
    </table>
     <div id="buttonblock-bottom" class="buttonblock">

        <input id="continue-shopping-button" class="right-arrow" type="submit" name="ContinueShoppingButton" value="{'Continue'|i18n("extension/xrowecommerce")}" title="{'Continue Shopping'|i18n("extension/xrowecommerce")}"/>
        <input id="empty-cart-button" type="button" onclick="empty_basket.submit();" class="button small-action" name="EmptyShoppingCartButton" value="{'Empty Cart'|i18n( 'extension/xrowecommerce' )}" title="Use this button to empty your shopping cart." />

        <input id="store-button" type="submit" class="right-arrow " name="StoreChangesButton" value={'Update'|i18n( 'extension/xrowecommerce' )} title="Use this button to update your shopping cart." />
        <input id="checkout-button" type="submit" class="right-arrow2 continue-button" name="CheckoutButton" value={'Checkout'|i18n( 'extension/xrowecommerce' )} title="Use this button to place your order." />
    </div>
   </div>
 </form>
 {* ####### Login Box ######## *}
    {if eq(ezini( 'BasketInformation', 'DisplayLogin', 'xrowecommerce.ini' ), 'enabled' )}
         {def $user=fetch( 'user', 'current_user' )}
         {if $user.is_logged_in|not()}
                        <div class="loginbox">
                            <p>{'Already a user?'|i18n("extension/xrowecommerce",'User name')}</p>
                            <form method="post" action={"user/login"|ezurl}">
                                <div class="wrap">
                                    <label for="id1">{"Username"|i18n("extension/xrowecommerce",'Username')}</label><div class="labelbreak"></div>
                                    <input type="text" name="Login" id="id1" value="{$User:login|wash}" tabindex="1" />
                                </div>
                                <div class="wrap">
                                    <label for="id2">{"Password"|i18n("extension/xrowecommerce")}</label><div class="labelbreak"></div>
                                    <input type="password" name="Password" id="id2" value="" tabindex="1" />
                                </div>
                                <div class="login-button">
                                    <input class="button" type="submit" name="LoginButton" value="{'Login'|i18n('extension/xrowecommerce')}" tabindex="1">
                                </div>
                                <input type="hidden" name="RedirectURI" value={"xrowecommerce/userregister"|ezroot} />
                             </form>
                        </div>
        {/if}
    {/if}
    {undef $currency $locale $symbol}
    {else}
    <div class="feedback">
        <form method="post" name="basket" action={"xrowecommerce/basket"|ezurl}>
            <p><b>{"You have no items in your shopping cart"|i18n("extension/xrowecommerce")|wash}</b></p>
            <div class="buttonblock">
                <input id="continue-button" class="right-arrow " type="submit" name="ContinueShoppingButton" value="{'Continue'|i18n("extension/xrowecommerce")}" />
            </div>
        </form>
    </div>
    {/if}
</div>

<form name="empty_basket" id="empty_basket" method="post" action={"shop/basket"|ezurl}>
<input type="hidden" name="RemoveProductItemButton" value="RemoveProductItemButton" />
{foreach $basket.items as $key => $product_item sequence array(bglight,bgdark) as $sequence}
    <input type="hidden" name="ProductItemIDList[]" value="{$product_item.id}" />
    <input type="hidden" name="RemoveProductItemDeleteList[]" value="{$product_item.id}" />

    <input class="quantity" type="hidden" name="ProductItemCountList[]" value="{$product_item.item_count}" size="3"/>
{/foreach}
</form>
