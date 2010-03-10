<div class="shop-basket">
<h2>My Account</h2>

<form method="post" action={"/shop/userregister/"|ezurl} name='register'>
  <div style="border: 1px solid #d3d5d6; width: 335px; padding: 12px; float: left;"><!-- Left Col Start -->
    <span class="headingur">Billing Information</span><br />
    <p>Please enter your billing address exactly as it appears on your credit card statement.<span class="required">* Required field</span></p>
    {section show=$input_error}
    <br />
    <div class="warning"><p><b>- Input did not validate, all fields marked with <span class="required">*</span> must be filled in.</b></p><br /></div>
    {/section}
  
    <div style="width: 160px; display: inline; float: left;">
      <label><span class="required">*</span>First name</label>
      <div class="labelbreak"></div>
      <input type="text" name="FirstName" value="{$first_name|wash}" style="width: 150px;" />
    </div>
        
    <div style="width: 35px; display: inline; float: left;">
      <label>MI</label>
      <div class="labelbreak"></div>
      <input class="halfbox" type="text" name="MI" size="2" value="{$mi|wash}" style="width: 25px;" />
    </div>
        
    <div style="width: 140px; display: inline; float: left;">
      <label><span class="required">*</span>Last name</label>
      <div class="labelbreak"></div>
      <input type="text" name="LastName" value="{$last_name|wash}" style="width: 132px;" />
    </div>
        
    <div class="block">
      <label><span class="required">*</span>Address 1</label>
      <div class="labelbreak"></div>
      <input class="box" type="text" name="Address1" size="20" value="{$address1|wash}" />
    </div>
    
    <div class="block">
      <label>Address 2</label>
      <div class="labelbreak"></div>
      <input class="box" type="text" name="Address2" size="20" value="{$address2|wash}" />
    </div>
    
    <div style="width: 240px; display: inline; float: left;">
      <label><span class="required">*</span>City</label>
      <div class="labelbreak"></div>
      <input type="text" name="City" value="{$city|wash}" style="width: 230px;" />
    </div>    
    
    <div style="width: 70px; display: inline; float: left;">
      <label><span class="required">*</span>State</label>
      <div class="labelbreak"></div>
      <select name="State" style="width: 60px;">
        <option value=""></option>
        <option  {if eq($state,'AL')} selected {/if}>AL</option>
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
      </select>
    </div>
    <div class="break"></div>
        
    <div style="width: 90px; display: inline; float: left;">
      <label><span class="required">*</span>Zip code</label>
      <div class="labelbreak"></div>
      <input type="text" name="Zip" value="{$zip|wash}" style="width: 80px;"/>
    </div>
    <div class="break"></div>
    
    <div style="width: 180px; display: inline; float: left;">
      <label><span class="required">*</span>Country</label>
      <div class="labelbreak"></div>
    
        {def $country_default_ini=ezini( 'ShopAccountHandlerDefaults', 'DefaultCountryCode', 'site.ini' )}
        {def $country_default=''}
        {def $country_list_item_code=''}
    
        {def $is_set=is_set($country)}
        {if $is_set}{if $country|ne('')}{def $country=$country|wash()}{else}{def $country=$country_default_ini}{/if}{else}{def $country=$country_default_ini}{/if}
        {def $countries=wrap_user_func('getCountryList', array(false, false))}
                <select name="Country" style="width: 170px;">
                    <option value=""></option>
                    {foreach $countries as $country_list_item}
                     {if $country_list_item.Alpha3|eq('')}{set $country_list_item_code=$country_list_item.Alpha2}{else}{set $country_list_item_code=$country_list_item.Alpha3}{/if}
                     <option value="{$country_list_item_code}"{if eq( $country, $country_list_item_code )} selected="selected"{/if}>{$country_list_item.Name}</option>
                    {/foreach}
    
                </select>
    </div>
    <div class="break"></div>
    
    <div class="block">
      <label><span class="required">*</span>Phone</label>
      <div class="labelbreak"></div>
      <input class="box" type="text" name="Phone" style="width: 170px;" value="{$phone|wash}" />
    </div>
    
    <div class="block">
      <label><span class="required">*</span>E-mail</label>
      <div class="labelbreak"></div>
      <input class="box" type="text" name="EMail" value="{$email|wash}" style="width: 170px;" />
    </div>
    <div class="break"></div>
    
    <div class="block">
      <label><span class="required">*</span>Shipping </label>
      <div class="labelbreak"></div>
      <select name="ShippingType">
        <option value="0"{if eq($shippingtype,0)} selected {/if}>Standard Shipping</option>
        <option value="1"{if eq($shippingtype,1)} selected {/if}>Next Day Service</option>
        <option value="2"{if eq($shippingtype,2)} selected {/if}>2nd Day Service</option>
      </select>
    </div>
    <div class="break"></div>
    <br />

    <div class="buttonblock">
      <input type="image" src={"images/cancel_small.gif"|ezdesign()} name="CancelButton" value="Cancel" />
      <input type="image" src={"images/continue_small.gif"|ezdesign()} name="StoreButton" value="Continue" />
    </div>
    <br />
  </div><!-- Left Col End -->

  <div style="border: 1px solid #d3d5d6; width: 335px; padding: 12px; float: left; margin-left: 15px;"><!-- Right Col Start -->
    <span class="headingur">Shipping Information</span><br />
    <table border="0" summary="Shipping Information">
     <tr>
       <td><input name="Shipping" value="1" type="checkbox" {$shipping|choose( '', 'checked="checked"' )} /></td>
        <td><p>My billing and shipping addresses are identical <b>(please leave form blank below)</b>. <span class="required">* Required field</span></p></td>
     </tr>
   </table>
{section show=$input_error}
   <br />
   <div class="warning">
     <p><b>- Input did not validate, all fields marked with <span class="required">*</span> must be filled in.</b></p><br />
   </div>

{/section}

  <div class="block">
    <div style="width: 160px; display: inline; float: left;">
      <label><span class="required">*</span>First name</label>
      <div class="labelbreak"></div>
      <input type="text" name="s_FirstName" value="{$s_first_name|wash}" style="width: 150px;" />
    </div>
    
    <div style="width: 35px; display: inline; float: left;">
      <label>MI</label>
      <div class="labelbreak"></div>
      <input class="halfbox" type="text" name="s_MI" size="2" value="{$s_mi|wash}" style="width: 25px;" />
    </div>
    
    <div style="width: 140px; display: inline; float: left;">
      <label><span class="required">*</span>Last name</label>
      <div class="labelbreak"></div>
      <input type="text" name="s_LastName" value="{$s_last_name|wash}" style="width: 132px;" />
    </div>
    
    <div class="block">
      <label><span class="required">*</span>Address 1</label>
      <div class="labelbreak"></div>
      <input class="box" type="text" name="s_Address1" size="20" value="{$s_address1|wash}" />
    </div>

    <div class="block">
      <label>Address 2</label>
      <div class="labelbreak"></div>
      <input class="box" type="text" name="s_Address2" size="20" value="{$s_address2|wash}" />
    </div>

    <div style="width: 240px; display: inline; float: left;">
      <label><span class="required">*</span>City</label>
      <div class="labelbreak"></div>
      <input type="text" name="s_City" value="{$s_city|wash}" style="width: 230px;" />
    </div>    

    <div style="width: 70px; display: inline; float: left;">
      <label><span class="required">*</span>State</label>
      <div class="labelbreak"></div>
      <select name="s_State" style="width: 60px;">
        <option value=""></option>
        <option  {if eq($s_state,'AL')} selected {/if}>AL</option>
        <option  {if eq($s_state,'AK')} selected {/if} >AK</option>
        <option  {if eq($s_state,'AS')} selected {/if} >AS</option>
        <option  {if eq($s_state,'AZ')} selected {/if} >AZ</option>
        <option  {if eq($s_state,'AR')} selected {/if} >AR</option>
        <option  {if eq($s_state,'CA')} selected {/if} >CA</option>
        <option  {if eq($s_state,'CO')} selected {/if} >CO</option>
        <option  {if eq($s_state,'CT')} selected {/if} >CT</option>
        <option  {if eq($s_state,'DE')} selected {/if} >DE</option>
        <option  {if eq($s_state,'DC')} selected {/if} >DC</option>
        <option  {if eq($s_state,'FM')} selected {/if} >FM</option>
        <option  {if eq($s_state,'FL')} selected {/if} >FL</option>
        <option  {if eq($s_state,'GA')} selected {/if} >GA</option>
        <option  {if eq($s_state,'GU')} selected {/if} >GU</option>
        <option  {if eq($s_state,'HI')} selected {/if} >HI</option>
        <option  {if eq($s_state,'ID')} selected {/if} >ID</option>
        <option  {if eq($s_state,'IL')} selected {/if} >IL</option>
        <option  {if eq($s_state,'IN')} selected {/if} >IN</option>
        <option  {if eq($s_state,'IA')} selected {/if} >IA</option>
        <option  {if eq($s_state,'KS')} selected {/if} >KS</option>
        <option  {if eq($s_state,'KY')} selected {/if} >KY</option>
        <option  {if eq($s_state,'LA')} selected {/if} >LA</option>
        <option  {if eq($s_state,'ME')} selected {/if} >ME</option>
        <option  {if eq($s_state,'MH')} selected {/if} >MH</option>
        <option  {if eq($s_state,'MD')} selected {/if} >MD</option>
        <option  {if eq($s_state,'MA')} selected {/if} >MA</option>
        <option  {if eq($s_state,'MI')} selected {/if} >MI</option>
        <option  {if eq($s_state,'MN')} selected {/if} >MN</option>
        <option  {if eq($s_state,'MS')} selected {/if} >MS</option>
        <option  {if eq($s_state,'MO')} selected {/if} >MO</option>
        <option  {if eq($s_state,'MT')} selected {/if} >MT</option>
        <option  {if eq($s_state,'NE')} selected {/if} >NE</option>
        <option  {if eq($s_state,'NV')} selected {/if} >NV</option>
        <option  {if eq($s_state,'NH')} selected {/if} >NH</option>
        <option  {if eq($s_state,'NJ')} selected {/if} >NJ</option>
        <option  {if eq($s_state,'NM')} selected {/if} >NM</option>
        <option  {if eq($s_state,'NY')} selected {/if} >NY</option>
        <option  {if eq($s_state,'NC')} selected {/if} >NC</option>
        <option  {if eq($s_state,'ND')} selected {/if} >ND</option>
        <option  {if eq($s_state,'MP')} selected {/if} >MP</option>
        <option  {if eq($s_state,'OH')} selected {/if} >OH</option>
        <option  {if eq($s_state,'OK')} selected {/if} >OK</option>
        <option  {if eq($s_state,'OR')} selected {/if} >OR</option>
        <option  {if eq($s_state,'PW')} selected {/if} >PW</option>
        <option  {if eq($s_state,'PA')} selected {/if} >PA</option>
        <option  {if eq($s_state,'PR')} selected {/if} >PR</option>
        <option  {if eq($s_state,'RI')} selected {/if} >RI</option>
        <option  {if eq($s_state,'SC')} selected {/if} >SC</option>
        <option  {if eq($s_state,'SD')} selected {/if} >SD</option>
        <option  {if eq($s_state,'TN')} selected {/if} >TN</option>
        <option  {if eq($s_state,'TX')} selected {/if} >TX</option>
        <option  {if eq($s_state,'UT')} selected {/if} >UT</option>
        <option  {if eq($s_state,'VT')} selected {/if} >VT</option>
        <option  {if eq($s_state,'VI')} selected {/if} >VI</option>
        <option  {if eq($s_state,'VA')} selected {/if} >VA</option>
        <option  {if eq($s_state,'WA')} selected {/if} >WA</option>
        <option  {if eq($s_state,'WV')} selected {/if} >WV</option>
        <option  {if eq($s_state,'WI')} selected {/if} >WI</option>
        <option  {if eq($s_state,'WY')} selected {/if} >WY</option>
      </select>
    </div>
    <div class="break"></div>

    <div style="width: 90px; display: inline; float: left;">
      <label><span class="required">*</span>Zip code</label>
      <div class="labelbreak"></div>
      <input type="text" name="s_Zip" value="{$s_zip|wash}" style="width: 80px;"/>
    </div>
    <div class="break"></div>

    <div style="width: 180px; display: inline; float: left;">
      <label><span class="required">*</span>Country</label>
      <div class="labelbreak"></div>

    {def $country_default=''}
    {def $is_set=is_set($s_country)}
    {if $is_set}{if $s_country|ne('')}{def $s_country=$country|wash()}{else}{def $s_country=$country_default_ini}{/if}{else}{def $s_country=$country_default_ini}{/if}

    {def $countries=wrap_user_func('getCountryList', array(false, false))}
      <select name="s_Country" style="width: 170px;">
        <option value=""></option>
                {foreach $countries as $country_list_item}
                 {if $country_list_item.Alpha3|eq('')}{def $country_list_item_code=$country_list_item.Alpha2}{else}{def $country_list_item_code=$country_list_item.Alpha3}{/if}
        <option value="{$country_list_item_code}"{if eq( $s_country, $country_list_item_code )} selected="selected"{/if}>{$country_list_item.Name}</option>
                {/foreach}
      </select>
    </div>
    <div class="break"></div>

    <div class="block">
      <label><span class="required">*</span>Phone</label>
      <div class="labelbreak"></div>
      <input class="box" type="text" name="s_Phone" style="width: 170px;" value="{$s_phone|wash}" />
    </div>

    <div class="block">
      <label><span class="required">*</span>E-mail</label>
      <div class="labelbreak"></div>
      <input class="box" type="text" name="s_EMail" value="{$s_email|wash}" style="width: 170px;" />
    </div>
    <div class="break"></div>

  </div>
    <br />
    {*
    <div class="buttonblock">
    <input type="image" src={"images/cancel_small.gif"|ezdesign()} name="CancelButton" value="Cancel" />
    <input type="image" src={"images/continue_small.gif"|ezdesign()} name="StoreButton" value="Continue" />
    </div>
    *}
<br />
  </div><!-- Right Col End-->
</form>
</div> <!-- shop-basket -->
