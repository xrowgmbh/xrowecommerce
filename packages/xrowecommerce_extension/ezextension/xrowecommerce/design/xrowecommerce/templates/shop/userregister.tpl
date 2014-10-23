<div class="shop shop-userregister">
    <h1>{'My Account'|i18n('extension/xrowecommerce')}</h1>
    {include uri="design:shop/basket_navigator.tpl" step='2'}
    
    {include uri="design:shop/userregister_preface.tpl"}
    
    {if and( fetch( 'user', 'current_user' ).is_logged_in|not(), ezini( 'Settings', 'ForceUserRegBeforeCheckout', 'xrowecommerce.ini' )|eq('true'))}
        <h2>{'Bought from us before?'|i18n('extension/xrowecommerce')}</h2>
        <div id="checkoutLogin">
            <p>{'If you have previously purchased from us, please login using the below fields'|i18n('extension/xrowecommerce')}:</p>
            <form method="post" action={"user/login"|ezurl}">
                <div class="inputGroup text">
                    <label for="id1">{'Email address'|i18n('extension/xrowecommerce')}:</label>
                    <input type="text" name="Login" id="id1" value="" tabindex="1" />
                </div>
                <div class="inputGroup text">
                    <label for="id2">{'Password'|i18n('extension/xrowecommerce')}:</label>
                    <input type="password" name="Password" id="id2" value="" tabindex="1" />
                </div>
                <div class="login-button">
                    <input class="button" type="submit" name="LoginButton" value="{'Login'|i18n('extension/xrowecommerce')}" tabindex="1" />
                </div>
                <input type="hidden" name="RedirectURI" value={"xrowecommerce/userregister"|ezroot} />
                <p><a href={'user/forgotpassword/'|ezurl()}>{'Forgotten password?'|i18n('extension/xrowecommerce')}</a></p>
                <input type="hidden" value="xrowecommerce/userregister" name="RedirectAfterLogin" />
             </form>
        </div>
        <h2 id="newCustReg">{"If you're a new customer, registering only takes a few seconds"|i18n('extension/xrowecommerce')}:</h2>
    {/if}
    {if eq(ezini( 'BasketInformation', 'DisplayLogin', 'xrowecommerce.ini' ), 'enabled' )}
        {def $user=fetch( 'user', 'current_user' )}
        {if $user.is_logged_in|not()}
            <form method="post" action={"/user/login/"|ezurl}>
                <fieldset class="loginbox">
                    <legend>{'Already a user?'|i18n("extension/xrowecommerce",'User name')}</legend>
                    <p>
                        {'Enter your username or email address and password below to sign-in.'|i18n("extension/xrowecommerce")}
                    </p>
                    <div class="block">
                        <label for="id1">{"Username"|i18n("extension/xrowecommerce",'User name')}</label><div class="labelbreak"></div>
                        <input type="text" size="15" name="Login" id="id1" value="{$User:login|wash}" tabindex="1" />
                    </div>
                    <div class="block">
                        <label for="id2">{"Password"|i18n("extension/xrowecommerce")}</label><div class="labelbreak"></div>
                        <input type="password" size="15" name="Password" id="id2" value="" tabindex="1" />
                    </div>
                    <p><a href={"/user/forgotpassword"|ezurl()}>{'Forgot Password?'|i18n("extension/xrowecommerce")}</a></p>
                    <div class="buttonblock">
                        <input type="submit" class="button" name="LoginButton" value="{'Login'|i18n('extension/xrowecommerce','Button')}" tabindex="1">
                    </div>
                    {section show=ezini( 'SiteSettings', 'LoginPage' )|eq( 'custom' )}
                        <p><a href={'/user/forgotpassword'|ezurl}>{'Forgot your password?'|i18n( 'extension/xrowecommerce' )}</a></p>
                    {/section}
                    <input type="hidden" name="RedirectURI" value="{$User:redirect_uri|wash}" />
                    {section show=and( is_set( $User:post_data ), is_array( $User:post_data ) )}
                    {section name=postData loop=$User:post_data }
                    <input name="Last_{$postData:key}" value="{$postData:item}" type="hidden" />
                    {/section}
                    {/section}
                </fieldset>
                <fieldset class="xrow-registerbox">
                    <legend>{'New Customer?'|i18n("extension/xrowecommerce")}</legend>
                    <p>{'Create an account to save your shipping and billing information.'|i18n("extension/xrowecommerce")}</p>
                    <div class="buttonblock">
                        <input class="button" type="submit" name="RegisterButton" value="{'Sign Up'|i18n('extension/xrowecommerce','Button')}" tabindex="1">
                    </div>
                </fieldset>
            </form>
        {/if}
        {undef $user}
    {/if}
    <form method="post" action={"xrowecommerce/userregister/"|ezurl} name='register' >
        <input type="submit" class="hide" style="display: hide;" name="StoreButton" value="{'Continue'|i18n('extension/xrowecommerce')}" />
        {def $country_default_ini=ezini( 'ShopAccountHandlerDefaults', 'DefaultCountryCode', 'xrowecommerce.ini' )}
        {if $country|trim()|ne('')}
            {set $country_default_ini = $country} 
        {/if}
            {if count($hazardous)|gt(0)}
                <div class="warning">
                    <h2>{'Hazardous item(s) found in your cart.'|i18n('extension/xrowecommerce')}</h2>
                    <p>
                        {'Dear Customer,'|i18n('extension/xrowecommerce')}<br />
                        {"We've found the following hazardous items from your shopping cart since we are not allowed to ship these items to your destination. For further questions please contact us."|i18n('extension/xrowecommerce')}<br />
                    </p>
                    <ul>
                        {foreach $hazardous as $item}
                        <li>{$item.item_count} x <a href={concat("/content/view/full/", $item.contentobject.main_node_id)|ezurl()}>{$item.name}</a></li>
                        {/foreach}
                    </ul>
                </div>
            {/if}
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
        <fieldset class="billing">
            <legend>{'Billing Information'|i18n('extension/xrowecommerce')}</legend>
            <p><span class="required">* <i>{'Required field'|i18n('extension/xrowecommerce')}</i></span></p>
            {if or($fields.company_name.enabled, $fields.company_additional.enabled, $fields.tax_id.enabled)}
                <div class="address-selection">
                    <div class="comp">
                        <label for="company">
                        <input id="company" type="radio" value="true" name="company" />
                        {'Company'|i18n('extension/xrowecommerce')}
                        </label>
                    </div>
                    <div class="home">
                        <label for="home">
                        <input checked="checked" id="home" type="radio" value="false" name="company" />
                        {'Private person'|i18n('extension/xrowecommerce')}
                        </label>
                    </div>
                </div>
             {/if}
            <div class="company">
                {if $fields.company_name.enabled}
                    <div class="companyname block{if is_set($fields.company_name.errors)} error{/if}">
                        <label>{'Company name'|i18n('extension/xrowecommerce')}{if $fields.company_name.required}<span class="required">*</span>{/if}</label>
                        <input type="text" name="company_name" id="company_name" value="{$company_name|wash}" />
                    </div>
                {/if}
                {if $fields.company_additional.enabled}
                    <div class="company_additional block{if is_set($fields.company_additional.errors)} error{/if}">
                        <label>{'Company additional information'|i18n('extension/xrowecommerce')}{if $fields.company_additional.required}<span class="required">*</span>{/if}</label>
                        <input type="text" name="company_additional" id="company_additional" value="{$company_additional|wash}" />
                    </div>
                {/if}
                {if $fields.tax_id.enabled}
                    <div class="taxid block{if is_set($fields.tax_id.errors)} error{/if}">
                        <label>{'Tax ID'|i18n('extension/xrowecommerce')}{if $fields.tax_id.required}<span class="required">*</span>{/if}</label>
                        <input type="text" name="tax_id" {if and( $tax_id, $tax_id_valid|eq('2') )} readonly="readonly" disabled{/if} value="{$tax_id|wash}" />
                    </div>
                {/if}
            </div>
            {if $fields.title.enabled}
                <div class="title block{if is_set($fields.title.errors)} error{/if}">
                    <label>{'Title'|i18n('extension/xrowecommerce')}</label>
                    <input type="text" name="title" id="title" value="{$title|wash}" />
                </div>
            {/if}
            {if $fields.first_name.enabled}
                <div class="firstname block{if is_set($fields.first_name.errors)} error{/if}">
                    <label>{'First name'|i18n('extension/xrowecommerce')}{if $fields.first_name.required}<span class="required">*</span>{/if}</label>
                    <input type="text" name="first_name" id="first_name" value="{$first_name|wash}" />
                </div>
            {/if}
            {if $fields.mi.enabled}
                <div class="mi block{if is_set($fields.mi.errors)} error{/if}">
                    <label>{'MI'|i18n('extension/xrowecommerce')}{if $fields.mi.required}<span class="required">*</span>{/if}</label>
                    <input class="halfbox" type="text" name="mi" id="mi" size="2" value="{$mi|wash}" />
                </div>
            {/if}
            {if $fields.last_name.enabled}
                <div class="lastname block{if is_set($fields.last_name.errors)} error{/if}">
                    <label>{'Last name'|i18n('extension/xrowecommerce')}{if $fields.last_name.required}<span class="required">*</span>{/if}</label>
                    <input type="text" name="last_name" id="last_name" value="{$last_name|wash}" />
                </div>
            {/if}
            {if $fields.address1.enabled}
                <div class="address1 block{if is_set($fields.address1.errors)} error{/if}">
                    <label>{'Address 1'|i18n('extension/xrowecommerce')}{if $fields.address1.required}<span class="required">*</span>{/if}</label>
                    <input type="text" name="address1" id="address1" size="20" value="{$address1|wash}" title="{'Street address, P.O. box, company name, c/o'|i18n('extension/xrowecommerce')}" />
                </div>
            {/if}
            {if $fields.address2.enabled}
                <div class="address2 block{if is_set($fields.address2.errors)} error{/if}">
                    <label>{'Address 2'|i18n('extension/xrowecommerce')}{if $fields.address2.required}<span class="required">*</span>{/if}</label>
                    <input type="text" name="address2" id="address2" size="20" value="{$address2|wash}" title="{'Apartment, suite, unit, building, floor, etc.'|i18n('extension/xrowecommerce')}" />
                </div>
            {/if}
            {if $fields.city.enabled}
                <div class="city block{if is_set($fields.city.errors)} error{/if}">
                    <label>{'City'|i18n('extension/xrowecommerce')}{if $fields.city.required}<span class="required">*</span>{/if}</label>
                    <input type="text" name="city" id="city" value="{$city|wash}" />
                </div>
            {/if}
            {if $fields.zip.enabled}
                <div class="zip block{if is_set($fields.zip.errors)} error{/if}">
                    <label>{'Zip'|i18n('extension/xrowecommerce')}{if $fields.zip.required}<span class="required">*</span>{/if}</label>
                    <input type="text" class="zip" name="zip" id="zip" value="{$zip|wash()}" />
                </div>
                <div class="break"></div>
            {/if}
            {if $fields.country.enabled}
                <div class="country block{if and(is_set($fields.country.errors), $fields.country.errors|count()|gt(0))} error{/if}">
                    <label>{'Country'|i18n('extension/xrowecommerce')}{if $fields.country.required}<span class="required">*</span>{/if}</label>
                    <select name="country" id="country">
                    {foreach $countries as $country_list_item}
                        {if $country|ne( '' )}
                        {* Backwards compatability *}
                            <option value="{$country_list_item.Alpha3}" {if and( $country|ne(''), eq( $country, $country_list_item.Alpha3 ))} selected="selected"{/if}>{$country_list_item.Name|wash}</option>
                        {else}
                            <option {if $country_default_ini|eq( $country_list_item.Alpha3 )}selected="selected"{/if} value="{$country_list_item.Alpha3}">{$country_list_item.Name|wash}</option>
                        {/if}
                    {/foreach}
                    </select>
                </div>
            {/if}
            {if $fields.state.enabled}
                <div class="state block{if is_set($fields.state.errors)} error{/if}">
                    <label>{'State / Province'|i18n('extension/xrowecommerce')}{if $fields.state.required}<span class="required">*</span>{/if}</label>
                    <select name="state" id="state">
                        {foreach $states as $key => $state_item}
                            <option {if eq( $state, $key )} selected="selected" {/if} value="{$key}">{$state_item|wash}</option>
                        {/foreach}
                    </select>
                </div>
            {/if}
            {if $fields.phone.enabled}
                <div class="phone block{if is_set($fields.phone.errors)} error{/if}">
                    <label>{'Phone'|i18n('extension/xrowecommerce')}{if $fields.phone.required}<span class="required">*</span>{/if}</label>
                    <input type="text" name="phone" class="phone" id="phone" value="{$phone|wash}" />
                </div>
            {/if}
            {if $fields.fax.enabled}
                <div class="fax block{if is_set($fields.fax.errors)} error{/if}">
                    <label>{'Fax'|i18n('extension/xrowecommerce')}{if $fields.fax.required}<span class="required">*</span>{/if}</label>
                    <input type="text" name="fax" id="fax" class="fax" value="{$fax|wash}" />
                </div>
            {/if}
            {if $fields.email.enabled}
                <div class="email block{if is_set($fields.email.errors)} error{/if}">
                    <label>{'E-mail'|i18n('extension/xrowecommerce')}{if $fields.email.required}<span class="required">*</span>{/if}</label>
                    <input class="email" type="text" name="email" id="email" value="{$email|wash}" />
                </div>
                {if $fields.email_confirm.enabled}
                    <div class="email_confirm block{if is_set($fields.email.errors)} error{/if}">
                        <label>{'Confirm E-mail'|i18n('extension/xrowecommerce')}{if $fields.email.required}<span class="required">*</span>{/if}</label>
                        <input class="email_confirm" type="text" name="email_confirm" id="email_confirm" value="{cond($email|ne(''), $email, $email_confirm)|wash}" />
                    </div>
                {/if}
            {/if}
            {if and( fetch( 'user', 'current_user' ).is_logged_in|not(), ezini( 'Settings', 'ForceUserRegBeforeCheckout', 'xrowecommerce.ini' )|eq('true'))}
                {if $fields.password.enabled }
                    <div class="password block{if is_set($fields.password.errors)} error{/if}">
                        <label>{'Password'|i18n('extension/xrowecommerce')}{if $fields.password.required}<span class="required">*</span>{/if}</label>
                        <input class="password" type="password" name="password" id="password" value="" />
                        <label>{'Confirm Password'|i18n('extension/xrowecommerce')}{if $fields.password.required}<span class="required">*</span>{/if}</label>
                        <input class="password_confirm" type="password" name="passwordConfirm" id="password_confirm" value="" />
                    </div>
                {/if}
            {/if}
            
            {if ezini('Fields','Newsletter','xrowecommerce.ini').enabled|eq('true')}
                <div class="newsletter block">
                    <label class="newsletter" for="newsletter"><input class="newsletter" type="checkbox" name="newsletter" id="newsletter" {if is_set($newsletter)} checked="checked" {/if} value="1" />{'Subscribe to newsletter'|i18n('extension/xrowecommerce')}</label>
                </div>
            {/if}

            {def $shipping_methods=fetch( 'shipping', 'list_methods' )}
            <div class="shippingtype block {if is_set($fields.shippingtype.errors)} error{/if}">
            {if $shipping_methods|count|gt(1)}
                <label>{'Shipping'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
                <div class="labelbreak"></div>
                <select name="shippingtype" id="shippingtype">
                    <option value="">&nbsp;</option>
                    {foreach $shipping_methods as $shipping_method}
                    <option value="{$shipping_method.identifier}" {if $shippingtype|eq($shipping_method.identifier)} selected="selected" {/if}>{$shipping_method.name}</option>
                    {/foreach}
                </select>
            {elseif $shipping_methods|count|eq(1)}
                <input id="shippingtype" name="shippingtype" value="{$shipping_methods.0.identifier}" type="hidden" />
            {/if}
            </div>
        </fieldset> {*LEFT COL END*}

        {* right column *}
        <fieldset class="shipping">
            <legend>{'Shipping Information'|i18n( 'extension/xrowecommerce' )}</legend>
            <label class="shipping-checkbox" for="shipping-checkbox">
            <p id="shipping-checkbox-text">
                <a id="shipping-link" href="#">
                    {'Enter different shipping address.'|i18n('extension/xrowecommerce')}
                </a>
            </p>
            <input class="shipping-checkbox" id="shipping-checkbox" name="shipping" value="1" type="checkbox" {if $shipping} checked="checked" {/if}  />
            {'My billing and shipping addresses are identical.'|i18n('extension/xrowecommerce')}
            </label>

            <div class="block" id="shippinginfo"{if $shipping} style="display: none;"{else} style="display: block;"{/if}>
                <p><span class="required">* {'Required field'|i18n('extension/xrowecommerce')}</span></p>
                
                {if or( $fields.s_company_name.enabled, $fields.s_company_additional.enabled)}
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
                            {'Private Person'|i18n('extension/xrowecommerce')}
                            </label>
                        </div>
                    </div>
                {/if}
                <div class="s_company">
                    {if $fields.s_company_name.enabled}
                        <div class="companyname block{if is_set($fields.s_company_name.errors)} error{/if}">
                            <label>{'Company name'|i18n('extension/xrowecommerce')}{if $fields.s_company_name.required}<span class="required">*</span>{/if}</label>
                            <input type="text" name="s_company_name" id="s_company_name" value="{$s_company_name|wash}" />
                        </div>
                    {/if}

                    {if $fields.s_company_additional.enabled}
                        <div class="company_additional block{if is_set($fields.s_company_additional.errors)} error{/if}">
                            <label>{'Company additional information'|i18n('extension/xrowecommerce')}{if $fields.s_company_additional.required}<span class="required">*</span>{/if}</label>
                            <input type="text" name="s_company_additional" id="s_company_additional" value="{$s_company_additional|wash}" />
                        </div>
                    {/if}
                </div>

                {if $fields.s_title.enabled}
                    <div class="title block{if is_set($fields.s_title.errors)} error{/if}">
                        <label>{'Title'|i18n('extension/xrowecommerce')}{if $fields.s_title.required}<span class="required">*</span>{/if}</label>
                        <input type="text" name="s_title" id="s_title" value="{$s_title|wash}" />
                    </div>
                {/if}

                {if $fields.s_first_name.enabled}
                    <div class="firstname block{if is_set($fields.s_first_name.errors)} error{/if}">
                        <label>{'First name'|i18n('extension/xrowecommerce')}{if $fields.s_first_name.required}<span class="required">*</span>{/if}</label>
                        <input type="text" name="s_first_name" id="s_first_name" value="{$s_first_name|wash}" />
                    </div>
                {/if}

                {if $fields.s_mi.enabled}
                    <div class="mi block{if is_set($fields.s_mi.errors)} error{/if}">
                        <label>{'MI'|i18n('extension/xrowecommerce')}{if $fields.s_mi.required}<span class="required">*</span>{/if}</label>
                        <input class="halfbox" type="text" name="s_mi" id="s_mi" size="2" value="{$s_mi|wash}" />
                    </div>
                {/if}

                {if $fields.s_last_name.enabled}
                    <div class="lastname block{if is_set($fields.s_last_name.errors)} error{/if}">
                        <label>{'Last name'|i18n('extension/xrowecommerce')}{if $fields.s_last_name.required}<span class="required">*</span>{/if}</label>
                        <input type="text" name="s_last_name" id="s_last_name" value="{$s_last_name|wash}" />
                    </div>
                {/if}

                {if $fields.s_address1.enabled}
                    <div class="address1 block{if is_set($fields.s_address1.errors)} error{/if}">
                        <label>{'Address 1'|i18n('extension/xrowecommerce')}{if $fields.s_address1.required}<span class="required">*</span>{/if}</label>
                        <input type="text" name="s_address1" id="s_address1" size="20" value="{$s_address1|wash}" />
                    </div>
                {/if}

                {if $fields.s_address2.enabled}
                    <div class="address2 block{if is_set($fields.s_address2.errors)} error{/if}">
                        <label>{'Address 2'|i18n('extension/xrowecommerce')}{if $fields.s_address2.required}<span class="required">*</span>{/if}</label>
                        <input type="text" name="s_address2" size="20" id="s_address2" value="{$s_address2|wash}" />
                    </div>
                {/if}

                {if $fields.s_city.enabled}
                    <div class="city block{if is_set($fields.s_city.errors)} error{/if}">
                        <label>{'City'|i18n('extension/xrowecommerce')}{if $fields.s_city.required}<span class="required">*</span>{/if}</label>
                        <input type="text" name="s_city" value="{$s_city|wash}" id="s_city" />
                    </div>
                {/if}

                {if $fields.s_zip.enabled}
                    <div class="zip block{if is_set($fields.s_zip.errors)} error{/if}">
                        <label>{'Zip'|i18n('extension/xrowecommerce')}{if $fields.s_zip.required}<span class="required">*</span>{/if}</label>
                        <input type="text" name="s_zip" id="s_zip" value="{$s_zip|wash()}"/>
                    </div>
                {/if}

                {if $fields.s_country.enabled}
                    <div class="country block{if and(is_set($fields.s_country.errors), $fields.s_country.errors|count()|gt(0))} error{/if}">
                        <label>{'Country'|i18n('extension/xrowecommerce')}{if $fields.s_country.required}<span class="required">*</span>{/if}</label>
                        <select name="s_country" id="s_country">
                        {foreach $countries as $country_list_item}
                            {if $country|ne( '' )}
                            {* Backwards compatability *}
                                <option value="{$country_list_item.Alpha3}" {if and( $s_country|ne(''), eq( $s_country, $country_list_item.Alpha3 ))} selected="selected"{/if}>{$country_list_item.Name|wash}</option>
                            {else}
                                <option {if $country_default_ini|eq( $country_list_item.Alpha3 )}selected="selected"{/if} value="{$country_list_item.Alpha3}">{$country_list_item.Name|wash}</option>
                            {/if}
                        {/foreach}
                        </select>
                    </div>
                {/if}

                {if $fields.s_state.enabled}
                    <div class="state block{if is_set($fields.s_state.errors)} error{/if}">
                        <label>{'State / Province'|i18n('extension/xrowecommerce')}{if $fields.s_state.required}<span class="required">*</span>{/if}</label>
                        <select name="s_state" id="s_state">
                            <option>&nbsp;</option>
                            {foreach $s_states as $key => $state_item}
                            <option {if eq($s_state,$key)} selected="selected" {/if} value="{$key}">{$state_item|wash}</option>
                            {/foreach}
                        </select>
                    </div>
                {/if}

                {if $fields.s_phone.enabled}
                    <div class="block phone{if is_set($fields.s_phone.errors)} error{/if}">
                        <label>{'Phone'|i18n('extension/xrowecommerce')}{if $fields.s_phone.required}<span class="required">*</span>{/if}</label>
                        <input class="s_phone" type="text" id="s_phone" name="s_phone" value="{$s_phone|wash}" />
                    </div>
                {/if}

                {if $fields.s_fax.enabled}
                    <div class="block fax{if is_set($fields.s_fax.errors)} error{/if}">
                        <label>{'Fax'|i18n('extension/xrowecommerce')}{if $fields.s_fax.required}<span class="required">*</span>{/if}</label>
                        <input class="s_fax" type="text" id="s_fax" name="s_fax" value="{$s_fax|wash}" />
                    </div>
                {/if}

                {if $fields.s_email.enabled}
                    <div class="block email{if is_set($fields.s_email.errors)} error{/if}">
                        <label>{'E-mail'|i18n('extension/xrowecommerce')}{if $fields.s_email.required}<span class="required">*</span>{/if}</label>
                        <input class="s_email" type="text" name="s_email" id="s_email" value="{$s_email|wash}" />
                    </div>
                    {if $fields.s_email_confirm.enabled}
                        <div class="s_email_confirm block{if is_set($fields.s_email_confirm.errors)} error{/if}">
                            <label>{'Confirm E-mail'|i18n('extension/xrowecommerce')}{if $fields.s_email.required}<span class="required">*</span>{/if}</label>
                            <input class="s_email_confirm" type="text" name="s_email_confirm" id="s_email_confirm" value="{$s_email_confirm|wash}" />
                        </div>
                    {/if}
                {/if}
            </div>

        {* / left column *}
        </fieldset>
        
        {if ezini('Fields','Coupon','xrowecommerce.ini').enabled|eq('true')}
            <fieldset class="coupon">
                <legend>{'Coupon'|i18n('extension/xrowecommerce')}</legend>
                <p>{'Please enter your coupon code exactly as it appears on your promotion.'|i18n('extension/xrowecommerce')}</p>
                <div class="block">
                    <label>{'Coupon'|i18n('extension/xrowecommerce')}</label>
                    <input type="text" name="coupon_code" id="coupon_code" value="{$coupon_code|wash}" size="20" />
                </div>
            </fieldset>
        {/if}
        
        <fieldset class="additional-information">
            <legend>{'Additional Information'|i18n( 'extension/xrowecommerce' )}</legend>
            
            {* Captcha Start *}
            {def $access=fetch( 'user', 'has_access_to',
                                hash( 'module',   'xrowecommerce',
                                      'function', 'bypass_captcha' ) )}
            
            {if and( ezini('Fields','Captcha','xrowecommerce.ini').enabled|eq('true'), $access|not, ezhttp( 'xrowCaptchaSolved', 'session' )|not)}
                <div class="block">
                    <label>{'Verification'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
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
                    <input class="reference" type="text" name="reference" id="reference" value="{$reference|wash}" />
                </div>
            {/if}

            {* Your Message *}
            {if ezini('Fields','Message','xrowecommerce.ini').enabled|eq('true')}
                <div class="block">
                    <label for="message">{'Your notes on order'|i18n('extension/xrowecommerce')}</label>
                    <textarea class="message" name="message" id="message" rows="10" cols="50">{$message|wash}</textarea>
                </div>
            {/if}
        </fieldset>

        <div class="buttonblock">
            <input id="cancel-button" class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('extension/xrowecommerce')}" title="{'Cancel order and return to basket'|i18n('extension/xrowecommerce')}" />
            <input id="continue-button" class="button" type="submit" name="StoreButton" value="{'Continue'|i18n('extension/xrowecommerce')}" title="{'Continue order'|i18n('extension/xrowecommerce')}" />
        </div>
    </form>
</div>
