{def $current_user = fetch( 'user', 'current_user' )
     $ca = $content_attributes_data_map
     $countries = fetch( 'content', 'country_list')
     $castring = 'ContentObjectAttribute'
     $country_default_ini = ezini( 'ShopAccountHandlerDefaults', 'DefaultCountryCode', 'xrowecommerce.ini' )}
{*ezhttp()|attribute(show)*}
<div class="shop-basket user_register">
    <form enctype="multipart/form-data"  action={"/user/register/"|ezurl} method="post" name="Register" id="register">

        <h1>{"Register user"|i18n("extension/xrowecommerce")}</h1>

        {section show=and( and( is_set( $checkErrNodeId ), $checkErrNodeId ), eq( $checkErrNodeId, true ) )}
         <div class="message-error">
            <h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {$errMsg}</h2>
         </div>
        {/section}
        {section show=$validation.processed}
        {if $validation.attributes|count()|gt(0)}
            <div class="warning">
                <h2>{"Input did not validate"|i18n("extension/xrowecommerce")}</h2>
        {/if}
            {section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
                <ul>
                    <li><strong>{$UnvalidatedAttributes:item.name}:</strong> {$UnvalidatedAttributes:item.description}</li>
                </ul>
                <div class="break"></div>
            {section-else}
            <div class="feedback">
                <h2>{'Input was stored successfully'|i18n( 'extension/xrowecommerce' )}</h2>
            </div>
            {/section}
        {if $validation.attributes|count()|gt(0)}
            </div>
        {/if}
        {/section}

        <div class="accountinfo">
            <h2>{'Account Information'|i18n( 'extension/xrowecommerce' )}</h2>
            <p>{'Enter your email address, login and password.'|i18n( 'extension/xrowecommerce' )}</p>
            <p>
                <span class="required">* {'Required field'|i18n("extension/xrowecommerce")}</span>
            </p>
            <div class="block username">
                <label><span class="required">*</span>{'Username'|i18n('extension/xrowecommerce')}</label>
                <div class="labelbreak"></div>
                <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.user_account.id}" />
                <input type="text" name="ContentObjectAttribute_data_user_login_{$ca.user_account.id}" value="{$ca.user_account.content.login}" size="20" />
            </div>
            <div class="break"></div>
            <div class="block email">
                <label><span class="required">*</span>{'E-mail'|i18n('extension/xrowecommerce')}</label>
                <div class="labelbreak"></div>
                <input type="text" id="email" class="box" name="ContentObjectAttribute_data_user_email_{$ca.user_account.id}" value="{$ca.user_account.content.email|wash()}" size="20"  />
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
                <h2>{'Billing Information'|i18n('extension/xrowecommerce')}</h2>
                <p>{'Please enter your billing address exactly as it appears on your credit card statement.'|i18n('extension/xrowecommerce')}</p>
                <p><span class="required">* {'Required field'|i18n('extension/xrowecommerce')}</span></p>
                <div class="address-selection">
                    <div class="comp">
                        <label for="company">
                        <input id="company" type="radio" value="company" name="company" />
                        {'Company'|i18n('extension/xrowecommerce')}
                        </label>
                    </div>
                    <div class="home">
                        <label for="home">
                        <input checked="checked" id="home" type="radio" value="company" name="company" />
                        {'Private person'|i18n('extension/xrowecommerce')}
                        </label>
                    </div>
                </div>
                <div class="block">
                    <div class="block company">
                        {if ezini( 'Fields', 'company_name', 'xrowecommerce.ini' ).enabled|eq("true")}
                        <div class="block company_name">
                            <label>{if ezini( 'Fields', 'company_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Company name'|i18n('extension/xrowecommerce')}</label>
                            <div class="labelbreak"></div>
                            <input type="text" id="company_name" name="{$castring}_ezstring_data_text_{$ca.company_name.id}" value="{$ca.company_name.content|wash()}" />
                            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.company_name.id}" />
                        </div>
                        {/if}
                        {if ezini( 'Fields', 'company_additional', 'xrowecommerce.ini' ).enabled|eq("true")}
                        <div class="block company_additional">
                            <label>{if ezini( 'Fields', 'company_additional', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Company additional information'|i18n('extension/xrowecommerce')}</label>
                            <div class="labelbreak"></div>
                            <input type="text" id="company_additional" name="{$castring}_ezstring_data_text_{$ca.company_additional.id}" value="{$ca.company_additional.content|wash()}" />
                            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.company_additional.id}" />
                        </div>
                        {/if}
                        {if ezini( 'Fields', 'tax_id', 'xrowecommerce.ini' ).enabled|eq("true")}
                        <div class="block tax_id">
                            <label>{if ezini( 'Fields', 'tax_id', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Tax ID'|i18n('extension/xrowecommerce')}</label>
                            <div class="labelbreak"></div>
                            <input type="text" id="tax_id" name="{$castring}_ezstring_data_text_{$ca.tax_id.id}" value="{$ca.tax_id.content|wash()}" />
                            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.tax_id.id}" />
                        </div>
                        {/if}
                    </div>

                    <div class="block title">
                        <label>{if ezini( 'Fields', 'title', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Title'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="title" name="{$castring}_ezstring_data_text_{$ca.title.id}" value="{$ca.title.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.title.id}" />
                    </div>

                    <div class="block first_name">
                        <label>{if ezini( 'Fields', 'first_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'First name'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="first_name" name="{$castring}_ezstring_data_text_{$ca.first_name.id}" value="{$ca.first_name.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.first_name.id}" />
                    </div>

                    {if ezini( 'Fields', 'mi', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block mi">
                        <label>{'MI'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="mi" name="{$castring}_ezstring_data_text_{$ca.mi.id}" size="2" value="{$ca.mi.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.mi.id}" />
                    </div>
                    {/if}

                    <div class="block last_name">
                        <label>{if ezini( 'Fields', 'last_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Last name'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="last_name" name="{$castring}_ezstring_data_text_{$ca.last_name.id}" value="{$ca.last_name.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.last_name.id}" />
                    </div>

                    <div class="block address1">
                        <label>{if ezini( 'Fields', 'address1', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Address 1'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="address1" class="box" name="{$castring}_ezstring_data_text_{$ca.address1.id}" size="20" value="{$ca.address1.content|wash()}" title="{'Apartment, suite, unit, building, floor, etc.'|i18n('extension/xrowecommerce')}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.address1.id}" />
                    </div>

                    {if ezini( 'Fields', 'address2', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block address2">
                        <label>{if ezini( 'Fields', 'address2', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Address 2'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="address2" class="box" name="{$castring}_ezstring_data_text_{$ca.address2.id}" size="20" value="{$ca.address2.content|wash()}" title="{'Street address, P.O. box, company name, c/o'|i18n('extension/xrowecommerce')}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.address2.id}" />
                    </div>
                    {/if}
                    {if ezini( 'Fields', 'city', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block city">
                        <label>{if ezini( 'Fields', 'city', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'City / Town'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="city" name="{$castring}_ezstring_data_text_{$ca.city.id}" value="{$ca.city.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.city.id}" />
                    </div>
                    {/if}
                    {if ezini( 'Fields', 'zip', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block zip">
                        <label>{if ezini( 'Fields', 'zip', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Zip / Postcode'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="zip" name="{$castring}_ezstring_data_text_{$ca.zip_code.id}" value="{$ca.zip_code.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.zip_code.id}" />
                    </div>
                    <div class="break"></div>
                    {/if}
                    {if ezini( 'Fields', 'country', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block country">
                        <label>{if ezini( 'Fields', 'country', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Country'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        {def $country = $ca.country.content.value}
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.country.id}" />
                        <select name="{$castring}_country_{$ca.country.id}[]" id="country">
                        {foreach $countries as $country_list_item}
                            {if ezini( 'Settings', 'UseAlpha3', 'xrowecommerce.ini' )|eq('true')}
                                {if $country|ne( '' )}
                                {* Backwards compatability *}
                                    <option value="{$country_list_item.Alpha3}" {if and( $country|ne(''), eq( $country, $country_list_item.Alpha3 ))} selected="selected"{/if}>{$country_list_item.Name|wash}</option>
                                {else}
                                    <option {if $country_default_ini|eq( $country_list_item.Alpha3 )}selected="selected"{/if} value="{$country_list_item.Alpha3}">{$country_list_item.Name|wash}</option>
                                {/if}
                            {else}
                                {if $country|ne( '' )}
                                {* Backwards compatability *}
                                    <option value="{$country_list_item.Alpha2}" {if and( $country|ne(''), eq( $country, $country_list_item.Alpha2 ))} selected="selected"{/if}>{$country_list_item.Name|wash}</option>
                                {else}
                                    <option {if $country_default_ini|eq( $country_list_item.Alpha2 )}selected="selected"{/if} value="{$country_list_item.Alpha2}">{$country_list_item.Name|wash}</option>
                                {/if}
                            {/if}
                        {/foreach}
                        </select>
                    </div>
                    {/if}

                    {if ezini( 'Fields', 'state', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="state block{if is_set($fields.state.errors)} error{/if}">
                        <label>{'State / Province'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'state', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.state.id}" />
                        <input type="hidden" id="hidden_state" value="{$ca.state.content}" />
                        <select name="{$castring}_ezstring_data_text_{$ca.state.id}" id="state">
                            <option>&nbsp;</option>
                            {if is_set($states)}
                            {foreach $states as $key => $state_item}
                            <option value="{$key}">{$state_item|wash}</option>
                            {/foreach}
                            {/if}
                        </select>
                    </div>
                    <div class="break"></div>
                    {else}
                        <input type="hidden" name="state" id="state" value="">
                    {/if}

                    {if ezini( 'Fields', 'phone', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block phone">
                        <label>{if ezini( 'Fields', 'phone', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Phone'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="phone" class="box" name="{$castring}_ezstring_data_text_{$ca.phone.id}" value="{$ca.phone.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.phone.id}" />
                    </div>
                    {/if}

                    {if ezini( 'Fields', 'fax', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block fax">
                        <label>{if ezini( 'Fields', 'fax', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Fax'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="fax" class="box" name="{$castring}_ezstring_data_text_{$ca.fax.id}" value="{$ca.fax.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.fax.id}" />
                    </div>
                    {/if}
                </div>
            </div>
            <div class="shipping">
                <h2>{'Shipping Information'|i18n('extension/xrowecommerce')}</h2>
                <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.shippingaddress.id}" />
                <label onclick="change();" class="shipping-checkbox" for="shipping-checkbox">
                    <input onlick="change();" class="shipping-checkbox" id="shipping-checkbox"  name="ContentObjectAttribute_data_boolean_{$ca.shippingaddress.id}" value="" type="checkbox" {$ca.shippingaddress.data_int|choose( '', 'checked="checked"' )} />
                    {'My billing and shipping addresses are identical.'|i18n('extension/xrowecommerce')}
                </label>

                <div class="block" id="shippinginfo">
                    <div class="s_address-selection">
                        <div class="s_comp">
                            <label for="s_company">
                            <input id="s_company" type="radio" value="company" name="s_company" />
                            {'Company'|i18n('extension/xrowecommerce')}
                            </label>
                        </div>
                        <div class="s_home">
                            <label for="s_home">
                            <input checked="checked" id="s_home" type="radio" value="company" name="s_company" />
                            {'Private person'|i18n('extension/xrowecommerce')}
                            </label>
                        </div>
                    </div>
                    <div class="s_company">
                        {if ezini( 'Fields', 's_company_name', 'xrowecommerce.ini' ).enabled|eq("true")}
                        <div class="block s_company_name">
                            <label>{if ezini( 'Fields', 's_company_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Company name'|i18n('extension/xrowecommerce')}</label>
                            <div class="labelbreak"></div>
                            <input type="text" id="s_company_name" name="{$castring}_ezstring_data_text_{$ca.s_company_name.id}" value="{$ca.s_company_name.content|wash()}" />
                            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_company_name.id}" />
                        </div>
                        {/if}

                        {if ezini( 'Fields', 's_company_additional', 'xrowecommerce.ini' ).enabled|eq("true")}
                        <div class="block s_company_additional">
                            <label>{if ezini( 'Fields', 's_company_additional', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Form of company'|i18n('extension/xrowecommerce')}</label>
                            <div class="labelbreak"></div>
                            <input type="text" id="s_company_additional" name="{$castring}_ezstring_data_text_{$ca.s_company_additional.id}" value="{$ca.s_company_additional.content|wash()}" />
                            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_company_additional.id}" />
                        </div>
                        {/if}
                    </div>

                    {if ezini( 'Fields', 's_title', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_title">
                        <label>{if ezini( 'Fields', 's_title', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Title'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="s_title" name="{$castring}_ezstring_data_text_{$ca.s_title.id}" value="{$ca.s_title.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_title.id}" />
                    </div>
                    {/if}

                    {if ezini( 'Fields', 's_first_name', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_first_name">
                        <label>{if ezini( 'Fields', 's_first_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'First name'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="s_first_name" name="{$castring}_ezstring_data_text_{$ca.s_first_name.id}" value="{$ca.s_first_name.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_first_name.id}" />
                    </div>
                    {/if}

                    {if ezini( 'Fields', 's_mi', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_mi">
                        <label>{'MI'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="s_mi" name="{$castring}_ezstring_data_text_{$ca.s_mi.id}" size="2" value="{$ca.s_mi.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_mi.id}" />
                    </div>
                    {/if}

                    {if ezini( 'Fields', 's_last_name', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_last_name">
                        <label>{if ezini( 'Fields', 's_last_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Last name'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="s_last_name" name="{$castring}_ezstring_data_text_{$ca.s_last_name.id}" value="{$ca.s_last_name.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_last_name.id}" />
                    </div>
                    <div class="break"></div>
                    {/if}

                    <div class="block s_address1">
                        <label>{if ezini( 'Fields', 's_address1', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Address 1'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="s_address1" class="box" name="{$castring}_ezstring_data_text_{$ca.s_address1.id}" size="20" value="{$ca.s_address1.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_address1.id}" />
                    </div>
                    <div class="break"></div>

                    {if ezini( 'Fields', 's_address2', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_address2">
                        <label>{if ezini( 'Fields', 's_address2', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Address 2'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="s_address2" class="box" name="{$castring}_ezstring_data_text_{$ca.s_address2.id}" size="20" value="{$ca.s_address2.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_address2.id}" />
                    </div>
                    <div class="break"></div>
                    {/if}

                    <div class="block s_city">
                        <label>{if ezini( 'Fields', 's_city', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'City / Town'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="s_city" name="{$castring}_ezstring_data_text_{$ca.s_city.id}" value="{$ca.s_city.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_city.id}" />
                    </div>
                    <div class="break"></div>

                    <div class="block s_zip">
                        <label>{if ezini( 'Fields', 's_zip', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Zip / Postcode'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="s_zip" name="{$castring}_ezstring_data_text_{$ca.s_zip_code.id}" value="{$ca.s_zip_code.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_zip_code.id}" />
                    </div>
                    <div class="break"></div>

                    {if ezini( 'Fields', 's_country', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_country">
                        <label>{if ezini( 'Fields', 's_country', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Country'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        {def $s_country = $ca.s_country.content.value}
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_country.id}" />
                        <select name="{$castring}_country_{$ca.s_country.id}" id="s_country">
                        {foreach $countries as $country_list_item}
                            {if ezini( 'Settings', 'UseAlpha3', 'xrowecommerce.ini' )|eq('true')}
                                {if $s_country|ne( '' )}
                                {* Backwards compatability *}
                                    <option value="{$country_list_item.Alpha3}" {if and( $s_country|ne(''), eq( $s_country, $country_list_item.Alpha3 ))} selected="selected"{/if}>{$country_list_item.Name|wash}</option>
                                {else}
                                    <option {if $country_default_ini|eq( $country_list_item.Alpha3 )}selected="selected"{/if} value="{$country_list_item.Alpha3}">{$country_list_item.Name|wash}</option>
                                {/if}
                            {else}
                                {if $s_country|ne( '' )}
                                {* Backwards compatability *}
                                    <option value="{$country_list_item.Alpha2}" {if and( $s_country|ne(''), eq( $s_country, $country_list_item.Alpha2 ))} selected="selected"{/if}>{$country_list_item.Name|wash}</option>
                                {else}
                                    <option {if $country_default_ini|eq( $country_list_item.Alpha2 )}selected="selected"{/if} value="{$country_list_item.Alpha2}">{$country_list_item.Name|wash}</option>
                                {/if}
                            {/if}
                        {/foreach}
                        </select>
                    </div>
                    <div class="break"></div>
                    {/if}

                    {if ezini( 'Fields', 's_state', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="state block{if is_set($fields.s_state.errors)} error{/if}">
                        <label>{'State / Province'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 's_state', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_state.id}" />
                        <input type="hidden" id="hidden_s_state" value="{$ca.s_state.content}" />
                        <select name="{$castring}_ezstring_data_text_{$ca.s_state.id}" id="s_state">
                            <option>&nbsp;</option>
                            {if is_set($s_state)}
                            {foreach $s_state as $key => $state_item}
                            <option value="{$key}">{$state_item|wash}</option>
                            {/foreach}
                            {/if}
                        </select>
                    </div>
                    <div class="break"></div>
                    {else}
                    <input type="hidden" name="state" id="s_state" value="">
                    {/if}

                    {if ezini( 'Fields', 's_phone', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_phone">
                        <label>{if ezini( 'Fields', 's_phone', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Phone'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="s_phone" class="box" name="{$castring}_ezstring_data_text_{$ca.s_phone.id}" value="{$ca.s_phone.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_phone.id}" />
                    </div>
                    <div class="break"></div>
                    {/if}

                    {if ezini( 'Fields', 's_fax', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_fax">
                        <label>{if ezini( 'Fields', 's_fax', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'Fax'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="s_fax" class="box" name="{$castring}_ezstring_data_text_{$ca.s_fax.id}" value="{$ca.s_fax.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_fax.id}" />
                    </div>
                    <div class="break"></div>
                    {/if}

                    {if ezini( 'Fields', 's_email', 'xrowecommerce.ini' ).enabled|eq("true")}
                    <div class="block s_email">
                        <label>{if ezini( 'Fields', 's_email', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}{'E-mail'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="s_email" class="box" name="{$castring}_ezstring_data_text_{$ca.s_email.id}" value="{$ca.s_email.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_email.id}" />
                    </div>
                    <div class="break"></div>
                    {/if}

                </div>
            </div>
        </div>
            <div class="break"></div>
            <div class="buttonblock">
                <input class="button left-arrow" type="submit" name="DCancelButton" value="{'Cancel'|i18n('extension/xrowecommerce')}" />
                <input class="button right-arrow" type="submit" name="PublishButton" value="{'Continue'|i18n('extension/xrowecommerce')}" />
            </div>
    </form>
</div>
{*
        {literal}
            <script type="text/javascript">
                // removed because it updates the values, if we already have data for shipping.
                // Idea only update if we have no data fopr shipping and data for billing.
                // changeShipping();
                updateShipping();
            </script>
        {/literal}

*}