<div class="shop">
<div class="paymentmethodselection">
<h1>{'Select a payment method'|i18n('extension/xrowecommerce')}</h1>

{include uri="design:shop/basket_navigator.tpl" step='4'}

{def $gateways = $event.workflow_type.allowed_gateways}
{if $gateways|gt(0)}
    <p>{'Please select your desired payment method below.'|i18n('extension/xrowecommerce')}</p>
<form method="post" action={"shop/checkout"|ezurl}>
    {foreach $gateways as $gateway}
        <input class="commerce_radiobutton" type="radio" name="SelectedGateway" value="{$gateway.value}" {run-once} checked="checked"  {/run-once}/><span>{$gateway.Name|wash}</span><br />
    {/foreach}
    <div class="buttonblock">
        <input id="cancle-button" class="button" type="submit" name="CancelButton"  value="{'Cancel'|i18n('extension/xrowecommerce')}" />
        <input id="continue-button" class="defaultbutton" type="submit" name="SelectButton"  value="{'Select'|i18n('extension/xrowecommerce')}" />
    </div>
</form>
{else}
 <p>{'You do not have permission to use any of the available payment methods.'|i18n('extension/xrowecommerce')}</p>
 <div class="buttonblock">
    <input id="cancle-button" class="button" type="submit" name="CancelButton"  value="{'Cancel'|i18n('extension/xrowecommerce')}" />
 </div>
{/if}
</div>
</div>