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
        	<input name="ContentObjectAttribute_data_user_login_{$ca.user_account.id}" value="{$ca.user_account.content.login}" type="text" disabled="disabled" >
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
        {*<div class="buttonblock" style="text-align: right;">
            <input type="image" src={"images/cancel_small.gif"|ezdesign()} name="DiscardButton" value="{'Cancel'|i18n('design/base')}" />
            <input type="image" src={"images/update.gif"|ezdesign()} name="PublishButton" value="Update">
        </div>*}
        
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
            	<label><span class="required">*</span>State / Province</label>
            	<div class="labelbreak"></div>
            	<input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.state.id}" />
        	    <select name="ContentObjectAttribute_ezstring_data_text_{$ca.state.id}" style="width: 80px;">
        	    	{def $state=$ca.state.content|wash()}
			<option value=""></option>
    	      <optgroup label="USA">
    			<option  {if eq($state,'AL')} selected {/if} >AL</option>
    			<option  {if eq($state,'AK')} selected {/if} >AK</option>
    			<option  {if eq($state,'AS')} selected {/if} >AS</option>
    			<option  {if eq($state,'AZ')} selected {/if} >AZ</option>
    			<option  {if eq($state,'AR')} selected {/if} >AR</option>
    			<option  {if eq($state,'CA')} selected {/if} >CA</option>
    			<option  {if eq($state,'CO')} selected {/if} >CO</option>
    			<option  {if eq($state,'CT')} selected {/if} >CT</option>
    			<option  {if eq($state,'DE')} selected {/if} >DE</option>
    			<option  {if eq($state,'DC')} selected {/if} >DC</option>
    			<option  {if eq($state,'FM')} selected {/if} >FM</option>
    			<option  {if eq($state,'FL')} selected {/if} >FL</option>
    			<option  {if eq($state,'GA')} selected {/if} >GA</option>
    			<option  {if eq($state,'GU')} selected {/if} >GU</option>
    			<option  {if eq($state,'HI')} selected {/if} >HI</option>
    			<option  {if eq($state,'ID')} selected {/if} >ID</option>
    			<option  {if eq($state,'IL')} selected {/if} >IL</option>
    			<option  {if eq($state,'IN')} selected {/if} >IN</option>
    			<option  {if eq($state,'IA')} selected {/if} >IA</option>
    			<option  {if eq($state,'KS')} selected {/if} >KS</option>
    			<option  {if eq($state,'KY')} selected {/if} >KY</option>
    			<option  {if eq($state,'LA')} selected {/if} >LA</option>
    			<option  {if eq($state,'ME')} selected {/if} >ME</option>
    			<option  {if eq($state,'MH')} selected {/if} >MH</option>
    			<option  {if eq($state,'MD')} selected {/if} >MD</option>
    			<option  {if eq($state,'MA')} selected {/if} >MA</option>
    			<option  {if eq($state,'MI')} selected {/if} >MI</option>
    			<option  {if eq($state,'MN')} selected {/if} >MN</option>
    			<option  {if eq($state,'MS')} selected {/if} >MS</option>
    			<option  {if eq($state,'MO')} selected {/if} >MO</option>
    			<option  {if eq($state,'MT')} selected {/if} >MT</option>
    			<option  {if eq($state,'NE')} selected {/if} >NE</option>
    			<option  {if eq($state,'NV')} selected {/if} >NV</option>
    			<option  {if eq($state,'NH')} selected {/if} >NH</option>
    			<option  {if eq($state,'NJ')} selected {/if} >NJ</option>
    			<option  {if eq($state,'NM')} selected {/if} >NM</option>
    			<option  {if eq($state,'NY')} selected {/if} >NY</option>
    			<option  {if eq($state,'NC')} selected {/if} >NC</option>
    			<option  {if eq($state,'ND')} selected {/if} >ND</option>
    			<option  {if eq($state,'MP')} selected {/if} >MP</option>
    			<option  {if eq($state,'OH')} selected {/if} >OH</option>
    			<option  {if eq($state,'OK')} selected {/if} >OK</option>
    			<option  {if eq($state,'OR')} selected {/if} >OR</option>
    			<option  {if eq($state,'PW')} selected {/if} >PW</option>
    			<option  {if eq($state,'PA')} selected {/if} >PA</option>
    			<option  {if eq($state,'PR')} selected {/if} >PR</option>
    			<option  {if eq($state,'RI')} selected {/if} >RI</option>
    			<option  {if eq($state,'SC')} selected {/if} >SC</option>
    			<option  {if eq($state,'SD')} selected {/if} >SD</option>
    			<option  {if eq($state,'TN')} selected {/if} >TN</option>
    			<option  {if eq($state,'TX')} selected {/if} >TX</option>
    			<option  {if eq($state,'UT')} selected {/if} >UT</option>
    			<option  {if eq($state,'VT')} selected {/if} >VT</option>
    			<option  {if eq($state,'VI')} selected {/if} >VI</option>
    			<option  {if eq($state,'VA')} selected {/if} >VA</option>
    			<option  {if eq($state,'WA')} selected {/if} >WA</option>
    			<option  {if eq($state,'WV')} selected {/if} >WV</option>
    			<option  {if eq($state,'WI')} selected {/if} >WI</option>
    			<option  {if eq($state,'WY')} selected {/if} >WY</option>
			 </optgroup>
		     <optgroup label="Canada">
		        <option  {if eq($state,'AB')} selected {/if} >AB</option>
		        <option  {if eq($state,'BC')} selected {/if} >BC</option>
    			<option  {if eq($state,'MB')} selected {/if} >MB</option>
    			<option  {if eq($state,'NB')} selected {/if} >NB</option>
		        <option  {if eq($state,'NF')} selected {/if} >NF</option>
		        <option  {if eq($state,'NS')} selected {/if} >NS</option>
		        <option  {if eq($state,'NT')} selected {/if} >NT</option>
    			<option  {if eq($state,'ON')} selected {/if} >ON</option>
		        <option  {if eq($state,'PE')} selected {/if} >PE</option>
    			<option  {if eq($state,'PQ')} selected {/if} >PQ</option>
    			<option  {if eq($state,'SK')} selected {/if} >SK</option>
    			<option  {if eq($state,'YT')} selected {/if} >YT</option>
		     </optgroup>
		     <optgroup label="Mexico">
		        <option  {if eq($state,'AG')} selected {/if} >AG</option>
		        <option  {if eq($state,'BC')} selected {/if} >BC</option>
    			<option  {if eq($state,'BS')} selected {/if} >BS</option>
    			<option  {if eq($state,'CH')} selected {/if} >CH</option>
		        <option  {if eq($state,'CL')} selected {/if} >CL</option>
		        <option  {if eq($state,'CM')} selected {/if} >CM</option>
		        <option  {if eq($state,'CO')} selected {/if} >CO</option>
    			<option  {if eq($state,'CS')} selected {/if} >CS</option>
		        <option  {if eq($state,'DF')} selected {/if} >DF</option>
    			<option  {if eq($state,'DG')} selected {/if} >DG</option>
    			<option  {if eq($state,'GR')} selected {/if} >GR</option>
    			<option  {if eq($state,'GT')} selected {/if} >GT</option>
    			<option  {if eq($state,'HG')} selected {/if} >HG</option>
		        <option  {if eq($state,'JA')} selected {/if} >JA</option>
    			<option  {if eq($state,'MI')} selected {/if} >MI</option>
    			<option  {if eq($state,'MO')} selected {/if} >MO</option>
		        <option  {if eq($state,'MX')} selected {/if} >MX</option>
		        <option  {if eq($state,'NA')} selected {/if} >NA</option>
		        <option  {if eq($state,'NL')} selected {/if} >NL</option>
    			<option  {if eq($state,'OA')} selected {/if} >OA</option>
		        <option  {if eq($state,'PU')} selected {/if} >PU</option>
    			<option  {if eq($state,'QR')} selected {/if} >QR</option>
    			<option  {if eq($state,'QT')} selected {/if} >QT</option>
    			<option  {if eq($state,'SI')} selected {/if} >SI</option>
    			<option  {if eq($state,'SL')} selected {/if} >SL</option>
		        <option  {if eq($state,'SO')} selected {/if} >SO</option>
    			<option  {if eq($state,'TB')} selected {/if} >TB</option>
    			<option  {if eq($state,'TL')} selected {/if} >TL</option>
		        <option  {if eq($state,'TM')} selected {/if} >TM</option>
		        <option  {if eq($state,'VE')} selected {/if} >VE</option>
		        <option  {if eq($state,'YU')} selected {/if} >YU</option>
    			<option  {if eq($state,'ZA')} selected {/if} >ZA</option>
		     </optgroup>
        	    </select>
            </div>
            {undef $state}
    
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

    {def $country_default=''}
    {def $is_set=is_set($country_value)}
    {if $is_set}{def $country_value='USA'}{else}{def $country_value=$ca.country.content|wash()}{/if}
    {def $countries=wrap_user_func('getCountryList', array(false, false))}

            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.country.id}" />
            <select name="ContentObjectAttribute_ezstring_data_text_{$ca.country.id}" style="width: 170px;">
                <option value=""></option>
                {foreach $countries as $country_list_item}
                  {if $country_list_item.Alpha3|eq('')}{def $country_list_item_code=$country_list_item.Alpha2}{else}{def $country_list_item_code=$country_list_item.Alpha3}{/if}
                 <option value="{$country_list_item_code}"{if eq( $country_default, $country_list_item_code )} selected="selected"{/if}{if eq( $country_value, $country_list_item_code)} selected="selected"{/if}>{$country_list_item.Name}</option>
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
    {*<div style="text-align: right;">
            <input type="image" src={"images/update.gif"|ezdesign()} name="PublishButton" value="Update">
        </div>*}
    </div>
    
    <div style="border: 1px solid #ebeeef; width: 390px; float: left; display: inline; margin:5px; padding: 8px;">
        <span class="heading1">Shipping Information</span><br />
        <table border="0">
        <tr>
        <td>
            <input name="ContentObjectAttribute_data_boolean_{$ca.shippingaddress.id}" value="" type="checkbox" {$ca.shippingaddress.data_int|choose( '', 'checked="checked"' )} >
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.shippingaddress.id}" />
        </td>
        <td>
            <p>
            My billing and shipping addresses are identical (please leave form blank below).
        </p>
        </td>
        </tr>
        </table>
        
        
        <div class="block">
   
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
            	<select name="ContentObjectAttribute_ezstring_data_text_{$ca.s_state.id}" style="width: 80px;">
        	    	
        	    	{def $state=$ca.s_state.content|wash()}
        			<option value=""></option>
	    	<optgroup label="USA">
    			<option  {if eq($state,'AL')} selected {/if} >AL</option>
    			<option  {if eq($state,'AK')} selected {/if} >AK</option>
    			<option  {if eq($state,'AS')} selected {/if} >AS</option>
    			<option  {if eq($state,'AZ')} selected {/if} >AZ</option>
    			<option  {if eq($state,'AR')} selected {/if} >AR</option>
    			<option  {if eq($state,'CA')} selected {/if} >CA</option>
    			<option  {if eq($state,'CO')} selected {/if} >CO</option>
    			<option  {if eq($state,'CT')} selected {/if} >CT</option>
    			<option  {if eq($state,'DE')} selected {/if} >DE</option>
    			<option  {if eq($state,'DC')} selected {/if} >DC</option>
    			<option  {if eq($state,'FM')} selected {/if} >FM</option>
    			<option  {if eq($state,'FL')} selected {/if} >FL</option>
    			<option  {if eq($state,'GA')} selected {/if} >GA</option>
    			<option  {if eq($state,'GU')} selected {/if} >GU</option>
    			<option  {if eq($state,'HI')} selected {/if} >HI</option>
    			<option  {if eq($state,'ID')} selected {/if} >ID</option>
    			<option  {if eq($state,'IL')} selected {/if} >IL</option>
    			<option  {if eq($state,'IN')} selected {/if} >IN</option>
    			<option  {if eq($state,'IA')} selected {/if} >IA</option>
    			<option  {if eq($state,'KS')} selected {/if} >KS</option>
    			<option  {if eq($state,'KY')} selected {/if} >KY</option>
    			<option  {if eq($state,'LA')} selected {/if} >LA</option>
    			<option  {if eq($state,'ME')} selected {/if} >ME</option>
    			<option  {if eq($state,'MH')} selected {/if} >MH</option>
    			<option  {if eq($state,'MD')} selected {/if} >MD</option>
    			<option  {if eq($state,'MA')} selected {/if} >MA</option>
    			<option  {if eq($state,'MI')} selected {/if} >MI</option>
    			<option  {if eq($state,'MN')} selected {/if} >MN</option>
    			<option  {if eq($state,'MS')} selected {/if} >MS</option>
    			<option  {if eq($state,'MO')} selected {/if} >MO</option>
    			<option  {if eq($state,'MT')} selected {/if} >MT</option>
    			<option  {if eq($state,'NE')} selected {/if} >NE</option>
    			<option  {if eq($state,'NV')} selected {/if} >NV</option>
    			<option  {if eq($state,'NH')} selected {/if} >NH</option>
    			<option  {if eq($state,'NJ')} selected {/if} >NJ</option>
    			<option  {if eq($state,'NM')} selected {/if} >NM</option>
    			<option  {if eq($state,'NY')} selected {/if} >NY</option>
    			<option  {if eq($state,'NC')} selected {/if} >NC</option>
    			<option  {if eq($state,'ND')} selected {/if} >ND</option>
    			<option  {if eq($state,'MP')} selected {/if} >MP</option>
    			<option  {if eq($state,'OH')} selected {/if} >OH</option>
    			<option  {if eq($state,'OK')} selected {/if} >OK</option>
    			<option  {if eq($state,'OR')} selected {/if} >OR</option>
    			<option  {if eq($state,'PW')} selected {/if} >PW</option>
    			<option  {if eq($state,'PA')} selected {/if} >PA</option>
    			<option  {if eq($state,'PR')} selected {/if} >PR</option>
    			<option  {if eq($state,'RI')} selected {/if} >RI</option>
    			<option  {if eq($state,'SC')} selected {/if} >SC</option>
    			<option  {if eq($state,'SD')} selected {/if} >SD</option>
    			<option  {if eq($state,'TN')} selected {/if} >TN</option>
    			<option  {if eq($state,'TX')} selected {/if} >TX</option>
    			<option  {if eq($state,'UT')} selected {/if} >UT</option>
    			<option  {if eq($state,'VT')} selected {/if} >VT</option>
    			<option  {if eq($state,'VI')} selected {/if} >VI</option>
    			<option  {if eq($state,'VA')} selected {/if} >VA</option>
    			<option  {if eq($state,'WA')} selected {/if} >WA</option>
    			<option  {if eq($state,'WV')} selected {/if} >WV</option>
    			<option  {if eq($state,'WI')} selected {/if} >WI</option>
    			<option  {if eq($state,'WY')} selected {/if} >WY</option>
			 </optgroup>
		     <optgroup label="Canada">
		        <option  {if eq($state,'AB')} selected {/if} >AB</option>
		        <option  {if eq($state,'BC')} selected {/if} >BC</option>
    			<option  {if eq($state,'MB')} selected {/if} >MB</option>
    			<option  {if eq($state,'NB')} selected {/if} >NB</option>
		        <option  {if eq($state,'NF')} selected {/if} >NF</option>
		        <option  {if eq($state,'NS')} selected {/if} >NS</option>
		        <option  {if eq($state,'NT')} selected {/if} >NT</option>
    			<option  {if eq($state,'ON')} selected {/if} >ON</option>
		        <option  {if eq($state,'PE')} selected {/if} >PE</option>
    			<option  {if eq($state,'PQ')} selected {/if} >PQ</option>
    			<option  {if eq($state,'SK')} selected {/if} >SK</option>
    			<option  {if eq($state,'YT')} selected {/if} >YT</option>
		     </optgroup>
		     <optgroup label="Mexico">
		        <option  {if eq($state,'AG')} selected {/if} >AG</option>
		        <option  {if eq($state,'BC')} selected {/if} >BC</option>
    			<option  {if eq($state,'BS')} selected {/if} >BS</option>
    			<option  {if eq($state,'CH')} selected {/if} >CH</option>
		        <option  {if eq($state,'CL')} selected {/if} >CL</option>
		        <option  {if eq($state,'CM')} selected {/if} >CM</option>
		        <option  {if eq($state,'CO')} selected {/if} >CO</option>
    			<option  {if eq($state,'CS')} selected {/if} >CS</option>
		        <option  {if eq($state,'DF')} selected {/if} >DF</option>
    			<option  {if eq($state,'DG')} selected {/if} >DG</option>
    			<option  {if eq($state,'GR')} selected {/if} >GR</option>
    			<option  {if eq($state,'GT')} selected {/if} >GT</option>
    			<option  {if eq($state,'HG')} selected {/if} >HG</option>
		        <option  {if eq($state,'JA')} selected {/if} >JA</option>
    			<option  {if eq($state,'MI')} selected {/if} >MI</option>
    			<option  {if eq($state,'MO')} selected {/if} >MO</option>
		        <option  {if eq($state,'MX')} selected {/if} >MX</option>
		        <option  {if eq($state,'NA')} selected {/if} >NA</option>
		        <option  {if eq($state,'NL')} selected {/if} >NL</option>
    			<option  {if eq($state,'OA')} selected {/if} >OA</option>
		        <option  {if eq($state,'PU')} selected {/if} >PU</option>
    			<option  {if eq($state,'QR')} selected {/if} >QR</option>
    			<option  {if eq($state,'QT')} selected {/if} >QT</option>
    			<option  {if eq($state,'SI')} selected {/if} >SI</option>
    			<option  {if eq($state,'SL')} selected {/if} >SL</option>
		        <option  {if eq($state,'SO')} selected {/if} >SO</option>
    			<option  {if eq($state,'TB')} selected {/if} >TB</option>
    			<option  {if eq($state,'TL')} selected {/if} >TL</option>
		        <option  {if eq($state,'TM')} selected {/if} >TM</option>
		        <option  {if eq($state,'VE')} selected {/if} >VE</option>
		        <option  {if eq($state,'YU')} selected {/if} >YU</option>
    			<option  {if eq($state,'ZA')} selected {/if} >ZA</option>
		     </optgroup>
        	    </select>
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

    {def $s_country_default=''}
    {def $is_set=is_set($s_country_value)}
    {if $is_set}{def $s_country_value='USA'}{else}{def $s_country_value=$ca.s_country.content|wash()}{/if}

            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_country.id}" />
            <select name="ContentObjectAttribute_ezstring_data_text_{$ca.s_country.id}" style="width: 170px;">
                <option value=""></option>
                {foreach $countries as $country_list_item}
                  {if $country_list_item.Alpha3|eq('')}{def $country_list_item_code=$country_list_item.Alpha2}{else}{def $country_list_item_code=$country_list_item.Alpha3}{/if}
                 <option value="{$country_list_item_code}"{if eq( $s_country_default, $country_list_item_code )} selected="selected"{/if}{if eq( $s_country_value, $country_list_item_code)} selected="selected"{/if}>{$country_list_item.Name}</option>
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
    {if $ca.creditcard}
        <div style="border: 1px solid #ebeeef; width: 390px; float: left; display: inline; margin:5px; padding: 8px;">
        <span class="heading1">Credi tcard Information</span><br />
        <p>
            Your credit card information is needed, if you want to make use of our recurring order option.
        </p>
        <label{section show=$ca.creditcard.has_validation_error} class="validation-error"{/section}>{$ca.creditcard.contentclass_attribute.name|wash}</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.creditcard.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.creditcard}
        </div>
        </div>
    {/if}
    {*    
    
    <div style="width: 390px; float: left; display: inline; margin:5px; padding: 8px;">
        <div style="text-align: right;">
            <input type="image" src={"images/update.gif"|ezdesign()} name="PublishButton" value="Update">
        </div>
    </div>


   <div style="border: 1px solid #ebeeef; width: 390px; float: left; display: inline; margin:5px; padding: 8px;">
        <span class="heading1">My Colors</span>
        <br />
        <br />
        <table>
            <tr>
                <td>Eyes:</td>
                <td>
                    {def $selected_id_array=$ca.eyes.content
                        $attribute=$ca.eyes
                        $attribute_base=ContentObjectAttribute}
                    
                        <input type="hidden" name="{$attribute_base}_ezselect_selected_array_{$attribute.id}" value="">
                        <select id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}" class="ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" name="{$attribute_base}_ezselect_selected_array_{$attribute.id}[]" {section show=$attribute.class_content.is_multiselect}multiple{/section}>
                        {section var=Options loop=$attribute.class_content.options}
                        <option value="{$Options.item.id}" {section show=$selected_id_array|contains( $Options.item.id )}selected="selected"{/section}>{$Options.item.name|wash( xhtml )}</option>
                        {/section}
                        </select>
                </td>
            </tr>
            <tr>
                <td>Hair:</td>
                <td>
                    {def $selected_id_array=$ca.hair.content
                        $attribute=$ca.hair
                        $attribute_base=ContentObjectAttribute}
                    
                            <input type="hidden" name="{$attribute_base}_ezselect_selected_array_{$attribute.id}" value="">
                            <select id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}" class="ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" name="{$attribute_base}_ezselect_selected_array_{$attribute.id}[]" {section show=$attribute.class_content.is_multiselect}multiple{/section}>
                            {section var=Options loop=$attribute.class_content.options}
                            <option value="{$Options.item.id}" {section show=$selected_id_array|contains( $Options.item.id )}selected="selected"{/section}>{$Options.item.name|wash( xhtml )}</option>
                            {/section}
                            </select>
                </td>
            </tr>
            <tr>
                <td>Skin Type:</td>
                <td>
                    {def $selected_id_array=$ca.skin_type.content
                        $attribute=$ca.skin_type
                        $attribute_base=ContentObjectAttribute}
                    
                            <input type="hidden" name="{$attribute_base}_ezselect_selected_array_{$attribute.id}" value="">
                            <select id="ezcoa-{if ne( $attribute_base, 'ContentObjectAttribute' )}{$attribute_base}-{/if}{$attribute.contentclassattribute_id}_{$attribute.contentclass_attribute_identifier}" class="ezcc-{$attribute.object.content_class.identifier} ezcca-{$attribute.object.content_class.identifier}_{$attribute.contentclass_attribute_identifier}" name="{$attribute_base}_ezselect_selected_array_{$attribute.id}[]" {section show=$attribute.class_content.is_multiselect}multiple{/section}>
                            {section var=Options loop=$attribute.class_content.options}
                            <option value="{$Options.item.id}" {section show=$selected_id_array|contains( $Options.item.id )}selected="selected"{/section}>{$Options.item.name|wash( xhtml )}</option>
                            {/section}
                            </select>
                </td>
            </tr>
        </table>
    </div>
*}
    <div style="width: 390px; float: left; display: inline; margin:5px; padding: 8px;">
        <div style="text-align: right;">
            <input type="image" src={"images/cancel_small.gif"|ezdesign()} name="DiscardButton" value="{'Cancel'|i18n('design/base')}" />
            <input type="image" src={"images/update.gif"|ezdesign()} name="PublishButton" value="Update">
            <input type="hidden" name="DiscardConfirm" value="0" /></div>
            {* <input type="hidden" name="RedirectURI" value="content/edit/{$object.id}/f/eng-US" /> *}
            {* <input type="hidden" name=" RedirectURIAfterPublish" value={"/"|ezurl} /> *}
            <input type="hidden" name="RedirectURI" value={''|ezurl} />
            <input type="hidden" name="RedirectIfDiscarded" value={ezhttp('LastAccessesURI','session')} /> 
            <br/>
        </div>
    </div>
    
{* right frame end *}
