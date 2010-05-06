{*
  Credit Card Form for eZ Authorize
  (could also be default form for other eZCurlGateway based class)

  You MUST have a post variable called 'validate'
  if you wish eZCurlGateway to catch the form once it's been posted.

  By default, it is the submit button.
*}
<div class="shop-basket">

<h2>{'Payment Information'|i18n('extension/xrowecommerce')}</h2>

{include uri="design:shop/basket_navigator.tpl" step='4'}

<p>{'Please enter your credit card information'|i18n('extension/xrowecommerce')}</p>

{if $errors|count|ne( 0 )}
<div class='payment_error'>
    <h5>{'Errors'|i18n('extension/xrowecommerce')}: </h5>
    {foreach $errors as $errmsg}
        <div>{$errmsg}</div>
    {/foreach}
</div>
{/if}

<form name="ezauthorizeForm" action="checkout" method="post">

<input type=hidden name="orderid" value="{$order_id}">
<input type=hidden name="amount" value="{$amount}">
<input type=hidden name="currency" value="US">
<input type="hidden" name="lang" value="en">

<table border="00" cellpadding="0" cellspacing="5">
<tr valign="top">
<td>
<table border="00" cellpadding="4" cellspacing="4" width="100%">
<tr>
<td>{'Name on Card'|i18n('extension/xrowecommerce')}: </td>
<td><input type="text" size="32" name="CardName" value="{$cardname}" style="width:150px;" /></td>
</tr>
<tr>
<td>{'Card Type'|i18n('extension/xrowecommerce')}: </td>
<td>
<select name="CardType">
    <option value=""></option>
    <option value="visa">Visa</option>
    <option value="mastercard" {if eq($cardtype, 'mastercard')}selected{/if}>MasterCard</option>
    <option value="americanexpress" {if eq($cardtype, 'americanexpress')}selected{/if}>American Express</option>
    {*<option value="discover" {if eq($cardtype, 'discover')}selected{/if}>Discover</option>*}
    <option value="amex" {if eq($cardtype, 'amex')}selected{/if}>Amex</option>
</select>
</tr>
<tr>
<td>{'Card Number'|i18n('extension/xrowecommerce')}: </td>
<td><input type="text" size="32" name="CardNumber" value="{$cardnumber}" style="width:150px;" /></td>
</tr>
<tr>
<td>{'Security Number'|i18n('extension/xrowecommerce')}: </td>
<td><input type="text" size="5" name="SecurityNumber" value="{$securitynumber}" /></td>
</tr>
<tr>
<td>{'Expiration Date'|i18n('extension/xrowecommerce')}: </td>
<td>
  <select name="ExpirationMonth">
    <option value=""></option>
    {* Dynamic Loop *}
    {for 1 to 12 as $month}
    	{if $month|count_chars|lt( 2 )}
    	    {set $month=concat( 0, $month )}
    	{/if}
        <option value="{$month}"{if $month|eq($expirationmonth)}selected="selected"{/if}>{$month}</option>
    {/for}
  </select>
  <select name="ExpirationYear">
    <option value=""></option>
    {* Dynamic Loop *}
    {def $currentyear=currentdate()|datetime( custom,'%Y' )}
    {for $currentyear to sum( $currentyear, 10 ) as $year}
        <option value="{$year}"{if $year|eq($expirationyear)}selected="selected"{/if}>{$year}</option>
    {/for}
  </select>
</td>
</tr>
<tr>
<td colspan="2">
<div class="buttonblock">
<input class="button" type="submit" name="CancelButton" value="{'One step back'|i18n('extension/xrowecommerce')}" />
<input class="button" type="submit" name="validate" value="{'Continue'|i18n('extension/xrowecommerce')}" />
</div>
</td>
</tr>
</table>
</td>

{def $s_display_help = ezini( 'eZAuthorizeSettings', 'DisplayHelp', 'ezauthorize.ini' )}
{if eq( $s_display_help, true )}
<td>&nbsp;</td>
<td>

<style type="text/css">
{literal}
body{
	margin:0px;

}
.dhtmlgoodies_question{	/* Styling question */
	/* Start layout CSS */
	color: #ffffff;
	font-weight: bold;
	font-size:0.9em;
	background-color:#317082;
	width:365px;
	margin-top:80px;
    padding:3px 5px 0px 8px;
	background-image:url('images/bg_answer.gif');
	background-repeat:no-repeat;
	background-position:top right;
	height:20px;
	/* End layout CSS */
	overflow:hidden;
	cursor:pointer;
}
.dhtmlgoodies_answer{	/* Parent box of slide down content */
	/* Start layout CSS */
	border:1px solid #317082;
	background-color:#E2EBED;
	width:400px;
    /*margin-left:12px;*/

	/* End layout CSS */

	visibility:hidden;
	height:0px;
	overflow:hidden;
	position:relative;

}
.dhtmlgoodies_answer_content{	/* Content that is slided down */
	padding:1px;
	font-size:0.9em;
	position:relative;
}
{/literal}
</style>
<script type="text/javascript">
{literal}
/************************************************************************************************************
(C) www.dhtmlgoodies.com, November 2005

This is a script from www.dhtmlgoodies.com. You will find this and a lot of other scripts at our website.

Terms of use:
You are free to use this script as long as the copyright message is kept intact. However, you may not
redistribute, sell or repost it without our permission.

Thank you!

www.dhtmlgoodies.com
Alf Magne Kalleland

************************************************************************************************************/

var dhtmlgoodies_slideSpeed = 10;	// Higher value = faster
var dhtmlgoodies_timer = 10;	// Lower value = faster

var objectIdToSlideDown = false;
var dhtmlgoodies_activeId = false;
var dhtmlgoodies_slideInProgress = false;
function showHideContent(e,inputId)
{
	if(dhtmlgoodies_slideInProgress)return;
	dhtmlgoodies_slideInProgress = true;
	if(!inputId)inputId = this.id;
	inputId = inputId + '';
	var numericId = inputId.replace(/[^0-9]/g,'');
	var answerDiv = document.getElementById('dhtmlgoodies_a' + numericId);

	objectIdToSlideDown = false;

	if(!answerDiv.style.display || answerDiv.style.display=='none'){
		if(dhtmlgoodies_activeId &&  dhtmlgoodies_activeId!=numericId){
			objectIdToSlideDown = numericId;
			slideContent(dhtmlgoodies_activeId,(dhtmlgoodies_slideSpeed*-1));
		}else{

			answerDiv.style.display='block';
			answerDiv.style.visibility = 'visible';

			slideContent(numericId,dhtmlgoodies_slideSpeed);
		}
	}else{
		slideContent(numericId,(dhtmlgoodies_slideSpeed*-1));
		dhtmlgoodies_activeId = false;
	}
}

function slideContent(inputId,direction)
{

	var obj =document.getElementById('dhtmlgoodies_a' + inputId);
	var contentObj = document.getElementById('dhtmlgoodies_ac' + inputId);
	height = obj.clientHeight;
	if(height==0)height = obj.offsetHeight;
	height = height + direction;
	rerunFunction = true;
	if(height>contentObj.offsetHeight){
		height = contentObj.offsetHeight;
		rerunFunction = false;
	}
	if(height<=1){
		height = 1;
		rerunFunction = false;
	}

	obj.style.height = height + 'px';
	var topPos = height - contentObj.offsetHeight;
	if(topPos>0)topPos=0;
	contentObj.style.top = topPos + 'px';
	if(rerunFunction){
		setTimeout('slideContent(' + inputId + ',' + direction + ')',dhtmlgoodies_timer);
	}else{
		if(height<=1){
			obj.style.display='none';
			if(objectIdToSlideDown && objectIdToSlideDown!=inputId){
				document.getElementById('dhtmlgoodies_a' + objectIdToSlideDown).style.display='block';
				document.getElementById('dhtmlgoodies_a' + objectIdToSlideDown).style.visibility='visible';
				slideContent(objectIdToSlideDown,dhtmlgoodies_slideSpeed);
			}else{
				dhtmlgoodies_slideInProgress = false;
			}
		}else{
			dhtmlgoodies_activeId = inputId;
			dhtmlgoodies_slideInProgress = false;
		}
	}
}



function initShowHideDivs()
{
	var divs = document.getElementsByTagName('DIV');
	var divCounter = 1;
	for(var no=0;no<divs.length;no++){
		if(divs[no].className=='dhtmlgoodies_question'){
			divs[no].onclick = showHideContent;
			divs[no].id = 'dhtmlgoodies_q'+divCounter;
			var answer = divs[no].nextSibling;
			while(answer && answer.tagName!='DIV'){
				answer = answer.nextSibling;
			}
			answer.id = 'dhtmlgoodies_a'+divCounter;
			contentDiv = answer.getElementsByTagName('DIV')[0];
			contentDiv.style.top = 0 - contentDiv.offsetHeight + 'px';
			contentDiv.className='dhtmlgoodies_answer_content';
			contentDiv.id = 'dhtmlgoodies_ac' + divCounter;
			answer.style.display='none';
			answer.style.height='1px';
			divCounter++;
		}
	}
}
{/literal}
</script>
<div class="question_answer_div">
<div class="dhtmlgoodies_question">{"Q: What are the advantages of security code verification?"|i18n("extension/xrowecommerce")}</div>

<div class="dhtmlgoodies_answer">
<div>
<img src={"three_digit_code_example.png"|ezimage} class="" alt="" title="" style="margin-top:0px;" /><br />
<div class="" style="margin:7px;width:250px;">{"Please enter the last 3 digits of your credit card's security code, which is printed on the back of your card."|i18n("extension/xrowecommerce")}</div>
<div>
		<h4>{"There are several advantages of security code verification"|i18n("extension/xrowecommerce")}</h4>
		<ul>
			<li>{"Increased customer security"|i18n("extension/xrowecommerce")}</li>
			<li>{"Faster order fullfilment"|i18n("extension/xrowecommerce")}</li>
			<li>{"Deters fraud"|i18n("extension/xrowecommerce")}</li>
		</ul>
	</div>
</div>
</div>

<script type="text/javascript">
initShowHideDivs();
//showHideContent(false,1);	// Automatically expand first item
</script>

{* <a>{'Help?'|i18n('extension/xrowecommerce')}</a>

<div>
<img src={"three_digit_code_example.png"|ezimage} class="" alt="" title="" style="margin-top:0px;" /><br />
<div class="" style="width: 250px;">{"Please enter the last 3 digits of your credit card's security code, which is printed on the back of your card."|i18n("extension/xrowecommerce")}</div>
</div>
*}
</td>
{/if}
</tr>
</table>
</form>

</div>