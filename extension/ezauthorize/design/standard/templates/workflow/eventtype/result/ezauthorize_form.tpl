{* 
  Credit Card Form for eZ Authorize
  (could also be default form for other eZCurlGateway based class)
 
  You MUST have a post variable called 'validate' 
  if you wish eZCurlGateway to catch the form once it's been posted. 

  By default, it is the submit button.
*}
<div class="maincontentbody">
        <div class="content-view-full">
            <div class="class-article">

<h2>Payment Information</h2>
<ul>
<li>Please enter your credit card information</li>
</ul>

{if ne($errors, 0)}
<b>There were errors on the form: </b><br />
<ul>
{foreach $errors as $errmsg}
<li>{$errmsg}</li>
{/foreach}
</ul>
{/if}

<form name="ezauthorizeForm" action={"/shop/checkout"|ezurl} method="post">

<input type=hidden name="orderid" value="{$order_id}" />
<input type=hidden name="amount" value="{$amount}" />
<input type=hidden name="currency" value="US" />
<input type="hidden" name="lang" value="en" />

<table border="0" cellpadding="4" cellspacing="4" summary="Enter Credit Card details">
<tr>
<td>Name on Card: </td>
<td><input type="text" size="32" name="CardName" value="{$cardname}" /></td>
</tr>
<tr>
<td>Card Type: </td>
<td>
<select name="CardType">
  <option value="visa">Visa</option>
  <option value="mastercard" {if eq($cardtype, 'mastercard')}selected{/if}>MasterCard</option>
  <option value="amercianexpress" {if eq($cardtype, 'americanexpress')}selected{/if}>American Express</option>
  <option value="discover" {if eq($cardtype, 'discover')}selected{/if}>Discover</option>
  <option value="amex" {if eq($cardtype, 'amex')}selected{/if}>Amex</option>
</select>
</tr>
<tr>
<td>Card Number: </td>
<td><input type="text" size="32" name="CardNumber" value="{$cardnumber}" /></td>
</tr>
<tr>
<td>Security Number: </td>
<td><input type="text" size="5" name="SecurityNumber" value="{$securitynumber}" /></td>
</tr>
<tr>
<td>Expiration Date: </td>
<td>
  <select name="ExpirationMonth">
    <option value="">Month</option>
    {* Dynamic Loop *}
    {for 1 to 12 as $month}
      {if $month|lt(10)}{set $month=concat('0',$month)}{/if}
    <option value="{$month}"{if eq($expirationmonth, $month)} selected="selected"{/if}>{$month}</option>
    {/for}
  </select>
  <select name="ExpirationYear">
    <option value="">Year</option>
    {* Dynamic Loop *}
    {def $curr_year=currentdate()|datetime( 'custom', '%y' )|int }
    {for $curr_year to $curr_year|sum(10) as $year}
      {if $year|lt(10)}{set $year=concat('0',$year)}{/if}
    <option value="{$year}"{if eq($expirationyear, $year)} selected="selected"{/if}>{$year}</option>
    {/for}
  </select>
</td>
</tr>
<tr>
<td colspan="2"><input class="defaultbutton" type="submit" name="validate" value="Submit" /><input class="defaultbutton" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/standard/workflow')}" /></td>
</tr>
</table>

{def $s_display_help = ezini( 'eZAuthorizeSettings', 'DisplayHelp', 'ezauthorize.ini' )}
{if eq( $s_display_help, true )}
<img src="http://usa.visa.com/img/cards/cardart_3Digit_code.gif" alt="" style="margin-top:10px;" />
{/if}

</form> 

</div>
</div>
</div>
