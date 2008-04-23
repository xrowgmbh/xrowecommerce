Name: {$order.account_information.first_name} {$order.account_information.last_name}
MI: {$order.account_information.mi}
Email: {$order.account_information.email}


Address1: {$order.account_information.address1}

{if gt(count($order.account_information.address2),0)}
Address2: {$order.account_information.address2}
{/if}

City: {$order.account_information.city}
State: {$order.account_information.state}
Country: {$order.account_information.country}
Zip code: {$order.account_information.zip}
Phone: {$order.account_information.phone}
Shipping: {switch match=$order.account_information.shippingtype}{case match="1"}Next Day Service{/case}{case match="2"}2nd Day Service{/case}{case}Standard Shipping{/case}{/switch}



{if eq($order.account_information.shipping,0)}
Shipped to:

Name: {$order.account_information.s_first_name} {$order.account_information.s_last_name}
MI: {$order.account_information.s_mi}
Address1: {$order.account_information.s_address1}

{if gt(count($order.account_information.s_address2),0)}
Address2: {$order.account_information.s_address2}
{/if}

City: {$order.account_information.s_city}
State: {$order.account_information.s_state}
Country: {$order.account_information.s_country}
Zip code: {$order.account_information.s_zip}
Phone: {$order.account_information.s_phone}
Email: {$order.account_information.s_email}
{/if}
