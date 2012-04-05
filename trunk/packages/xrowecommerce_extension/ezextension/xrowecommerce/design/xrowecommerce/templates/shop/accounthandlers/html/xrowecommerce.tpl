{def $shiplist=fetch( 'shipping', 'list_all_methods' )
     $gateways=fetch( 'xrowecommerce', 'list_all_gateways' )
     $country=false()
     $fetchStoredTransaction = ezini( 'eZAuthorizeSettings', 'StoreTransactionInformation', 'ezauthorize.ini' )
}

{* eZAuthorize + eZGPG - CC Storage Additions *}

<div class="shop-account">
    <table class="order_box">
        <caption>{"Customer"|i18n("extension/xrowecommerce")}</caption>
        {if and( eq(ezini( 'Fields', 'company_name', 'xrowecommerce.ini' ).enabled, 'true' ), $order.account_information.company_name)}
            <tr>
                <th>{'Company'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.company_name|wash}</td>
            </tr>
        {/if}
        {if and( eq(ezini( 'Fields', 'company_additional', 'xrowecommerce.ini' ).enabled, 'true' ), $order.account_information.company_additional)}
            <tr>
                <th>{'Company additional information'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.company_additional|wash}</td>
            </tr>
        {/if}
        {if and( eq(ezini( 'Fields', 'tax_id', 'xrowecommerce.ini' ).enabled, 'true' ), $order.account_information.tax_id)}
            <tr>
                <th>{'Tax ID'|i18n('extension/xrowecommerce')}:</th>
                <td>
                    {$order.account_information.tax_id|wash} {if $order.account_information.tax_id_valid|eq('0')} ({'unconfirmed'|i18n('extension/xrowecommerce')}){/if}
                </td>
            </tr>
        {/if}
        <tr>
            <th>{'Name'|i18n('extension/xrowecommerce')}:</th>
            <td>
                {if and(is_set($order.account_information.title),$order.account_information.title|eq('0'))}
                    {'Mr.'|i18n('extension/xrowecommerce')}
                {elseif and(is_set($order.account_information.title),$order.account_information.title|eq('1'))}
                    {'Mrs.'|i18n('extension/xrowecommerce')}
                {/if}
                {$order.account_information.first_name|wash}
                {$order.account_information.last_name|wash}
            </td>
        </tr>
        <tr>
            <th>{'Email'|i18n('extension/xrowecommerce')}:</th>
            <td>{$order.account_information.email|wash}</td>
        </tr>
    </table>

    {if eq($order.account_information.shipping,1)}
        <table id="combined-address-table" border="0" cellspacing="0"
            cellpadding="0" class="order_box">
            <caption>{"Billing & shipping address"|i18n("extension/xrowecommerce")|wash}</caption>
            <tr>
                <th>{'To'|i18n('extension/xrowecommerce')}:</th>
                <td>
                    {if $order.account_information.company_name|wash}
                        {$order.account_information.company_name|wash}
                        {$order.account_information.company_additional|wash},
                    {/if}
                    {if and(is_set($order.account_information.title),$order.account_information.title|eq('0'))}
                        {'Mr.'|i18n('extension/xrowecommerce')}
                    {elseif and(is_set($order.account_information.title),$order.account_information.title|eq('1'))}
                        {'Mrs.'|i18n('extension/xrowecommerce')}
                    {/if}
                    {$order.account_information.first_name|wash}
                    {$order.account_information.last_name|wash}
                </td>
            </tr>
            <tr>
                <th>{'Address'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.address1|wash}</td>
            </tr>
            {if gt(count($order.account_information.address2),0)}
                <tr>
                    <th>&nbsp;</th>
                    <td>{$order.account_information.address2|wash}</td>
                </tr>
            {/if}
            <tr>
                <th>{'Zip code'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.zip|wash}</td>
            </tr>
            <tr>
                <th>{'City'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.city|wash}</td>
            </tr>
            {if $order.account_information.state}
                <tr>
                    <th>{'State'|i18n('extension/xrowecommerce')}:</th>
                    <td>{$order.account_information.state|wash()|get_state( $order.account_information.country )}</td>
                </tr>
            {/if}
            {foreach fetch( 'xrowecommerce', 'get_country_list') as $tmp_country}
                {if $tmp_country.Alpha3|eq($order.account_information.country)}
                    {set $country=$tmp_country}
                    {break}
                {/if}
            {/foreach}
            <tr>
                <th>{'Country'|i18n('extension/xrowecommerce')}:</th>
                <td>{*set $country=fetch( 'content', 'country_list', hash( 'filter', 'Alpha3', 'value', $order.account_information.country ) )*}{$country.Name|wash}
                </td>
            </tr>
            <tr>
                <th>{'Phone'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.phone|wash}</td>
            </tr>
            {if eq(ezini( 'Fields', 'fax', 'xrowecommerce.ini' ).enabled, 'true' )}
                <tr>
                    <th>{'Fax'|i18n('extension/xrowecommerce')}:</th>
                    <td>{$order.account_information.fax|wash}</td>
                </tr>
            {/if}
            {if eq(ezini( 'BasketInformation', 'DisplayShipping', 'xrowecommerce.ini' ), 'enabled' )}
                <tr>
                    <th>{'Shipping'|i18n('extension/xrowecommerce')}:</th>
                    <td>{if $shiplist} {foreach $shiplist as $method} {if $method.identifier|eq($order.account_information.shippingtype)}
                    {$method.name} {/if} {/foreach} {/if}</td>
                </tr>
            {/if}
        </table>
    {else}
        <table id="billing-address-table" class="order_box" border="0" cellspacing="0" cellpadding="0">
            <caption>{"Billing Address"|i18n("extension/xrowecommerce")}</caption>
            <tr>
                <th>{'Name'|i18n('extension/xrowecommerce')}:</th>
                <td>
                    {if $order.account_information.company_name}
                        {$order.account_information.company_name|wash}
                        {$order.account_information.company_additional|wash}
                    {else}
                        {if and(is_set($order.account_information.title),$order.account_information.title|eq('0'))}
                            {'Mr.'|i18n('extension/xrowecommerce')}
                        {elseif and(is_set($order.account_information.title),$order.account_information.title|eq('1'))}
                            {'Mrs.'|i18n('extension/xrowecommerce')}
                        {/if}
                        {$order.account_information.first_name|wash}
                        {$order.account_information.last_name|wash}
                    {/if}
                </td>
            </tr>
            <tr>
                <th>{'Address'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.address1|wash}</td>
            </tr>
            {if gt(count($order.account_information.address2),0)}
            <tr>
                <th>&nbsp;</th>
                <td>{$order.account_information.address2|wash}</td>
            </tr>
            {/if}
            <tr>
                <th>{'Zip code'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.zip|wash}</td>
            </tr>
            <tr>
                <th>{'City'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.city|wash}</td>
            </tr>
            {if $order.account_information.state}
            <tr>
                <th>{'State'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.state|wash()|get_state( $order.account_information.country )}</td>
            </tr>
            {/if}
            {foreach fetch( 'xrowecommerce', 'get_country_list') as $tmp_country}
                {if $tmp_country.Alpha3|eq($order.account_information.country)}
                    {set $country=$tmp_country}
                    {break}
                {/if}
            {/foreach}
            <tr>
                <th>{'Country'|i18n('extension/xrowecommerce')}:</th>
                <td>{*set $country=fetch( 'content', 'country_list', hash( 'filter', 'Alpha3', 'value', $order.account_information.country ) )*}{$country.Name|wash}
                </td>
            </tr>
            <tr>
                <th>{'Phone'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.phone|wash}</td>
            </tr>
            {if eq(ezini( 'Fields', 'fax', 'xrowecommerce.ini' ).enabled, 'true' )}
            <tr>
                <th>{'Fax'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.fax|wash}</td>
            </tr>
            {/if}
            {if eq(ezini( 'BasketInformation', 'DisplayShipping', 'xrowecommerce.ini' ), 'enabled' )}
                <tr>
                    <th>{'Shipping'|i18n('extension/xrowecommerce')}:</th>
                    <td>{foreach $shiplist as $method}
                            {if $method.identifier|eq($order.account_information.shippingtype)}
                                {$method.name|wash}
                            {/if}
                        {/foreach}
                    </td>
                </tr>
            {/if}
        </table>
        <table id="shipping-address-table" valign="top" class="order_box" border="0" cellspacing="0" cellpadding="0">
            <caption>{"Shipping Address"|i18n("extension/xrowecommerce")}</caption>
            <tr>
                <th>{'To'|i18n('extension/xrowecommerce')}:</th>
                <td>
                    {if $order.account_information.s_company_name}
                        {$order.account_information.s_company_name|wash}
                        {$order.account_information.s_company_additional|wash},
                    {/if}
                    {if and(is_set($order.account_information.s_title),$order.account_information.s_title|eq('0'))}
                        {'Mr.'|i18n('extension/xrowecommerce')}
                    {elseif and(is_set($order.account_information.s_title),$order.account_information.s_title|eq('1'))}
                        {'Mrs.'|i18n('extension/xrowecommerce')}
                    {/if}
                    {$order.account_information.s_first_name|wash}
                    {$order.account_information.s_mi|wash}
                    {$order.account_information.s_last_name|wash}
                </td>
            </tr>
            <tr>
                <th>{'Address'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.s_address1|wash}</td>
            </tr>
            {if gt(count($order.account_information.s_address2),0)}
            <tr>
                <th>&nbsp;</th>
                <td>{$order.account_information.s_address2|wash}</td>
            </tr>
            {/if}
            <tr>
                <th>{'Zip code'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.s_zip|wash}</td>
            </tr>
            <tr>
                <th>{'City'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.s_city|wash}</td>
            </tr>
            {if $order.account_information.s_state}
            <tr>
                <th>{'State'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.s_state|wash()|get_state( $order.account_information.s_country )}
                </td>
            </tr>
            {/if}
            {foreach fetch( 'xrowecommerce', 'get_country_list') as $tmp_country}
                {if $tmp_country.Alpha3|eq($order.account_information.s_country)}
                    {set $country=$tmp_country}
                    {break}
                {/if}
            {/foreach}
            <tr>
                <th>{'Country'|i18n('extension/xrowecommerce')}:</th>
                <td>{*set $country=fetch( 'content', 'country_list', hash( 'filter', 'Alpha3', 'value', $order.account_information.s_country ) )*}{$country.Name|wash}</td>
            </tr>
            <tr>
                <th>{'Phone'|i18n('extension/xrowecommerce')}:</th>
                <td>{$order.account_information.s_phone|wash}</td>
            </tr>
            {if eq(ezini( 'Fields', 'Fax', 'xrowecommerce.ini' ).enabled, 'true' )}
                {if eq(ezini( 'ShippingSettings', 'DisplayFax', 'xrowecommerce.ini' ), 'enabled' )}
                    <tr>
                        <th>{'Fax'|i18n('extension/xrowecommerce')}:</th>
                        <td>{$order.account_information.s_fax|wash}</td>
                    </tr>
                {/if}
            {/if}
            {if eq(ezini( 'Fields', 'Email', 'xrowecommerce.ini' ).enabled, 'true' )}
                <tr>
                    <th>{'Email'|i18n('extension/xrowecommerce')}:</th>
                    <td>{$order.account_information.s_email|wash}</td>
                </tr>
            {/if}
        </table>
    {/if}
    <table id="additional-orderinformation-table" class="order_box" border="0" cellspacing="0" cellpadding="0">
        <caption>{"Additional Orderinformation"|i18n("extension/xrowecommerce")}</caption>
        {if ezini( 'Fields', 'NoPartialDelivery', 'xrowecommerce.ini' ).enabled|eq('true')}
            <tr>
                <th>{'Partial delivery'|i18n('extension/xrowecommerce')}:</th>
                <td>
                {if $order.account_information.no_partial_delivery}
                    {'No'|i18n('extension/xrowecommerce')}
                {else}
                    {'Yes'|i18n('extension/xrowecommerce')}
                {/if}
                </td>
            </tr>
        {/if}
        {if ezini( 'BasketInformation', 'DisplayPaymentmethod', 'xrowecommerce.ini' )|eq( 'enabled' )}
            {* Enhancement - If credit card information is needed, include the number of the step in which the credit card information should be displayed. *}
            {if array( '' )|contains( $step )}
                {if $order.account_information.paymentmethod}
                    <tr>
                        <th>{'Payment method'|i18n('extension/xrowecommerce')}:</th>
                        <td>
                        {if $gateways|gt(0)}
                            {foreach $gateways as $gateway}
                                {if $order.account_information.paymentmethod|eq($gateway.value)}
                                    {$gateway.Name|wash}
                                {/if}
                            {/foreach}
                        {/if}
                            {if $order.account_information.type}
                                {switch match=$order.account_information.type}
                                    {case in=array(1,2,3,4)}
                                        {$order.account_information.number|wash} {$order.account_information.month|wash} / {$order.account_information.year|wash}
                                    {/case}
                                    {case match='5'}
                                        {$order.account_information.accountnumber|wash} {$order.account_information.bankcode|wash}
                                    {/case}
                                {/switch}
                            {/if}
                        </td>
                    </tr>
                {else}
                    <tr>
                        <th>{'Payment method'|i18n('extension/xrowecommerce')}:</th>
                        <td>{'Unkown'|i18n('extension/xrowecommerce')}</td>
                    </tr>
                {/if}
            {/if}
        {/if}
        {if and(ezini( 'Fields', 'Reference', 'xrowecommerce.ini' ).enabled|eq('true'), $order.account_information.reference)}
        <tr>
            <th>{'Reference'|i18n('extension/xrowecommerce')}:</th>
            <td>{$order.account_information.reference|wash}</td>
        </tr>
        {/if}
        {if and(ezini( 'Fields', 'Message', 'xrowecommerce.ini' ).enabled|eq('true'), $order.account_information.message)}
        <tr>
            <th>{'Your notes on order'|i18n('extension/xrowecommerce')}:</th>
            <td>{$order.account_information.message|wash()|nl2br()}</td>
        </tr>
        {/if}
    </table>

    {if and( eq( $fetchStoredTransaction, true), ne( $order.account_information.ezauthorize_card_date, '') )} {def $key = ezini( 'eZGPGSettings', 'KeyID', 'ezgpg.ini' )}
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
