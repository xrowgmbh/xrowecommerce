{def $shiplist=fetch( 'shipping', 'list_all_methods' )}
{def $gateways=fetch( 'xrowecommerce', 'list_all_gateways' )}

{if and( eq(ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.company_name)}
{'Company'|i18n('extension/xrowecommerce')}: {$order.account_information.company_name}

{/if}
{if and( eq(ezini( 'Settings', 'CompanyAdditional', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.company_additional)}
{'Company additional information'|i18n('extension/xrowecommerce')}: {$order.account_information.company_additional}

{/if}
{if and( eq(ezini( 'Settings', 'TaxID', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.tax_id)}
{'Tax ID'|i18n('extension/xrowecommerce')}: {$order.account_information.tax_id} {if $order.account_information.tax_id_valid|not} ({'unconfirmed'|i18n('extension/xrowecommerce')}){/if}

{/if}
{'Name'|i18n( 'extension/xrowecommerce')}: {$order.account_information.first_name} {$order.account_information.mi} {$order.account_information.last_name}

{'Email'|i18n( 'extension/xrowecommerce')}: {$order.account_information.email}

{'Address1'|i18n( 'extension/xrowecommerce')}: {$order.account_information.address1}

{if gt(count($order.account_information.address2),0)}
{'Address2'|i18n( 'extension/xrowecommerce')}: {$order.account_information.address2}

{/if}
{'City'|i18n( 'extension/xrowecommerce')}: {$order.account_information.city}

{if $order.account_information.state}
{'State'|i18n('extension/xrowecommerce')}: {$order.account_information.state}

{/if}
{'Country'|i18n( 'extension/xrowecommerce')}: {$order.account_information.country}

{'Zip code'|i18n( 'extension/xrowecommerce')}: {$order.account_information.zip}

{'Phone'|i18n( 'extension/xrowecommerce')}: {$order.account_information.phone}

{'Shipping'|i18n( 'extension/xrowecommerce')}: {foreach  $shiplist as $method}{if $method.identifier|eq($order.account_information.shippingtype)}{$method.name}{/if}{/foreach}

{if eq($order.account_information.shipping,0)}
{'Shipped to'|i18n( 'extension/xrowecommerce')}:

{'Name'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_first_name} {$order.account_information.s_mi} {$order.account_information.s_last_name}

{'Address1'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_address1}

{if gt(count($order.account_information.s_address2),0)}
{'Address2'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_address2}

{/if}
{'City'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_city}

{if $order.account_information.s_state}
{'State'|i18n('extension/xrowecommerce')}: {$order.account_information.s_state}

{/if}

{'Country'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_country}

{'Zip code'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_zip}

{'Phone'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_phone}

{'Email'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_email}

{/if}

{"Additional Orderinformation"|i18n("extension/xrowecommerce")}:

{if ezini( 'Fields', 'NoPartialDelivery', 'xrowecommerce.ini' )|eq( 'enabled' )}
{'Partial delivery'|i18n('extension/xrowecommerce')}: {if $order.account_information.no_partial_delivery}{'No'|i18n('extension/xrowecommerce')}{else}{'Yes'|i18n('extension/xrowecommerce')}{/if}

{/if}
{if $order.account_information.paymentmethod}
{'Payment method'|i18n('extension/xrowecommerce')}: {if $gateways|gt(0)}{foreach $gateways as $gateway}{if $order.account_information.paymentmethod|eq($gateway.value)}{$gateway.Name|wash}{/if}{/foreach}{/if}
{$order.account_information.paymentmethod}
        {if $order.account_information.type}
        {switch match=$order.account_information.type}
        {case in=array(1,2,3,4)}
        {$order.account_information.number} {$order.account_information.month} / {$order.account_information.year}
        {/case}
        {case match='5'}
        {$order.account_information.accountnumber} {$order.account_information.bankcode}
        {/case}
        {/switch}
        {/if}
{else}
{'Payment method'|i18n('extension/xrowecommerce')}: {'Unkown'|i18n('extension/xrowecommerce')}

{/if}
{if and(ezini( 'Settings', 'Reference', 'xrowecommerce.ini' )|eq( 'enabled' ), $order.account_information.reference)}
{'Reference'|i18n('extension/xrowecommerce')}: {$order.account_information.reference}

{/if}
{if and(ezini( 'Settings', 'Message', 'xrowecommerce.ini' )|eq( 'enabled' ), $order.account_information.message)}
{'Your notes on order'|i18n('extension/xrowecommerce')}:
{$order.account_information.message}

{/if}
