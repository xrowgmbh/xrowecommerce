{def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
     $locale = false()
     $symbol = false()}
{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{/if}


{include uri="design:shop/invoicepart.tpl"}
<p style="clear:both;page-break-after:always">&nbsp;</p>
{include uri="design:shop/invoicepart.tpl" packingslip=true()}