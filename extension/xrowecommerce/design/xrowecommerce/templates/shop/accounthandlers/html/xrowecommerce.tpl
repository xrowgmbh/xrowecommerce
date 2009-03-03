{def $shiplist=fetch( 'shipping', 'list_all_methods' )}

{* eZAuthorize + eZGPG - CC Storage Additions *}
{def $fetchStoredTransaction = ezini( 'eZAuthorizeSettings', 'StoreTransactionInformation', 'ezauthorize.ini' )}

<div class="shop-account">

    <table class="order_box">
    <caption>{"Customer"|i18n("extension/xrowecommerce")}</caption>
{if and( eq(ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.companyname)}
        <tr><th>{'Company'|i18n('extension/xrowecommerce')}: </th><td>{$order.account_information.companyname}</td></tr>
{/if}
{if and( eq(ezini( 'Settings', 'CompanyAdditional', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.companyadditional)}
        <tr><th>{'Company additional information'|i18n('extension/xrowecommerce')}: </th><td>{$order.account_information.companyadditional}</td></tr>
{/if}
                {if and( eq(ezini( 'Settings', 'TaxID', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.tax_id)}
                    <tr><th>{'Tax ID'|i18n('extension/xrowecommerce')}: </th><td>{$order.account_information.tax_id}</td></tr>
                {/if}
        <tr><th>{'Name'|i18n('extension/xrowecommerce')}: </th><td>{$order.account_information.first_name} {$order.account_information.last_name}</td></tr>
        <tr><th>{'Email'|i18n('extension/xrowecommerce')}: </th><td>{$order.account_information.email}</td></tr>
        </table>


    {if eq($order.account_information.shipping,1)}
            <table id="combined-address-table" border="0"  cellspacing="0" cellpadding="0" class="order_box">
            <caption>{"Delivery & Shipping Address"|i18n("extension/xrowecommerce")|wash}</caption>
                <tr><th>{'Address'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.address1}</td></tr>
                {if gt(count($order.account_information.address2),0)}
                <tr><th>&nbsp;</th><td>{$order.account_information.address2}</td></tr>
                {/if}
                <tr><th>{'City'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.city}</td></tr>
                <tr><th>{'State'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.state}</td></tr>
                <tr><th>{'Zip code'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.zip}</td></tr>
                <tr><th>{'Country'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.country}</td></tr>
                <tr><th>{'Phone'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.phone}</td></tr>
                <tr><th>{'Shipping'|i18n('extension/xrowecommerce')}:</th><td>

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
            <table id="billing-address-table" class="order_box" border="0"  cellspacing="0" cellpadding="0">
             <caption>{"Billing Address"|i18n("extension/xrowecommerce")}</caption>
                <tr><th>{'Address'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.address1}</td></tr>
                {if gt(count($order.account_information.address2),0)}
                <tr><th>&nbsp;</th><td>{$order.account_information.address2}</td></tr>
                {/if}
                <tr><th>{'City'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.city}</td></tr>
                <tr><th>{'State'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.state}</td></tr>
                <tr><th>{'Zip code'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.zip}</td></tr>
                <tr><th>{'Country'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.country}</td></tr>
                <tr><th>{'Phone'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.phone}</td></tr>
                <tr><th>{'Shipping'|i18n('extension/xrowecommerce')}:</th>
                <td>
                {foreach  $shiplist as $method}
                    {if $method.identifier|eq($order.account_information.shippingtype)}
                       $method.name}
                    {/if}
                {/foreach}
                </td></tr>
            </table>
            <table id="shipping-address-table" valign="top" class="order_box" border="0"  cellspacing="0" cellpadding="0">
                <caption>{"Shipping Address"|i18n("extension/xrowecommerce")}</caption>
{if and( ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' )|eq( 'enabled' ), $order.account_information.company_name)}
                <tr><th>{'Company'|i18n( 'extension/xrowecommerce' )}:</th><td>{$order.account_information.s_company_name}</td></tr>
{/if}
                <tr><th>{'Name'|i18n( 'extension/xrowecommerce' )}:</th><td>{$order.account_information.s_first_name} {$order.account_information.s_last_name}</td></tr>
                <tr><th>{'MI'|i18n( 'extension/xrowecommerce' )}:</th><td>{$order.account_information.s_mi}</td></tr>
                <tr><th>{'Address'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.s_address1}</td></tr>
                {if gt(count($order.account_information.s_address2),0)}
                <tr><th>&nbsp;</th><td>{$order.account_information.s_address2}</td></tr>
                {/if}
                <tr><th>{'City'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.s_city}</td></tr>
                <tr><th>{'State'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.s_state}</td></tr>
                <tr><th>{'Zip code'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.s_zip}</td></tr>
                <tr><th>{'Country'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.s_country}</td></tr>
                <tr><th>{'Phone'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.s_phone}</td></tr>
                <tr><th>{'Email'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.s_email}</td></tr>
            </table>
    {/if}
                <table id="additional-orderinformation-table" class="order_box" border="0"  cellspacing="0" cellpadding="0">
             <caption>{"Additional Orderinformation"|i18n("extension/xrowecommerce")}</caption>
                {if ezini( 'Settings', 'NoPartialDelivery', 'xrowecommerce.ini' )|eq( 'enabled' )}
                <tr><th>{'Partial delivery'|i18n('extension/xrowecommerce')}:</th>
                <td>
                {if $order.account_information.no_partial_delivery}
                    {'no'|i18n('extension/xrowecommerce')}
                {else}
                    {'yes'|i18n('extension/xrowecommerce')}
                {/if}
                </td></tr>
                {/if}
                {if ezini( 'Settings', 'Reference', 'xrowecommerce.ini' )|eq( 'enabled' )}
                <tr><th>{'Reference'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.reference}</td></tr>
                {/if}
                {if ezini( 'Settings', 'Message', 'xrowecommerce.ini' )|eq( 'enabled' )}
                <tr><th>{'Message'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.message}</td></tr>
                {/if}
            </table>
            
                    {if and( eq( $fetchStoredTransaction, true), ne( $order.account_information.ezauthorize_card_date, '') )}
        {def $key = ezini( 'eZGPGSettings', 'KeyID', 'ezgpg.ini' )}

        <table id="paymentinformation-table" class="order_box" cellspacing="0">
        <caption>{'Payment information'|i18n( 'extension/xrowecommerce' )}</caption>
            <tr>
                <th>{'Card Holder\'s Name'|i18n( 'extension/xrowecommerce' )}</th>
                <td>{ezgpg_decrypt($order.account_information.ezauthorize_card_name, $key)}</td>
            </tr>
            <tr>
                <th>{'Last 4 Digits of Card Number'|i18n( 'extension/xrowecommerce' )}</th>
                <td>{ezgpg_decrypt_limit($order.account_information.ezauthorize_card_number, $key)}</td>
            </tr>
            <tr>
                <th>{'Card Expiration Date'|i18n( 'extension/xrowecommerce' )}</th>
                <td>{$order.account_information.ezauthorize_card_date}</td>
            </tr>
        </table>

        {/if}
</div>
