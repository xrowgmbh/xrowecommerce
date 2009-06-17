{def $cols=6}
{if ezini( 'Settings', 'ShowColumnPosition', 'xrowecommerce.ini')|ne('enabled')}
{set $cols=$cols|sub(1)}
{/if}
{if ezini( 'Settings', 'ShowColumnTax', 'xrowecommerce.ini')|ne('enabled')}
{set $cols=$cols|sub(1)}
{/if}

<div class="shop shop-orderview">
    <h1>{"Order receipt #%order_id "|i18n("extension/xrowecommerce",,
         hash( '%order_id', $order.order_nr,
               '%order_status', $order.status_name ) )}</h1>

{include uri="design:shop/basket_navigator.tpl" step='5'}
<div class="no-print">
<form method="post" action={"xrowecommerce/basket"|ezurl}>
<input type="submit" class="hide" name="Continue" value="{'Continue'|i18n('extension/xrowecommerce')}" />
        <div id="buttonblock-top" class="buttonblock">
            <input id="print-button" class="button" type="button" name="Print" onclick="window.print();" value="{'Print order'|i18n('extension/xrowecommerce')}" title="{'Print order receipt'|i18n('extension/xrowecommerce')}" />
            <input id="continue-button" class="button" type="submit" name="ContinueShoppingButton" value="{'Homepage'|i18n('design/ezwebin/collectedinfo/form')|wash()}" title="{'Continue shopping'|i18n('extension/xrowecommerce')}" />
        </div>
</form>
</div>
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
        {"Price"|i18n("extension/xrowecommerce")}
        </th>
        <th class="totalprice">
        {"Total Price"|i18n("extension/xrowecommerce")}
        </th>
    </tr>
    {foreach $order.product_items as $key => $product_item sequence array(bglight,bgdark) as $sequence}
    <tr class="{$sequence} product-line">
        {if ezini( 'Settings', 'ShowColumnPosition', 'xrowecommerce.ini' )|eq('enabled')}
        <td class="position">
        {$key|sum(1)}
        </td>
        {/if}
        <td class="product-name basketspace">
            {$product_item.item_count}
        </td>
        <td class="basketspace cart_item">
            {include uri="design:shop/product_cell_view.tpl" view="overview"}
        </td>
        {if ezini( 'Settings', 'ShowColumnTax', 'xrowecommerce.ini' )|eq('enabled')}
        <td class="basketspace">
        {$product_item.vat_value} %
        </td>
        {/if}
        <td class="basketspace price">
            {if $product_item.discount_percent}
            {def $discount = $product_item.price_ex_vat|div(100)|mul($product_item.discount_percent)
                 $price = $product_item.price_ex_vat|sub($discount)}
            {else}
                {def $price = $product_item.price_ex_vat}
            {/if}
            {$price|l10n( 'currency', $locale, $symbol )}
            {undef $discount $price}
        </td>
        <td class="align_right basketspace totalprice">
        {$product_item.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
     </tr>
     {/foreach}
     <tr class="subtotal-line">
		<td colspan="{$cols|sub(1)}">
         	{"Subtotal ex. tax"|i18n("extension/xrowecommerce")}
        </td>
        <td class="align_right totalprice">
             <strong class="price">{$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}</strong>
        </td>
     </tr>
    {foreach $order.order_items as $OrderItem sequence array(bglight,bgdark) as $sequence}
    <tr class="{$sequence} orderitem-line">
        <td class="line" colspan="{$cols|sub(1)}">
        {$OrderItem.description}
        </td>
        <td class="align_right line">
        {$OrderItem.price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
    </tr>
    {/foreach}
    {def $taxpercent = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)
     $percentage = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)|l10n('number') }
     {if $taxpercent|eq(0)|not}
<tr class="{$sequence}">
    <td class="line" colspan ="{$cols|sub(1)}">
    {'Tax'|i18n('extension/xrowecommerce')}
    </td>
    <td class="line align_right totalprice">
    {sub($order.total_inc_vat, $order.total_ex_vat)|l10n( 'currency', $locale, $symbol )}
    </td>
</tr>
{/if}
    <tr class="grandtotal-line">
        <td colspan="{$cols|sub(1)}">
        {"Order total"|i18n("extension/xrowecommerce")}
        </td>
        <td class="align_right totalprice">
        <strong>{$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}</strong>
        </td>
    </tr>
</table>
<div class="no-print">
<form method="post" action={""|ezurl}>
        <div id="buttonblock-bottom" class="buttonblock">
            <input id="print-button" class="button" type="button" name="Print" onclick="window.print();" value="{'Print order'|i18n('extension/xrowecommerce')}" title="{'Print order receipt'|i18n('extension/xrowecommerce')}" />
            <input id="continue-button" class="button" type="submit" name="Continue" value="{'Homepage'|i18n('design/ezwebin/collectedinfo/form')|wash()}" title="{'Continue shopping'|i18n('extension/xrowecommerce')}" />
        </div>
</form>
</div>
{def $user_city=fetch( 'xrowecommerce', 'get_shopaccount_value', hash( 'name','city', 'order', $order) )}
{def $user_state=fetch( 'xrowecommerce', 'get_shopaccount_value', hash( 'name','state', 'order', $order) )}
{def $user_country=fetch( 'xrowecommerce', 'get_shopaccount_value', hash( 'name','country', 'order', $order) )}

<script type="text/javascript">
var gaJsHost = (("https:" == document.location.protocol) ? "https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + "google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
</script>

<script type="text/javascript">
  try {ldelim}

  var pageTracker = _gat._getTracker("{ezini( 'GoogleAnalyticsWorkflow', 'Urchin', 'googleanalytics.ini'  )}");
  pageTracker._initData();
  pageTracker._trackPageview();

  pageTracker._addTrans(
    "{$order.order_nr}",                                          // Order ID
    "{ezini( 'InvoiceSettings', 'CompanyName', 'xrowecommmerce.ini'  )}", // Affiliation
    "{$order.total_ex_vat}",                                    // Total
    "{$order.order_info.total_price_info.total_price_vat}",    // Tax
    "{$order.account_information.shipping}",                  // Shipping
    "{$user_city}",                                          // City
    "{$user_state}",                                        // State
    "{$user_country}"                                      // Country
  );
 {foreach $order.product_items as $product_item}
 {def $prod=fetch( 'content', 'node', hash( 'node_id', $product_item.node_id ) )}
    {if and( is_set($prod.data_map.variation), $prod.data_map.variation.content.option_list|count|gt(0) )}
        {foreach $product_item.item_object.option_list as $option_item}
        {def $vary=$product_item.item_object.contentobject.data_map.variation.content.option_list[$product_item.item_object.option_list.0.option_item_id]}
        pageTracker._addItem(
                    "{$order.order_nr}",                                                     // Order ID
                    "{$option_item.value} - {$prod.name|wash()}",                           // SKU
                    "{$vary.comment}",                                                 // Product Name
                    "{$product_item.item_object.contentobject.main_node.parent.name}",    // Category
                    "{$product_item.price_ex_vat|l10n( 'currency', $locale, $symbol )}", // Price
                    "{$product_item.item_count}"                                        // Quantity
                  );
        {/foreach}
    {else}
                  pageTracker._addItem(
                    "{$order.order_nr}",                                                     // Order ID
                    "{$product_item.object_name|wash()}",     // SKU
                    "{$product_item.object_name|wash()}",                                                 // Product Name
                    "{$product_item.item_object.contentobject.main_node.parent.name}",    // Category
                    "{$product_item.price_ex_vat|l10n( 'currency', $locale, $symbol )}", // Price
                    "{$product_item.item_count}"                                        // Quantity
                  );
    {/if}
{/foreach}
  pageTracker._trackTrans();
  {ldelim} catch(err) {ldelim}{rdelim}
</script>
</div>
