<div class="shop shop-basket">
{include uri="design:shop/basket_navigator.tpl" step='4'}

<h1>{"Payment information"|i18n("extension/xrowcomdirect")}</h1>
<ul>
<li>{"Please enter your debit card information"|i18n("extension/xrowcomdirect")}</li>
</ul>

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

<form name="form" action={"/shop/checkout"|ezurl} method="post">
<input type="submit" class="hide" style="display: hide;" name="validate" value="{'Continue'|i18n('extension/xrowcomdirect')}" />
<input type="hidden" name="cardtype" value="5" />

<table summary="{"Please enter your credit card information"|i18n("extension/xrowcomdirect")}">
<tr>
<td>{"Name of account"|i18n("extension/xrowcomdirect")}:</td>
<td><input type="text" size="27" name="name" value="{$ecname|wash()}" /></td>
</tr>
<tr>
<td>{"Account number"|i18n("extension/xrowcomdirect")}:</td>
<td><input id="number" type="text" maxlength="10" size="12" name="number" value="{$accountnumber|wash()}" /></td>
</tr>
<tr>
<td>{"Bank code"|i18n("extension/xrowcomdirect")}: </td>
<td><input id="bankcode" type="text" maxlength="8" size="10" name="bankcode" value="{$bankcode|wash()}" /></td>
</tr>
<tr>
<td colspan="2">
<div id="buttonblock-bottom" class="buttonblock">
 <input id="cancel-button" class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('extension/xrowcomdirect')}" />    
    <input id="continue-button" class="defaultbutton" type="submit" name="validate" value="{'Continue'|i18n('extension/xrowcomdirect')}" />
   
</div>
</td>
</tr>
</table>

</form> 

</div>
