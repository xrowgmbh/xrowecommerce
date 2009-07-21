<div class="shop shop-payment shop-payment-gateway">
{include uri="design:shop/basket_navigator.tpl" step='4'}

<h1>{"Payment information"|i18n("extension/xrowcomdirect")}</h1>

<form name="form" action={"/shop/checkout"|ezurl} method="post">
<input type="submit" class="hide" style="display: hide;" name="validate" value="{'Continue'|i18n('extension/xrowcomdirect')}" />

<div class="form">
<h4>{"Please enter your cash card details"|i18n("extension/xrowcomdirect")}</h4>

{if $errors|count|gt(0)}
<div class="warning">
<h2>{'Validation error'|i18n('extension/xrowcomdirect')}</h2>
<ul>
{foreach $errors as $error}
<li>{$error|wash()}</li>
{/foreach}
</ul>
</div>
{/if}

<input type="hidden" name="cardtype" value="5" />

<table summary="{"Please enter your cash card details"|i18n("extension/xrowcomdirect")}">
<tr>
<td>{"Card Holder's Name"|i18n("extension/xrowcomdirect")}:</td>
<td><input type="text" size="27" name="name" value="{$ecname|wash()}" /></td>
</tr>
<tr>
<td>{"Account Number"|i18n("extension/xrowcomdirect")}:</td>
<td><input id="number" type="text" maxlength="10" size="12" name="number" value="{$accountnumber|wash()}" /></td>
</tr>
<tr>
<td>{"Bank Code"|i18n("extension/xrowcomdirect")}: </td>
<td><input id="bankcode" type="text" maxlength="8" size="10" name="bankcode" value="{$bankcode|wash()}" /></td>
</tr>
</table>
</div>
<div id="buttonblock-bottom" class="buttonblock">
 <input id="cancel-button" class="button" type="submit" name="CancelButton" value="{'One step back'|i18n('extension/xrowcomdirect')}" />    
 <input id="continue-button" class="defaultbutton" type="submit" name="validate" value="{'Send Order'|i18n('extension/xrowcomdirect')}" />  
 <div class="break"></div>
</div>

</form> 

</div>
