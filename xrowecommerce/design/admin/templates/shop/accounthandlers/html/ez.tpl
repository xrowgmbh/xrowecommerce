{* Name. *}
<div class="block">
<label>{'Name'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}:</label>
{let customer_user=fetch( content, object, hash( object_id, $order.user_id ) )}
<a href={$customer_user.main_node.url_alias|ezurl}>{$order.account_information.first_name}&nbsp;{$order.account_information.last_name}</a>
{/let}
</div>

{* Email. *}
<div class="block">
<label>{'E-mail'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}:</label>
<a href="mailto:{$order.account_information.email}">{$order.account_information.email}</a>
</div>

{* Address. *}
<div class="block">

<fieldset>
<legend>{'Address'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</legend>
{if ne($order.account_information.shipping,1)}
<table class="list" cellspacing="0">
<tr>
<td><b>Billingaddress</b></td>
<td><b>Shippingaddress</b></td>
</tr>
<tr>
<td>
{/if}
<table class="list" cellspacing="0">
<tr>
    <td>{'First Name'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.first_name}</td>
</tr>
<tr>
    <td>MI</td>
    <td>{$order.account_information.mi}</td>
</tr>
<tr>
    <td>{'Last Name'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.last_name}</td>
</tr>
<tr>
    <td>{'Address 1'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.address1}</td>
</tr>
<tr>
    <td>{'Address 2'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.address2}</td>
</tr>
<tr>
    <td>{'City'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.city}</td>
</tr>
<tr>
    <td>{'State'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.state}</td>
</tr>
<tr>
    <td>{'Zip Code'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.zip}</td>
</tr>
<tr>
    <td>{'Country'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.country}</td>
</tr>
<tr>
    <td>{'Phone'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.phone}</td>
</tr>
<tr>
    <td>{'Shipping Type'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>
    
    {if eq($order.account_information.shippingtype,0)}Standard Shipping (deprecated)
    {elseif eq($order.account_information.shippingtype,1)}Next Day Service (deprecated)
    {elseif eq($order.account_information.shippingtype,2)}2nd Day Service (deprecated)
    {elseif eq($order.account_information.shippingtype,3)}UPS Ground (USA only)
    {elseif eq($order.account_information.shippingtype,4)}UPS Next Day Air (USA only)
    {elseif eq($order.account_information.shippingtype,5)}UPS 2nd Day Air (USA only)
    {elseif eq($order.account_information.shippingtype,6)}USPS Express Mail International (EMS)
    {elseif eq($order.account_information.shippingtype,7)}USPS Global Express Guaranteed
    {else}Unknown Shipping
        {/if}
    </td>
</tr>
</table>
{if ne($order.account_information.shipping,1)}
</td>
<td>
<table class="list" cellspacing="0">
<tr>
    <td>{'First Name'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.s_first_name}</td>
</tr>
<tr>
    <td>MI</td>
    <td>{$order.account_information.s_mi}</td>
</tr>
<tr>
    <td>{'Last Name'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.s_last_name}</td>
</tr>
<tr>
    <td>{'Address 1'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.s_address1}</td>
</tr>
<tr>
    <td>{'Address 2'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.s_address2}</td>
</tr>
<tr>
    <td>{'City'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.s_city}</td>
</tr>
<tr>
    <td>{'State'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.s_state}</td>
</tr>
<tr>
    <td>{'Zip Code'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.s_zip}</td>
</tr>
<tr>
    <td>{'Country'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.s_country}</td>
</tr>
<tr>
    <td>{'Phone'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.s_phone}</td>
</tr>
<tr>
    <td>Email</td>
    <td>{$order.account_information.s_email}</td>
</tr>
</table>
</td>
</tr>
</table>
{/if}
</fieldset>

{* eZAuthorize + eZGPG - CC Storage Additions *} 
{def $fetchStoredTransaction = ezini( 'eZAuthorizeSettings', 'StoreTransactionInformation', 'ezauthorize.ini' )}
{if and( eq( $fetchStoredTransaction, true), ne( $order.account_information.ezauthorize_card_date, '') )}

{def $key = ezini( 'eZGPGSettings', 'KeyID', 'ezgpg.ini' )}
{def $displayTransactionLink = ezini( 'eZAuthorizeSettings', 'ShowAuthorizeDotNetMerchantTransactionDetailHTTPLink', 'ezauthorize.ini' )}
{def $displayRefundButton = ezini( 'eZAuthorizeSettings', 'ShowAuthorizeDotNetRefundButton', 'ezauthorize.ini' )}

<br />
<script language="Javascript">
{literal}
<!--
 function doubleConfirmMessagePaymentRefund()
 {
  var agree=confirm('Are you absolutly certain you want to refund this order?\n\nThis action is permenant ...');

  if(agree)
  {
    alert('A log of this change will be saved.');
    return true ;
  }
  else
  {
    alert('A wise decision!');
    return false ;
  }

  return false ;
}
-->
{/literal}
</script>

{* $order.account_information|attribute(show,1) *}
{* $order.account_information|attribute(show,1) *}
{* $order|attribute(show,1) *}

<fieldset>
<legend>{'Payment information'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</legend>
<table class="list" cellspacing="0">
<tr>
    <td>{'Authorize.net : Transaction ID'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{if eq( $displayTransactionLink, true )}<a target="_blank" href="https://account.authorize.net/ui/themes/anet/merch.aspx?page=history&sub=detail&transID={$order.account_information.ezauthorize_transaction_id}">{$order.account_information.ezauthorize_transaction_id}</a>{else}{$order.account_information.ezauthorize_transaction_id}{/if}</td>
</tr>
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
<tr>
    <td>{'Card Type'|i18n( 'design/admin/shop/accounthandlers/html/ez' )}</td>
    <td>{$order.account_information.ezauthorize_card_type|upfirst()}</td>
</t>
{if eq( $displayRefundButton, true) }
<tr>
    <td>{* 'Refund Order'|i18n( 'design/admin/shop/accounthandlers/html/ez' ) *}</td>
    <td style="text-align:right; padding-top: 10px; padding-right: 5px;"><form name="refund" action="/refund/refund/order/{$order.id}" method="post" onSubmit="return doubleConfirmMessagePaymentRefund()"><input name="order" type="hidden" value="{$order.id}" /><input class="button" type=submit name="SubmitButton" value="Refund Order Payment" title="Click this button if you wish to refund this order payment." /></form></td>
</tr>
{/if}
</table>
</fieldset>
{/if}

</div>
