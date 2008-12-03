<div class="shop-basket">
    <h1>{"Order Reciept #%order_id "|i18n("design/base/shop",,
         hash( '%order_id', $order.order_nr,
               '%order_status', $order.status_name ) )}</h1>

{include uri="design:shop/basket_navigator.tpl" step='5'}

    {shop_account_view_gui view=html order=$order}
    {def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
         $locale = false()
         $symbol = false()}
    {if $currency}
        {set locale = $currency.locale
             symbol = $currency.symbol}
    {/if}

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
        {"Price"|i18n("design/base/shop")}
        </th>
        <th class="totalprice">
        {"Total Price"|i18n("design/base/shop")}
        </th>
    </tr>
    {section var=product_item loop=$order.product_items sequence=array(bglight,bgdark)}
    <tr>
        <td class="{$product_item.sequence} product-name basketspace" align="center" valign="top">
            {$product_item.item_count}
        </td>
        <td class="{$product_item.sequence} basketspace cart_item">
            {include uri="design:shop/product_cell_view.tpl"}
        </td>
        <td valign="top" class="basketspace">
        {$product_item.vat_value} %

        </td>
        <td valign="top" class="basketspace">
        {$product_item.price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
        <td valign="top" class="basketspace totalprice">
        {$product_item.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
     </tr>
     {/section}
     <tr>
		<td class="line" colspan="4">
         	{"Subtotal ex. tax"|i18n("design/base/shop")}:
        </td>
        <td class="totalprice line">
             <strong class="price">{$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}</strong>
        </td>
     </tr>
    {section name=OrderItem loop=$order.order_items show=$order.order_items sequence=array(bglight,bgdark)}
    <tr>
        <td class="{$OrderItem:sequence}" colspan="4">
        {$OrderItem:item.description}:
        </td>
        <td class="{$OrderItem:sequence}">
        {$OrderItem:item.price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
    </tr>
    {/section}
    {def $taxpercent = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)
     $percentage = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)|l10n('number') }
     {if $taxpercent|eq(0)|not}
<tr>
    <td class="{$OrderItem:sequence} line3" colspan ="4">
    {'Tax'|i18n('design/standard/shop')}
    </td>
    <td class="{$OrderItem:sequence} totalprice">
    {sub($order.total_inc_vat, $order.total_ex_vat)|l10n( 'currency', $locale, $symbol )}
    </td>
</tr>
{/if}
    <tr>
        <td class="price line" colspan="4">
        {"Order total"|i18n("design/base/shop")}
        </td>
        <td class="price totalprice line">
        <strong>{$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}</strong>
        </td>
    </tr>
</table>


{def $user_city=wrap_user_func('getXMLString', array('city', $order.data_text_1))}
{def $user_state=wrap_user_func('getXMLString', array('state', $order.data_text_1))}

{def $user_country=wrap_user_func('getXMLString', array('country', $order.data_text_1))}

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript">
  var pageTracker = _gat._getTracker("{ezini( 'GoogleAnalyticsWorkflow', 'Urchin', 'googleanalytics.ini'  )}");
  pageTracker._initData();
  pageTracker._trackPageview();

  pageTracker._addTrans(
    "{$order.order_nr}",                                          // Order ID
    "{ezini( 'InvoiceSettings', 'CompanyName', 'order.ini'  )}", // Affiliation
    "{$order.total_ex_vat}",                                    // Total
    "{$order.order_info.total_price_info.total_price_vat}",    // Tax
    "{$order.account_information.shipping}",                  // Shipping
    "{$user_city}",                                          // City
    "{$user_state}",                                        // State
    "{$user_country}"                                      // Country
  );
 {section var=product_item loop=$order.product_items sequence=array(bglight,bgdark)}
 {def $prod=fetch( 'content', 'node', hash( 'node_id', $product_item.node_id ) )}
 {def $prod=fetch( 'content', 'node', hash( 'node_id', $product_item.node_id ) )}
    {if $prod.data_map.variation.content.option_list|count|gt(0)}
        {section var=option_item loop=$product_item.item_object.option_list}
        {def $vary=$product_item.item_object.contentobject.data_map.variation.content.option_list[$product_item.item_object.option_list.0.option_item_id]}

                    "{$order.order_nr}",                                                     // Order ID
                    "{$option_item.value} - {$prod.name|wash()}",                           // SKU
                    "{$vary.comment}    ",                                                 // Product Name
                    "{$product_item.item_object.contentobject.main_node.parent.name}",    // Category
                    "{$product_item.price_ex_vat|l10n( 'currency', $locale, $symbol )}", // Price
                    "{$product_item.item_count}"                                        // Quantity
                  );
        {/section}
    {else}
                  pageTracker._addItem(
                    "{$order.order_nr}",                                                     // Order ID
                    "{$prod.data_map.product_id.content|wash()} - {$prod.name|wash()}",     // SKU
                    "{$prod.name|wash()}",                                                 // Product Name 
                    "{$product_item.item_object.contentobject.main_node.parent.name}",    // Category
                    "{$product_item.price_ex_vat|l10n( 'currency', $locale, $symbol )}", // Price
                    "{$product_item.item_count}"                                        // Quantity
                  );
    {/if}
{/section}
  pageTracker._trackTrans();
</script>
</div>
