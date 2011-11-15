<div class="shop shop-payment shop-payment-selectgateway">

{include uri="design:shop/basket_navigator.tpl" step='4'}

<h1>{'Select a payment method'|i18n('extension/xrowecommerce')}</h1>

{def $gateways = $event.allowed_gateways}
{if $gateways|gt(0)}
    <p>{'Please select your desired payment method below.'|i18n('extension/xrowecommerce')}</p>
<form method="post" action={"xrowecommerce/checkout"|ezurl}>
<input class="hide" style="display: hide;" type="submit" name="SelectButton"  value="{'Select'|i18n('extension/xrowecommerce')}" />
    <ul id="gateways">
    {foreach $gateways as $gateway}
        <li><input class="commerce_radiobutton" type="radio" name="SelectedGateway" value="{$gateway.value}" {run-once} checked="checked"  {/run-once}/>
        <span>
            {$gateway.Name|wash}
            {if $gateway.costs|gt(0)}
                ({'plus %gateway_costs'|i18n('extension/xrowecommerce', '', hash( '%gateway_costs', $gateway.costs|l10n('currency')))})
            {/if}
        </span>
        {if concat('gateway/', $gateway.value, '.png')|ezdesign(no)|begins_with( '/extension' )}
        <img src={concat('gateway/', $gateway.value, '.png')|ezdesign} />
        {/if}
        </li>
    {/foreach}
    </ul>
    <div id="buttonblock-bottom" class="buttonblock">
        <input id="cancel-button" class="button left-arrow2" type="submit" name="CancelButton" value="{'One step back'|i18n('extension/xrowecommerce')}" title="{'One step back'|i18n('extension/xrowecommerce')}"/>
        <input id="continue-button" class="button right-arrow2" type="submit" name="SelectButton" value="{'Continue'|i18n('extension/xrowecommerce')}" title="{'Continue'|i18n('extension/xrowecommerce')}"/>
    </div>
</form>
{else}
 <p>{'You do not have permission to use any of the available payment methods.'|i18n('extension/xrowecommerce')}</p>
 <div class="buttonblock">
    <input id="cancel-button" class="button" type="submit" name="CancelButton"  value="{'Cancel'|i18n('extension/xrowecommerce')}" />
    <div class="break"></div>
 </div>
{/if}

</div>