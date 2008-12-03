<div class="shop-basket">
    <form method="post" action={"/shop/confirmorder/"|ezurl}>
        <h1>{"Confirm order"|i18n("extension/xrowecommerce")}</h1>
        {include uri="design:shop/basket_navigator.tpl" step='3'}
        <div class="buttonblock">
			<input class="left-arrow2 smallbutton" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/base/shop')}" />
			<input class="right-arrow2 smallbutton" type="submit" name="ConfirmOrderButton" value="{'Confirm'|i18n('design/base/shop')}" />
        </div>
        <div class="break"></div>
        {shop_account_view_gui view=html order=$order}
        {def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
            $locale = false()
            $symbol = false()}
        {if $currency}
            {set locale = $currency.locale
                 symbol = $currency.symbol}
        {/if}
        {if count($hazardous)|gt(0)}
        
            <p>{'Hazardous item(s) found in your cart.'|i18n('design/base/shop')}</p>
            <p>
                {'Dear Customer,'|i18n('design/base/shop')}<br />
                {"We've removed the following hazardous items from your shopping cart since we are not allowed to ship these items to your destination. For further questions please contact %companyname%."|i18n('design/base/shop',,hash('%companyname%', ezini( 'InvoiceSettings', 'CompanyName', 'order.ini'  )))}<br />
                <ul>
                    {foreach $hazardous as $item}
                    <li>{$item.item_count} x <a href={concat("/content/view/full/", $item.contentobject.main_node_id)|ezurl()}>{$item.name}</a></li>
                    {/foreach}
                </ul>
        	</p>
        {/if}
        
        {if count( $order.product_items )|gt(0)}
            <table class="order">
                <caption>{"Product items"|i18n("design/base/shop")}</caption>
                <tr class="lightbg">
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
                    <th class="totalprice">
                        {"Total Price"|i18n("design/base/shop")}
                    </th>
                </tr>
                {section var=product_item loop=$order.product_items sequence=array(bglight,bgdark)}
                <tr>
                   <td class="{$product_item.sequence} product-name basketspace">
                        <b>{$product_item.item_count}</b>
                    </td>
                   <td class="{$product_item.sequence} product-name basketspace cart_item">
                    {include uri="design:shop/product_cell_view.tpl"}
                    </td>

            <td class="{$product_item.sequence} product-name basketspace">
               {$product_item.vat_value} %
            </td>
            <td class="{$product_item.sequence} product-name basketspace">
               {$product_item.price_ex_vat|l10n( 'currency', $locale, $symbol )}
            </td>
            <td class="{$product_item.sequence} product-name basketspace totalprice">
                {$product_item.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}
            </td>
         </tr>
         {/section}
     <tr>
		<td class="line" colspan="4">
         	{"Subtotal ex. tax"|i18n("design/base/shop")}
        </td>
        <td colspan="5" class="align_right line totalprice">
             <strong class="price">{$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}</strong>
        </td>
     </tr>
    {section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}
    <tr >
        <td class="{$OrderItem:sequence}" colspan="4">
        {$OrderItem:item.description}:
        </td>
        <td class="{$OrderItem:sequence} basketspace align_right totalprice">
        {$OrderItem:item.price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
    </tr>
    {/section}
    {def $taxpercent = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)
         $percentage = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)|l10n('number') }
    {if $taxpercent|eq(0)|not}
    <tr>
        <td class="{$OrderItem:sequence} line" colspan ="4">
        {"Tax"|i18n("design/base/shop")}
        </td>
        <td class="{$OrderItem:sequence} basketspace line align_right totalprice">
        {sub($order.total_inc_vat, $order.total_ex_vat)|l10n( 'currency', $locale, $symbol )}
        </td>
    </tr>
    {/if}
    <tr>
        <td class="line price"  colspan ="4">
        <b>{"Order total"|i18n("design/base/shop")}</b>
        </td>
        <td class="line align_right price totalprice">
        <strong>{$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}</strong>
        </td>
    </tr>
    </table>
    {else}
    {* If the shopping cart is empty after removing hazardous items... *}
    <h3>{"Sorry, there are no items left in your cart."|i18n("extension/xrowecommerce")}</h3>
    
    {/if}
    </form>
</div>