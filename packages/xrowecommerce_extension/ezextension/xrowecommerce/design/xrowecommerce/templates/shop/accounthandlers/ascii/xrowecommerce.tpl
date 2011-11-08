{def $shiplist=fetch( 'shipping', 'list_all_methods' )}{def $gateways=fetch( 'xrowecommerce', 'list_all_gateways' )}
{if and( eq(ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.company_name)}{'Company'|i18n('extension/xrowecommerce')}: {$order.account_information.company_name}{array('0013')|chr()}{/if}
{if and( eq(ezini( 'Settings', 'CompanyAdditional', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.company_additional)}{'Company additional information'|i18n('extension/xrowecommerce')}: {$order.account_information.company_additional}{array('0013')|chr()}{/if}
{array('0013')|chr()}
{if and( eq(ezini( 'Settings', 'TaxID', 'xrowecommerce.ini' ), 'enabled' ), $order.account_information.tax_id)}{'Tax ID'|i18n('extension/xrowecommerce')}: {$order.account_information.tax_id} {if $order.account_information.tax_id_valid|not} ({'unconfirmed'|i18n('extension/xrowecommerce')}){/if}{array('0013')|chr()}{/if}
{'Name'|i18n( 'extension/xrowecommerce')}: {$order.account_information.first_name} {$order.account_information.mi} {$order.account_information.last_name}{array('0013')|chr()}
{'Email'|i18n( 'extension/xrowecommerce')}: {$order.account_information.email}{array('0013')|chr()}
{'Address1'|i18n( 'extension/xrowecommerce')}: {$order.account_information.address1}{array('0013')|chr()}
{if gt(count($order.account_information.address2),0)}{'Address2'|i18n( 'extension/xrowecommerce')}: {$order.account_information.address2}{array('0013')|chr()}{/if}
{'City'|i18n( 'extension/xrowecommerce')}: {$order.account_information.city}{array('0013')|chr()}
{if $order.account_information.state}{'State'|i18n('extension/xrowecommerce')}: {$order.account_information.state|get_state($order.account_information.country)}{array('0013')|chr()}{/if}
{'Country'|i18n( 'extension/xrowecommerce')}: {*$order.account_information.country*}{foreach fetch( 'xrowecommerce', 'get_country_list') as $tmp_country}{if $tmp_country.Alpha3|eq($order.account_information.country)}{set $country=$tmp_country}{$tmp_country.Name}{break}{/if}{/foreach}{array('0013')|chr()}
{'Zip code'|i18n( 'extension/xrowecommerce')}: {$order.account_information.zip}{array('0013')|chr()}
{'Phone'|i18n( 'extension/xrowecommerce')}: {$order.account_information.phone}{array('0013')|chr()}
{'Shipping'|i18n( 'extension/xrowecommerce')}: {foreach  $shiplist as $method}{if $method.identifier|eq($order.account_information.shippingtype)}{$method.name}{/if}{/foreach}{array('0013')|chr()}
{if eq($order.account_information.shipping,0)}{'Shipped to'|i18n( 'extension/xrowecommerce')}:{array('0013')|chr()}
{'Name'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_first_name} {$order.account_information.s_mi} {$order.account_information.s_last_name}{array('0013')|chr()}
{'Address1'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_address1}{array('0013')|chr()}
{if gt(count($order.account_information.s_address2),0)}{'Address2'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_address2}{array('0013')|chr()}{/if}
{'City'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_city}{array('0013')|chr()}
{if $order.account_information.s_state}{'State'|i18n('extension/xrowecommerce')}: {$order.account_information.s_state|get_state($order.account_information.s_country)}{array('0013')|chr()}{/if}
{'Country'|i18n( 'extension/xrowecommerce')}: {*$order.account_information.s_country*}{foreach fetch( 'xrowecommerce', 'get_country_list') as $tmp_country}{if $tmp_country.Alpha3|eq($order.account_information.s_country)}{set $country=$tmp_country}{$tmp_country.Name}{break}{/if}{/foreach}{array('0013')|chr()}
{'Zip code'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_zip}{array('0013')|chr()}
{'Phone'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_phone}{array('0013')|chr()}
{'Email'|i18n( 'extension/xrowecommerce')}: {$order.account_information.s_email}{array('0013')|chr()}
{/if}
{array('0013')|chr()}
{"Additional Orderinformation"|i18n("extension/xrowecommerce")}:{array('0013')|chr()}
{array('0013')|chr()}
{if ezini( 'Fields', 'NoPartialDelivery', 'xrowecommerce.ini' )|eq( 'enabled' )}{'Partial delivery'|i18n('extension/xrowecommerce')}: {if $order.account_information.no_partial_delivery}{'No'|i18n('extension/xrowecommerce')}{else}{'Yes'|i18n('extension/xrowecommerce')}{/if}{array('0013')|chr()}{/if}
{if $order.account_information.paymentmethod}
{'Payment method'|i18n('extension/xrowecommerce')}: {if $gateways|gt(0)}{foreach $gateways as $gateway}{if $order.account_information.paymentmethod|eq($gateway.value)}{$gateway.Name|wash}{/if}{/foreach}{array('0013')|chr()}{/if}
{if $order.account_information.type}{switch match=$order.account_information.type}{case in=array(1,2,3,4)}{$order.account_information.number} {$order.account_information.month} / {$order.account_information.year}{array('0013')|chr()}{/case}{case match='5'}{$order.account_information.accountnumber} {$order.account_information.bankcode}{array('0013')|chr()}{/case}{/switch}{/if}{else}{'Payment method'|i18n('extension/xrowecommerce')}: {'Unkown'|i18n('extension/xrowecommerce')}{array('0013')|chr()}{/if}
{if and(ezini( 'Settings', 'Reference', 'xrowecommerce.ini' )|eq( 'enabled' ), $order.account_information.reference)}{'Reference'|i18n('extension/xrowecommerce')}: {$order.account_information.reference}{array('0013')|chr()}{/if}
{if and(ezini( 'Settings', 'Message', 'xrowecommerce.ini' )|eq( 'enabled' ), $order.account_information.message)}{'Your notes on order'|i18n('extension/xrowecommerce')}:{array('0013')|chr()}{$order.account_information.message}{/if}