<div class="shop-basket">
    <h2>{"Order Reciept #%order_id "|i18n("design/base/shop",,
         hash( '%order_id', $order.order_nr,
               '%order_status', $order.status_name ) )}</h2>
 <br />
    <div class="shopping_cart_path">
    <div>1. Cart</div>
    <div>2. Billing, Shipping and Coupons</div>
    <div>3. Confirmation</div>
    <div>4. Payment info</div>
    <div class="shopping_cart_path_select">5. Order completed</div>
    <div>6. Review order</div>
    </div>
    <div class="break"></div>
    <br />
    {shop_account_view_gui view=html order=$order}

    {def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
         $locale = false()
         $symbol = false()}

    {if $currency}
        {set locale = $currency.locale
             symbol = $currency.symbol}
    {/if}

    <div class="content-basket">
    <table cellspacing="0" border="0">
    <tr class="lightbg">
    <th>
        {"Quantity"|i18n("design/base/shop")}
        </th>
        <th>
        {"Item"|i18n("design/base/shop")}
        </th>
        <th align="right">
        {"TAX"|i18n("design/base/shop")}
        </th>
        <th align="right">
        {"Price"|i18n("design/base/shop")}
        </th>
        <th align="right">
        {"Discount"|i18n("design/base/shop")}
        </th>
        <th align="right">
        {"Total Price"|i18n("design/base/shop")}
        </th>
    </tr>
    {section var=product_item loop=$order.product_items sequence=array(bglight,bgdark)}
    <tr>
        <td class="{$product_item.sequence} product-name basketspace" align="center" valign="top">
            {$product_item.item_count}
        </td>
        <td class="{$product_item.sequence} basketspace">
         {def $prod=fetch( 'content', 'node', hash( 'node_id', $product_item.node_id ) )}


            <table>
                 {def $prod=fetch( 'content', 'node', hash( 'node_id', $product_item.node_id ) )}
                 {if $prod.data_map.variation.content.option_list|count|gt(0)}
                 {section var=option_item loop=$product_item.item_object.option_list}
                    <tr>
                        <td rowspan="4" width="120">
                        {attribute_view_gui image_class=small attribute=$product_item.item_object.contentobject.data_map.image}
                        </td>
                        <td>
                        <p><a class="basketlink" href={concat("/content/view/full/",$prod.node_id)|ezurl}>{$prod.name|wash()}</a></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        {$prod.data_map.product_id.content|wash()}{$option_item.value}
                        </td>
                    </tr>
                    <tr>
                        <td>
                        {def $vary=$product_item.item_object.contentobject.data_map.variation.content.option_list[$product_item.item_object.option_list.0.option_item_id]}
                        {$vary.comment}                        
                        </td>
                    </tr>
                    <tr>
                        <td>
                        {*if or(ne($vary.weight, false()), ne($vary.weight, "0"))}Weight: {$vary.weight} lbs
                        {/if*}
                        Weight: {$vary.weight|wash()} lbs
                        </td>
                    </tr>
                    {/section}
                 {else}
                    <tr>
                        <td rowspan="4" width="120">
                        {attribute_view_gui image_class=small attribute=$product_item.item_object.contentobject.data_map.image}
                        </td>
                        <td>
                        <p><a class="basketlink" href={concat("/content/view/full/",$prod.node_id)|ezurl}>{$prod.name|wash()}</a></p>
                        </td>
                    </tr>
                    <tr>
                        <td>
                        {$prod.data_map.product_id.content|wash()}{$option_item.value}
                        </td>
                    </tr>
                    <tr>
                        <td>
                        {def $vary=$product_item.item_object.contentobject.data_map.variation.content.option_list[$product_item.item_object.option_list.0.option_item_id]}
                        {$vary.comment}                        
                        </td>
                    </tr>
                    <tr>
                        <td>
                        {*if or(ne($vary.weight, false()), ne($vary.weight, "0"))}Weight: {$vary.weight} lbs
                        {/if}
                        {$prod.data_map|attribute(show)*}
                        Weight: {attribute_view_gui attribute=$prod.data_map.weight} lbs
                        </td>
                    </tr>
                    {/if}
                </table>



        {*section show=$product_item.item.item_object.option_list}
              <table class="shop-option_list">
         {section var=option_item loop=$product_item.item_object.option_list}
         <tr>
         {<td class="shop-option_name">{$option_item.name}</td>}
             <td class="shop-option_value">
                 {def $vary=$product_item.item_object.contentobject.data_map.variation.content.option_list[$product_item.item_object.option_list.0.option_item_id]}
                 {$option_item.value}<br />
                 <b>{$vary.comment}</b><br />
                 {if or(ne($vary.weight, false()), ne($vary.weight, "0"))}Weight:{$vary.weight} lbs</b><br />{/if}
             </td>
         </tr>
         {/section}
         </table>
         {section-else}
             <table class="shop-option_list">
                 <tr>
                    {def $prod=fetch( 'content', 'node', hash( 'node_id', $product_item.node_id ) )}
                     <td class="shop-option_value">{$prod.data_map.product_id.content}</td>
                 </tr>
            </table>

         {/section*}
        </td>
        <td valign="top" class="basketspace">
        {$product_item.vat_value} %

        </td>
        <td valign="top" class="basketspace">
        {$product_item.price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
        <td valign="top" class="basketspace">
        {$product_item.discount_percent}%
        </td>
        <td valign="top" class="basketspace">
        {$product_item.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
     </tr>
     {/section}
     <tr>
         <td colspan='6' class="line3 line2 align_right">
         Subtotal Ex. TAX:
         <b class="price">{$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}</b>
         </td>
     </tr>
        <tr><td colspan="6"></tr>
     </table>
     </div>



    <h2>{"Order summary"|i18n("design/base/shop")}:</h2>
    <table class="list" cellspacing="0" cellpadding="0" border="0">
    <tr>
        <td class="bgdark">
        {"Subtotal of items Ex. Taxt"|i18n("design/base/shop")}:
        </td>
        <td class="bgdark align_right">
        {$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
    </tr>
    {section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}
    <tr>
        <td class="{$OrderItem:sequence}">
        {$OrderItem:item.description}:
        </td>
        <td class="{$OrderItem:sequence}" style="text-align: right;">
        {$OrderItem:item.price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
    </tr>
    {/section}
    {def $taxpercent = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)
     $percentage = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)|l10n('number') }
     {if $taxpercent|eq(0)|not}
<tr>
    <td colspan ="2" class="line3"></td>
</tr>
<tr>
    <td class="{$OrderItem:sequence}<!--[if IE]> line3<![endif]-->">
    Tax{if or( $percentage|eq(8.25), $percentage|eq(6.00) )} ( {$percentage} % ){/if}:
    </td>
    <td class="{$OrderItem:sequence} align_right<!--[if IE]> line3<![endif]-->">
    {sub($order.total_inc_vat, $order.total_ex_vat)|l10n( 'currency', $locale, $symbol )}
    </td>
</tr>
{/if}
<tr>
    <td colspan ="2"></td>
</tr>
    <tr>
        <td class="price line2">
        {"Order total"|i18n("design/base/shop")}
        </td>
        <td class="price align_right line2">
        {$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}
        </td>
    </tr>
    </table>


{def $user_city=wrap_user_func('getXMLString', array('city', $order.data_text_1))}
{def $user_state=wrap_user_func('getXMLString', array('state', $order.data_text_1))}

{def $user_country=wrap_user_func('getXMLString', array('country', $order.data_text_1))}

{* Format:
    UTM:T|[order-id]|[affiliation]|
    [total]|[tax]| [shipping]|[city]|[state]|[country] UTM:I|[order-id]|[sku/code]|[productname]|[category]|[price]|
    [quantity]
*}

    <form style="display:none;" name="utmform">
    <textarea id="utmtrans">UTM:T|[{$order.order_nr}]|[alconeco]|
    [{$order.total_ex_vat}]|[{sub($order.total_inc_vat,$order.total_ex_vat)}]| [{section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}{$OrderItem:item.price_inc_vat}{/section}]|[{$user_city}]|[{$user_state}]|[{$user_country}] {section var=product_item loop=$order.product_items sequence=array(bglight,bgdark)}UTM:I|[{$order.order_nr}]|[{$product_item.node_id}]|[{$product_item.object_name}]|[{$product_item.item_object.contentobject.main_node.parent.name}]|[{$product_item.price_inc_vat}]|[{$product_item.item_count}]</textarea>{/section}
    </form>

    <script language="text/javascript">
    __utmSetTrans();
    </script>

</div>
