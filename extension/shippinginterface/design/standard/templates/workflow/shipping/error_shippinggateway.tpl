{include uri="design:shop/basket_navigator.tpl" step='2'}
<h1>{"Shipping Gateway"|i18n("extension/xrowecommerce")}</h1>
{if $message}
<div class="warning">
<h2>{'Error'|i18n('extension/xrowecommerce')}</h2>
<ul>
<li>{$message|wash}</li>
</ul>
</div>
{/if}
<p>
{"The shipping gateway provider has a temporary problem. Please try again later or contact support."|i18n("extension/xrowecommerce")}
</p>
<form method="post" action={"xrowecommerce/userregister"|ezurl}>
 <div class="buttonblock">
    <input id="cancel-button" class="button" type="submit" name="Back"  value="{'Back'|i18n('extension/xrowecommerce')}" />
    <div class="break"></div>
 </div>
 </form>