{def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
     $locale = false()
     $symbol = false()}
{if is_set($currency)}
    {set $locale = $currency.locale
         $symbol = $currency.symbol}
{/if}

<div class="print-invoice">
{include uri="design:shop/invoicepart.tpl"}
<p style="clear:both;page-break-after:always">&nbsp;</p>
{include uri="design:shop/invoicepart.tpl" packingslip=true()}
<p style="clear:both;page-break-after:always">&nbsp;</p>
</div>