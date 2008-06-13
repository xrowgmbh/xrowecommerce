{def $ca=$content_attributes_data_map
     $castring="ContentObjectAttribute_ezstring_data_text_"}

     {* left frame start *}    
    <div style="border: 1px solid #ebeeef; width: 290px; float: left; display: inline; margin:5px; padding: 8px;">
        <span class="heading1">Account Information</span>
        <br />
        <p>Although your Login User Name must be kept the same, you can update your Email Address and/or change your Password by entering new information in the appropriate field.  When complete, click the "Update" button at the bottom of the page.</p>
        <p><span class="required">* Required field</span></p>
        <br />
        <div class="block">
        	<label><span class="required">*</span>Username</label>
        	<div class="labelbreak"></div>
        	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.user_account.id}" size="20" />
        	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.user_account.id}" />
        	<input name="ContentObjectAttribute_data_user_login_{$ca.user_account.id}" value="{$ca.user_account.content.login}" type="text" disabled="disabled" />
        </div>
        <div class="break"></div>
        
        <div class="block">
        	<label><span class="required">*</span>E-mail</label>
        	<div class="labelbreak"></div>
        	<input class="box" type="text" name="ContentObjectAttribute_data_user_email_{$ca.user_account.id}" value="{$ca.user_account.content.email|wash()}" size="20"  />
        </div>
        <div class="break"></div>

        <div class="block">
        	<label>Password</label>
        	<div class="labelbreak"></div>
        	<input class="box" type="password" name="ContentObjectAttribute_data_user_password_{$ca.user_account.id}" value="{section show=$ca.user_account.content.original_password}{$ca.user_account.content.original_password}{section-else}{section show=$ca.user_account.content.has_stored_login}_ezpassword{/section}{/section}" size="20" />
        </div>
        <div class="break"></div>
        <div class="block">
        	<label>Confirm Password</label>
        	<div class="labelbreak"></div>
        	<input class="box" type="password" name="ContentObjectAttribute_data_user_password_confirm_{$ca.user_account.id}" value="{section show=$ca.user_account.content.original_password_confirm}{$ca.user_account.content.original_password_confirm}{section-else}{section show=$ca.user_account.content.has_stored_login}_ezpassword{/section}{/section}" size="20" />
        </div>
        <div class="break"></div>
        <br />
        
    </div>
    
    
    
    
{* left frame end *}
     <div style="float: left;">

{* right frame start *}
    <div style="border: 1px solid #ebeeef; width: 390px; float: left; display: inline; margin:5px; padding: 8px;">
        <span class="heading1">Billing Information</span>
        <br />
        <p>Please enter your billing address exactly as it appears on your credit card statement.</p>
        <p><span class="required">* Required field</span></p>
        
        <input type="hidden" name="MainNodeID" value="{$main_node_id}" />

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
            	<label>State / Province</label>
            	<div class="labelbreak"></div>
            	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.state.id}" />
            	{attribute_edit_gui attribute_base=$attribute_base attribute=$ca.state}
            </div>
    
            <div style="width: 90px; display: inline; float: left;">
                <label><span class="required">*</span>Zip / Postcode</label>
                <div class="labelbreak"></div>
                <input type="text" name="{$castring}{$ca.zip_code.id}" value="{$ca.zip_code.content|wash()}" style="width: 80px;"/>
                <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.zip_code.id}" />
            </div>
                <div class="break"></div>

<div style="width: 180px; display: inline; float: left;">
    <label><span class="required">*</span>Country</label>
    <div class="labelbreak"></div>
    {*TODO we MAY has to set a default country*}
    {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.country}
</div>
    <div class="break"></div>

            

            <div class="block">
            	<label><span class="required">*</span>Phone</label>
            	<div class="labelbreak"></div>
            	<input class="box" type="text" name="{$castring}{$ca.phone.id}" value="{$ca.phone.content|wash()}" style="width: 170px;" />
            	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.phone.id}" />
            </div>
            
            <div class="break"></div>
            
            
            <div class="block">
            	<label><span class="required">*</span>Shipping</label>
            	<div class="labelbreak"></div>
            	{attribute_edit_gui attribute_base=$attribute_base attribute=$ca.shippingtype}
            	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.shippingtype.id}" />
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
		            <p>My billing and shipping addresses are identical.</p>
		        </td>
	        </tr>
        </table>

        <div class="block" id="shippinginfo">

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
            	<label>State / Province</label>
            	<div class="labelbreak"></div>
            	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_state.id}" />
                {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.s_state}
            </div>
    
            <div style="width: 90px; display: inline; float: left;">
                <label><span class="required">*</span>Zip / Postcode</label>
                <div class="labelbreak"></div>
                <input type="text" name="{$castring}{$ca.s_zip_code.id}" value="{$ca.s_zip_code.content|wash()}" style="width: 80px;"/>
                <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_zip_code.id}" />
            </div>
            <div class="break"></div>
            
<div style="width: 90px; display: inline; float: left;">
    <label><span class="required">*</span>Country</label>
    <div class="labelbreak"></div>
    {*TODO we MAY has to set a default country*}
    {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.s_country}
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
    {if $ca.creditcard}
        <div style="border: 1px solid #ebeeef; width: 390px; float: left; display: inline; margin:5px; padding: 8px;">
        <span class="heading1">Credi tcard Information</span><br />
        <p>
            Your credit card information is needed, if you want to make use of our recurring order option.
        </p>
        <label{section show=$ca.creditcard.has_validation_error} class="validation-error"{/section}>{$ca.creditcard.contentclass_attribute.name|wash}</label><div class="labelbreak" />
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.creditcard.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.creditcard}
        </div>
        </div>
    {/if}
    <div style="width: 390px; float: left; display: inline; margin:5px; padding: 8px;">
        <div style="text-align: right;">
            <input class="left-arrow2" type="submit" name="DiscardButton" value="{'Cancel'|i18n('design/base')}" />
            <input class="right-arrow" type="submit" name="PublishButton" value="Update" />
            <input type="hidden" name="DiscardConfirm" value="0" />
            <input type="hidden" name="RedirectURI" value={''|ezurl()} />
            <input type="hidden" name="RedirectIfDiscarded" value="{ezhttp('LastAccessesURI','session')}" /> 
            <br/>
        </div>
    </div>
    
{* right frame end *}

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
