{def $ca=$content_attributes_data_map
     $castring="ContentObjectAttribute_ezstring_data_text_"}

<div class="shop-basket">
    <form enctype="multipart/form-data"  action={"/user/register/"|ezurl} method="post" name="Register">
    <input type="hidden" name="RedirectAfterUserRegister" value={"/"|ezurl()} />
    <h2>{"Register user"|i18n("design/standard/user")}</h2>
    <br />

    {* left frame *}

        <div style="border: 1px solid #ebeeef; width: 290px; float: left; display: inline; margin:5px; padding: 8px;">
            <span class="heading1">Account Information</span>
            <br />
            <p>
                Enter your email address, login and password.<br /> <span class="required">* Required field</span><br />
            </p>
            <br />

            <div class="block">
            <label><span class="required">*</span>Login</label>
            	<div class="labelbreak"></div>
            	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.user_account.id}" />
            	<input type="text" name="ContentObjectAttribute_data_user_login_{$ca.user_account.id}" value="{$ca.user_account.content.login}" size="20">

            </div>
            <div class="break"></div>

            <div class="block">
            	<label><span class="required">*</span>E-mail</label>
            	<div class="labelbreak"></div>
            	<input class="box" type="text" name="ContentObjectAttribute_data_user_email_{$ca.user_account.id}" value="{$ca.user_account.content.email|wash()}" size="20"  />
            	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.user_account.id}" />
            </div>
            <div class="break"></div>

            <div class="block">
            	<label><span class="required">*</span>Password</label>
            	<div class="labelbreak"></div>
            	<input class="box" type="password" name="ContentObjectAttribute_data_user_password_{$ca.user_account.id}" value="{section show=$ca.user_account.content.original_password}{$ca.user_account.content.original_password}{section-else}{section show=$ca.user_account.content.has_stored_login}_ezpassword{/section}{/section}" size="20" />
            </div>
            <div class="break"></div>

            <div class="block">
            	<label><span class="required">*</span>Confirm Password</label>
            	<div class="labelbreak"></div>
            	<input class="box" type="password" name="ContentObjectAttribute_data_user_password_confirm_{$ca.user_account.id}" value="{section show=$ca.user_account.content.original_password_confirm}{$ca.user_account.content.original_password_confirm}{section-else}{section show=$ca.user_account.content.has_stored_login}_ezpassword{/section}{/section}" size="20" />
            </div>
            <div class="break"></div>        
            <br />
        </div>
        
        {* end of left frame *}
    
    {* right frame *}
    <div style="float: left;">
        <div style="border: 1px solid #ebeeef; width: 390px; float: left; display: inline; margin:5px; padding: 8px;">
            <span class="heading1">Billing Information</span>
            <br />
            <p>
                Please enter your billing address exactly as it appears on your credit card statement.<br /><span class="required">* Required field</span>
            </p>
            
            {section show=and( and( is_set( $checkErrNodeId ), $checkErrNodeId ), eq( $checkErrNodeId, true ) )}
            	 <div class="message-error">
            		<h2><span class="time">[{currentdate()|l10n( shortdatetime )}]</span> {$errMsg}</h2>
            	 </div>
            {/section}
            
            {section show=$validation.processed}
            
            	{section name=UnvalidatedAttributes loop=$validation.attributes show=$validation.attributes}
            		<div class="warning">
            		<h2>{"Input did not validate"|i18n("design/standard/user")}</h2>
            		<ul>
            		<li>{$UnvalidatedAttributes:item.name}: {$UnvalidatedAttributes:item.description}</li>
            		</ul>
            		</div>
            	{section-else}
            		<div class="feedback">
            		<h2>{"Input was stored successfully"|i18n("design/standard/user")}</h2>
            		</div>
            	{/section}
            
            {/section}        

            <div class="block">
    
            	<div style="width: 180px; display: inline; float: left;">
                	<label><span class="required">*</span>First name</label>
                	<div class="labelbreak"></div>
                	<input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.first_name.id}" value="{$ca.first_name.content|wash()}" style="width: 170px;" />
                	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.first_name.id}" />
                </div>
                
            	<div style="width: 40px; display: inline; float: left;">
                	<label>MI</label>
                	<div class="labelbreak"></div>
                	<input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.mi.id}" size="2" value="{$ca.mi.content|wash()}" style="width: 30px;" />
                	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.mi.id}" />
                </div>
                
                <div style="width: 170px; display: inline; float: left;">
                	<label><span class="required">*</span>Last name</label>
                	<div class="labelbreak"></div>
                	<input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.last_name.id}" value="{$ca.last_name.content|wash()}" style="width: 160px;" />
                	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.last_name.id}" />
                </div>
                
            	<div class="block">
            		<label><span class="required">*</span>Address 1</label>
            		<div class="labelbreak"></div>
            		<input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.address1.id}" size="20" value="{$ca.address1.content|wash()}" />
            		<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.address1.id}" />
            	</div>
            
            	<div class="block">
            		<label>Address 2</label>
            		<div class="labelbreak"></div>
            		<input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.address2.id}" size="20" value="{$ca.address2.content|wash()}" />
            		<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.address2.id}" />
            	</div>

                <div style="width: 240px; display: inline; float: left;">
                	<label><span class="required">*</span>City / Town</label>
                	<div class="labelbreak"></div>
                	<input type="text" name="{$castring}{$ca.city.id}" value="{$ca.city.content|wash()}" style="width: 230px;" />
                	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.city.id}" />
                </div>    
                
                <div style="width: 90px; display: inline; float: left;">
                	<label><span class="required">*</span>State / Province</label>
                	<div class="labelbreak"></div>
                	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.state.id}" />
                    {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.s_state}
                </div>
                <div class="break"></div>
                {undef $state}

                <div style="width: 90px; display: inline; float: left;">
                    <label><span class="required">*</span>Zip / Postcode</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="{$castring}{$ca.zip_code.id}" value="{$ca.zip_code.content|wash()}" style="width: 170px;"/>
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.zip_code.id}" />
                </div>
                    <div class="break"></div>

<div style="width: 180px; display: inline; float: left;">
    <label><span class="required">*</span>Country</label>
    <div class="labelbreak"></div>
    {def $country_default_ini=ezini( 'ShopAccountHandlerDefaults', 'DefaultCountryCode', 'site.ini' )}
    {def $country_default=$country_default_ini}
    {def $countries=fetch( 'xrowecommerce', 'get_country_list', hash() )}
    {*$countries|attribute(show,2)*}
    {def $is_set_b=is_set($country)}
    {if $is_set_b}{def $country=$country_default_ini}{else}{def $country=''}{/if}

        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.country.id}" />
            <select name="ContentObjectAttribute_country_{$ca.country.id}" style="width: 17 0px;">
                <option value=""></option>
                {foreach $countries as $country_list_item}
                 {def $country_list_item_code=''}
                 {set $country_list_item_code=$country_list_item.Alpha2}
                 <option value="{$country_list_item_code}"{if eq( $country_default, $country_list_item_code )} selected="selected"{/if}{if eq( $country, $country_list_item_code)} selected="selected"{/if}>{$country_list_item.Name}</option>
                  {undef $country_list_item_code}
                {/foreach}
            </select>
</div>
    <div class="break"></div>
    
                <div class="block">
                	<label><span class="required">*</span>Phone</label>
                	<div class="labelbreak"></div>
                	<input class="box" type="text" name="{$castring}{$ca.phone.id}" value="{$ca.phone.content|wash()}" style="width: 170px;" />
                	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.phone.id}" />
                </div>
            </div>
        </div>
        <div style="width: 390px; float: left; display: inline; margin:5px; padding: 8px;">
        </div>
    
        <div style="border: 1px solid #ebeeef; width: 390px; float: left; display: inline; margin:5px; padding: 8px;">
            <span class="heading1">Shipping Information</span><br />
            <table border="0">
                <tr>
                    <td>
                        <input onchange="change(this.checked);" name="ContentObjectAttribute_data_boolean_{$ca.shippingaddress.id}" value="" type="checkbox" {$ca.shippingaddress.data_int|choose( '', 'checked="checked"' )} />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.shippingaddress.id}" />
                    </td>
                    <td>
                        <p>
                            My billing and shipping addresses are identical.
                        </p>
                    </td>
                </tr>
            </table>

            <div class="block" id="shippinginfo")}>
    
            	<div style="width: 180px; display: inline; float: left;">
                	<label><span class="required">*</span>First name</label>
                	<div class="labelbreak"></div>
                	<input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_first_name.id}" value="{$ca.s_first_name.content|wash()}" style="width: 170px;" />
                	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_first_name.id}" />
                </div>
                
            	<div style="width: 40px; display: inline; float: left;">
                	<label>MI</label>
                	<div class="labelbreak"></div>
                	<input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_mi.id}" size="2" value="{$ca.s_mi.content|wash()}" style="width: 30px;" />
                	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_mi.id}" />
                </div>
                
                <div style="width: 170px; display: inline; float: left;">
                	<label><span class="required">*</span>Last name</label>
                	<div class="labelbreak"></div>
                	<input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_last_name.id}" value="{$ca.s_last_name.content|wash()}" style="width: 160px;" />
                	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_last_name.id}" />
                </div>
                
            	<div class="block">
            		<label><span class="required">*</span>Address 1</label>
            		<div class="labelbreak"></div>
            		<input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_address1.id}" size="20" value="{$ca.s_address1.content|wash()}" />
            		<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_address1.id}" />
            	</div>
            
            	<div class="block">
            		<label>Address 2</label>
            		<div class="labelbreak"></div>
            		<input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_address2.id}" size="20" value="{$ca.s_address2.content|wash()}" />
            		<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_address2.id}" />
            	</div>
            
            	<div style="width: 240px; display: inline; float: left;">
                	<label><span class="required">*</span>City / Town</label>
                	<div class="labelbreak"></div>
                	<input type="text" name="{$castring}{$ca.s_city.id}" value="{$ca.s_city.content|wash()}" style="width: 230px;" />
                	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_city.id}" />
                </div>    

                <div style="width: 90px; display: inline; float: left;">
                	<label><span class="required">*</span>State / Province</label>
                	<div class="labelbreak"></div>
                	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_state.id}" />
                    {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.s_state}
                </div>
                <div class="break"></div>
    
                <div style="width: 90px; display: inline; float: left;">
                    <label><span class="required">*</span>Zip / Postcode</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="{$castring}{$ca.s_zip_code.id}" value="{$ca.s_zip_code.content|wash()}" style="width: 170px;"/>
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_zip_code.id}" />
                </div>
                <div class="break"></div>

<div style="width: 90px; display: inline; float: left;">
    <label><span class="required">*</span>Country</label>
    <div class="labelbreak"></div>

    {def $is_set_s=is_set($s_country)}
    {if $is_set_s}{def $s_country=$country_default_ini}{else}{def $s_country=''}{/if}

        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_country.id}" />
            <select name="ContentObjectAttribute_country_{$ca.s_country.id}" style="width: 17 0px;">
                <option value=""></option>
                {foreach $countries as $country_list_item}
                 {def $country_list_item_code=''}
                 {set $country_list_item_code=$country_list_item.Alpha2}
                 <option value="{$country_list_item_code}"{if eq( $country_default, $country_list_item_code )} selected="selected"{/if}{if eq( $s_country, $country_list_item_code)} selected="selected"{/if}>{$country_list_item.Name}</option>
                  {undef $country_list_item_code}
                {/foreach}
            </select>
</div>
    <div class="break"></div>
                <div class="block">
                	<label><span class="required">*</span>Phone</label>
                	<div class="labelbreak"></div>
                	<input class="box" type="text" name="{$castring}{$ca.s_phone.id}" value="{$ca.s_phone.content|wash()}" style="width: 170px;" />
                	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_phone.id}" />
                </div>
                
                <div class="block">
                	<label><span class="required">*</span>E-mail</label>
                	<div class="labelbreak"></div>
                	<input class="box" type="text" name="ContentObjectAttribute_data_text_{$ca.s_email.id}" value="{$ca.s_email.content|wash()}" style="width: 170px;" />
                	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_email.id}" />
                </div>
                    <div class="break"></div>
                </div>
                    </div>
                        <div style="width: 390px; float: left; display: inline; margin:5px; padding: 8px;">
                    <div style="text-align: right;">
                    <input class="button" type="submit" name="DCancelButton" value="{'Cancel'|i18n('design/base')}" />
                    <input class="right-arrow" type="submit" name="PublishButton" value="Update">
                    </div>
                    </div>
       </div>
        {* end of right frame *}
    </form>        
</div>

<script type="text/javascript">
function change(status)
{ldelim}
if (status)
    {ldelim}
    document.getElementById("shippinginfo").style.display = 'none';
    {rdelim}
    else
    {ldelim}
            document.getElementById("shippinginfo").style.display = 'block';
    {rdelim}
{rdelim}

function shipping(status)
{ldelim}
if (document.register.Shipping.checked == false)
    {ldelim}
        status = document.register.scountry.value;
    {rdelim}
{rdelim}
</script>