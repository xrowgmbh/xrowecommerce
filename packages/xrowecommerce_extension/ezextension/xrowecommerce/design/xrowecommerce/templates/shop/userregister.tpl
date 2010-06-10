<div class="shop shop-userregister">

    <form method="post" action={"xrowecommerce/userregister/"|ezurl} name='register' >
        <input type="submit" class="hide" style="display: hide;" name="StoreButton" value="{'Continue'|i18n('extension/xrowecommerce')}" />
        <h1>{'My Account'|i18n('extension/xrowecommerce')}</h1>
        
        {include uri="design:shop/basket_navigator.tpl" step='2'}
        {include uri="design:shop/userregister_preface.tpl"}    
        
        {def $error_count = false}
        {foreach $fields as $error}
        {if is_set($error.errors)}
            {foreach $error.errors as $items}
                {if $items|count()|gt(0)}
                  {set $error_count = "true"}
                {/if}
            {/foreach}
        {/if}
        {/foreach}
        {if $error_count|eq("true")}
            <div class="warning">
                <h2>{'Validation errors'|i18n('extension/xrowecommerce')}</h2>
                <ul>
                {foreach $fields as $error}
                {if is_set($error.errors)}
                    {foreach $error.errors as $items}
                        <li>{$items}</li>
                    {/foreach}
                 {/if}
                {/foreach}
                </ul>
            </div>
        {/if}
        {undef $error_count}
        <div>
            <div class="billing">
                <h3>{'Billing Information'|i18n('extension/xrowecommerce')}</h3>
                <p><span class="required">* <i>{'Required field'|i18n('extension/xrowecommerce')}</i></span></p>
                <div class="labelbreak"></div>
                
                {if $fields.company_name.enabled}
                    <div class="ur_companyname block{if is_set($fields.company_name.errors)} error{/if}">
                        <label>{'Company name'|i18n('extension/xrowecommerce')}{if $fields.company_name.required}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="company_name" id="company_name" value="{$company_name|wash}" />
                    </div>
                {/if}
                {if $fields.company_additional.enabled}
                    <div class="ur_company_additional block{if is_set($fields.company_additional.errors)} error{/if}">
                        <label>{'Company additional information'|i18n('extension/xrowecommerce')}{if $fields.company_additional.required}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="company_additional" id="company_additional" value="{$company_additional|wash}" />
                    </div>
                {/if}
                {if $fields.tax_id.enabled}
                    <div class="ur_taxid block{if is_set($fields.tax_id.errors)} error{/if}">
                        <label>{'Tax ID'|i18n('extension/xrowecommerce')}{if $fields.tax_id.required}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="tax_id" {if and( $tax_id, $tax_id_valid|eq('2') )} readonly="readonly" disabled{/if} value="{$tax_id|wash}" />
                    </div>
                {/if}
                {if $fields.first_name.enabled}
                    <div class="ur_firstname block{if is_set($fields.first_name.errors)} error{/if}">
                        <label>{'First name'|i18n('extension/xrowecommerce')}{if $fields.first_name.required}<span class="required">*</span></label>
                        <div class="labelbreak"></div>
                        <input type="text" name="first_name" id="first_name" value="{$first_name|wash}" />
                    </div>
                {/if}
                {if $fields.mi.enabled}
                    <div class="ur_mi block{if is_set($fields.mi.errors)} error{/if}">
                        <label>{'MI'|i18n('extension/xrowecommerce')}{if $fields.mi.required}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input class="halfbox" type="text" name="mi" id="mi" size="2" value="{$mi|wash}" />
                    </div>
                {/if}
                {if $fields.last_name.enabled}
                    <div class="ur_lastname block{if is_set($fields.last_name.errors)} error{/if}">
                        <label>{'Last name'|i18n('extension/xrowecommerce')}{if $fields.last_name.required}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="last_name" id="last_name" value="{$last_name|wash}" />
                    </div>
                {/if}
                {if $fields.address1.enabled}
                    <div class="block{if is_set($fields.address1.errors)} error{/if}">
                        <label>{'Address 1'|i18n('extension/xrowecommerce')}{if $fields.address1.required}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="address1" id="address1" size="20" value="{$address1|wash}" title="{'Street address, P.O. box, company name, c/o'|i18n('extension/xrowecommerce')}" />
                    </div>
                {/if}
                {if $fields.address2.enabled}
                    <div class="block{if is_set($fields.address2.errors)} error{/if}">
                        <label>{'Address 2'|i18n('extension/xrowecommerce')}{if $fields.address2.required}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="address2" id="address2" size="20" value="{$address2|wash}" title="{'Apartment, suite, unit, building, floor, etc.'|i18n('extension/xrowecommerce')}" />
                    </div>
                {/if}
                {if $fields.zip.enabled}
                    <div class="zip block{if is_set($fields.zip.errors)} error{/if}">
                        <label>{'Zip'|i18n('extension/xrowecommerce')}{if $fields.zip.required}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" class="zip" name="zip" id="zip" value="{$zip|wash()}" />
                    </div>
                {/if}
                {if $fields.city.enabled}
                    <div class="break"></div>
                    <div class="city block{if is_set($fields.city.errors)} error{/if}">
                        <label>{'City'|i18n('extension/xrowecommerce')}{if $fields.city.required}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="city" id="city" value="{$city|wash}" />
                    </div>
                {/if}

                    <div class="break"></div>
                
                {if $fields.country.enabled}
                    <div class="country block{if is_set($fields.country.errors)} error{/if}">
                        <label>{'Country'|i18n('extension/xrowecommerce')}{if $fields.country.required}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                                <select name="country" id="country">
                                    {foreach $countries as $country_list_item}
                                     <option value="{$country_list_item.Alpha3}" {if and( $country|ne(''), eq( $country, $country_list_item.Alpha3 ))} selected="selected"{/if}>
                                        {$country_list_item.Name|wash}
                                    </option>
                                    {/foreach}
                                </select>
                    
                    </div>
                {/if}
                
                <div class="break"></div>

                {if $fields.state.enabled}
                    <div class="state block{if is_set($fields.state.errors)} error{/if}">
                        <label>{'State / Province'|i18n('extension/xrowecommerce')}{if $fields.state.required}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <select class="state" name="state" id="state">
                            {foreach $states as $key => $state_item}
                            <option {if eq($state,$key)} selected="selected" {/if}>{$state_item|wash}</option>
                            {/foreach}
                        </select>
                    </div>
                {/if}
      
                {if $fields.phone.enabled}
                    <div class="block{if is_set($fields.phone.errors)} error{/if}">
                        <label>{'Phone'|i18n('extension/xrowecommerce')}{if $fields.phone.required}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="phone" class="phone" id="phone" value="{$phone|wash}" />
                    </div>
                {/if}
                
                {if $fields.fax.enabled}
                    <div class="block{if is_set($fields.fax.errors)} error{/if}">
                        <label>{'Fax'|i18n('extension/xrowecommerce')}{if $fields.fax.required}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="fax" id="fax" class="fax" value="{$fax|wash}" />
                    </div>
                {/if}
                
                {if $fields.email.enabled}
                    <div class="block{/if}{if is_set($fields.email.errors)} error{/if}">
                        <label>{'E-mail'|i18n('extension/xrowecommerce')}{if $fields.email.required}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input class="phone" type="text" name="email" id="email" value="{$email|wash}" />
                    </div>
                {/if}
                <div class="break"></div>
                
                {def $shipping_methods=fetch( 'shipping', 'list_methods' )}
                <div class="block">
                    {if $shipping_methods|count|gt(1)}
                            <label>{'Shipping'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
                            <div class="labelbreak"></div>
                            <select name="shippingtype" id="shippingtype">
                                {foreach $shipping_methods as $shipping_method}
                                <option value="{$shipping_method.identifier}" {if $shippingtype|eq($shipping_method.identifier)} selected="selected" {/if}>{$shipping_method.name}</option>
                                {/foreach}
                            </select>
                    {elseif $shipping_methods|count|eq(1)}
                        <input id="shippingtype" name="shippingtype" value="{$shipping_methods.0.identifier}" type="hidden">
                    {/if}
                </div>
                <div class="break"></div>
                
            </div> {*LEFT COL END*}
            
            {* right column *}
            <div class="shipping">
                <h3>{'Shipping Information'|i18n( 'extension/xrowecommerce' )}</h3>
                <label class="shipping-checkbox" for="shipping-checkbox"><input class="shipping-checkbox" id="shipping-checkbox" name="shipping" value="1" type="checkbox" {if $shipping} checked="checked" {/if}  />{'My billing and shipping addresses are identical.'|i18n('extension/xrowecommerce')}</label>
                <div class="block" id="shippinginfo"{if $shipping} style="display: none;"{else} style="display: block;"{/if}>
                    <p><span class="required">* {'Required field'|i18n('extension/xrowecommerce')}</span></p>
                    
                    {if $fields.s_company_name.enabled}
                        <div class="ur_companyname block{if is_set($fields.s_company_name.errors)} error{/if}">
                            <label>{'Company name'|i18n('extension/xrowecommerce')}{if $fields.s_company_name.required}<span class="required">*</span>{/if}</label>
                            <div class="labelbreak"></div>
                            <input type="text" name="s_company_name" id="s_company_name" value="{$s_company_name|wash}" />
                    
                        </div>
                    {/if}
                    
                    {if $fields.s_company_additional.enabled}
                        <div class="ur_company_additional block{if is_set($fields.s_company_additional.errors)} error{/if}">
                            <label>{'Company additional information'|i18n('extension/xrowecommerce')}{if $fields.s_company_additional.required}<span class="required">*</span>{/if}</label>
                            <div class="labelbreak"></div>
                            <input type="text" name="s_company_additional" id="s_company_additional" value="{$s_company_additional|wash}" />
                        </div>
                    {/if}
                    
                    {if $fields.s_first_name.enabled}
                        <div class="ur_firstname block{if is_set($fields.s_first_name.errors)} error{/if}">
                            <label>{'First name'|i18n('extension/xrowecommerce')}{if $fields.s_first_name.required}<span class="required">*</span>{/if}</label>
                            <div class="labelbreak"></div>
                            <input type="text" name="s_first_name" id="s_first_name" value="{$s_first_name|wash}" />
                        </div>
                    {/if}
                    
                    {if $fields.s_mi.enabled}
                        <div class="ur_mi block{if is_set($fields.s_mi.errors)} error{/if}">
                            <label>{'MI'|i18n('extension/xrowecommerce')}{if $fields.s_mi.required}<span class="required">*</span>{/if}</label>
                            <div class="labelbreak"></div>
                            <input class="halfbox" type="text" name="s_mi" id="s_mi" size="2" value="{$s_mi|wash}" />
                        </div>
                    {/if}
                    
                    {if $fields.s_last_name.enabled}
                        <div class="ur_lastname block{if is_set($fields.s_last_name.errors)} error{/if}">
                            <label>{'Last name'|i18n('extension/xrowecommerce')}{if $fields.s_last_name.required}<span class="required">*</span>{/if}</label>
                            <div class="labelbreak"></div>
                            <input type="text" name="s_last_name" id="s_last_name" value="{$s_last_name|wash}" />
                        </div>
                    {/if}
                    
                    {if $fields.s_address1.enabled}
                        <div class="block{if is_set($fields.s_address1.errors)} error{/if}">
                            <label>{'Address 1'|i18n('extension/xrowecommerce')}{if $fields.s_address1.required}<span class="required">*</span>{/if}</label>
                            <div class="labelbreak"></div>
                            <input type="text" name="s_address1" id="s_address1" size="20" value="{$s_address1|wash}" />
                        </div>
                    {/if}
                    
                    {if $fields.s_address2.enabled}
                        <div class="block{if is_set($fields.s_address2.errors)} error{/if}">
                            <label>{'Address 2'|i18n('extension/xrowecommerce')}{if $fields.s_address2.required}<span class="required">*</span>{/if}</label>
                            <div class="labelbreak"></div>
                            <input type="text" name="s_address2" size="20" id="s_address2" value="{$s_address2|wash}" />
                        </div>
                    {/if}
                    
                    {if $fields.s_zip.enabled}
                        <div class="zip block{if is_set($fields.s_zip.errors)} error{/if}">
                            <label>{'Zip'|i18n('extension/xrowecommerce')}{if $fields.s_zip.required}<span class="required">*</span>{/if}</label>
                            <div class="labelbreak"></div>
                            <input type="text" name="s_zip" id="s_zip" value="{$s_zip|wash()}"/>
                        </div>
                    {/if}
                    <div class="break"></div>
                    
                    {if $fields.s_city.enabled}
                        <div class="city block{if is_set($fields.s_city.errors)} error{/if}">
                            <label>{'City'|i18n('extension/xrowecommerce')}{if $fields.s_city.required}<span class="required">*</span>{/if}</label>
                            <div class="labelbreak"></div>
                            <input type="text" name="s_city" value="{$s_city|wash}" id="s_city" />
                        </div>
                    {/if}

                    {if $fields.s_country.enabled}
                        <div class="country block{if is_set($fields.s_country.errors)} error{/if}">
                            <label>{'Country'|i18n('extension/xrowecommerce')}{if $fields.s_country.required}<span class="required">*</span>{/if}</label>
                            <div class="labelbreak"></div>
                                    <select name="s_country" id="s_country">
                                        {foreach $countries as $country_list_item}
                                         <option value="{$country_list_item.Alpha3}" {if and( $s_country|ne(''), eq( $s_country, $country_list_item.Alpha3 ))} selected="selected"{/if}>
                                            {$country_list_item.Name|wash} 
                                        </option>
                                        {/foreach}
                                    </select>
                        </div>
                        <div class="break"></div>
                    {/if}

                    {if $fields.s_state.enabled}
                        <div class="state block{if is_set($fields.s_state.errors)} error{/if}">
                            <label>{'State / Province'|i18n('extension/xrowecommerce')}{if $fields.s_state.required}<span class="required">*</span>{/if}</label>
                            <div class="labelbreak"></div>
                            <select name="s_state" id="s_state">
                            {foreach $s_states as $key => $state_item}
                            <option {if eq($s_state,$key)} selected="selected" {/if}>{$state_item|wash}</option>
                            {/foreach}
                                </select>
                        </div>
                        <div class="break"></div>
                    {/if}

                    {if $fields.s_phone.enabled}
                        <div class="block{if is_set($fields.s_phone.errors)} error{/if}">
                            <label>{'Phone'|i18n('extension/xrowecommerce')}{if $fields.s_phone.required}<span class="required">*</span>{/if}</label>
                            <div class="labelbreak"></div>
                            <input class="phone" type="text" id="s_phone" name="s_phone" value="{$s_phone|wash}" />
                        </div>
                    {/if}
    
                    {if $fields.s_fax.enabled}
                        <div class="block{if is_set($fields.s_fax.errors)} error{/if}">
                            <label>{'Fax'|i18n('extension/xrowecommerce')}{if $fields.s_fax.required}<span class="required">*</span>{/if}</label>
                            <div class="labelbreak"></div>
                            <input class="phone" type="text" id="s_fax" name="s_fax" value="{$s_fax|wash}" />
                        </div>
                    {/if}
    
                    {if $fields.s_email.enabled}
                        <div class="block{if is_set($fields.s_email.errors)} error{/if}">
                            <label>{'E-mail'|i18n('extension/xrowecommerce')}{if $fields.s_email.required}<span class="required">*</span>{/if}</label>
                            <div class="labelbreak"></div>
                            <input class="phone" type="text" name="s_email" id="semail" value="{$s_email|wash}" />
                        </div>
                        <div class="break"></div>
                    {/if}
                </div>
            
            {* / left column *}
            </div>
            
            {if ezini('Fields','Coupon','xrowecommerce.ini').enabled|eq('true')}
                <div class="coupon">
                    <h3>{'Coupon'|i18n('extension/xrowecommerce')}</h3>
                    <p>{'Please enter your coupon code exactly as it appears on your promotion.'|i18n('extension/xrowecommerce')}</p>
                    <div class="block">
                        <label>{'Coupon'|i18n('extension/xrowecommerce')}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="coupon_code" id="coupon_code" value="{$coupon_code|wash}" size="20" />
                    </div>
                </div>
            {/if}
    
            <div class="additional-information">
                <h3>{'Additional Information'|i18n( 'extension/xrowecommerce' )}</h3>
    
                {* Captcha Start *}
                    {def $access=fetch( 'user', 'has_access_to',
                                        hash( 'module',   'xrowecommerce',
                                              'function', 'bypass_captcha' ) )}
    
                    {if and( ezini('Fields','Captcha','xrowecommerce.ini').enabled|eq('true'), $access|not, ezhttp( 'xrowCaptchaSolved', 'session' )|not)}
                        <div class="block">
                            <label>{'Verification'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
                            <div class="labelbreak"></div>
                            {def $lang=ezini('Display','OverrideLang','recaptcha.ini')}
                            {if $lang|eq('')}{set $lang="en"}{/if}
                            <script type="text/javascript">
                                RecaptchaTheme='{ezini('Display','Theme','recaptcha.ini')}';
                                RecaptchaLang='{$lang}';
                                {literal}
                                var RecaptchaOptions = {
                                theme: RecaptchaTheme,
                                lang: RecaptchaLang,
                                };
                                {/literal}
                            </script>
                            {recaptcha_get_html()}
                        </div>
                    {/if}
                {* Captcha End *}
    
                {* Your No Partial Delivery *}
                {if ezini('Fields','NoPartialDelivery','xrowecommerce.ini').enabled|eq('true')}
                    <div class="block">
                            <label class="no_partial_delivery" for="no_partial_delivery"><input class="no_partial_delivery" type="checkbox" name="no_partial_delivery" id="no_partial_delivery" {if is_set($no_partial_delivery)} checked="checked" {/if} value="1" />{'No partial delivery'|i18n('extension/xrowecommerce')}</label>
                    </div>
                {/if}
            
                {* Your Reference *}
                {if ezini('Fields','Reference','xrowecommerce.ini').enabled|eq('true')}
                    <div class="block">
                            <label for="reference">{'Your reference'|i18n('extension/xrowecommerce')}</label>
                            <div class="labelbreak"></div>
                            <input class="reference" type="text" name="reference" id="reference" value="{$reference|wash}" />
                    </div>
                {/if}
            
                {* Your Message *}
                {if ezini('Fields','Message','xrowecommerce.ini').enabled|eq('true')}
                    <div class="block">
                            <label for="message">{'Your notes on order'|i18n('extension/xrowecommerce')}</label>
                            <div class="labelbreak"></div>
                            <textarea class="message" name="message" id="message" rows="10" cols="50">{$message|wash}</textarea>
                    </div>
                {/if}
            </div>
            
            <div class="buttonblock">
                <input id="cancel-button" class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('extension/xrowecommerce')}" title="{'Cancel order'|i18n('extension/xrowecommerce')}" />
                <input id="continue-button" class="button" type="submit" name="StoreButton" value="{'Continue'|i18n('extension/xrowecommerce')}" title="{'Continue order'|i18n('extension/xrowecommerce')}" />
            </div>
        </div>
        {literal}
            <script type="text/javascript">
                // removed because it updates the values, if we already have data for shipping.
                // Idea only update if we have no data fopr shipping and data for billing.
                // changeShipping(); 
                updateShipping();
            </script>
        {/literal}
    </form>
</div>