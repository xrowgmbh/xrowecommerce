{'Name'|i18n( 'extension/xrowecommerce')}: {$order.account_information.first_name} {$order.account_information.last_name}
{'MI'|i18n( 'extension/xrowecommerce')}: {$order.account_information.mi}
{'Email'|i18n( 'extension/xrowecommerce')}: {$order.account_information.email}


{'Address1'|i18n( 'extension/xrowecommerce')}: {$order.account_information.address1}

{if gt(count($order.account_information.address2),0)}
{'Address2'|i18n( 'extension/xrowecommerce')}: {$order.account_information.address2}
{/if}

{'City'|i18n( 'extension/xrowecommerce')}: {$order.account_information.city}
{'State'|i18n( 'extension/xrowecommerce')}: {$order.account_information.state}
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
{'State'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_state}
{'Country'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_country}
{'Zip code'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_zip}
{'Phone'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_phone}
{'Email'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_email}
{/if}
