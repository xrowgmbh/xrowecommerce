{* ezdbug_dump($order.account_information) *}

{* eZAuthorize + eZGPG - CC Storage Additions *}
{def $fetchStoredTransaction = ezini( 'eZAuthorizeSettings', 'StoreTransactionInformation', 'ezauthorize.ini' )}

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
<td valign="top">
	<b>{"Customer"|i18n("design/standard/shop")}</b>
	</p>
	<p>
	{'Name'|i18n('design/standard/shop')}: {$order.account_information.first_name} {$order.account_information.last_name}<br />
	{'Email'|i18n('design/standard/shop')}: {$order.account_information.email}<br />

{if and( eq( $fetchStoredTransaction, true), ne( $order.account_information.ezauthorize_card_date, '') )}
{def $key = ezini( 'eZGPGSettings', 'KeyID', 'ezgpg.ini' )}

<fieldset>
<legend>{'Payment information'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</legend>
<table class="list" cellspacing="0">
<tr>
    <td>{'Card Holder\'s Name'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{ezgpg_decrypt($order.account_information.ezauthorize_card_name, $key)}</td>
</tr>
<tr>
    <td>{'Last 4 Digits of Card Number'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{ezgpg_decrypt_limit($order.account_information.ezauthorize_card_number, $key)}</td>
</tr>
<tr>
    <td>{'Card Expiration Date'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.ezauthorize_card_date}</td>
</tr>
</table>
</fieldset>
{/if}

</td>
<td valign="top" width="300px;">
{if eq($order.account_information.shipping,1)}
<b>{"Address"|i18n("design/standard/shop")}</b>
<table border="0"  cellspacing="0" cellpadding="0">
<tr><td>{'Address'|i18n('design/standard/shop')}:</td><td>{$order.account_information.address1}</td></tr>
{if gt(count($order.account_information.address2),0)}
<tr><td>&nbsp;</td><td>{$order.account_information.address2}</td></tr>
{/if}
<tr><td>{'City'|i18n('design/standard/shop')}:</td><td>{$order.account_information.city}</td></tr>
<tr><td>{'State'|i18n('design/standard/shop')}:</td><td>{$order.account_information.state}</td></tr>
<tr><td>{'Zip code'|i18n('design/standard/shop')}:</td><td>{$order.account_information.zip}</td></tr>
<tr><td>{'Country'|i18n('design/standard/shop')}:</td><td>{$order.account_information.country}</td></tr>
<tr><td>{'Phone'|i18n('design/standard/shop')}:</td><td>{$order.account_information.phone}</td></tr>
<tr><td>{'Shipping'|i18n('design/standard/shop')}:</td><td>
{switch match=$order.account_information.shippingtype}
{case match="1"}
    Next Day Service
{/case}
{case match="2"}
    2nd Day Service
{/case}
{case}
    Standard Shipping
{/case}
{/switch}
</td></tr>
</table>

{else}

<b>{"Billing Address"|i18n("design/standard/shop")}</b>
<table border="0"  cellspacing="0" cellpadding="0">
<tr><td>{'Address'|i18n('design/standard/shop')}:</td><td>{$order.account_information.address1}</td></tr>
{if gt(count($order.account_information.address2),0)}
<tr><td>&nbsp;</td><td>{$order.account_information.address2}</td></tr>
{/if}
<tr><td>{'City'|i18n('design/standard/shop')}:</td><td>{$order.account_information.city}</td></tr>
<tr><td>{'State'|i18n('design/standard/shop')}:</td><td>{$order.account_information.state}</td></tr>
<tr><td>{'Zip code'|i18n('design/standard/shop')}:</td><td>{$order.account_information.zip}</td></tr>
<tr><td>{'Country'|i18n('design/standard/shop')}:</td><td>{$order.account_information.country}</td></tr>
<tr><td>{'Phone'|i18n('design/standard/shop')}:</td><td>{$order.account_information.phone}</td></tr>
<tr><td>{'Shipping'|i18n('design/standard/shop')}:</td><td>
{switch match=$order.account_information.shippingtype}
{case match="1"}
    Next Day Service
{/case}
{case match="2"}
    2nd Day Service
{/case}
{case}
    Standard Shipping
{/case}
{/switch}

</td></tr>
</table>
<br />
<b>{"Shipping Address"|i18n("design/standard/shop")}</b>
<table border="0"  cellspacing="0" cellpadding="0">
<tr><td>Name:</td><td>{$order.account_information.s_first_name} {$order.account_information.s_last_name}</td></tr>
<tr><td>MI:</td><td>{$order.account_information.s_mi}</td></tr>
<tr><td>{'Address'|i18n('design/standard/shop')}:</td><td>{$order.account_information.s_address1}</td></tr>
{if gt(count($order.account_information.s_address2),0)}
<tr><td>&nbsp;</td><td>{$order.account_information.s_address2}</td></tr>
{/if}
<tr><td>{'City'|i18n('design/standard/shop')}:</td><td>{$order.account_information.s_city}</td></tr>
<tr><td>{'State'|i18n('design/standard/shop')}:</td><td>{$order.account_information.s_state}</td></tr>
<tr><td>{'Zip code'|i18n('design/standard/shop')}:</td><td>{$order.account_information.s_zip}</td></tr>
<tr><td>{'Country'|i18n('design/standard/shop')}:</td><td>{$order.account_information.s_country}</td></tr>
<tr><td>{'Phone'|i18n('design/standard/shop')}:</td><td>{$order.account_information.s_phone}</td></tr>
<tr><td>{'Email'|i18n('design/standard/shop')}:</td><td>{$order.account_information.s_email}</td></tr>
</table>


{/if}
</td>
</tr>
</table>
