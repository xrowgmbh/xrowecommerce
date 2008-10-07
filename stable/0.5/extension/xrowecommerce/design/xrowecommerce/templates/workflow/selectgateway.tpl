<div class="paymentmethodselection">
<h2>{'Select a payment method'|i18n('design/base/shop')}</h2>
    <div class="shopping_cart_path">
    <div>{'1. Cart'|i18n('design/base/shop')}</div>
    <div>{'2. Billing, Shipping and Coupons'|i18n('design/base/shop')}</div>
    <div>{'3. Confirmation'|i18n('design/base/shop')}</div>
    <div class="shopping_cart_path_select">{'4. Payment info'|i18n('design/base/shop')}</div>
    <div>{'5. Order completed'|i18n('design/base/shop')}</div>
    <div>{'6. Review reciept'|i18n('design/base/shop')}</div>
    </div>
    <div class="break"></div>
    <div class="commerce_paymentselection">
    <p>{'Please select your desired payment method below.'|i18n('design/base/shop')}</p>
<form method="post" action={"shop/checkout"|ezurl}>
    {section name=Gateways loop=$event.selected_gateways}
        <input class="commerce_radiobutton" type="radio" name="SelectedGateway" value="{$Gateways:item.value}"
                {run-once}
                    checked="checked"
                {/run-once}
        /><span>{$Gateways:item.Name|wash}</span><br />
    {/section}
    <div class="buttonblock">
        <input class="defaultbutton" type="submit" name="SelectButton"  value="{'Select'|i18n('design/standard/workflow')}" />
        <input class="button" type="submit" name="CancelButton"  value="{'Cancel'|i18n('design/standard/workflow')}" />
    </div>
</form>
</div>

