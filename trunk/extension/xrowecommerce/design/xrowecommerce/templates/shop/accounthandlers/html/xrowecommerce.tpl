{def $shiplist=fetch( 'shipping', 'list_all_methods' )}

{* eZAuthorize + eZGPG - CC Storage Additions *}
{def $fetchStoredTransaction = ezini( 'eZAuthorizeSettings', 'StoreTransactionInformation', 'ezauthorize.ini' )}

<table class="shop-account" width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td>
    <table class="order_box">
        <tr><th colspan="2">{"Customer"|i18n("extension/xrowecommerce")}</th></tr>
{if and( eq(ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.companyname)}
        <tr><td class="bold">{'Company'|i18n('extension/xrowecommerce')}: </td><td>{$order.account_information.companyname}</td></tr>
{/if}
{if and( eq(ezini( 'Settings', 'CompanyAdditional', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.companyadditional)}
        <tr><td class="bold">{'Company additional information'|i18n('extension/xrowecommerce')}: </td><td>{$order.account_information.companyadditional}</td></tr>
{/if}
                {if and( eq(ezini( 'Settings', 'TaxID', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.tax_id)}
                    <tr><td class="bold">{'Tax ID'|i18n('extension/xrowecommerce')}: </td><td>{$order.account_information.tax_id}</td></tr>
                {/if}
        <tr><td class="bold">{'Name'|i18n('extension/xrowecommerce')}: </td><td>{$order.account_information.first_name} {$order.account_information.last_name}</td></tr>
        <tr><td class="bold">{'Email'|i18n('extension/xrowecommerce')}: </td><td>{$order.account_information.email}</td></tr>
        </table>
        {if and( eq( $fetchStoredTransaction, true), ne( $order.account_information.ezauthorize_card_date, '') )}
        {def $key = ezini( 'eZGPGSettings', 'KeyID', 'ezgpg.ini' )}
        
        <fieldset>
        <legend>{'Payment information'|i18n( 'extension/xrowecommerce' )}</legend>
        <table class="list" cellspacing="0">
            <tr>
                <td>{'Card Holder\'s Name'|i18n( 'extension/xrowecommerce' )}</td>
                <td>{ezgpg_decrypt($order.account_information.ezauthorize_card_name, $key)}</td>
            </tr>
            <tr>
                <td>{'Last 4 Digits of Card Number'|i18n( 'extension/xrowecommerce' )}</td>
                <td>{ezgpg_decrypt_limit($order.account_information.ezauthorize_card_number, $key)}</td>
            </tr>
            <tr>
                <td>{'Card Expiration Date'|i18n( 'extension/xrowecommerce' )}</td>
                <td>{$order.account_information.ezauthorize_card_date}</td>
            </tr>
        </table>
    </td><td>
        </fieldset>
        {/if}
        </td>
        <td valign="top" class="order_left">
</td>
</tr>
<tr><td>
    {if eq($order.account_information.shipping,1)}
            <table border="0"  cellspacing="0" cellpadding="0" class="order_box">
                <tr><th colspan="2">{"Delivery & Shipping Address"|i18n("extension/xrowecommerce")}</th></tr>
                <tr><td class="bold">{'Address'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.address1}</td></tr>
                {if gt(count($order.account_information.address2),0)}
                <tr><td>&nbsp;</td><td>{$order.account_information.address2}</td></tr>
                {/if}
                <tr><td class="bold">{'City'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.city}</td></tr>
                <tr><td class="bold">{'State'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.state}</td></tr>
                <tr><td class="bold">{'Zip code'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.zip}</td></tr>
                <tr><td class="bold">{'Country'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.country}</td></tr>
                <tr><td class="bold">{'Phone'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.phone}</td></tr>
                <tr><td class="bold">{'Shipping'|i18n('extension/xrowecommerce')}:</td><td>

                {if $shiplist}
                {foreach  $shiplist as $method}
                {if $method.identifier|eq($order.account_information.shippingtype)}
                {$method.name}
                {/if}
                {/foreach}
                {/if}
                </td></tr>
            </table>
    {else}
            <table class="order_box" border="0"  cellspacing="0" cellpadding="0">
                <tr><th colspan="2">{"Billing Address"|i18n("extension/xrowecommerce")}</th></tr>
                <tr><td class="bold">{'Address'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.address1}</td></tr>
                {if gt(count($order.account_information.address2),0)}
                <tr><td>&nbsp;</td><td>{$order.account_information.address2}</td></tr>
                {/if}
                <tr><td class="bold">{'City'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.city}</td></tr>
                <tr><td class="bold">{'State'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.state}</td></tr>
                <tr><td class="bold">{'Zip code'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.zip}</td></tr>
                <tr><td class="bold">{'Country'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.country}</td></tr>
                <tr><td class="bold">{'Phone'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.phone}</td></tr>
                <tr><td class="bold">{'Shipping'|i18n('extension/xrowecommerce')}:</td>
                <td>
                {foreach  $shiplist as $method}
                    {if $method.identifier|eq($order.account_information.shippingtype)}
                       $method.name}
                    {/if}
                {/foreach}
                </td></tr>
            </table>
            <table valign="top" class="order_box" border="0"  cellspacing="0" cellpadding="0">
                <tr><th colspan="2">{"Shipping Address"|i18n("extension/xrowecommerce")}</th></tr>
{if and( eq(ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.companyname)}
                <tr><td class="bold">{'Company'|i18n( 'extension/xrowecommerce' )}:</td><td>{$order.account_information.scompanyname}</td></tr>
{/if}
                <tr><td class="bold">{'Name'|i18n( 'extension/xrowecommerce' )}:</td><td>{$order.account_information.s_first_name} {$order.account_information.s_last_name}</td></tr>
                <tr><td class="bold">{'MI'|i18n( 'extension/xrowecommerce' )}:</td><td>{$order.account_information.s_mi}</td></tr>
                <tr><td class="bold">{'Address'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.s_address1}</td></tr>
                {if gt(count($order.account_information.s_address2),0)}
                <tr><td>&nbsp;</td><td>{$order.account_information.s_address2}</td></tr>
                {/if}
                <tr><td class="bold">{'City'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.s_city}</td></tr>
                <tr><td class="bold">{'State'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.s_state}</td></tr>
                <tr><td class="bold">{'Zip code'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.s_zip}</td></tr>
                <tr><td class="bold">{'Country'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.s_country}</td></tr>
                <tr><td class="bold">{'Phone'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.s_phone}</td></tr>
                <tr><td class="bold">{'Email'|i18n('extension/xrowecommerce')}:</td><td>{$order.account_information.s_email}</td></tr>
            </table>
    {/if}
    
    </td>
    </tr>
</table>
