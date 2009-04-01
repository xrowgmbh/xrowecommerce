{def $current_user=fetch( 'user', 'current_user' )}
<div class="shop shop-userregister">

<form method="post" action={"xrowecommerce/userregister/"|ezurl} name='register' >
<input type="submit" class="hide" sytle="display: hide;" name="StoreButton" value="{'Continue'|i18n('extension/xrowecommerce')}" />
<h1>{'My Account'|i18n('extension/xrowecommerce')}</h1>

{include uri="design:shop/basket_navigator.tpl" step='2'}

{include uri="design:shop/userregister_preface.tpl"}
{if $input_error}
<div class="warning">
<h2>{'Validation error'|i18n('extension/xrowecommerce')}</h2>
<ul>
<li>{'Input did not validate, all fields marked with %mark% must be filled in.'|i18n('extension/xrowecommerce', , hash( '%mark%', '<span class="required">*</span>' ))}</li>
</ul>
</div>
{/if}

{if $errors}
<div class="warning">
<h2>{'Validation errors'|i18n('extension/xrowecommerce')}</h2>
<ul>
{foreach $errors as $error}
<li>{$error}</li>
{/foreach}
</ul>
</div>
{/if}
<div class="billing">

<h3>{'Billing Information'|i18n('extension/xrowecommerce')}</h3>
<p><span class="required">* <i>{'Required field'|i18n('extension/xrowecommerce')}</i></span></p>
<div class="labelbreak"></div>


{if eq(ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' ), 'enabled' )}
    <div class="ur_companyname">
        <label>{'Company name'|i18n('extension/xrowecommerce')}</label>
        <div class="labelbreak"></div>
        <input type="text" name="companyname" value="{$company_name|wash}" />
    </div>
{/if}
{if eq(ezini( 'Settings', 'CompanyAdditional', 'xrowecommerce.ini' ), 'enabled' )}
    <div class="ur_company_additional">
        <label>{'Company additional information'|i18n('extension/xrowecommerce')}</label>
        <div class="labelbreak"></div>
        <input type="text" name="companyadditional" value="{$company_additional|wash}" />
    </div>
{/if}
{if eq(ezini( 'Settings', 'TaxID', 'xrowecommerce.ini' ), 'enabled' )}
    <div class="ur_taxid">
        <label>{'Tax ID'|i18n('extension/xrowecommerce')}</label>
        <div class="labelbreak"></div>
        <input type="text" name="TaxID" value="{$tax_id|wash}" />
    </div>
{/if}
	<div class="ur_firstname">
    	<label>{'First name'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
    	<div class="labelbreak"></div>
    	<input type="text" name="FirstName" id="firstname" value="{$first_name|wash}" />
    </div>
{if eq(ezini( 'Settings', 'MI', 'xrowecommerce.ini' ), 'enabled' )}
	<div class="ur_mi">
    	<label>{'MI'|i18n('extension/xrowecommerce')}</label>
    	<div class="labelbreak"></div>
    	<input class="halfbox" type="text" name="MI" id="mi" size="2" value="{$mi|wash}" />
    </div>
{/if}
    <div class="ur_lastname">
    	<label>{'Last name'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
    	<div class="labelbreak"></div>
    	<input type="text" name="LastName" id="lastname" value="{$last_name|wash}" />
    </div>

	<div class="block">
		<label>{'Address 1'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
		<div class="labelbreak"></div>
		<input class="box" type="text" name="Address1" id="address1" size="20" value="{$address1|wash}" />
	</div>

	<div class="block">
		<label>{'Address 2'|i18n('extension/xrowecommerce')}</label>
		<div class="labelbreak"></div>
		<input class="box" type="text" name="Address2" id="address2" size="20" value="{$address2|wash}" />
	</div>

	<div class="city">
    	<label>{'City'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
    	<div class="labelbreak"></div>
    	<input type="text" name="City" id="city" value="{$city|wash}" />
    </div>
{if eq(ezini( 'Settings', 'State', 'xrowecommerce.ini' ), 'enabled' )}
    <div class="state">
    	<label>{'State / Province'|i18n('extension/xrowecommerce')}</label>
    	<div class="labelbreak"></div>
	    <select class="state" name="State" id="state">
	    <option value="">&nbsp;</option>
	    	<optgroup label="USA">
    			<option  {if eq($state,'AL')} selected="selected" {/if} >AL</option>
    			<option  {if eq($state,'AK')} selected="selected" {/if} >AK</option>
    			<option  {if eq($state,'AS')} selected="selected" {/if} >AS</option>
    			<option  {if eq($state,'AZ')} selected="selected" {/if} >AZ</option>
    			<option  {if eq($state,'AR')} selected="selected" {/if} >AR</option>
    			<option  {if eq($state,'CA')} selected="selected" {/if} >CA</option>
    			<option  {if eq($state,'CO')} selected="selected" {/if} >CO</option>
    			<option  {if eq($state,'CT')} selected="selected" {/if} >CT</option>
    			<option  {if eq($state,'DE')} selected="selected" {/if} >DE</option>
    			<option  {if eq($state,'DC')} selected="selected" {/if} >DC</option>
    			<option  {if eq($state,'FM')} selected="selected" {/if} >FM</option>
    			<option  {if eq($state,'FL')} selected="selected" {/if} >FL</option>
    			<option  {if eq($state,'GA')} selected="selected" {/if} >GA</option>
    			<option  {if eq($state,'GU')} selected="selected" {/if} >GU</option>
    			<option  {if eq($state,'HI')} selected="selected" {/if} >HI</option>
    			<option  {if eq($state,'ID')} selected="selected" {/if} >ID</option>
    			<option  {if eq($state,'IL')} selected="selected" {/if} >IL</option>
    			<option  {if eq($state,'IN')} selected="selected" {/if} >IN</option>
    			<option  {if eq($state,'IA')} selected="selected" {/if} >IA</option>
    			<option  {if eq($state,'KS')} selected="selected" {/if} >KS</option>
    			<option  {if eq($state,'KY')} selected="selected" {/if} >KY</option>
    			<option  {if eq($state,'LA')} selected="selected" {/if} >LA</option>
    			<option  {if eq($state,'ME')} selected="selected" {/if} >ME</option>
    			<option  {if eq($state,'MH')} selected="selected" {/if} >MH</option>
    			<option  {if eq($state,'MD')} selected="selected" {/if} >MD</option>
    			<option  {if eq($state,'MA')} selected="selected" {/if} >MA</option>
    			<option  {if eq($state,'MI')} selected="selected" {/if} >MI</option>
    			<option  {if eq($state,'MN')} selected="selected" {/if} >MN</option>
    			<option  {if eq($state,'MS')} selected="selected" {/if} >MS</option>
    			<option  {if eq($state,'MO')} selected="selected" {/if} >MO</option>
    			<option  {if eq($state,'MT')} selected="selected" {/if} >MT</option>
    			<option  {if eq($state,'NE')} selected="selected" {/if} >NE</option>
    			<option  {if eq($state,'NV')} selected="selected" {/if} >NV</option>
    			<option  {if eq($state,'NH')} selected="selected" {/if} >NH</option>
    			<option  {if eq($state,'NJ')} selected="selected" {/if} >NJ</option>
    			<option  {if eq($state,'NM')} selected="selected" {/if} >NM</option>
    			<option  {if eq($state,'NY')} selected="selected" {/if} >NY</option>
    			<option  {if eq($state,'NC')} selected="selected" {/if} >NC</option>
    			<option  {if eq($state,'ND')} selected="selected" {/if} >ND</option>
    			<option  {if eq($state,'MP')} selected="selected" {/if} >MP</option>
    			<option  {if eq($state,'OH')} selected="selected" {/if} >OH</option>
    			<option  {if eq($state,'OK')} selected="selected" {/if} >OK</option>
    			<option  {if eq($state,'OR')} selected="selected" {/if} >OR</option>
    			<option  {if eq($state,'PW')} selected="selected" {/if} >PW</option>
    			<option  {if eq($state,'PA')} selected="selected" {/if} >PA</option>
    			<option  {if eq($state,'PR')} selected="selected" {/if} >PR</option>
    			<option  {if eq($state,'RI')} selected="selected" {/if} >RI</option>
    			<option  {if eq($state,'SC')} selected="selected" {/if} >SC</option>
    			<option  {if eq($state,'SD')} selected="selected" {/if} >SD</option>
    			<option  {if eq($state,'TN')} selected="selected" {/if} >TN</option>
    			<option  {if eq($state,'TX')} selected="selected" {/if} >TX</option>
    			<option  {if eq($state,'UT')} selected="selected" {/if} >UT</option>
    			<option  {if eq($state,'VT')} selected="selected" {/if} >VT</option>
    			<option  {if eq($state,'VI')} selected="selected" {/if} >VI</option>
    			<option  {if eq($state,'VA')} selected="selected" {/if} >VA</option>
    			<option  {if eq($state,'WA')} selected="selected" {/if} >WA</option>
    			<option  {if eq($state,'WV')} selected="selected" {/if} >WV</option>
    			<option  {if eq($state,'WI')} selected="selected" {/if} >WI</option>
    			<option  {if eq($state,'WY')} selected="selected" {/if} >WY</option>
			 </optgroup>
		     <optgroup label="Canada">
		        <option  {if eq($state,'AB')} selected="selected" {/if} >AB</option>
		        <option  {if eq($state,'BC')} selected="selected" {/if} >BC</option>
    			<option  {if eq($state,'MB')} selected="selected" {/if} >MB</option>
    			<option  {if eq($state,'NB')} selected="selected" {/if} >NB</option>
		        <option  {if eq($state,'NF')} selected="selected" {/if} >NF</option>
		        <option  {if eq($state,'NS')} selected="selected" {/if} >NS</option>
		        <option  {if eq($state,'NT')} selected="selected" {/if} >NT</option>
    			<option  {if eq($state,'ON')} selected="selected" {/if} >ON</option>
		        <option  {if eq($state,'PE')} selected="selected" {/if} >PE</option>
    			<option  {if eq($state,'PQ')} selected="selected" {/if} >PQ</option>
    			<option  {if eq($state,'SK')} selected="selected" {/if} >SK</option>
    			<option  {if eq($state,'YT')} selected="selected" {/if} >YT</option>
		     </optgroup>
		     <optgroup label="Mexico">
		        <option  {if eq($state,'AG')} selected="selected" {/if} >AG</option>
		        <option  {if eq($state,'BC')} selected="selected" {/if} >BC</option>
    			<option  {if eq($state,'BS')} selected="selected" {/if} >BS</option>
    			<option  {if eq($state,'CH')} selected="selected" {/if} >CH</option>
		        <option  {if eq($state,'CL')} selected="selected" {/if} >CL</option>
		        <option  {if eq($state,'CM')} selected="selected" {/if} >CM</option>
		        <option  {if eq($state,'CO')} selected="selected" {/if} >CO</option>
    			<option  {if eq($state,'CS')} selected="selected" {/if} >CS</option>
		        <option  {if eq($state,'DF')} selected="selected" {/if} >DF</option>
    			<option  {if eq($state,'DG')} selected="selected" {/if} >DG</option>
    			<option  {if eq($state,'GR')} selected="selected" {/if} >GR</option>
    			<option  {if eq($state,'GT')} selected="selected" {/if} >GT</option>
    			<option  {if eq($state,'HG')} selected="selected" {/if} >HG</option>
		        <option  {if eq($state,'JA')} selected="selected" {/if} >JA</option>
    			<option  {if eq($state,'MI')} selected="selected" {/if} >MI</option>
    			<option  {if eq($state,'MO')} selected="selected" {/if} >MO</option>
		        <option  {if eq($state,'MX')} selected="selected" {/if} >MX</option>
		        <option  {if eq($state,'NA')} selected="selected" {/if} >NA</option>
		        <option  {if eq($state,'NL')} selected="selected" {/if} >NL</option>
    			<option  {if eq($state,'OA')} selected="selected" {/if} >OA</option>
		        <option  {if eq($state,'PU')} selected="selected" {/if} >PU</option>
    			<option  {if eq($state,'QR')} selected="selected" {/if} >QR</option>
    			<option  {if eq($state,'QT')} selected="selected" {/if} >QT</option>
    			<option  {if eq($state,'SI')} selected="selected" {/if} >SI</option>
    			<option  {if eq($state,'SL')} selected="selected" {/if} >SL</option>
		        <option  {if eq($state,'SO')} selected="selected" {/if} >SO</option>
    			<option  {if eq($state,'TB')} selected="selected" {/if} >TB</option>
    			<option  {if eq($state,'TL')} selected="selected" {/if} >TL</option>
		        <option  {if eq($state,'TM')} selected="selected" {/if} >TM</option>
		        <option  {if eq($state,'VE')} selected="selected" {/if} >VE</option>
		        <option  {if eq($state,'YU')} selected="selected" {/if} >YU</option>
    			<option  {if eq($state,'ZA')} selected="selected" {/if} >ZA</option>
		     </optgroup>
	    </select>
    </div>
{/if}    
    <div class="break"></div>

<div class="zip">
    <label>{'ZIP'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
    <div class="labelbreak"></div>
    <input type="text" class="zip" name="Zip" id="zip" value="{$zip|wash}" />
</div>
    <div class="break"></div>

<div class="country">
    <label>{'Country'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
    <div class="labelbreak"></div>
    {def $country_default_ini=ezini( 'ShopAccountHandlerDefaults', 'DefaultCountryCode' )}
    {def $country_default=''}
    {def $country_list_item_code=''}

    {def $is_set=is_set($country)}
    {if $is_set}
        {if $country|ne('')}
            {set $country=$country|wash()}
        {else}
            {set $country=$country_default_ini}
        {/if}
    {else}
        {def $country=$country_default_ini}
    {/if}
    {def $countries=fetch( 'content', 'country_list', array(false, false))}
            <select name="Country" id="country" onchange="shipping(this.value);">
                <option value="">&nbsp;</option>
                {foreach $countries as $country_list_item}
                {if $country_list_item.Alpha3|eq('')}{set $country_list_item_code=$country_list_item.Alpha2}{else}{set $country_list_item_code=$country_list_item.Alpha3}{/if}
                 <option value="{$country_list_item_code}" {if $current_user.is_logged_in}{if eq( $country, $country_list_item.Alpha3 )} selected="selected"{/if}{else}{if eq( $country, $country_list_item_code )} selected="selected"{/if}{/if}>
                    {$country_list_item.Name}
                </option>
                {/foreach}
            </select>

</div>
    <div class="break"></div>

<div class="block">
	<label>{'Phone'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
	<div class="labelbreak"></div>
	<input type="text" name="Phone" class="phone" id="phone" value="{$phone|wash}" />
</div>
{if eq(ezini( 'Settings', 'Fax', 'xrowecommerce.ini' ), 'enabled' )}
<div class="block">
    <label>{'Fax'|i18n('extension/xrowecommerce')}</label>
    <div class="labelbreak"></div>
    <input type="text" name="Fax" id="fax" class="fax" value="{$fax|wash}" />
</div>
{/if}
<div class="block">
	<label>{'E-mail'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
	<div class="labelbreak"></div>
	<input class="phone" type="text" name="EMail" id="email" value="{$email|wash}" />
</div>
    <div class="break"></div>

{def $shipping_methods=fetch( 'shipping', 'list_methods' )}
{if $shipping_methods|count|gt(0)}
<div class="block">
	<label>{'Shipping'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
	<div class="labelbreak"></div>
	<select name="ShippingType">
	{foreach $shipping_methods as $shipping}
	<option value="{$shipping.identifier}" {if $shippingtype|eq($shipping.identifier)} selected="selected" {/if}>{$shipping.name}</option>
	{/foreach}
	</select>
</div>
{/if}
    <div class="break"></div>
</div> {*LEFT COL END*}

{if ezini('Settings','Coupon','xrowecommerce.ini')|eq('enabled')}
<div class="coupon">

<span class="headingur">{'Coupon'|i18n('extension/xrowecommerce')}</span>
<p>{'Please enter your coupon code exactly as it appears on your promotion.'|i18n('extension/xrowecommerce')}</p>
<div class="block">
	<label>{'Coupon'|i18n('extension/xrowecommerce')}</label>
	<div class="labelbreak"></div>
	<input class="box" type="text" name="coupon_code" id="coupon_code" value="{$coupon_code|wash}" size="20" />
</div>
</div>
{/if}

{* right column *}
<div class="shipping">
<h3>{'Shipping Information'|i18n( 'extension/xrowecommerce' )}</h3>
<label class="shipping-checkbox" for="shipping-checkbox"><input class="shipping-checkbox" id="shipping-checkbox" name="Shipping" value="1" type="checkbox" {if $shipping} checked="checked" {/if} onchange="change(this.checked); shipping(document.register.country.value);" />{'My billing and shipping addresses are identical.'|i18n('extension/xrowecommerce')}</label>


<div class="block" id="shippinginfo"{if $shipping} style="display: none;"{else} style="display: block;"{/if}>
<p><span class="required">* {'Required field'|i18n('extension/xrowecommerce')}</span></p>
{if eq(ezini( 'Settings', 'CompanyName', 'xrowecommerce.ini' ), 'enabled' )}
    <div class="ur_companyname">
        <label>{'Company name'|i18n('extension/xrowecommerce')}</label>
        <div class="labelbreak"></div>
        <input type="text" name="scompanyname" id="scompanyname" value="{$scompany_name|wash}" />

    </div>
{/if}
{if eq(ezini( 'Settings', 'CompanyAdditional', 'xrowecommerce.ini' ), 'enabled' )}
    <div class="ur_company_additional">
        <label>{'Company additional information'|i18n('extension/xrowecommerce')}</label>
        <div class="labelbreak"></div>
        <input type="text" name="scompanyadditional" id="scompanyadditional" value="{$scompanyadditional|wash}" />
    </div>
{/if}
	<div class="ur_firstname">
    	<label>{'First name'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
    	<div class="labelbreak"></div>
    	<input type="text" name="s_FirstName" id="sfirstname" value="{$s_first_name|wash}" />
    </div>
{if eq(ezini( 'Settings', 'MI', 'xrowecommerce.ini' ), 'enabled' )}
	<div class="ur_mi">
    	<label>{'MI'|i18n('extension/xrowecommerce')}</label>
    	<div class="labelbreak"></div>
    	<input class="halfbox" type="text" name="s_MI" id="smi" size="2" value="{$s_mi|wash}" />
    </div>
{/if}
    <div class="ur_lastname">
    	<label>{'Last name'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
    	<div class="labelbreak"></div>
    	<input type="text" name="s_LastName" id="slastname" value="{$s_last_name|wash}" />
    </div>

	<div class="block">
		<label>{'Address 1'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
		<div class="labelbreak"></div>
		<input class="box" type="text" name="s_Address1" id="saddress1" size="20" value="{$s_address1|wash}" />
	</div>

	<div class="block">
		<label>{'Address 2'|i18n('extension/xrowecommerce')}</label>
		<div class="labelbreak"></div>
		<input class="box" type="text" name="s_Address2" size="20" id="saddress2" value="{$s_address2|wash}" />
	</div>


	<div class="city">
    	<label>{'City'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
    	<div class="labelbreak"></div>
    	<input type="text" name="s_City" value="{$s_city|wash}" id="scity" />
    </div>
{if eq(ezini( 'Settings', 'State', 'xrowecommerce.ini' ), 'enabled' )}
    <div class="state">
    	<label>{'State / Province'|i18n('extension/xrowecommerce')}</label>
    	<div class="labelbreak"></div>
    <select name="s_State" id="sstate">
            <option value="">&nbsp;</option>
	    	<optgroup label="USA">
    			<option  {if eq($s_state,'AL')} selected="selected" {/if} >AL</option>
    			<option  {if eq($s_state,'AK')} selected="selected" {/if} >AK</option>
    			<option  {if eq($s_state,'AS')} selected="selected" {/if} >AS</option>
    			<option  {if eq($s_state,'AZ')} selected="selected" {/if} >AZ</option>
    			<option  {if eq($s_state,'AR')} selected="selected" {/if} >AR</option>
    			<option  {if eq($s_state,'CA')} selected="selected" {/if} >CA</option>
    			<option  {if eq($s_state,'CO')} selected="selected" {/if} >CO</option>
    			<option  {if eq($s_state,'CT')} selected="selected" {/if} >CT</option>
    			<option  {if eq($s_state,'DE')} selected="selected" {/if} >DE</option>
    			<option  {if eq($s_state,'DC')} selected="selected" {/if} >DC</option>
    			<option  {if eq($s_state,'FM')} selected="selected" {/if} >FM</option>
    			<option  {if eq($s_state,'FL')} selected="selected" {/if} >FL</option>
    			<option  {if eq($s_state,'GA')} selected="selected" {/if} >GA</option>
    			<option  {if eq($s_state,'GU')} selected="selected" {/if} >GU</option>
    			<option  {if eq($s_state,'HI')} selected="selected" {/if} >HI</option>
    			<option  {if eq($s_state,'ID')} selected="selected" {/if} >ID</option>
    			<option  {if eq($s_state,'IL')} selected="selected" {/if} >IL</option>
    			<option  {if eq($s_state,'IN')} selected="selected" {/if} >IN</option>
    			<option  {if eq($s_state,'IA')} selected="selected" {/if} >IA</option>
    			<option  {if eq($s_state,'KS')} selected="selected" {/if} >KS</option>
    			<option  {if eq($s_state,'KY')} selected="selected" {/if} >KY</option>
    			<option  {if eq($s_state,'LA')} selected="selected" {/if} >LA</option>
    			<option  {if eq($s_state,'ME')} selected="selected" {/if} >ME</option>
    			<option  {if eq($s_state,'MH')} selected="selected" {/if} >MH</option>
    			<option  {if eq($s_state,'MD')} selected="selected" {/if} >MD</option>
    			<option  {if eq($s_state,'MA')} selected="selected" {/if} >MA</option>
    			<option  {if eq($s_state,'MI')} selected="selected" {/if} >MI</option>
    			<option  {if eq($s_state,'MN')} selected="selected" {/if} >MN</option>
    			<option  {if eq($s_state,'MS')} selected="selected" {/if} >MS</option>
    			<option  {if eq($s_state,'MO')} selected="selected" {/if} >MO</option>
    			<option  {if eq($s_state,'MT')} selected="selected" {/if} >MT</option>
    			<option  {if eq($s_state,'NE')} selected="selected" {/if} >NE</option>
    			<option  {if eq($s_state,'NV')} selected="selected" {/if} >NV</option>
    			<option  {if eq($s_state,'NH')} selected="selected" {/if} >NH</option>
    			<option  {if eq($s_state,'NJ')} selected="selected" {/if} >NJ</option>
    			<option  {if eq($s_state,'NM')} selected="selected" {/if} >NM</option>
    			<option  {if eq($s_state,'NY')} selected="selected" {/if} >NY</option>
    			<option  {if eq($s_state,'NC')} selected="selected" {/if} >NC</option>
    			<option  {if eq($s_state,'ND')} selected="selected" {/if} >ND</option>
    			<option  {if eq($s_state,'MP')} selected="selected" {/if} >MP</option>
    			<option  {if eq($s_state,'OH')} selected="selected" {/if} >OH</option>
    			<option  {if eq($s_state,'OK')} selected="selected" {/if} >OK</option>
    			<option  {if eq($s_state,'OR')} selected="selected" {/if} >OR</option>
    			<option  {if eq($s_state,'PW')} selected="selected" {/if} >PW</option>
    			<option  {if eq($s_state,'PA')} selected="selected" {/if} >PA</option>
    			<option  {if eq($s_state,'PR')} selected="selected" {/if} >PR</option>
    			<option  {if eq($s_state,'RI')} selected="selected" {/if} >RI</option>
    			<option  {if eq($s_state,'SC')} selected="selected" {/if} >SC</option>
    			<option  {if eq($s_state,'SD')} selected="selected" {/if} >SD</option>
    			<option  {if eq($s_state,'TN')} selected="selected" {/if} >TN</option>
    			<option  {if eq($s_state,'TX')} selected="selected" {/if} >TX</option>
    			<option  {if eq($s_state,'UT')} selected="selected" {/if} >UT</option>
    			<option  {if eq($s_state,'VT')} selected="selected" {/if} >VT</option>
    			<option  {if eq($s_state,'VI')} selected="selected" {/if} >VI</option>
    			<option  {if eq($s_state,'VA')} selected="selected" {/if} >VA</option>
    			<option  {if eq($s_state,'WA')} selected="selected" {/if} >WA</option>
    			<option  {if eq($s_state,'WV')} selected="selected" {/if} >WV</option>
    			<option  {if eq($s_state,'WI')} selected="selected" {/if} >WI</option>
    			<option  {if eq($s_state,'WY')} selected="selected" {/if} >WY</option>
			 </optgroup>
		     <optgroup label="Canada">
		        <option  {if eq($s_state,'AB')} selected="selected" {/if} >AB</option>
		        <option  {if eq($s_state,'BC')} selected="selected" {/if} >BC</option>
    			<option  {if eq($s_state,'MB')} selected="selected" {/if} >MB</option>
    			<option  {if eq($s_state,'NB')} selected="selected" {/if} >NB</option>
		        <option  {if eq($s_state,'NF')} selected="selected" {/if} >NF</option>
		        <option  {if eq($s_state,'NS')} selected="selected" {/if} >NS</option>
		        <option  {if eq($s_state,'NT')} selected="selected" {/if} >NT</option>
    			<option  {if eq($s_state,'ON')} selected="selected" {/if} >ON</option>
		        <option  {if eq($s_state,'PE')} selected="selected" {/if} >PE</option>
    			<option  {if eq($s_state,'PQ')} selected="selected" {/if} >PQ</option>
    			<option  {if eq($s_state,'SK')} selected="selected" {/if} >SK</option>
    			<option  {if eq($s_state,'YT')} selected="selected" {/if} >YT</option>
		     </optgroup>
		     <optgroup label="Mexico">
		        <option  {if eq($s_state,'AG')} selected="selected" {/if} >AG</option>
		        <option  {if eq($s_state,'BC')} selected="selected" {/if} >BC</option>
    			<option  {if eq($s_state,'BS')} selected="selected" {/if} >BS</option>
    			<option  {if eq($s_state,'CH')} selected="selected" {/if} >CH</option>
		        <option  {if eq($s_state,'CL')} selected="selected" {/if} >CL</option>
		        <option  {if eq($s_state,'CM')} selected="selected" {/if} >CM</option>
		        <option  {if eq($s_state,'CO')} selected="selected" {/if} >CO</option>
    			<option  {if eq($s_state,'CS')} selected="selected" {/if} >CS</option>
		        <option  {if eq($s_state,'DF')} selected="selected" {/if} >DF</option>
    			<option  {if eq($s_state,'DG')} selected="selected" {/if} >DG</option>
    			<option  {if eq($s_state,'GR')} selected="selected" {/if} >GR</option>
    			<option  {if eq($s_state,'GT')} selected="selected" {/if} >GT</option>
    			<option  {if eq($s_state,'HG')} selected="selected" {/if} >HG</option>
		        <option  {if eq($s_state,'JA')} selected="selected" {/if} >JA</option>
    			<option  {if eq($s_state,'MI')} selected="selected" {/if} >MI</option>
    			<option  {if eq($s_state,'MO')} selected="selected" {/if} >MO</option>
		        <option  {if eq($s_state,'MX')} selected="selected" {/if} >MX</option>
		        <option  {if eq($s_state,'NA')} selected="selected" {/if} >NA</option>
		        <option  {if eq($s_state,'NL')} selected="selected" {/if} >NL</option>
    			<option  {if eq($s_state,'OA')} selected="selected" {/if} >OA</option>
		        <option  {if eq($s_state,'PU')} selected="selected" {/if} >PU</option>
    			<option  {if eq($s_state,'QR')} selected="selected" {/if} >QR</option>
    			<option  {if eq($s_state,'QT')} selected="selected" {/if} >QT</option>
    			<option  {if eq($s_state,'SI')} selected="selected" {/if} >SI</option>
    			<option  {if eq($s_state,'SL')} selected="selected" {/if} >SL</option>
		        <option  {if eq($s_state,'SO')} selected="selected" {/if} >SO</option>
    			<option  {if eq($s_state,'TB')} selected="selected" {/if} >TB</option>
    			<option  {if eq($s_state,'TL')} selected="selected" {/if} >TL</option>
		        <option  {if eq($s_state,'TM')} selected="selected" {/if} >TM</option>
		        <option  {if eq($s_state,'VE')} selected="selected" {/if} >VE</option>
		        <option  {if eq($s_state,'YU')} selected="selected" {/if} >YU</option>
    			<option  {if eq($s_state,'ZA')} selected="selected" {/if} >ZA</option>
		     </optgroup>
	    </select>
    </div>
    <div class="break"></div>
{/if}

<div class="zip">
    <label>{'Zip'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
    <div class="labelbreak"></div>
    <input type="text" name="s_Zip" id="szip" value="{$s_zip|wash}"/>
</div>
    <div class="break"></div>

<div class="country">
    <label>{'Country'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
    <div class="labelbreak"></div>


    {set $country_default=''}
    {set $is_set=is_set($s_country)}
    {if is_set($s_country)|not}
        {def $s_country=''}
    {/if}
    {if and($s_country|eq(''),$country)}
            {set $s_country=$country|wash()}
    {/if}
    {if and($s_country|eq(''),$country|not)}
            {set $s_country=$country_default_ini|wash()}
    {/if}
    {set $country_list_item_code=''}
    <input type="hidden" name="sik_country" id="sik_country" value="USA" />
            <select name="s_Country" id="scountry" onchange="shipping(document.register.country.value);">
                <option value="">&nbsp;</option>
                {foreach $countries as $country_id => $country_list_item}
                 {if $country_list_item.Alpha3|eq('')}
                 {set $country_list_item_code=$country_list_item.Alpha2}
                 {else}
                 {set $country_list_item_code=$country_list_item.Alpha3}
                 {/if}
                 <option value="{$country_list_item_code}" {if $current_user.is_logged_in}{if eq($s_country,$country_list_item_code)} selected="selected"{/if}{else}{if eq($s_country,$country_list_item_code)} selected="selected"{/if}{/if}>{$country_list_item.Name}</option>
                {/foreach}
            </select>
</div>
    <div class="break"></div>

<div class="block">
	<label>{'Phone'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
	<div class="labelbreak"></div>
	<input class="phone" type="text" id="sphone" name="s_Phone" value="{$s_phone|wash}" />
</div>

{if eq(ezini( 'Settings', 'Fax', 'xrowecommerce.ini' ), 'enabled' )}
<div class="block">
    <label>{'Fax'|i18n( 'extension/xrowecommerce' )}</label>
    <div class="labelbreak"></div>
    <input class="phone" type="text" id="sfax" name="s_Fax" value="{$s_fax|wash}" />
</div>
{/if}
<div class="block">
	<label>{'E-mail'|i18n('extension/xrowecommerce')}<span class="required">*</span></label>
	<div class="labelbreak"></div>
	<input class="phone" type="text" name="s_EMail" id="semail" value="{$s_email|wash}" />
</div>
    <div class="break"></div>

    </div>


{* / left column *}
</div>


<div class="additional-information">
<h3>{'Additional Information'|i18n( 'extension/xrowecommerce' )}</h3>

{* Captcha *}
{def $access=fetch( 'user', 'has_access_to',
                    hash( 'module',   'xrowecommerce',
                          'function', 'bypass_captcha' ) )} 
{if and( ezini('Settings','Captcha','xrowecommerce.ini')|eq('enabled'), $access|not, ezhttp( 'xrowCaptchaSolved', 'session' )|not)}

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
{* Captcha *}


{* Your No Partial Delivery *}
{if and( ezini('Settings','NoPartialDelivery','xrowecommerce.ini')|eq('enabled'))}
<div class="block">        
        <label class="no_partial_delivery" for="no_partial_delivery"><input class="no_partial_delivery" type="checkbox" name="no_partial_delivery" id="no_partial_delivery" {if $no_partial_delivery} checked="checked" {/if} value="1" />{'No partial delivery'|i18n('extension/xrowecommerce')}</label>
       
</div>
{/if}
{* Your Reference *}
{if and( ezini('Settings','Reference','xrowecommerce.ini')|eq('enabled'))}
<div class="block">        
        <label for="reference">{'Your reference'|i18n('extension/xrowecommerce')}</label>
        <div class="labelbreak"></div>  
        <input class="reference" type="text" name="reference" id="reference" value="{$reference|wash}" />
</div>
{/if}
{* Your Message *}
{if and( ezini('Settings','Message','xrowecommerce.ini')|eq('enabled'))}
<div class="block">        
        <label for="message">{'Your notes on order'|i18n('extension/xrowecommerce')}</label>
        <div class="labelbreak"></div>  
        <textarea class="message" name="message" id="message" rows="10" cols="50">{$message|wash}</textarea>
</div>
{/if}

</div>

<div class="buttonblock">
    <input id="cancle-button" class="button left-arrow" type="submit" name="CancelButton" value="{'Cancel'|i18n('extension/xrowecommerce')}" title="{'Cancel order'|i18n('extension/xrowecommerce')}" />
    <input id="continue-button" class="button right-arrow" type="submit" name="StoreButton" value="{'Continue'|i18n('extension/xrowecommerce')}" title="{'Continue order'|i18n('extension/xrowecommerce')}" />
</div>
{literal}
<script type="text/javascript">
    if (document.register.Shipping.checked == false)
        {
            country = document.register.scountry.value;
        }
    if (document.register.Shipping.checked == true)
        {
            country = document.register.Country.value;
        }
    if (country == "USA")
    {
        for (i = 0; i < document.register.ShippingType.length; ++i)
        {
            if ( document.register.ShippingType[i].value >= 6  &&  document.register.ShippingType[i].value <= 7 )
                document.register.ShippingType.options[i].disabled = true;
            else document.register.ShippingType.options[i].disabled = false;
        }
        if (document.register.ShippingType.selectedIndex+3 >=6 && document.register.ShippingType.selectedIndex+3 <=7)
            document.register.ShippingType.options[0].selected = true;
    }
    else
    {
        for (i = 0; i < document.register.ShippingType.length; ++i)
        {
            if ( document.register.ShippingType[i].value >= 3  &&  document.register.ShippingType[i].value <= 5 )
                document.register.ShippingType.options[i].disabled = true;
            else document.register.ShippingType.options[i].disabled = false;
        }
            if (document.register.ShippingType.selectedIndex+3 >=3 && document.register.ShippingType.selectedIndex+3 <=5)
        {
        	if ( document.register.ShippingType.options[3] )
        	{
            	document.register.ShippingType.options[3].selected = true;
            }
        }
    }
</script>
{/literal}



<script type="text/javascript">
{literal}
function change(status)
{
if (status)
    {
        document.getElementById("shippinginfo").style.display = 'none';
    }
    else
    {
    if( document.register.companyname )
    {
        document.register.scompanyname.value = document.register.companyname.value;
    }
    if( document.register.companyadditional )
    {
        document.register.scompanyadditional.value = document.register.companyadditional.value;
    }
    if( document.register.mi )
    {
    	document.register.smi.value = document.register.mi.value;
    }
    if( document.register.state )
    {
        document.register.sstate.value = document.register.state.value;
    }
        document.register.sfirstname.value = document.register.firstname.value;
        document.register.slastname.value = document.register.lastname.value;
        
        document.register.szip.value = document.register.zip.value;
        document.register.sphone.value = document.register.phone.value;
        document.register.sfax.value = document.register.fax.value;
        document.register.semail.value = document.register.email.value;
        document.register.saddress1.value = document.register.address1.value;
        document.register.saddress2.value = document.register.address2.value;
        document.register.scity.value = document.register.city.value;
        document.register.scountry.value = document.register.country.value;
        document.getElementById("shippinginfo").style.display = 'block';
    }
}

function shipping(status)
{
	if ( document.register.ShippingType == null )
	{
		return false;
	}
    if ( document.register.Shipping.checked == false)
    {
        status = document.register.scountry.value;
        document.getElementById("shippinginfo").style.display = 'block';
    }
    
    if (status == "USA")
    {
        for (i = 0; i < document.register.ShippingType.length; ++i)
        {
            if ( document.register.ShippingType[i].value >= 6  &&  document.register.ShippingType[i].value <= 7 )
                document.register.ShippingType.options[i].disabled = true;
            else document.register.ShippingType.options[i].disabled = false;
        }
        if (document.register.ShippingType.selectedIndex+3 >=6 && document.register.ShippingType.selectedIndex+3 <=7)
            document.register.ShippingType.options[0].selected = true;
    }
    else
    {
        for (i = 0; i < document.register.ShippingType.length; ++i)
        {
            if ( document.register.ShippingType[i].value >= 3  &&  document.register.ShippingType[i].value <= 5 )
                document.register.ShippingType.options[i].disabled = true;
            else document.register.ShippingType.options[i].disabled = false;
        }
        if (document.register.ShippingType.selectedIndex+3 >=3 && document.register.ShippingType.selectedIndex+3 <=5)
        {
        	if ( document.register.ShippingType.options[3] )
        	{
            	document.register.ShippingType.options[3].selected = true;
            }
        }
    }
}
{/literal}
</script>


</form>
</div>
