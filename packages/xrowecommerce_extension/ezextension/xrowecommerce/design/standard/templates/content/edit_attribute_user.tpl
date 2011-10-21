{def $ca=$content_attributes_data_map
     $castring="ContentObjectAttribute_ezstring_data_text_"}
     {* left frame start *}
 <div class="shop-basket user_register" id="user_edit">
    <h1>{'Edit account information'|i18n('extension/xrowecommerce')}</h1>
    <div class="accountinfo">
        <h2>{'Account Information'|i18n('extension/xrowecommerce')}</h2>
        <p>{'Although your Login User Name must be kept the same, you can update your Email Address and/or change your Password by entering new information in the appropriate field.  When complete, click the "Update" button at the bottom of the page.'|i18n('extension/xrowecommerce')}</p>
        <p><span class="required">* {'Required field'|i18n('extension/xrowecommerce')}</span></p>

        <div class="ezuser">
        {attribute_edit_gui attribute=$ca.user_account}
        </div>
        <div class="break"></div>
    </div>

{* left frame end *}
    <div class="billing_shipping">
{* right frame start *}
        <div class="billing">

            {include uri="design:content/edit_attribute_user_pretext.tpl"}

            <input type="hidden" name="MainNodeID" value="{$main_node_id}" />
            <div class="block">
                <div class="address-selection">
                    <div class="comp">
                        <label for="company">
                        <input id="company" type="radio" value="company" name="company" />
                        {'Company'|i18n('extension/xrowecommerce')}
                        </label>
                    </div>
                    <div class="home">
                        <label for="home">
                        <input {if $ca.company_name.content|ne('')}checked="checked"{/if} id="home" type="radio" value="company" name="company" />
                        {'Private person'|i18n('extension/xrowecommerce')}
                        </label>
                    </div>
                </div>
                <div class="company" {if $ca.company_name.content|ne('')}style="display: none;"{/if}>
                    {if ezini('Fields','company_name','xrowecommerce.ini').enabled|eq('true')}
                    <div class="block">
                        <label>{'Company name'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'company_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.company_name.id}" value="{$ca.company_name.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.company_name.id}" />
                    </div>
                    {/if}
                    {if ezini('Fields','company_additional','xrowecommerce.ini').enabled|eq('true')}
                    <div class="block company_additional">
                        <label>{'Company additional information'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'company_additional', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.company_additional.id}" value="{$ca.company_additional.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.company_additional.id}" />
                    </div>
                    {/if}
                    {if ezini('Fields','tax_id','xrowecommerce.ini').enabled|eq('true')}
                    <div class="block tax_id">
                        <label>{'Tax ID'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'tax_id', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.tax_id.id}" value="{$ca.tax_id.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.tax_id.id}" />
                    </div>
                </div>
                {/if}
                {if ezini( 'Fields', 'title', 'xrowecommerce.ini' ).enabled|eq("true")}
                <div class="block title">
                    <label>{'Title'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'title', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.title.id}" />
                    <select name="ContentObjectAttribute_ezselect_selected_array_{$ca.title.id}[]" id="title">
                        {foreach $ca.title.class_content.options as $title_data}
                            <option value="{$title_data.id}" {if eq( $ca.title.content.0, $title_data.id )} selected="selected"{/if}>{$title_data.name|wash()}</option>
                        {/foreach}
                    </select>
                </div>
                {/if}
                {if ezini('Fields','first_name','xrowecommerce.ini').enabled|eq('true')}
                <div class="block first_name">
                    <label>{'First name'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'first_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.first_name.id}" value="{$ca.first_name.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.first_name.id}" />
                </div>
                {/if}
                {if ezini('Fields','mi','xrowecommerce.ini').enabled|eq('true')}
                <div class="block mi">
                    <label>{'MI'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'mi', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_mi.id}" size="2" value="{$ca.s_mi.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_mi.id}" />
                </div>
                {/if}
                {if ezini('Fields','last_name','xrowecommerce.ini').enabled|eq('true')}
                <div class="block last_name">
                    <label>{'Last name'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'last_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.last_name.id}" value="{$ca.last_name.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.last_name.id}" />
                </div>
                {/if}
                {if ezini('Fields','address1','xrowecommerce.ini').enabled|eq('true')}
                <div class="block address1">
                    <label>{'Address 1'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'address1', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.address1.id}" size="20" value="{$ca.address1.content|wash()}" title="{'Apartment, suite, unit, building, floor, etc.'|i18n('extension/xrowecommerce')}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.address1.id}" />
                </div>
                {/if}
                {if ezini('Fields','address2','xrowecommerce.ini').enabled|eq('true')}
                <div class="block address2">
                    <label>{'Address 2'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'address2', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.address2.id}" size="20" value="{$ca.address2.content|wash()}" title="{'Street address, P.O. box, company name, c/o'|i18n('extension/xrowecommerce')}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.address2.id}" />
                </div>
                {/if}
                {if ezini('Fields','zip','xrowecommerce.ini').enabled|eq('true')}
                <div class="block zip">
                    <label>{'Zip / Postcode'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'zip', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="{$castring}{$ca.zip_code.id}" value="{$ca.zip_code.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.zip_code.id}" />
                </div>
                {/if}
                {if ezini('Fields','city','xrowecommerce.ini').enabled|eq('true')}
                <div class="block city">
                    <label>{'City'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'city', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="{$castring}{$ca.city.id}" value="{$ca.city.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.city.id}" />
                </div>
                {/if}
                {if ezini('Fields','state','xrowecommerce.ini').enabled|eq('true')}
                <div class="block state">
                    <label>{'State / Province'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'state', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.state.id}" />
                    {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.state}
                </div>
                {/if}
                <div class="break"></div>
                {if ezini('Fields','country','xrowecommerce.ini').enabled|eq('true')}
                <div class="block country">
                    <label>{'Country'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'country', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    {*TODO we MAY has to set a default country*}
                    {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.country}
                </div>
                {/if}
                <div class="break"></div>
                {if ezini('Fields','phone','xrowecommerce.ini').enabled|eq('true')}
                <div class="block phone">
                    <label>{'Phone'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'phone', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="{$castring}{$ca.phone.id}" value="{$ca.phone.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.phone.id}" />
                </div>
                {/if}
                <div class="break"></div>
                {if ezini('Fields','fax','xrowecommerce.ini').enabled|eq('true')}
                <div class="block fax">
                    <label>{'Fax'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'phone', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="{$castring}{$ca.fax.id}" value="{$ca.fax.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.fax.id}" />
                </div>
                {/if}

                {include uri="design:content/edit_attribute_user_custom_left.tpl"}
            </div>
        </div>

        <div class="shipping">
            <h3>{'Shipping Information'|i18n('extension/xrowecommerce')}</h3>
            <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.shippingaddress.id}" />
            <label onclick="change();" class="shipping-checkbox" for="shipping-checkbox">
                <input class="shipping-checkbox" onclick="change();" id="shipping-checkbox"  name="ContentObjectAttribute_data_boolean_{$ca.shippingaddress.id}" value="" type="checkbox" {$ca.shippingaddress.data_int|choose( '', 'checked="checked"' )} />
                {'My billing and shipping addresses are identical.'|i18n('extension/xrowecommerce')}
            </label>

            <div id="shippinginfo">
                <div class="s_address-selection">
                    <div class="s_comp">
                        <label for="s_company">
                        <input id="s_company" type="radio" value="company" name="s_company" />
                        {'Company'|i18n('extension/xrowecommerce')}
                        </label>
                    </div>
                    <div class="s_home">
                        <label for="s_home">
                        <input {if $ca.company_name.content|ne('')}checked="checked"{/if} id="s_home" type="radio" value="company" name="s_company" />
                        {'Private person'|i18n('extension/xrowecommerce')}
                        </label>
                    </div>
                </div>
                <div class="s_company" {if $ca.company_name.content|ne('')}style="display: none;"{/if}>
                    {if ezini('Fields','s_company_name','xrowecommerce.ini').enabled|eq('true')}
                    <div class="block s_company_name">
                        <label>{'Company name'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 's_company_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" id="s_company_name" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_company_name.id}" value="{$ca.s_company_name.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_company_name.id}" />
                    </div>
                    {/if}
                    {if ezini('Fields','s_company_additional','xrowecommerce.ini').enabled|eq('true')}
                    <div class="block s_company_additional">
                        <label>{'Company additional information'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 's_company_additional', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_company_additional.id}" value="{$ca.s_company_additional.content|wash()}" />
                        <input type="hidden" id="s_company_name" name="ContentObjectAttribute_id[]" value="{$ca.s_company_additional.id}" />
                    </div>
                    {/if}
                </div>
                {if ezini( 'Fields', 's_title', 'xrowecommerce.ini' ).enabled|eq("true")}
                <div class="block s_title">
                    <label>{'Title'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 's_title', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_title.id}" />
                    <select name="ContentObjectAttribute_ezselect_selected_array_{$ca.s_title.id}[]" id="s_title">
                        {foreach $ca.s_title.class_content.options as $title_data}
                            <option value="{$title_data.id}" {if eq( $ca.s_title.content.0, $title_data.id )} selected="selected"{/if}>{$title_data.name|wash()}</option>
                        {/foreach}
                    </select>
                </div>
                {/if}
                {if ezini('Fields','s_first_name','xrowecommerce.ini').enabled|eq('true')}
                <div class="block s_first_name">
                    <label>{'First name'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 's_first_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_first_name.id}" value="{$ca.s_first_name.content|wash()}" />
                    <input type="hidden" id="s_company_name" name="ContentObjectAttribute_id[]" value="{$ca.s_first_name.id}" />
                </div>
                {/if}
                {if ezini('Fields','s_mi','xrowecommerce.ini').enabled|eq('true')}
                <div class="block s_mi">
                    <label>{'MI'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 's_mi', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_mi.id}" size="2" value="{$ca.s_mi.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_mi.id}" />
                </div>
                {/if}
                {if ezini('Fields','s_last_name','xrowecommerce.ini').enabled|eq('true')}
                <div class="block s_last_name">
                    <label>{'Last name'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 's_last_name', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_last_name.id}" value="{$ca.s_last_name.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_last_name.id}" />
                </div>
                {/if}
                {if ezini('Fields','s_address1','xrowecommerce.ini').enabled|eq('true')}
                <div class="block s_address1">
                    <label>{'Address 1'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 's_address1', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_address1.id}" size="20" value="{$ca.s_address1.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_address1.id}" />
                </div>
                {/if}
                {if ezini('Fields','s_address2','xrowecommerce.ini').enabled|eq('true')}
                <div class="block s_address2">
                    <label>{'Address 2'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 's_address2', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="ContentObjectAttribute_ezstring_data_text_{$ca.s_address2.id}" size="20" value="{$ca.s_address2.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_address2.id}" />
                </div>
                {/if}
                {if ezini('Fields','s_zip','xrowecommerce.ini').enabled|eq('true')}
                <div class="block s_zip">
                    <label>{'Zip / Postcode'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 's_zip', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="{$castring}{$ca.s_zip_code.id}" value="{$ca.s_zip_code.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_zip_code.id}" />
                </div>
                {/if}
                {if ezini('Fields','s_city','xrowecommerce.ini').enabled|eq('true')}
                <div class="block s_city">
                    <label>{'City'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 'city', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input type="text" name="{$castring}{$ca.s_city.id}" value="{$ca.s_city.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_city.id}" />
                </div>
                {/if}
                {if ezini('Fields','s_state','xrowecommerce.ini').enabled|eq('true')}
                <div class="block s_state">
                    <label>{'State / Province'|i18n('extension/xrowecommerce')}</label>
                    <div class="labelbreak"></div>
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_state.id}" />
                    {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.s_state}
                </div>
                {/if}
                <div class="break"></div>
                {if ezini('Fields','s_country','xrowecommerce.ini').enabled|eq('true')}
                <div class="block s_country">
                    <label>{'Country'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 's_country', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    {*TODO we MAY has to set a default country*}
                    {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.s_country}
                </div>
                {/if}
                <div class="break"></div>
                {if ezini('Fields','s_phone','xrowecommerce.ini').enabled|eq('true')}
                <div class="block s_phone">
                    <label>{'Phone'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 's_phone', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="{$castring}{$ca.s_phone.id}" value="{$ca.s_phone.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_phone.id}" />
                </div>
                {/if}
                {if ezini('Fields','s_fax','xrowecommerce.ini').enabled|eq('true')}
                    {if eq(ezini( 'ShippingSettings', 'DisplayFax', 'xrowecommerce.ini' ), 'enabled' )}
                    <div class="block s_fax">
                        <label>{'Fax'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 's_fax', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                        <div class="labelbreak"></div>
                        <input class="box" type="text" name="{$castring}{$ca.s_fax.id}" value="{$ca.s_fax.content|wash()}" />
                        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_fax.id}" />
                    </div>
                    {/if}
                {/if}
                {if ezini('Fields','s_email','xrowecommerce.ini').enabled|eq('true')}
                <div class="block s_email">
                    <label>{'E-mail'|i18n('extension/xrowecommerce')}{if ezini( 'Fields', 's_email', 'xrowecommerce.ini' ).required|eq("true")}<span class="required">*</span>{/if}</label>
                    <div class="labelbreak"></div>
                    <input class="box" type="text" name="ContentObjectAttribute_data_text_{$ca.s_email.id}" value="{$ca.s_email.content|wash()}" />
                    <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.s_email.id}" />
                </div>
                <div class="break"></div>
                {/if}
                {include uri="design:content/edit_attribute_user_custom_right.tpl"}
            </div>
        </div>
    {if $ca.creditcard}
        <div class="block">
            <h3>{'Credit card Information'|i18n('extension/xrowecommerce')}</h3>
            <p>
                {'Your credit card information is needed, if you want to make use of our recurring order option.'|i18n('extension/xrowecommerce')}
            </p>
            <label{section show=$ca.creditcard.has_validation_error} class="validation-error"{/section}>{$ca.creditcard.contentclass_attribute.name|wash}</label>
            <div class="labelbreak" />
                <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.creditcard.id}" />
                {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.creditcard}
            </div>
        </div>
    {/if}

</div>
<div class="buttonblock">
    <input class="button left-arrow2" type="submit" name="DiscardButton" value="{'Cancel'|i18n('extension/xrowecommerce')}" />
    <input class="button right-arrow" type="submit" name="PublishButton" value="{'Save'|i18n('extension/xrowecommerce', 'Save Button' )}" />
    <input type="hidden" name="DiscardConfirm" value="0" />
    <input type="hidden" name="RedirectURI" value="{concat('/user/edit/',$object.id)}" />
    <input type="hidden" name="RedirectIfDiscarded" value="{concat('/user/edit/',$object.id)}" />
</div>

{* right frame end *}

</div>