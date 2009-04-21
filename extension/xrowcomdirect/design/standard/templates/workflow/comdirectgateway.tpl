<div class="shop shop-basket">
{include uri="design:shop/basket_navigator.tpl" step='4'}

<h1>{"Payment Information"|i18n("extension/xrowcomdirect")}</h1>
<ul>
<li>{"Please enter your credit card information"|i18n("extension/xrowcomdirect")}</li>
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
<input type="submit" class="hide" style="display: hide;" name="validate" value="{'Continue'|i18n('extension/xrowecommerce')}" />

<table summary="{"Please enter your credit card information"|i18n("extension/xrowcomdirect")}">
<tr>
<td>{"Name on credit card"|i18n("extension/xrowcomdirect")}:</td>
<td><input type="text" size="32" name="name" value="{$name|wash()}" /></td>
</tr>
<tr>
<td>{"Credit card type"|i18n("extension/xrowcomdirect")}:</td>
<td>
<select name="cardtype">
  <option value="2">Visa</option>
  <option value="1" {if eq($cardtype, '1')}selected{/if}>MasterCard</option>
  <option value="4" {if eq($cardtype, '4')}selected{/if}>American Express</option>
  <option value="3" {if eq($cardtype, '3')}selected{/if}>Discover</option>
</select>
</tr>
<tr>
<td>{"Credit card number"|i18n("extension/xrowcomdirect")}:</td>
<td><input id="number" type="text" size="32" name="number" value="{$number|wash()}" /></td>
</tr>
<tr>
<td>{"Credit card security code"|i18n("extension/xrowcomdirect")}: </td>
<td><input id="securitycode" type="text" size="5" name="securitycode" value="{$securitycode|wash()}" /></td>
</tr>
<tr>
<td>{"Expiration date"|i18n("extension/xrowcomdirect")}: </td>
<td>
  <select id="expirationmonth" name="expirationmonth">
    <option value="">{"Month"|i18n("extension/xrowcomdirect")}</option>
    {* Dynamic Loop *}
    {for 1 to 12 as $month}
      {if $month|lt(10)}{set $month=concat('0',$month)}{/if}
    <option value="{$month|wash()}"{if eq($expirationmonth, $month)} selected="selected"{/if}>{$month|wash()}</option>
    {/for}
  </select>
  <select id="expirationyear" name="expirationyear">
    <option value="">{"Year"|i18n("extension/xrowcomdirect")}</option>
    {* Dynamic Loop *}
    {def $curr_year=currentdate()|datetime( 'custom', '%y' )|int }
    {for $curr_year to $curr_year|sum(10) as $year}
      {if $year|lt(10)}{set $year=concat('0',$year)}{/if}
    <option value="{$year|wash()}"{if eq($expirationyear, $year)} selected="selected"{/if}>{$year|wash()}</option>
    {/for}
  </select>
</td>
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


<p>{"What are the advantages of security code verification?"|i18n("extension/xrowcomdirect")}</p>

<p><img src={"three_digit_code_example.png"|ezimage} class="" alt="" title="" /></p>
<p>{"Please enter the last 3 digits of your credit card's security code, which is printed on the back of your card."|i18n("extension/xrowcomdirect")}</p>
        <h4>{"There are several advantages of security code verification"|i18n("extension/xrowcomdirect")}</h4>
        <ul>
            <li>{"Increased customer security"|i18n("extension/xrowcomdirect")}</li>
            <li>{"Faster order fullfilment"|i18n("extension/xrowcomdirect")}</li>
            <li>{"Deters fraud"|i18n("extension/xrowcomdirect")}</li>
        </ul>

</form> 

</div>
