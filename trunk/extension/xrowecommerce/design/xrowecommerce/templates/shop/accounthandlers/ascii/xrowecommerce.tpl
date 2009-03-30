
{if and( eq(ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.company_name)}
{'Company'|i18n('extension/xrowecommerce')}: {$order.account_information.company_name}
{/if}

{if and( eq(ezini( 'Settings', 'CompanyAdditional', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.company_additional)}
{'Company additional information'|i18n('extension/xrowecommerce')}: {$order.account_information.company_additional}
{/if}

{if and( eq(ezini( 'Settings', 'TaxID', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.tax_id)}
    {'Tax ID'|i18n('extension/xrowecommerce')}: {$order.account_information.tax_id}
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
{'Shipping'|i18n( 'extension/xrowecommerce')}: {switch match=$order.account_information.shippingtype}{case match="1"}Next Day Service{/case}{case match="2"}2nd Day Service{/case}{case}Standard Shipping{/case}{/switch}



{if eq($order.account_information.shipping,0)}
{'Shipped to'|i18n( 'extension/xrowecommerce')}:

{'Name'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_first_name} {$order.account_information.s_last_name}
{'MI'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_mi}
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
{if ezini( 'Settings', 'NoPartialDelivery', 'xrowecommerce.ini' )|eq( 'enabled' )}
{'Partial delivery'|i18n('extension/xrowecommerce')}: {if $order.account_information.no_partial_delivery}{'No'|i18n('extension/xrowecommerce')}{else}{'Yes'|i18n('extension/xrowecommerce')}{/if}
{/if}
{if $order.account_information.paymentmethod}
{'Payment method'|i18n('extension/xrowecommerce')}: {$order.account_information.paymentmethod}
{else}
{'Payment method'|i18n('extension/xrowecommerce')}: {'Unkown'|i18n('extension/xrowecommerce')}
{/if}
{if and(ezini( 'Settings', 'Reference', 'xrowecommerce.ini' )|eq( 'enabled' ), $order.account_information.reference)}
{'Reference'|i18n('extension/xrowecommerce')}:{$order.account_information.reference}
{/if}
{if and(ezini( 'Settings', 'Message', 'xrowecommerce.ini' )|eq( 'enabled' ), $order.account_information.message}}
{'Your notes on order'|i18n('extension/xrowecommerce')}:
{$order.account_information.message}
{/if}
