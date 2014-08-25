{* Login - Override Template *}
<div class="content-login">
<form method="post" action={"/user/login/"|ezurl}>
    <div id="login_full">
        <h1>{'My Account'|i18n("extension/xrowecommerce")}</h1>
        <fieldset class="xrow-loginbox">
            <legend>{'Already Registered?'|i18n("extension/xrowecommerce")}</legend>
            <p>
                {'Enter your username or email address and password below to sign-in.'|i18n("extension/xrowecommerce")}
            </p>
            {section show=$User:warning.bad_login}
            <div class="warning">
                <h2>{"Could not login"|i18n("extension/xrowecommerce")}</h2>
                <ul>
                    <li>{"A valid username and password is required to login."|i18n("extension/xrowecommerce")}</li>
                </ul>
            </div>
            {section-else}
            {section show=$site_access.allowed|not}
            <div class="warning">
                <h2>{"Access not allowed"|i18n("extension/xrowecommerce")}</h2>
                <ul>
                    <li>{"You are not allowed to access %1."|i18n("extension/xrowecommerce",,array($site_access.name))}</li>
                </ul>
            </div>
            {/section}
            {/section}
            <div class="block">
                <label for="id1">{"Username"|i18n("extension/xrowecommerce",'User name')}</label><div class="labelbreak"></div>
                <input class="halfbox" type="text" size="10" name="Login" id="id1" value="{$User:login|wash}" tabindex="1" />
            </div>
            <div class="block">
                <label for="id2">{"Password"|i18n("extension/xrowecommerce")}</label><div class="labelbreak"></div>
                <input class="halfbox" type="password" size="10" name="Password" id="id2" value="" tabindex="1" />
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
    </div>
</form>
</div>