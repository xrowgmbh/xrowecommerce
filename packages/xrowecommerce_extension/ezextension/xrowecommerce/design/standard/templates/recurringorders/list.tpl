{*
 This template works with GMT date for any date you have to append |sub(currentdate()|datetime( 'custom', '%Z' )) to get the 
correct locale date
*}

<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">
<div class="recurringorders-list">
<h1>Automatic Delivery</h1>

<div class="yui-skin-sam">
{foreach $messages as $message}
<div class="message-{$message.type}">
    <h2>{$message.text}</h2>
</div>
{/foreach}


{def $currency = fetch( 'shop', 'currency' )
         $locale = false()
         $symbol = false()}
{if $currency}
        {set locale = $currency.locale
             symbol = $currency.symbol}
{/if}
{def $user=fetch( 'user', 'current_user' )}

<div id="main-wide">
{content_view_gui content_object=$user.contentobject view="address"}
<form name="recurringorders" method="post" action={concat( "content/edit/", $user.contentobject.id )|ezurl}>
<div class="block">
<input class="button" type="submit" name="Cancel" value="Edit profile" />
<div class="break"></div>
</div>
</form>
<form name="recurringorders" method="post" action={concat( "recurringorders/list/", $collection.id )|ezurl}>

<h3>Settings</h3>
<label for="Pause"><input id="Pause" name="Pause" type="checkbox" value="1" {if $collection.status|ne('1')}checked{/if} /> Pause Automatic Delivery</label>
<p>If you are away and you wish to not receive your orders please check this box. We will also pause your Automatic Delivery, if we notice problems with your order request.</p>
<p>
<i>Important Note</i>
</p>
<p>Items with the same "Next order" date will be combined on the same order.</p>

<table class="list">
	<tr>
	    <th></th>
	    <th>Product name</th>
	    <th>Variation</th>
	    <th>Date added</th>
	    <th>Amount</th>
	    <th>Price per item</th>
	    <th>Price</th>
	</tr>
	{foreach $collection.list as $item}
		<tr>
		    <td><input size="6" name="RemoveArray[]" type="checkbox" value="{$item.item_id}"/></td>
		    <td>{$item.object.name|wash(xhtml)}</td>
		    <td>
		        {foreach $item.options as $option}
		        {if $option.name}{$option.name|wash(xhtml)}{/if}
		        {if $option.comment}{$option.comment|wash(xhtml)}{/if}
		        <br />
		        {/foreach}
		    </td>
		    <td>{$item.created|sub(currentdate()|datetime( 'custom', '%Z' ))|l10n( 'shortdate' )}</td>
		    <td><input size="3" name="ItemArray[{$item.item_id}][amount]" type="text" value="{$item.amount}"/></td>
		    <td>{$item.price_per_item|l10n( 'currency', $locale, $symbol )}</td>
		    <td>{$item.price|l10n( 'currency', $locale, $symbol )}</td>
		</tr>
		<tr>
		    <th>Frequency</th>
		    <td>
			    <input size="6" name="ItemArray[{$item.item_id}][cycle]" type="text" value="{$item.cycle}"/>
			    {def $list=fetch('recurringorders','fetch_text')}
		            <select name="ItemArray[{$item.item_id}][cycle_unit]"> {* disabled for subscriptions *}
					    {foreach $list as $key => $text}
						    {if ezini( 'RecurringOrderSettings','DisabledCycles','recurringorders.ini')|contains($key)|not}
			                    <option value="{$key}" {if $item.cycle_unit|eq($key)} selected="selected"{/if}>{$text}</option>
						    {/if}
					    {/foreach}
				    </select>
			    {undef $list}
		    </td>
		</tr>
		<tr>
		    <th>Next order</th>
		    <td>
				<script type="text/javascript">
				YUI().use("node", function(Y) {ldelim}
					Y.on("domready", function() {ldelim}
						var cal{$item.item_id} = new YAHOO.widget.Calendar("cal{$item.item_id}","cal{$item.item_id}-container", 
						{ldelim}
						  pagedate:"{$item.next_date|sub(currentdate()|datetime( 'custom', '%Z' ))|datetime( 'custom', '%m/%Y' )}", 
						  selected:"{$item.next_date|sub(currentdate()|datetime( 'custom', '%Z' ))|l10n( 'shortdate' )}", 
						  mindate:"{fetch('recurringorders','now')|sum(86400)|l10n( 'shortdate' )}",
						  maxdate:"{fetch('recurringorders','now')|sub(currentdate()|datetime( 'custom', '%Z' ))|sum(86400)|sum(7776000)|l10n( 'shortdate' )}" {rdelim} );
						cal{$item.item_id}.selectEvent.subscribe(handleSelect, cal{$item.item_id}, true);
						cal{$item.item_id}.render();
				
					{rdelim} );
					
					Y.on("click", function(e) {ldelim}
					    ShowHide( '#' + 'cal{$item.item_id}-container' );
			            {rdelim}, "#cal{$item.item_id}");
					{rdelim} );
				</script>
				<input size="6" type="text" name="ItemArray[{$item.item_id}][next_date]" id="cal{$item.item_id}-date" readonly="readonly" value="{$item.next_date|sub(currentdate()|datetime( 'custom', '%Z' ))|l10n( 'shortdate' )}"/>
				<input class="button" type="button" id="cal{$item.item_id}" name="cal{$item.item_id}" value="Change Date" />
				<div id="cal{$item.item_id}-container" class="container hide"></div>
			    <p>This date will determine on which day the order is being processed. The shipped goods will arrive at your shipping location a short time thereafter.</p>
		    </td>
		    <td></td>
		</tr>
		{if $item.last_success}
			<tr>
			    <th>Last order</th>
			    <td>{$item.last_success|sub(currentdate()|datetime( 'custom', '%Z' ))|l10n( 'shortdate' )}</td>
			    <td></td>
			</tr>
		{/if}
	{/foreach}
</table>
<p>All prices exclude tax, shipping and handling. Those will be added to your order once a new order is created.</p>
<input class="button" type="submit" name="Remove" value="Remove" />
<input class="button" type="submit" name="Update" value="Update" />
<input class="button" type="submit" name="Cancel" value="Cancel" />
</form>
<!--
Last : {$collection.last_run|l10n( 'shortdate' )}<br/>
Now : {$collection.now|l10n( 'shortdate' )}<br/>
-->
</div>

</div> {* YUI SAM *}
</div>
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>
