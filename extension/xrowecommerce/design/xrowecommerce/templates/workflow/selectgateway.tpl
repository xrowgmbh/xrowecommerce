<div class="paymentmethodselection">
<h1>{'Select a payment method'|i18n('extension/xrowecommerce')}</h1>

{include uri="design:shop/basket_navigator.tpl" step='3'}

    <p>{'Please select your desired payment method below.'|i18n('extension/xrowecommerce')}</p>
<form method="post" action={"shop/checkout"|ezurl}>
    {foreach $event.selected_gateways as $gateway}
        <input class="commerce_radiobutton" type="radio" name="SelectedGateway" value="{$gateway.value}" {run-once} checked="checked"  {/run-once}/><span>{$gateway.Name|wash}</span><br />
    {/foreach}
    <div class="buttonblock">
        <input class="defaultbutton" type="submit" name="SelectButton"  value="{'Select'|i18n('extension/xrowecommerce')}" />
        <input class="button" type="submit" name="CancelButton"  value="{'Cancel'|i18n('extension/xrowecommerce')}" />
    </div>
</form>
</div>

{$event|attribute(show, 2)}