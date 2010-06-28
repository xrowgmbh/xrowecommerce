{def $current_user=fetch( 'user', 'current_user' )}
{def $ca=$content_attributes_data_map
     $castring="ContentObjectAttribute_ezstring_data_text_"}

<div class="shop-basket user_register">
    <form enctype="multipart/form-data"  action={"/user/register/"|ezurl} method="post" name="Register" id="register">

        <h1>{"Register user"|i18n("extension/xrowecommerce")}</h1>

        {section show=and( and( is_set( $checkErrNodeId ), $checkErrNodeId ), eq( $checkErrNodeId, true ) )}
         <div class="message-error">
            <h3><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {$errMsg}</h3>
         </div>
        {/section}
        {section show=$validation.processed}
        {if $validation.attributes|count()|gt(0)}
            <div class="warning">
                <h3>{"Input did not validate"|i18n("extension/xrowecommerce")}</h3>
        {/if}
            {section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
                <ul>
                    <li><strong>{$UnvalidatedAttributes:item.name}:</strong> {$UnvalidatedAttributes:item.description}</li>
                </ul>
                <div class="break"></div>
            {section-else}
            <div class="feedback">
                <h3>{"Input was stored successfully"|i18n("extension/xrowecommerce")}</h3>
            </div>
            {/section}
        {if $validation.attributes|count()|gt(0)}
        </div>
        {/if}
        {/section}

        <div class="accountinfo">
            <h3>{'Account Information'|i18n('extension/xrowecommerce')}</h3>
            <p>{"Enter your email address, login and password."|i18n("extension/xrowecommerce")}</p>
            <p>
                <span class="required">* {'Required field'|i18n("extension/xrowecommerce")}</span>
            </p>
            <div class="block username">
                <label><span class="required">*</span>{"Username"|i18n("extension/xrowecommerce")}</label>
                <div class="labelbreak"></div>
                <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.user_account.id}" />
                <input type="text" name="ContentObjectAttribute_data_user_login_{$ca.user_account.id}" value="{$ca.user_account.content.login}" size="20" />
            </div>
            <div class="break"></div>
            <div class="block email">
                <label><span class="required">*</span>{'E-mail'|i18n('extension/xrowecommerce')}</label>
                <div class="labelbreak"></div>
                <input class="box" type="text" name="ContentObjectAttribute_data_user_email_{$ca.user_account.id}" value="{$ca.user_account.content.email|wash()}" size="20"  />
                <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.user_account.id}" />
            </div>
            <div class="break"></div>
            <div class="block password">
                <label><span class="required">*</span>{'Password'|i18n('extension/xrowecommerce')}</label>
                <div class="labelbreak"></div>
                <input class="box" type="password" name="ContentObjectAttribute_data_user_password_{$ca.user_account.id}" value="{section show=$ca.user_account.content.original_password}{$ca.user_account.content.original_password}{section-else}{section show=$ca.user_account.content.has_stored_login}_ezpassword{/section}{/section}" size="20" />
            </div>
            <div class="break"></div>
            <div class="block password_confirm">
                <label><span class="required">*</span>{'Confirm Password'|i18n('extension/xrowecommerce')}</label>
                <div class="labelbreak"></div>
                <input class="box" type="password" name="ContentObjectAttribute_data_user_password_confirm_{$ca.user_account.id}" value="{section show=$ca.user_account.content.original_password_confirm}{$ca.user_account.content.original_password_confirm}{section-else}{section show=$ca.user_account.content.has_stored_login}_ezpassword{/section}{/section}" size="20" />
            </div>
            <div class="break"></div>
        </div>

        <div class="billing_shipping">
            <div class="billing">
                <h3>{'Billing Information'|i18n('extension/xrowecommerce')}</h3>
                <p>{'Please enter your billing address exactly as it appears on your credit card statement.'|i18n('extension/xrowecommerce')}</p>
                <p><span class="required">* {'Required field'|i18n('extension/xrowecommerce')}</span></p>

                <div class="block">
                    {if ezini( 'Fields', 'company_name', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block company_name">
                        <label>{if ezini( 'Fields', 'company_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Company name'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.company_name.id}" value="{$ca.company_name.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.company_name.id}" />
                    </div>
                    {/if}
                    {if ezini( 'Fields', 'company_additional', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block company_additional">
                        <label>{if ezini( 'Fields', 'company_additional', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Form of company'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.company_additional.id}" value="{$ca.company_additional.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.company_additional.id}" />
                    </div>
                    {/if}
                    {if ezini( 'Fields', 'tax_id', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block tax_id">
                        <label>{if ezini( 'Fields', 'tax_id', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Tax ID'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.tax_id.id}" value="{$ca.tax_id.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.tax_id.id}" />
                    </div>
                    {/if}

                    <div class="block first_name">
                        <label>{if ezini( 'Fields', 'first_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'First name'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.first_name.id}" value="{$ca.first_name.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.first_name.id}" />
                    </div>
                    {if ezini( 'Fields', 'mi', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block mi">
                        <label>{'MI'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.mi.id}" size="2" value="{$ca.mi.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.mi.id}" />
                    </div>
                    {/if}
                    <div class="block last_name">
                        <label>{if ezini( 'Fields', 'last_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Last name'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.last_name.id}" value="{$ca.last_name.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.last_name.id}" />
                    </div>
                    <div class="block address1">
                        <label>{if ezini( 'Fields', 'address1', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Address 1'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.address1.id}" size="20" value="{$ca.address1.content|wash()}" title="{'Apartment, suite, unit, building, floor, etc.'|i18n('extension/xrowecommerce')}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.address1.id}" />
                    </div>
                    {if ezini( 'Fields', 'address2', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block address2">
                        <label>{if ezini( 'Fields', 'address2', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Address 2'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.address2.id}" size="20" value="{$ca.address2.content|wash()}" title="{'Street address, P.O. box, company name, c/o'|i18n('extension/xrowecommerce')}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.address2.id}" />
                    </div>
                    {/if}
                    {if ezini( 'Fields', 'zip', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block zip">
                        <label>{if ezini( 'Fields', 'zip', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Zip / Postcode'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="{$castring}{$ca.zip_code.id}" value="{$ca.zip_code.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.zip_code.id}" />
                    </div>
                    <div class="break"></div>
                    {/if}
                    {if ezini( 'Fields', 'city', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block city">
                        <label>{if ezini( 'Fields', 'city', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'City / Town'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="{$castring}{$ca.city.id}" value="{$ca.city.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.city.id}" />
                    </div>
                    {/if}
                    <div class="break"></div>
                    {undef $state}
                    {if ezini( 'Fields', 'country', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="country">
                        <label>{if ezini( 'Fields', 'country', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Country'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        {def $country = $ca.country.content.value}

                        {def $country_default_ini=ezini( 'ShopAccountHandlerDefaults', 'DefaultCountryCode' )}
                        {def $country_default=''}
                        {def $country_list_item_code=''}
                        {def $countries=fetch( 'content', 'country_list')}
                        <select name="ContentObjectAttribute_country_{$ca.country.id}[]" id="country">
                            <option value="">&nbsp;</option>
                            {def $alpha_2 = ''}
                            {foreach $countries as $key => $current_country}
                                 {set $alpha_2 = $current_country.Alpha2}
                                 {if $country|ne( '' )}
                                    {if $country|is_array|not}
                                        {* Backwards compatability *}
                                        <option {if $country|eq( $current_country.Name )}selected="selected"{/if} value="{$alpha_2}">{$current_country.Name}</option>
                                    {else}
                                        <option {if is_set( $country.$alpha_2 )}selected="selected"{/if} value="{$alpha_2}">{$current_country.Name}</option>
                                    {/if}
                                 {else}
                                        <option {if is_set( $class_content.default_countries.$alpha_2 )}selected="selected"{/if} value="{$alpha_2}">{$current_country.Name}</option>
                                 {/if}
                            {/foreach}
                        </select>
                    </div>
                    {/if}
                    {if ezini( 'Fields', 'state', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block state">
                        <label>{if ezini( 'Fields', 'state', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'State / Province'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.state.id}" />
                        {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.state}
                    </div>
                    {/if}
                    <div class="break"></div>
                    {if ezini( 'Fields', 'phone', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block phone">
                        <label>{if ezini( 'Fields', 'phone', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Phone'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input class="box" type="text" name="{$castring}{$ca.phone.id}" value="{$ca.phone.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.phone.id}" />
                    </div>
                    {/if}
                    {if ezini( 'Fields', 'fax', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block fax">
                        <label>{if ezini( 'Fields', 'fax', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Fax'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input class="box" type="text" name="{$castring}{$ca.fax.id}" value="{$ca.fax.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.fax.id}" />
                    </div>
                    {/if}
                </div>
            </div>
            <div class="shipping">
                <h3>{'Shipping Information'|i18n('extension/xrowecommerce')}</h3>
                <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.shippingaddress.id}" />
                <label onclick="change();" class="shipping-checkbox" for="shipping-checkbox">
                    <input onlick="change();" class="shipping-checkbox" id="shipping-checkbox"  name="ContentObjectAttribute_data_boolean_{$ca.shippingaddress.id}" value="" type="checkbox" {$ca.shippingaddress.data_int|choose( '', 'checked="checked"' )} />
                    {'My billing and shipping addresses are identical.'|i18n('extension/xrowecommerce')}
                </label>

                <div class="block" id="shippinginfo">
                    {if ezini( 'Fields', 's_company_name', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_company_name">
                        <label>{if ezini( 'Fields', 's_company_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Company name'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_company_name.id}" value="{$ca.s_company_name.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_company_name.id}" />
                    </div>
                    {/if}
                    {if ezini( 'Fields', 's_company_additional', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_company_additional">
                        <label>{if ezini( 'Fields', 's_company_additional', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Form of company'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_company_additional.id}" value="{$ca.s_company_additional.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_company_additional.id}" />
                    </div>
                    {/if}
                    {if ezini( 'Fields', 's_first_name', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_first_name">
                        <label>{if ezini( 'Fields', 's_first_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'First name'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_first_name.id}" value="{$ca.s_first_name.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_first_name.id}" />
                    </div>
                    {/if}
                    {if ezini( 'Fields', 's_mi', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_mi">
                        <label>{'MI'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_mi.id}" size="2" value="{$ca.s_mi.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_mi.id}" />
                    </div>
                    {/if}
                    {if ezini( 'Fields', 's_last_name', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_last_name">
                        <label>{if ezini( 'Fields', 's_last_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Last name'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_last_name.id}" value="{$ca.s_last_name.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_last_name.id}" />
                    </div>
                    {/if}
                    <div class="block s_address1">
                        <label>{if ezini( 'Fields', 's_address1', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Address 1'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_address1.id}" size="20" value="{$ca.s_address1.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_address1.id}" />
                    </div>
                    {if ezini( 'Fields', 's_address2', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_address2">
                        <label>{if ezini( 'Fields', 's_address2', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Address 2'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_address2.id}" size="20" value="{$ca.s_address2.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_address2.id}" />
                    </div>
                    {/if}
                    <div class="block s_zip">
                        <label>{if ezini( 'Fields', 's_zip', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Zip / Postcode'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="{$castring}{$ca.s_zip_code.id}" value="{$ca.s_zip_code.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_zip_code.id}" />
                    </div>
                    <div class="break"></div>
                    <div class="block s_city">
                        <label>{if ezini( 'Fields', 's_city', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'City / Town'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="{$castring}{$ca.s_city.id}" value="{$ca.s_city.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_city.id}" />
                    </div>
                    <div class="break"></div>
                    {if ezini( 'Fields', 's_country', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="s_country">
                        <label>{if ezini( 'Fields', 's_country', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Country'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        {def $s_country = $ca.s_country.content.value}
                        
                        {def $s_countries=fetch( 'content', 'country_list')
                             $s_country_list_item_code=''}
                        <input type="hidden" name="sik_country" id="sik_country" value="USA" />
                        <select name="ContentObjectAttribute_country_{$ca.s_country.id}[]" id="scountry">
                            <option value="">&nbsp;</option>
                            {foreach $s_countries as $key => $s_current_country}
                                 {set $alpha_2 = $s_current_country.Alpha2}
                                 {if $s_country|ne( '' )}
                                    {if $s_country|is_array|not}
                                        {* Backwards compatability *}
                                        <option {if $s_country|eq( $s_current_country.Name )}selected="selected"{/if} value="{$alpha_2}">{$s_current_country.Name}</option>
                                    {else}
                                        <option {if is_set( $s_country.$alpha_2 )}selected="selected"{/if} value="{$alpha_2}">{$s_current_country.Name}</option>
                                    {/if}
                                 {else}
                                        <option {if is_set( $class_content.default_countries.$alpha_2 )}selected="selected"{/if} value="{$alpha_2}">{$s_current_country.Name}</option>
                                 {/if}
                            {/foreach}                            
                        </select>
                    </div>
                    {/if}
                    {if ezini( 'Fields', 's_state', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="s_state">
                        <label>{if ezini( 'Fields', 's_state', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'State / Province'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_state.id}" />
                        {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.s_state}
                    </div>
                    {/if}
                    <div class="break"></div>
                    {if ezini( 'Fields', 's_phone', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_phone">
                        <label>{if ezini( 'Fields', 's_phone', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Phone'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input class="box" type="text" name="{$castring}{$ca.s_phone.id}" value="{$ca.s_phone.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_phone.id}" />
                    </div>
                    {/if}
                    {if ezini( 'Fields', 's_fax', 'xrowecommerce.ini' ).enabled|eq("true")}
                        <div class="block s_fax">
                            <label>{if ezini( 'Fields', 's_fax', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Fax'|i18n('extension/xrowecommerce')}</label>
                            <div class="labelbreak"></div>
                            <input class="box" type="text" name="{$castring}{$ca.s_fax.id}" value="{$ca.s_fax.content|wash()}" />
                            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_fax.id}" />
                        </div>
                    {/if}
                    {if ezini( 'Fields', 's_email', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_email">
                        <label>{if ezini( 'Fields', 's_email', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'E-mail'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input class="box" type="text" name="ContentObjectAttribute_data_text_{$ca.s_email.id}" value="{$ca.s_email.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_email.id}" />
                    </div>
                    {/if}
                    <div class="break"></div>
                </div>
            </div>
        </div>
            <div class="break"></div>
            <div class="buttonblock">
                <input class="button" type="submit" name="DCancelButton" value="{'Cancel'|i18n('extension/xrowecommerce')}" />
                <input class="button" type="submit" name="PublishButton" value="{'Continue'|i18n('extension/xrowecommerce')}" />
            </div>
    </form>
</div>