{def $currency = fetch( 'shop', 'currency', hash( 'code', $order.productcollection.currency_code ) )
     $locale = false()
     $symbol = false()}
{if $currency}
    {set locale = $currency.locale
         symbol = $currency.symbol}
{/if}
<div class="context-block">
    <form name="contentserver" method="post" action={'orderedit/edit'|ezurl}>
        <div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
            <h1 class="context-title">{'Orderitem'|i18n( 'design/admin/shop/orderlist')} {$ordernr|wash()}</h1>
            <div class="header-mainline"></div>
        </div></div></div></div></div></div>
        
        <div class="box-ml"><div class="box-mr"><div class="box-content">
            <table class="list" cellspacing="0">
                {foreach $orderitems as $orderitem}
                <tr class="bglight">
                    <th scope="row">{'Order Number'|i18n( 'extension/xrowecommerce' )}:</th>
                    <td>
                        <input type="hidden" name="orderitemid[]" value="{$orderitem.id}" />
                        <b>{$ordernr|wash()}</b>
                    </td>
                </tr>
                <tr class="bglight">
                    <th scope="row">{'Original description'|i18n( 'extension/xrowecommerce' )}:</th>
                    <td>
                        {$orderitem.description|wash()}
                    </td>
                </tr>
                
                <tr class="bglight">
                    <th scope="row">{'Description'|i18n( 'extension/xrowecommerce' )}:</th>
                    <td>
                        <input type="text" name="description_{$orderitem.id}" value="{$orderitem.description}" size="130" />
                    </td>
                </tr>
                <tr class="bglight">
                    <th scope="row">{'Original price'|i18n( 'extension/xrowecommerce' )}:</th>
                    <td>
                        {$orderitem.price|l10n( 'currency', $locale, $symbol )}
                    </td>
                </tr>
                
                <tr class="bglight">
                    <th scope="row">{'Price'|i18n( 'extension/xrowecommerce' )}:</th>
                    <td>
                        {$symbol} <input type="text" name="price_{$orderitem.id}" value="{$orderitem.price}" size="10" />
                    </td>
                </tr>
                
                {/foreach}
            </table>
    
    </div></div></div>
        <div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
            <div class="controlbar">
                <div class="block">
                    <div class="button-left">
                        <input class="button" type="submit" name="Store" value="{'Store shippingcosts'|i18n( 'design/admin/shop/orderlist' )}" title="Store shippingcosts"/>
                        <input class="button" type="submit" name="Cancel" value="{'Cancel'|i18n( 'design/admin/shop/orderlist' )}" title="Cancel this action" />
                    </div>
                    <div class="break"></div>
                </div>
            </div>
        </div></div></div></div></div></div>
    </form>
</div>
