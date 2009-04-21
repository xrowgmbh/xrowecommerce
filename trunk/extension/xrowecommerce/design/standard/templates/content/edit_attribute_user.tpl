{def $ca=$content_attributes_data_map
     $castring="ContentObjectAttribute_ezstring_data_text_"}
     {* left frame start *}
 <div class="shop-basket user_register">
    <div class="accountinfo">
        <h1>{'Account Information'|i18n('extension/xrowecommerce')}</h1>
        <p>{'Although your Login User Name must be kept the same, you can update your Email Address and/or change your Password by entering new information in the appropriate field.  When complete, click the "Update" button at the bottom of the page.'|i18n('extension/xrowecommerce')}</p>
        <p><span class="required">* {'Required field'|i18n('extension/xrowecommerce')}</span></p>

        <div class="block">
            <label><span class="required">*</span>{'Username'|i18n('extension/xrowecommerce')}</label>
            <div class="labelbreak"></div>
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.user_account.id}" size="20" />
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.user_account.id}" />
            <input name="ContentObjectAttribute_data_user_login_{$ca.user_account.id}" value="{$ca.user_account.content.login}" type="text" disabled="disabled" />
        </div>
        <div class="break"></div>
        <div class="block">
            <label><span class="required">*</span>{'E-mail'|i18n('extension/xrowecommerce')}</label>
            <div class="labelbreak"></div>
            <input class="box" type="text" name="ContentObjectAttribute_data_user_email_{$ca.user_account.id}" value="{$ca.user_account.content.email|wash()}" size="20"  />
        </div>
        <div class="break"></div>
        <div class="block">
            <label>{'Password'|i18n('extension/xrowecommerce')}</label>
            <div class="labelbreak"></div>
            <input class="box" type="password" name="ContentObjectAttribute_data_user_password_{$ca.user_account.id}" value="{section show=$ca.user_account.content.original_password}{$ca.user_account.content.original_password}{section-else}{section show=$ca.user_account.content.has_stored_login}_ezpassword{/section}{/section}" size="20" />
        </div>
        <div class="break"></div>
        <div class="block">
            <label>{'Confirm Password'|i18n('extension/xrowecommerce')}</label>
            <div class="labelbreak"></div>
            <input class="box" type="password" name="ContentObjectAttribute_data_user_password_confirm_{$ca.user_account.id}" value="{section show=$ca.user_account.content.original_password_confirm}{$ca.user_account.content.original_password_confirm}{section-else}{section show=$ca.user_account.content.has_stored_login}_ezpassword{/section}{/section}" size="20" />
        </div>
        <div class="break"></div>
    </div>

{* left frame end *}
    <div class="billing_shipping">

{* right frame start *}
        <div class="billing">

            {include uri="design:content/edit_attribute_user_pretext.tpl"}

            <input type="hidden" name="MainNodeID" value="{$main_node_id}" />
            <div class="block">
                {if eq(ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' ), 'enabled' )}
                <div class="block">
                    <label><span class="required">*</span>{'Company name'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.company_name.id}" value="{$ca.company_name.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.company_name.id}" />
                </div>
                {/if}
                {if eq(ezini( 'Settings', 'CompanyAdditional', 'xrowecommerce.ini' ), 'enabled' )}
                <div class="block">
                    <label>{'Form of company'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.company_additional.id}" value="{$ca.company_additional.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.company_additional.id}" />
                </div>
                {/if}
                {if eq(ezini( 'Settings', 'TaxID', 'xrowecommerce.ini' ), 'enabled' )}
                <div>
                    <label>{'Tax ID'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.tax_id.id}" value="{$ca.tax_id.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.tax_id.id}" />
                </div>
                {/if}
                <div class="block">
                    <label><span class="required">*</span>{'First name'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.first_name.id}" value="{$ca.first_name.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.first_name.id}" />
                </div>
                {if eq(ezini( 'Settings', 'MI', 'xrowecommerce.ini' ), 'enabled' )}
                <div>
                    <label>{'MI'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_mi.id}" size="2" value="{$ca.s_mi.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_mi.id}" />
                </div>
                {/if}
                <div class="block">
                    <label><span class="required">*</span>{'Last name'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.last_name.id}" value="{$ca.last_name.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.last_name.id}" />
                </div>
                <div class="block">
                    <label><span class="required">*</span>{'Address 1'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.address1.id}" size="20" value="{$ca.address1.content|wash()}" title="{'Apartment, suite, unit, building, floor, etc.'|i18n('extension/xrowecommerce')}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.address1.id}" />
                </div>
                {if eq(ezini( 'Settings', 'Address2', 'xrowecommerce.ini' ), 'enabled' )}
                <div class="block">
                    <label>{'Address 2'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.address2.id}" size="20" value="{$ca.address2.content|wash()}" title="{'Street address, P.O. box, company name, c/o'|i18n('extension/xrowecommerce')}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.address2.id}" />
                </div>
                {/if}
                <div class="block">
                    <label><span class="required">*</span>{'Zip / Postcode'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="{$castring}{$ca.zip_code.id}" value="{$ca.zip_code.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.zip_code.id}" />
                </div>
                <div class="block">
                    <label><span class="required">*</span>{'City / Town'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="{$castring}{$ca.city.id}" value="{$ca.city.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.city.id}" />
                </div>
                {if eq(ezini( 'Settings', 'State', 'xrowecommerce.ini' ), 'enabled' )}
                <div>
                    <label>{'State / Province'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.state.id}" />
                    {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.state}
                </div>
                {/if}
                <div class="break"></div>
                <div class="block">
                    <label><span class="required">*</span>{'Country'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    {*TODO we MAY has to set a default country*}
                    {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.country}
                </div>
                <div class="break"></div>
                <div class="block">
                    <label><span class="required">*</span>{'Phone'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="{$castring}{$ca.phone.id}" value="{$ca.phone.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.phone.id}" />
                </div>
                <div class="break"></div>
                {if eq(ezini( 'Settings', 'Fax', 'xrowecommerce.ini' ), 'enabled' )}
                <div class="block">
                    <label>{'Fax'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="{$castring}{$ca.fax.id}" value="{$ca.fax.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.fax.id}" />
                </div>
                {/if}

                {include uri="design:content/edit_attribute_user_custom_left.tpl"}
            </div>
        </div>

        <div class="shipping">
            <h3>{'Shipping Information'|i18n('extension/xrowecommerce')}</h3>
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.shippingaddress.id}" />
            <label onclick="change();" class="shipping-checkbox" for="ContentObjectAttribute_data_boolean_{$ca.shippingaddress.id}">
                <input onclick="change();" id="ContentObjectAttribute_data_boolean_{$ca.shippingaddress.id}"  name="ContentObjectAttribute_data_boolean_{$ca.shippingaddress.id}" value="" type="checkbox" {$ca.shippingaddress.data_int|choose( '', 'checked="checked"' )} />
                {'My billing and shipping addresses are identical.'|i18n('extension/xrowecommerce')}
            </label>

            <div class="block" id="shippinginfo">
                {if eq(ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' ), 'enabled' )}
                <div class="block">
                    <label><span class="required">*</span>{'Company name'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_company_name.id}" value="{$ca.s_company_name.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_company_name.id}" />
                </div>
                {/if}
                {if eq(ezini( 'Settings', 'CompanyAdditional', 'xrowecommerce.ini' ), 'enabled' )}
                <div class="block">
                    <label>{'Form of company'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_company_additional.id}" value="{$ca.s_company_additional.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_company_additional.id}" />
                </div>
                {/if}
                <div class="block">
                    <label><span class="required">*</span>{'First name'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_first_name.id}" value="{$ca.s_first_name.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_first_name.id}" />
                </div>
                {if eq(ezini( 'Settings', 'MI', 'xrowecommerce.ini' ), 'enabled' )}
                <div class="block">
                    <label>{'MI'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_mi.id}" size="2" value="{$ca.s_mi.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_mi.id}" />
                </div>
                {/if}
                <div class="block">
                    <label><span class="required">*</span>{'Last name'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_last_name.id}" value="{$ca.s_last_name.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_last_name.id}" />
                </div>
                <div class="block">
                    <label><span class="required">*</span>{'Address 1'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_address1.id}" size="20" value="{$ca.s_address1.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_address1.id}" />
                </div>
                <div class="block">
                    <label>{'Address 2'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_address2.id}" size="20" value="{$ca.s_address2.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_address2.id}" />
                </div>
                <div class="block">
                    <label><span class="required">*</span>{'Zip / Postcode'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="{$castring}{$ca.s_zip_code.id}" value="{$ca.s_zip_code.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_zip_code.id}" />
                </div>
                <div class="block">
                    <label><span class="required">*</span>{'City / Town'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="{$castring}{$ca.s_city.id}" value="{$ca.s_city.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_city.id}" />
                </div>
                {if eq(ezini( 'Settings', 'State', 'xrowecommerce.ini' ), 'enabled' )}
                <div>
                    <label>{'State / Province'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_state.id}" />
                    {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.s_state}
                </div>
                {/if}
                <div class="break"></div>
                <div class="block">
                    <label><span class="required">*</span>{'Country'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    {*TODO we MAY has to set a default country*}
                    {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.s_country}
                </div>
                <div class="break"></div>
                <div class="block">
                    <label><span class="required">*</span>{'Phone'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="{$castring}{$ca.s_phone.id}" value="{$ca.s_phone.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_phone.id}" />
                </div>
                {if eq(ezini( 'Settings', 'Fax', 'xrowecommerce.ini' ), 'enabled' )}
                    <div class="block">
                    <label>{'Fax'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="{$castring}{$ca.s_fax.id}" value="{$ca.s_fax.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_fax.id}" />
                </div>
                {/if}
                <div class="block">
                    <label><span class="required">*</span>{'E-mail'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="ContentObjectAttribute_data_text_{$ca.s_email.id}" value="{$ca.s_email.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_email.id}" />
                </div>
                <div class="break"></div>
                {include uri="design:content/edit_attribute_user_custom_right.tpl"}
            </div>
        </div>
    {if $ca.creditcard}
        <div class="block">
            <h3>{'Credit card Information'|i18n('extension/xrowecommerce')}</h3>
            <p>
                {'Your credit card information is needed, if you want to make use of our recurring order option.'|i18n('extension/xrowecommerce')}
            </p>
            <label{section show=$ca.creditcard.has_validation_error} class="validation-error"{/section}>{$ca.creditcard.contentclass_attribute.name|wash}</label>
            <div class="labelbreak" />
                <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.creditcard.id}" />
                {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.creditcard}
            </div>
        </div>
    {/if}

</div>
<div class="buttonblock">
    <input class="left-arrow2" type="submit" name="DiscardButton" value="{'Cancel'|i18n('extension/xrowecommerce')}" />
    <input class="right-arrow" type="submit" name="PublishButton" value="{'Save'|i18n('extension/xrowecommerce', 'Save Button' )}" />
    <input type="hidden" name="DiscardConfirm" value="0" />
    <input type="hidden" name="RedirectURI" value="{concat('/user/edit/',$object.id)}" />
    <input type="hidden" name="RedirectIfDiscarded" value="{concat('/user/edit/',$object.id)}" />
</div>

{* right frame end *}

<script type="text/javascript">
function change()
{ldelim}
if (document.getElementById( 'ContentObjectAttribute_data_boolean_{$ca.shippingaddress.id}' ).checked)
    {ldelim}
    document.getElementById( 'shippinginfo' ).style.display = 'none';
    {rdelim}
    else
    {ldelim}
            document.getElementById( 'shippinginfo' ).style.display = 'block';
    {rdelim}
{rdelim}
</script>

</div>