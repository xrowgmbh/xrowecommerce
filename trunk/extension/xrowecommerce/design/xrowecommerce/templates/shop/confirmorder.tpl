{def $cols=6}
{if ezini( 'Settings', 'ShowColumnPosition', 'xrowecommerce.ini')|ne('enabled')}
{set $cols=$cols|sub(1)}
{/if}
{if ezini( 'Settings', 'ShowColumnTax', 'xrowecommerce.ini')|ne('enabled')}
{set $cols=$cols|sub(1)}
{/if}
<script type="text/javascript" src={"javascript/yui/build/yahoo-dom-event/yahoo-dom-event.js"|ezdesign}></script>
<script type="text/javascript">             
{literal}
function checkCOS( element )
{
	{/literal}
	{if ezini( 'Settings', 'ConditionsOfService', 'xrowecommerce.ini')|ne('enabled')}
	element.form.submit();
	var fakebutton = document.createElement('input');
    fakebutton.name = element.name;
    fakebutton.type = 'hidden'
    fakebutton.value = element.value;
    element.parentNode.appendChild( fakebutton );
    return true;
    {/if}
    {literal}
	var container = document.getElementById( 'cos' );
	if ( container )
	{
		if( container.checked )
		{
			var fakebutton = document.createElement('input');
			fakebutton.name = element.name;
			fakebutton.type = 'hidden'
		    fakebutton.value = element.value;
			element.parentNode.appendChild( fakebutton );
			element.form.submit();
			return true;
		}
		else
		{
			alert( '{/literal}{'Accept the terms and conditions to continue.'|i18n('extension/xrowecommerce')}{literal}' );
			window.location.hash="cos";
			YAHOO.util.Dom.setStyle( 'cos-content', 'display', 'block');
			return false;
		}
	}
}
function toggleCOS()
{        
    var container = document.getElementById( 'cos-content' );
    if ( container )
    {
        if ( YAHOO.util.Dom.getStyle( container, 'display') == 'block' )
        {
        	YAHOO.util.Dom.setStyle( container, 'display', 'none');
        }
        else
        {
        	YAHOO.util.Dom.setStyle( container, 'display', 'block');
        }
    }
}
function showAlert()
{
    var handleOK = function() {
        this.cancel();
    };
    
    var oPanel3 = new YAHOO.widget.SimpleDialog("panel-3", {
        
        modal: true,
        icon: YAHOO.widget.SimpleDialog.ICON_INFO,
        visible: false,
        fixedcenter: true,
        constraintoviewport: true,
        width: "300px",
        role: "alertdialog",
        buttons: [ { text:"OK", handler:handleOK, isDefault:true } ],
        text: "Your changes have been saved."
    
    }); 

    oPanel3.setHeader("Info");
    oPanel3.render(document.body);

    var oTooltip3 = new YAHOO.widget.Tooltip("tooltip-3", { 
        context:"show-dialog-3", 
        text:"Shows a Modal Dialog built using SimpleDialog using the ARIA role of alertdialog.",
        iframe: true,
        showDelay:500 } );

}
{/literal}
</script>
<div class="shop shop-confirmorder">
    <form method="post" action={"shop/confirmorder"|ezurl} id="confirmorder" name="confirmorder">
        <h1>{"Confirm order"|i18n("extension/xrowecommerce")}</h1>
        {include uri="design:shop/basket_navigator.tpl" step='3'}
        
        <div id="buttonblock-top" class="buttonblock">
			<input id="cancel-button" class="left-arrow2" type="submit" name="CancelButton" value="{'Cancel'|i18n('extension/xrowecommerce')}" title="{'Cancel order'|i18n('extension/xrowecommerce')}"/>
			<input id="continue-button" class="right-arrow2" type="button" onclick="checkCOS(this);" name="ConfirmOrderButton" value="{'Confirm'|i18n('extension/xrowecommerce')}" title="{'Confirm order'|i18n('extension/xrowecommerce')}"/>
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
        
            <p>{'Hazardous item(s) found in your cart.'|i18n('extension/xrowecommerce')}</p>
            <p>
                {'Dear Customer,'|i18n('extension/xrowecommerce')}<br />
                {"We've removed the following hazardous items from your shopping cart since we are not allowed to ship these items to your destination. For further questions please contact %companyname%."|i18n('extension/xrowecommerce',,hash('%companyname%', ezini( 'InvoiceSettings', 'CompanyName', 'order.ini'  )))}<br />
                <ul>
                    {foreach $hazardous as $item}
                    <li>{$item.item_count} x <a href={concat("/content/view/full/", $item.contentobject.main_node_id)|ezurl()}>{$item.name}</a></li>
                    {/foreach}
                </ul>
        	</p>
        {/if}
        
        {if count( $order.product_items )|gt(0)}
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
                    {if ezini( 'Settings', 'ShowColumnTax', 'xrowecommerce.ini')|eq('enabled')}
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
                </tr>
                {foreach $order.product_items as $key => $product_item sequence array(bglight,bgdark) as $sequence}
                <tr class="{$sequence} product-line">
                   {if ezini( 'Settings', 'ShowColumnPosition', 'xrowecommerce.ini' )|eq('enabled')}
                   <td class="position">
                   {$key|sum(1)}
                   </td>
                   {/if}
                   <td class="product-name basketspace">
                        <b>{$product_item.item_count}</b>
                    </td>
                   <td class="product-name basketspace cart_item">
                    {include uri="design:shop/product_cell_view.tpl" view="confirmorder"}
                    </td>
            {if ezini( 'Settings', 'ShowColumnTax', 'xrowecommerce.ini' )|eq('enabled')}
            <td class="align_right product-name basketspace">
               {$product_item.vat_value} %
            </td>
            {/if}
            <td class="align_right product-name basketspace price">
               {$product_item.price_ex_vat|l10n( 'currency', $locale, $symbol )}
            </td>
            <td class="align_right product-name basketspace totalprice">{$product_item.total_price_ex_vat|l10n( 'currency', $locale, $symbol )}</td>
         </tr>
         {/foreach}
     <tr class="subtotal-line">
		<td colspan="{$cols|sub(1)}">
         	{"Subtotal ex. tax"|i18n("extension/xrowecommerce")}
        </td>
        <td class="align_right basketspace totalprice">
             <strong class="price">{$order.product_total_ex_vat|l10n( 'currency', $locale, $symbol )}</strong>
        </td>
     </tr>
    {foreach $order.order_items as $OrderItem sequence array(bglight,bgdark) as $sequence}
    <tr class="{$sequence} orderitem-line">
        <td class=" line" colspan="{$cols|sub(1)}">
        {$OrderItem.description}
        </td>
        <td class="basketspace line align_right totalprice">
        {$OrderItem.price_ex_vat|l10n( 'currency', $locale, $symbol )}
        </td>
    </tr>
    {/foreach}
    {def $taxpercent = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)
         $percentage = mul( div(sub($order.total_inc_vat, $order.total_ex_vat), $order.total_ex_vat), 100)|l10n('number') }
    {if $taxpercent|eq(0)|not}
    <tr class="{$sequence} tax-line">
        <td class="line" colspan ="{$cols|sub(1)}">
        {"Tax"|i18n("extension/xrowecommerce")}
        </td>
        <td class="basketspace line align_right totalprice">
        {sub($order.total_inc_vat, $order.total_ex_vat)|l10n( 'currency', $locale, $symbol )}
        </td>
    </tr>
    {/if}
    <tr class="grandtotal-line">
        <td class="price" colspan="{$cols|sub(1)}">
        <b>{"Order total"|i18n("extension/xrowecommerce")}</b>
        </td>
        <td class="align_right price totalprice">
        <strong>{$order.total_inc_vat|l10n( 'currency', $locale, $symbol )}</strong>
        </td>
    </tr>
    </table>
    {if ezini( 'Settings', 'ConditionsOfService', 'xrowecommerce.ini')|eq('enabled')}
        <a name="cos"></a>
        <label class="cos" for="cos" ><input id="cos" name="cos" class="cos" value="1" type="checkbox" /><span title="{'Show the terms and conditions.'|i18n('extension/xrowecommerce')}">{'I have read the %linkstart%general terms and conditions%linkend% and accept them.'|i18n('extension/xrowecommerce',,hash('%linkstart%', '<a onclick="toggleCOS(); ">', '%linkend%', '</a>' ))}</span></label>
        <div id="cos-content" style="display: none;">
        {include uri="design:shop/terms_and_conditions.tpl" view="confirmorder"}
        </div>
    {/if}
        <div id="buttonblock-bottom" class="buttonblock">
            <input id="cancel-button" class="left-arrow2" type="submit" name="CancelButton" value="{'Cancel'|i18n('extension/xrowecommerce')}" title="{'Cancel order'|i18n('extension/xrowecommerce')}"/>
            <input id="continue-button" class="right-arrow2" type="button" onclick="checkCOS(this);" name="ConfirmOrderButton" value="{'Confirm'|i18n('extension/xrowecommerce')}" title="{'Confirm order'|i18n('extension/xrowecommerce')}"/>
        </div>
    {else}
    {* If the shopping cart is empty after removing hazardous items... *}
    <h3>{"Sorry, there are no items left in your cart."|i18n("extension/xrowecommerce")}</h3>
    
    {/if}
    </form>
</div>