{* ezdbug_dump($order.account_information) *}

{* eZAuthorize + eZGPG - CC Storage Additions *}
{def $fetchStoredTransaction = ezini( 'eZAuthorizeSettings', 'StoreTransactionInformation', 'ezauthorize.ini' )}

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr><td>
    <table class="order_box">
    	<tr><th colspan="2">{"Customer"|i18n("design/standard/shop")}</th></tr>
{if eq(ezini( 'DisplayFieldSettings', 'DisplayCompanyName', 'fieldsettings.ini' ), 'enabled' )}
    	<tr><td class="bold">{'Company'|i18n('design/standard/shop')}: </td><td>{$order.account_information.company_name}</td></tr>
{/if}
{if eq(ezini( 'DisplayFieldSettings', 'DisplayCompanyAdditional', 'fieldsettings.ini' ), 'enabled' )}
        <tr><td class="bold">{'Form'|i18n('design/standard/shop')}: </td><td>{$order.account_information.company_additional}</td></tr>
{/if}
{if eq(ezini( 'DisplayFieldSettings', 'DisplayTaxId', 'fieldsettings.ini' ), 'enabled' )}
        <tr><td class="bold">{'TaxID'|i18n('design/standard/shop')}: </td><td>{$order.account_information.tax_id}</td></tr>
{/if}
    	<tr><td class="bold">{'Name'|i18n('design/standard/shop')}: </td><td>{$order.account_information.first_name} {$order.account_information.last_name}</td></tr>
    	<tr><td class="bold">{'Email'|i18n('design/standard/shop')}: </td><td>{$order.account_information.email}</td></tr>
    	</table>
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
    </td><td>
    
    
        </fieldset>
        {/if}
        
        </td>
        <td valign="top" class="order_left">
</td><td></td></tr>
<tr><td>  
            {if eq($order.account_information.shipping,1)}
            
     
    
            <table border="0"  cellspacing="0" cellpadding="0" class="order_box">
                <tr><th colspan="2">{"Delivery & Shipping Address"|i18n("design/standard/shop")}</th></tr>
                {*<tr><td class="bold">{'Company'|i18n('design/standard/shop')}:</td><td>{$order.account_information.company_name}</td></tr>*}
                <tr><td class="bold">{'Address'|i18n('design/standard/shop')}:</td><td>{$order.account_information.address1}</td></tr>
                {if gt(count($order.account_information.address2),0)}
                <tr><td>&nbsp;</td><td>{$order.account_information.address2}</td></tr>
                {/if}
                <tr><td class="bold">{'City'|i18n('design/standard/shop')}:</td><td>{$order.account_information.city}</td></tr>
                <tr><td class="bold">{'State'|i18n('design/standard/shop')}:</td><td>{$order.account_information.state}</td></tr>
                <tr><td class="bold">{'Zip code'|i18n('design/standard/shop')}:</td><td>{$order.account_information.zip}</td></tr>
                <tr><td class="bold">{'Country'|i18n('design/standard/shop')}:</td><td>{$order.account_information.country}</td></tr>
                <tr><td class="bold">{'Phone'|i18n('design/standard/shop')}:</td><td>{$order.account_information.phone}</td></tr>
                <tr><td class="bold">{'Shipping'|i18n('design/standard/shop')}:</td><td>
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
             
            <table class="order_box" border="0"  cellspacing="0" cellpadding="0">
                <tr><th colspan="2">{"Billing Address"|i18n("design/standard/shop")}</th></tr>
                <tr><td class="bold">{'Address'|i18n('design/standard/shop')}:</td><td>{$order.account_information.address1}</td></tr>
                {if gt(count($order.account_information.address2),0)}
                <tr><td>&nbsp;</td><td>{$order.account_information.address2}</td></tr>
                {/if}
                <tr><td class="bold">{'City'|i18n('design/standard/shop')}:</td><td>{$order.account_information.city}</td></tr>
                <tr><td class="bold">{'State'|i18n('design/standard/shop')}:</td><td>{$order.account_information.state}</td></tr>
                <tr><td class="bold">{'Zip code'|i18n('design/standard/shop')}:</td><td>{$order.account_information.zip}</td></tr>
                <tr><td class="bold">{'Country'|i18n('design/standard/shop')}:</td><td>{$order.account_information.country}</td></tr>
                <tr><td class="bold">{'Phone'|i18n('design/standard/shop')}:</td><td>{$order.account_information.phone}</td></tr>
                <tr><td class="bold">{'Shipping'|i18n('design/standard/shop')}:</td>
                
                <td>
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


            
            <table valign="top" class="order_box" border="0"  cellspacing="0" cellpadding="0">
                <tr><th colspan="2">{"Shipping Address"|i18n("design/standard/shop")}</th></tr>
                {if eq(ezini( 'DisplayFieldSettings', 'DisplayCompanyName', 'fieldsettings.ini' ), 'enabled' )}
                <tr><td class="bold">Company:</td><td>{$order.account_information.s_company_name}</td></tr>
                {/if}
                <tr><td class="bold">Name:</td><td>{$order.account_information.s_first_name} {$order.account_information.s_last_name}</td></tr>
                <tr><td class="bold">MI:</td><td>{$order.account_information.s_mi}</td></tr>
                <tr><td class="bold">{'Address'|i18n('design/standard/shop')}:</td><td>{$order.account_information.s_address1}</td></tr>
                {if gt(count($order.account_information.s_address2),0)}
                <tr><td>&nbsp;</td><td>{$order.account_information.s_address2}</td></tr>
                {/if}
                <tr><td class="bold">{'City'|i18n('design/standard/shop')}:</td><td>{$order.account_information.s_city}</td></tr>
                <tr><td class="bold">{'State'|i18n('design/standard/shop')}:</td><td>{$order.account_information.s_state}</td></tr>
                <tr><td class="bold">{'Zip code'|i18n('design/standard/shop')}:</td><td>{$order.account_information.s_zip}</td></tr>
                <tr><td class="bold">{'Country'|i18n('design/standard/shop')}:</td><td>{$order.account_information.s_country}</td></tr>
                <tr><td class="bold">{'Phone'|i18n('design/standard/shop')}:</td><td>{$order.account_information.s_phone}</td></tr>
                <tr><td class="bold">{'Email'|i18n('design/standard/shop')}:</td><td>{$order.account_information.s_email}</td></tr>
            </table>


    {/if}
    
    </td><td>&nbsp</td></tr>
</table>
