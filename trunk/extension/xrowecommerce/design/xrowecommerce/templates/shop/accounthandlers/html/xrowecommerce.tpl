{def $shiplist=fetch( 'shipping', 'list_all_methods' )}
{def $gateways=fetch( 'xrowecommerce', 'list_all_gateways' )}
{def $country=false()}
{* eZAuthorize + eZGPG - CC Storage Additions *}
{def $fetchStoredTransaction = ezini( 'eZAuthorizeSettings', 'StoreTransactionInformation', 'ezauthorize.ini' )}

<div class="shop-account">

    <table class="order_box">
    <caption>{"Customer"|i18n("extension/xrowecommerce")}</caption>
{if and( eq(ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.company_name)}
        <tr><th>{'Company'|i18n('extension/xrowecommerce')}: </th><td>{$order.account_information.company_name|wash}</td></tr>
{/if}
{if and( eq(ezini( 'Settings', 'CompanyAdditional', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.company_additional)}
        <tr><th>{'Company additional information'|i18n('extension/xrowecommerce')}: </th><td>{$order.account_information.company_additional|wash}</td></tr>
{/if}
                {if and( eq(ezini( 'Settings', 'TaxID', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.tax_id)}
                    <tr><th>{'Tax ID'|i18n('extension/xrowecommerce')}: </th><td>{$order.account_information.tax_id|wash}</td></tr>
                {/if}
        <tr><th>{'Name'|i18n('extension/xrowecommerce')}: </th><td>{$order.account_information.first_name|wash} {$order.account_information.last_name|wash}</td></tr>
        <tr><th>{'Email'|i18n('extension/xrowecommerce')}: </th><td>{$order.account_information.email|wash}</td></tr>
        </table>


    {if eq($order.account_information.shipping,1)}
            <table id="combined-address-table" border="0"  cellspacing="0" cellpadding="0" class="order_box">
            <caption>{"Delivery & Shipping Address"|i18n("extension/xrowecommerce")|wash}</caption>
                <tr><th>{'To'|i18n('extension/xrowecommerce')}:</th><td>{if $order.account_information.company_name|wash}{$order.account_information.company_name|wash} {$order.account_information.company_additional|wash},{/if} {$order.account_information.first_name|wash} {$order.account_information.last_name|wash}</td></tr>
                <tr><th>{'Address'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.address1|wash}</td></tr>
                {if gt(count($order.account_information.address2),0)}
                <tr><th>&nbsp;</th><td>{$order.account_information.address2|wash}</td></tr>
                {/if}
                <tr><th>{'City'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.city|wash}</td></tr>
                {if $order.account_information.state}
                <tr><th>{'State'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.state|wash}</td></tr>
                {/if}
                <tr><th>{'Zip code'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.zip|wash}</td></tr>
                <tr><th>{'Country'|i18n('extension/xrowecommerce')}:</th><td>{set $country=fetch( 'content', 'country_list', hash( 'filter', 'Alpha3', 'value', $order.account_information.country ) )}{$country.Name|wash}</td></tr>
                <tr><th>{'Phone'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.phone|wash}</td></tr>
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
                <tr><th>{'Name'|i18n('extension/xrowecommerce')}:</th><td>{if $order.account_information.company_name|wash}{$order.account_information.company_name|wash} {$order.account_information.company_additional|wash}{else}{$order.account_information.first_name|wash} {$order.account_information.last_name|wash}{/if}</td></tr>
                <tr><th>{'Address'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.address1|wash}</td></tr>
                {if gt(count($order.account_information.address2),0)}
                <tr><th>&nbsp;</th><td>{$order.account_information.address2|wash}</td></tr>
                {/if}
                <tr><th>{'City'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.city|wash}</td></tr>
                {if $order.account_information.state}
                <tr><th>{'State'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.state|wash}</td></tr>
                {/if}
                <tr><th>{'Zip code'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.zip|wash}</td></tr>
                <tr><th>{'Country'|i18n('extension/xrowecommerce')}:</th><td>{set $country=fetch( 'content', 'country_list', hash( 'filter', 'Alpha3', 'value', $order.account_information.country ) )}{$country.Name|wash}</td></tr>
                <tr><th>{'Phone'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.phone|wash}</td></tr>
                <tr><th>{'Shipping'|i18n('extension/xrowecommerce')}:</th>
                <td>
                {foreach $shiplist as $method}
                    {if $method.identifier|eq($order.account_information.shippingtype)}
                       {$method.name|wash}
                    {/if}
                {/foreach}
                </td></tr>
            </table>
            <table id="shipping-address-table" valign="top" class="order_box" border="0"  cellspacing="0" cellpadding="0">
                <caption>{"Shipping Address"|i18n("extension/xrowecommerce")}</caption>
                <tr><th>{'To'|i18n('extension/xrowecommerce')}:</th><td>{if $order.account_information.company_name|wash}{$order.account_information.company_name|wash} {$order.account_information.company_additional|wash},{/if} {$order.account_information.first_name|wash} {$order.account_information.s_mi|wash} {$order.account_information.last_name|wash}</td></tr>
                <tr><th>{'Address'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.s_address1|wash}</td></tr>
                {if gt(count($order.account_information.s_address2),0)}
                <tr><th>&nbsp;</th><td>{$order.account_information.s_address2|wash}</td></tr>
                {/if}
                <tr><th>{'City'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.s_city|wash}</td></tr>
                {if $order.account_information.s_state}
                <tr><th>{'State'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.s_state|wash}</td></tr>
                {/if}
                <tr><th>{'Zip code'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.s_zip|wash}</td></tr>
                <tr><th>{'Country'|i18n('extension/xrowecommerce')}:</th><td>{set $country=fetch( 'content', 'country_list', hash( 'filter', 'Alpha3', 'value', $order.account_information.s_country ) )}{$country.Name|wash}</td></tr>
                <tr><th>{'Phone'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.s_phone|wash}</td></tr>
                <tr><th>{'Email'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.s_email|wash}</td></tr>
            </table>
    {/if}
                <table id="additional-orderinformation-table" class="order_box" border="0"  cellspacing="0" cellpadding="0">
             <caption>{"Additional Orderinformation"|i18n("extension/xrowecommerce")}</caption>
                {if ezini( 'Settings', 'NoPartialDelivery', 'xrowecommerce.ini' )|eq( 'enabled' )}
                <tr><th>{'Partial delivery'|i18n('extension/xrowecommerce')}:</th>
                <td>
                {if $order.account_information.no_partial_delivery}
                    {'No'|i18n('extension/xrowecommerce')}
                {else}
                    {'Yes'|i18n('extension/xrowecommerce')}
                {/if}
                </td></tr>
                {/if}
                {if $order.account_information.paymentmethod}
                <tr><th>{'Payment method'|i18n('extension/xrowecommerce')}:</th><td>

{if $gateways|gt(0)}
    {foreach $gateways as $gateway}
        {if $order.account_information.paymentmethod|eq($gateway.value)}{$gateway.Name|wash}{/if}
    {/foreach}
{/if}
                </td></tr>
                {else}
                <tr><th>{'Payment method'|i18n('extension/xrowecommerce')}:</th><td>{'Unkown'|i18n('extension/xrowecommerce')}</td></tr>
                {/if}
                {if and(ezini( 'Settings', 'Reference', 'xrowecommerce.ini' )|eq( 'enabled' ), $order.account_information.reference)}
                <tr><th>{'Reference'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.reference|wash}</td></tr>
                {/if}
                {if and(ezini( 'Settings', 'Message', 'xrowecommerce.ini' )|eq( 'enabled' ), $order.account_information.message)}
                <tr><th>{'Your notes on order'|i18n('extension/xrowecommerce')}:</th><td>{$order.account_information.message|wash()|nl2br()}</td></tr>
                {/if}
            </table>
            
                    {if and( eq( $fetchStoredTransaction, true), ne( $order.account_information.ezauthorize_card_date, '') )}
        {def $key = ezini( 'eZGPGSettings', 'KeyID', 'ezgpg.ini' )}

        <table id="paymentinformation-table" class="order_box" cellspacing="0">
        <caption>{'Payment information'|i18n( 'extension/xrowecommerce' )}</caption>
            <tr>
                <th>{'Card Holder\'s Name'|i18n( 'extension/xrowecommerce' )}</th>
                <td>{ezgpg_decrypt($order.account_information.ezauthorize_card_name, $key)|wash}</td>
            </tr>
            <tr>
                <th>{'Last 4 Digits of Card Number'|i18n( 'extension/xrowecommerce' )}</th>
                <td>{ezgpg_decrypt_limit($order.account_information.ezauthorize_card_number, $key)|wash}</td>
            </tr>
            <tr>
                <th>{'Card Expiration Date'|i18n( 'extension/xrowecommerce' )}</th>
                <td>{$order.account_information.ezauthorize_card_date|wash}</td>
            </tr>
        </table>

        {/if}
</div>
