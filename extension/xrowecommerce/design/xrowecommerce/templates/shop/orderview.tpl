<div class="shop-basket">
    <h1>{"Order Receipt #%order_id "|i18n("extension/xrowecommerce",,
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
    <caption>{"Product items"|i18n("extension/xrowecommerce")}</caption>
    <tr class="lightbg">
        <th>
        {"Quantity"|i18n("extension/xrowecommerce")}
        </th>
        <th>
        {"Item"|i18n("extension/xrowecommerce")}
        </th>
        <th>
        {"Tax"|i18n("extension/xrowecommerce")}
        </th>
        <th>
        {"Price"|i18n("extension/xrowecommerce")}
        </th>
        <th class="totalprice">
        {"Total Price"|i18n("extension/xrowecommerce")}
        </th>
    </tr>
    {foreach $order.product_items as $product_item sequence array(bglight,bgdark) as $sequence}
    <tr class="product-line">
        <td class="{$product_item.sequence} product-name basketspace">
            {$product_item.item_count}
        </td>
        <td class="{$product_item.sequence} basketspace cart_item">
            {include uri="design:shop/product_cell_view.tpl" product_item=$product_item}
        </td>
        <td class="basketspace">
        {$product_item.vat_value} %

        </td>
        <td class="basketspace">
        {$product_item.price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
        <td class="align_right basketspace totalprice">
        {$product_item.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
     </tr>
     {/foreach}
     <tr class="subtotal-line">
		<td  colspan="4">
         	{"Subtotal ex. tax"|i18n("extension/xrowecommerce")}
        </td>
        <td class="align_right totalprice">
             <strong class="price">{$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}</strong>
        </td>
     </tr>
    {foreach $order.order_items as $OrderItem sequence array(bglight,bgdark) as $sequence}
    <tr class="orderitem-line">
        <td class="{$sequence} line" colspan="4">
        {$OrderItem.description}
        </td>
        <td class="{$sequence} align_right line">
        {$OrderItem.price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
    </tr>
    {/foreach}
    {def $taxpercent = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)
     $percentage = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)|l10n('number') }
     {if $taxpercent|eq(0)|not}
<tr>
    <td class="{$sequence} line" colspan ="4">
    {'Tax'|i18n('extension/xrowecommerce')}
    </td>
    <td class="{$sequence} line align_right totalprice">
    {sub($order.total_inc_vat, $order.total_ex_vat)|l10n( 'currency', $locale, $symbol )}
    </td>
</tr>
{/if}
    <tr class="grandtotal-line">
        <td colspan="4">
        {"Order total"|i18n("extension/xrowecommerce")}
        </td>
        <td class="align_right totalprice">
        <strong>{$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}</strong>
        </td>
    </tr>
</table>

{def $user_city=fetch( 'xrowecommerce', 'get_shopaccount_value', hash( 'name','city', 'order', $order) )}
{def $user_state=fetch( 'xrowecommerce', 'get_shopaccount_value', hash( 'name','state', 'order', $order) )}
{def $user_country=fetch( 'xrowecommerce', 'get_shopaccount_value', hash( 'name','country', 'order', $order) )}

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
