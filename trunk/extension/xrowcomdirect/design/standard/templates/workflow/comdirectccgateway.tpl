<div class="shop shop-payment shop-payment-gateway">
	{include uri="design:shop/basket_navigator.tpl" step='4'}
	<h1>{"Payment Information"|i18n("extension/xrowcomdirect")}</h1>
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
				<td>{"Card Holder"|i18n("extension/xrowcomdirect")}:</td>
				<td><input type="text" maxlength="32" size="32" name="name" value="{$name|wash()}" /></td>
			</tr>
			<tr>
				<td>{"Credit Card Type"|i18n("extension/xrowcomdirect")}:</td>
				<td>
					<select name="cardtype">
						{foreach ezini( 'EPaymentSettings', 'ActiveCreditcards', 'xrowecommerce.ini' ) as $key => $name}
							<option value="{$key}" {if $cardtype|eq($key)}selected{/if}>{$name|wash}</option>
						{/foreach}
					</select>
			</tr>
			<tr>
				<td>{"Card Number"|i18n("extension/xrowcomdirect")}:</td>
				<td><input id="number" type="text" maxlength="19" size="32" name="number" value="{$number|wash()}" /></td>
			</tr>
			<tr>
				<td>{"Security Code"|i18n("extension/xrowcomdirect")}: </td>
				<td>
		   			<input id="securitycode" type="text" maxlength="4" size="5" name="securitycode" value="{$securitycode|wash()}" />
					<img id="help-securitycode" src={"tooltip.jpg"|ezimage()} alt="{'Information'|i18n('mobotix/memory_calculator')}" width="16" height="16" hspace="0" vspace="0" border="0"/>
				</td>
			</tr>
			<tr>
				<td>{"Expiry Date"|i18n("extension/xrowcomdirect")}: </td>
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
	<div id="security-code-info" class="jqtooltip" style="width:400px;">
		<p>{"The card security code consists of a series of 3 digits on the back of your credit card."|i18n("extension/xrowcomdirect")}</p>
		<p><img src={"three_digit_code_example.png"|ezimage} alt="{"The card with 3 digits security code."|i18n("extension/xrowcomdirect")}" title="{"The card with 3 digits security code."|i18n("extension/xrowcomdirect")}" /></p>
		<p>{"This code constitutes an important security feature which should prevent the use of faked or stolen credit cards. It is not stored on our servers, and its request is for increased safety of our customers only."|i18n("extension/xrowcomdirect")}</p>
	</div>
					{literal}
					<script type="text/javascript">
						$("#help-securitycode").tooltip({
							tip: '#security-code-info',
							position: 'center right',
							offset: [0, 15],
							delay: 0
						});
					</script>
					{/literal}
	<div id="buttonblock-bottom" class="buttonblock">
	 	<input id="cancel-button" class="button" type="submit" name="CancelButton" value="{'One step back'|i18n('extension/xrowcomdirect')}" />    
	    <input id="continue-button" class="defaultbutton" type="submit" name="validate" value="{'Send Order'|i18n('extension/xrowcomdirect')}" />
   		<div class="break"></div>
	</div>
	</form> 
</div>