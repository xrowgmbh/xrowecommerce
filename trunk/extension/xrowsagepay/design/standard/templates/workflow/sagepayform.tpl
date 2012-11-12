

{if $errors|count|ne( 0 )}
<h5 class='payment_error'>{'Errors'|i18n('extension/xrowsagepay')}:</h5>
<ul class='payment_error'>
    {foreach $errors as $errmsg}
        <li>{$errmsg}</li>
    {/foreach}
</ul>
{/if}

<p>{"You will now leave this website an continue your payment on Sage Pay."|i18n("extension/xrowsagepay")}</p>

{if $errors|count|eq( 0 )}
<p>{"You will be automaticly redirected to Sage Pay."|i18n("extension/xrowsagepay")}</p>
{literal}
  <script>
  $(document).ready(function () {
  $("#SagePayForm").submit();
});
  </script>
{/literal}
{/if}


<form name="SagePayForm" id="SagePayForm" method="POST" action="{ezini( 'Settings', 'ServiceURL', 'xrowsagepay.ini' )}"> 
<input type="hidden" value="" name="navigate">
<input type="hidden" value="2.23" name="VPSProtocol">
<input type="hidden" value="{ezini( 'Settings', 'TransactionType', 'xrowsagepay.ini' )}" name="TxType">
<input type="hidden" value="{ezini( 'Settings', 'VendorName', 'xrowsagepay.ini' )}" name="Vendor">
<input type="hidden" value="{$Crypt}" name="Crypt">
<button type="submit" title="{"Proceed to Form registration"|i18n("extension/xrowsagepay")}">{"Continue"|i18n("extension/xrowsagepay")}</button>
</form>