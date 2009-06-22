<link rel="stylesheet" type="text/css" href="{'javascript/yui/build/container/assets/skins/sam/container.css'|ezdesign(no)}" />
<script type="text/javascript" src="{'javascript/yui/build/yahoo-dom-event/yahoo-dom-event.js'|ezdesign(no)}"></script>
<script type="text/javascript" src="{'javascript/yui/build/container/container-min.js'|ezdesign(no)}"></script>

<div class="shop shop-payment shop-payment-gateway">

{include uri="design:shop/basket_navigator.tpl" step='4'}

<h1>{"Payment information"|i18n("extension/xrowcomdirect")}</h1>

<form name="form" action={"/shop/checkout"|ezurl} method="post">
<input type="submit" class="hide" style="display: hide;" name="validate" value="{'Continue'|i18n('extension/xrowcomdirect')}" />

<div class="form">
<h4>{"Please enter your credit card details"|i18n("extension/xrowcomdirect")}</h4>

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

<table summary="{"Please enter your credit card details"|i18n("extension/xrowcomdirect")}">
<tr>
<td>{"Card holder's name"|i18n("extension/xrowcomdirect")}:</td>
<td><input type="text" maxlength="32" size="32" name="name" value="{$name|wash()}" /></td>
</tr>
<tr>
<td>{"Card type"|i18n("extension/xrowcomdirect")}:</td>
<td>
<select name="cardtype">
{foreach ezini( 'EPaymentSettings', 'ActiveCreditcards', 'xrowecommerce.ini' ) as $key => $name}
  <option value="{$key}" {if $cardtype|eq($key)}selected{/if}>{$name|wash}</option>
{/foreach}
</select>
</tr>
<tr>
<td>{"Card number"|i18n("extension/xrowcomdirect")}:</td>
<td><input id="number" type="text" maxlength="19" size="32" name="number" value="{$number|wash()}" /></td>
</tr>
<tr>
<td>{"Security code"|i18n("extension/xrowcomdirect")}: </td>
<td>
    <input id="securitycode" type="text" maxlength="4" size="5" name="securitycode" value="{$securitycode|wash()}" />
    <button id="help-securitycode" class="help" type="button" title="{"Information"|i18n("extension/xrowcomdirect")}">{"i"|i18n("extension/xrowcomdirect", 'i for information')}</button>
    <script>
        YAHOO.namespace("example.container");

        function initHelp() {ldelim}
            YAHOO.example.container.helpsecuritycode = new YAHOO.widget.Overlay("security-code-info", {ldelim} context:["help-securitycode","tl","br"], visible:false, width:"400px" {rdelim} );
            YAHOO.example.container.helpsecuritycode.render();

            YAHOO.util.Event.addListener("help-securitycode", "mouseover", YAHOO.example.container.helpsecuritycode.show, YAHOO.example.container.helpsecuritycode, true);
            YAHOO.util.Event.addListener("help-securitycode", "mouseout", YAHOO.example.container.helpsecuritycode.hide, YAHOO.example.container.helpsecuritycode, true);

        {rdelim}

        YAHOO.util.Event.addListener(window, "load", initHelp);
</script>
</td>
</tr>
<tr>
<td>{"Expiry date"|i18n("extension/xrowcomdirect")}: </td>
<td>
  <select id="expirationmonth" name="expirationmonth">
    <option value="">{"Month"|i18n("extension/xrowcomdirect")}</option>
    {* Dynamic Loop *}
    {for 1 to 12 as $expirationmonth}
      {if $expirationmonth|lt(10)}{set $expirationmonth=concat('0',$expirationmonth)}{/if}
    <option value="{$expirationmonth|wash()}"{if eq($expirationmonth, $month)} selected="selected"{/if}>{$expirationmonth|wash()}</option>
    {/for}
  </select>
  <select id="expirationyear" name="expirationyear">
    <option value="">{"Year"|i18n("extension/xrowcomdirect")}</option>
    {* Dynamic Loop *}
    {def $curr_year=currentdate()|datetime( 'custom', '%Y' )|int }
    {for $curr_year to $curr_year|sum(10) as $expirationyear}
      {if $expirationyear|lt(10)}{set $expirationyear=concat('0',$expirationyear)}{/if}
    <option value="{$expirationyear|wash()}"{if eq($expirationyear, $year)} selected="selected"{/if}>{$expirationyear|wash()}</option>
    {/for}
  </select>
</td>
</tr>
</table>
</div>
<div id="security-code-info" style="visibility:hidden">
<p>{"What are the advantages of security code verification?"|i18n("extension/xrowcomdirect")}</p>

<p><img src={"three_digit_code_example.png"|ezimage} class="" alt="" title="" /></p>
<p>{"Please enter the last 3 digits of your credit card's security code, which is printed on the back of your card."|i18n("extension/xrowcomdirect")}</p>
        <h4>{"There are several advantages of security code verification"|i18n("extension/xrowcomdirect")}</h4>
        <ul>
            <li>{"Increased customer security"|i18n("extension/xrowcomdirect")}</li>
            <li>{"Faster order fullfilment"|i18n("extension/xrowcomdirect")}</li>
            <li>{"Deters fraud"|i18n("extension/xrowcomdirect")}</li>
        </ul>
</div>

<div id="buttonblock-bottom" class="buttonblock">
 <input id="cancel-button" class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('extension/xrowcomdirect')}" />    
    <input id="continue-button" class="defaultbutton" type="submit" name="validate" value="{'Continue'|i18n('extension/xrowcomdirect')}" />
   <div class="break"></div>
</div>

</form> 

</div>
