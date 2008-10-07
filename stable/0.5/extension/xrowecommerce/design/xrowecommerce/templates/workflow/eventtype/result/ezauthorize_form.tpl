{*
  Credit Card Form for eZ Authorize
  (could also be default form for other eZCurlGateway based class)

  You MUST have a post variable called 'validate'
  if you wish eZCurlGateway to catch the form once it's been posted.

  By default, it is the submit button.
*}
<div class="shop-basket">

<h2>Payment Information</h2>

<br />
    <div class="shopping_cart_path">
    <div>1. Cart</div>
    <div>2. Billing, Shipping and Coupons</div>
    <div>3. Confirmation</div>
    <div class="shopping_cart_path_select">4. Payment info</div>
    <div>5. Order completed</div>
    <div>6. Review reciept</div>
    </div>
    <div class="break"></div>
    <br />
    <br />

<p>Please enter your credit card information</p>

{if ne($errors, 0)}
<b>There were errors on the form: </b><br />
<ul>
{foreach $errors as $errmsg}
<li>{$errmsg}</li>
{/foreach}
</ul>
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
<td>Name on Card: </td>
<td><input type="text" size="32" name="CardName" value="{$cardname}" style="width:150px;" /></td>
</tr>
<tr>
<td>Card Type: </td>
<td>
<select name="CardType">
  <option value="visa">Visa</option>
  <option value="mastercard" {if eq($cardtype, 'mastercard')}selected{/if}>MasterCard</option>
  <option value="americanexpress" {if eq($cardtype, 'americanexpress')}selected{/if}>American Express</option>
  {*<option value="discover" {if eq($cardtype, 'discover')}selected{/if}>Discover</option>*}
  <option value="amex" {if eq($cardtype, 'amex')}selected{/if}>Amex</option>
</select>
</tr>
<tr>
<td>Card Number: </td>
<td><input type="text" size="32" name="CardNumber" value="{$cardnumber}" style="width:150px;" /></td>
</tr>
<tr>
<td>Security Number: </td>
<td><input type="text" size="5" name="SecurityNumber" value="{$securitynumber}" /></td>
</tr>
<tr>
<td>Expiration Date: </td>
<td>
  <select name="ExpirationMonth">
    <option value=""></option>
    {* Dynamic Loop *}
    <option value="01" {if eq($expirationmonth, '01')}selected{/if}>01</option>
    <option value="02" {if eq($expirationmonth, '02')}selected{/if}>02</option>
    <option value="03" {if eq($expirationmonth, '03')}selected{/if}>03</option>
    <option value="04" {if eq($expirationmonth, '04')}selected{/if}>04</option>
    <option value="05" {if eq($expirationmonth, '05')}selected{/if}>05</option>
    <option value="06" {if eq($expirationmonth, '06')}selected{/if}>06</option>
    <option value="07" {if eq($expirationmonth, '07')}selected{/if}>07</option>
    <option value="08" {if eq($expirationmonth, '08')}selected{/if}>08</option>
    <option value="09" {if eq($expirationmonth, '09')}selected{/if}>09</option>
    <option value="10" {if eq($expirationmonth, '10')}selected{/if}>10</option>
    <option value="11" {if eq($expirationmonth, '11')}selected{/if}>11</option>
    <option value="12" {if eq($expirationmonth, '12')}selected{/if}>12</option>
  </select>
  <select name="ExpirationYear">
    <option value=""></option>
    {* Dynamic Loop *}
    <option value="05" {if eq($expirationyear, '05')}selected{/if}>05</option>
    <option value="06" {if eq($expirationyear, '06')}selected{/if}>06</option>
    <option value="07" {if eq($expirationyear, '07')}selected{/if}>07</option>
    <option value="08" {if eq($expirationyear, '08')}selected{/if}>08</option>
    <option value="09" {if eq($expirationyear, '09')}selected{/if}>09</option>
    <option value="10" {if eq($expirationyear, '10')}selected{/if}>10</option>
    <option value="11" {if eq($expirationyear, '11')}selected{/if}>11</option>
    <option value="12" {if eq($expirationyear, '12')}selected{/if}>12</option>
    <option value="13" {if eq($expirationyear, '13')}selected{/if}>13</option>
    <option value="14" {if eq($expirationyear, '14')}selected{/if}>14</option>
    <option value="15" {if eq($expirationyear, '15')}selected{/if}>15</option>
    <option value="16" {if eq($expirationyear, '16')}selected{/if}>16</option>
  </select>
</td>
</tr>
<tr>
<td colspan="2">
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/standard/workflow')}" />
<input class="button" type="submit" name="validate" value="Submit" />
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
	color: #365638;
	font-weight: bold;
	font-size:0.9em;
	background-color:#317082;
	width:401px;
    margin-left:12px;
	margin-bottom:2px;
	margin-top:-62px;
    padding-top:2px;
	padding-left:2px;
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
    margin-left:12px;

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
<div class="dhtmlgoodies_question">Q: What are the advantages of security code verification?</div>

<div class="dhtmlgoodies_answer">
<div>
<img src={"three_digit_code_example.png"|ezimage} class="" alt="" title="" style="margin-top:0px;" /><br />
<div class="" style="margin:7px;width:250px;">Please enter the last 3 digits of your credit card's security code, which is printed on the back of your card.</div>
<div>
		<h4>There are several advantages of security code verification</h4>
		<ul>
			<li>Increased customer security</li>
			<li>Faster order fullfilment</li>
			<li>Deters fraud</li>
		</ul>
	</div>
</div>
</div>

<script type="text/javascript">
initShowHideDivs();
//showHideContent(false,1);	// Automatically expand first item
</script>

{* <a>Help?</a>

<div>
<img src={"three_digit_code_example.png"|ezimage} class="" alt="" title="" style="margin-top:0px;" /><br />
<div class="" style="width: 250px;">Please enter the last 3 digits of your credit card's security code, which is printed on the back of your card.</div>
</div>
*}
</td>
{/if}
</table>
</form>

</div>
