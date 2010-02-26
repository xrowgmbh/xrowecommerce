{if $active_attribute_list|count|gt(0)}

{foreach $active_attribute_list as $key => $attribute}

<div id="xrow_attribute_{$attribute.identifier|wash}" style="display: none;">
<table class="list" width="100%">
<tbody>
    <tr>
        <td valign="top" class="tight"><img src={"trash-icon-16x16.gif"|ezimage} alt="{"Delete attribute"|i18n( 'extension/xrowecommerce/productvariation' )|wash}"  width="16" height="16" onclick="return xrow_delete_template( 'xrow_tr_attribute_{$attribute.identifier|wash}', 'xrow_attribute_{$attribute.identifier|wash}', '{$attribute.identifier|wash}', '{$attribute.name|wash('javascript')}', '{"You try to delete this attribute from the product template. Please confirm this action by clicking the OK button."|i18n( 'extension/xrowecommerce/productvariation' )|wash('javascript')}', 'AttributeIDList', 'noinfo', 'AttributeSortListID' );" /></td>
        <td valign="top" width="100%"><strong>{$attribute.name|wash} ({$attribute.data_type_obj.name|wash})</strong>
            {if ne( $attribute.desc|trim, '')}<p>{$attribute.desc|wash|nl2br}</p>{/if}

            {include uri=concat( "design:productvariation/template/", $attribute.data_type, ".tpl" )
                     attribute=$attribute
                     error=$error
                     template=$template}

             <input type="hidden" name="AttributeIDArray[]" value="{$attribute.id}" />
        </td>
        <td valign="top" class="tight"><img src={"button-move_up.gif"|ezimage} alt="Move up"  width="16" height="16" onclick="return xrow_move(  {ldelim} direction: 'up', tr: this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode {rdelim} );" /></td>
        <td valign="top" class="tight"><img src={"button-move_down.gif"|ezimage} alt="Move down" width="16" height="16" title="Move down" onclick="return xrow_move( {ldelim} direction: 'down', tr: this.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode.parentNode {rdelim});" /></td>
    </tr>
</tbody>
</table>
<br />

</div>

{/foreach}

{/if}


<form action={concat( "productvariation/templateedit/", $template.id, "/", $language_locale )|ezurl} method="post" name="templateedit">
{if ne( $language_locale, '' )}
<input type="hidden" name="EditLanguage" value="{$language_locale}" />
{/if}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{"Edit product template"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{* Items per page selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
</div>
<div class="break"></div>
</div>
</div>
<div class="context-attributes">

{if $error|count|gt(0)}
<div  class="message-error">
    <h2>{"Edit template - error"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</h2>
    <div class="warning">
        {"One or more errors occured. Please correct them."|i18n( 'extension/xrowecommerce/productvariation' )|wash}
    </div>
</div>
{else}
    <h2>{"Edit template"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</h2>
{/if}

<div class="block">
<label{if is_set( $error.name )} class="message-error"{/if}>{"Name"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
<input class="box" type="text" name="name" value="{if is_set( $error.name )|not}{$name|wash}{/if}" />
{if is_set( $error.name )}
    {"Please enter a name for the attribute."|i18n( 'extension/xrowecommerce/productvariation' )|wash}
{/if}
</div>

<div class="block">
<label>{"Description"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
<textarea class="box" name="description" rows="5" cols="70">{$desc|wash}</textarea>
</div>
<label><input type="checkbox" name="active" value="1" {cond( $active, 'checked="checked"', '' )}/>
    {"Active"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>

<h2>{"Attributes"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</h2>

{if $active_attribute_list|count|eq(0)}
    <p>
        {"Please enter some attributes in the setup, before you create the template."|i18n( 'extension/xrowecommerce/productvariation' )|wash}
    </p>
{else}

<p id="noinfo">
     {'No attributes added for this template.'|i18n( 'extension/xrowecommerce/productvariation' )|wash}
</p>
<table cellpadding="0" cellspacing="0" border="0" width="100%">
<tbody id="attributetbody"></tbody>
</table>

<fieldset>
<legend>{"Available attributes"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</legend>
<div>
    {'Use the select box and the button below to add attributes to this product template.'|i18n( 'extension/xrowecommerce/productvariation' )|wash}
</div>

<select name="AttributeList" id="AttributeIDList">
{foreach $active_attribute_list as $attribute}
    <option value="{$attribute.identifier|wash}">{$attribute.name|wash}</option>
{/foreach}
</select>

<input type="button" class="button" onclick="return addAttribute( findAttribute( 'AttributeIDList' ), 'attributetbody', 'AttributeIDList', 'noinfo', 'AttributeSortListID', findAttributeName( 'AttributeIDList' ), true );" name="addButton" value="{"Add attribute"|i18n( 'extension/xrowecommerce/productvariation' )|wash}" />

</fieldset>

<fieldset>
    <legend>{"Default sort order"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</legend>
    <select name="AttributeSortList" id="AttributeSortListID">
        <option value="">{"No sorting"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</option>
    {if $template.has_attribute_list}
    {foreach $template.attribute_list as $attribute}
        <option value="{$attribute.attribute.identifier}"{if $template.sortby.attribute|eq($attribute.attribute.identifier)} selected="selected"{/if}>{$attribute.attribute.name|wash}</option>

    {/foreach}
    {/if}
    </select>
    <select name="AttributeSortMethod">
        <option value="asc"{if $template.sortby.method|eq('asc')} selected="selected"{/if}>{"Ascending"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</option>
        <option value="desc"{if $template.sortby.method|eq('desc')} selected="selected"{/if}>{"Descending"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</option>
    </select>
</fieldset>

{if $template.has_attribute_list}
<script type="text/javascript">
{foreach $template.attribute_list as $key => $attribute}
    addAttribute( '{$attribute.attribute.identifier}', 'attributetbody', 'AttributeIDList', 'noinfo', 'AttributeSortListID', '{$attribute.attribute.name|wash('javascript')}', false );

{/foreach}
</script>
{/if}


{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'extension/xrowecommerce/productvariation' )}" title="{'Cancel'|i18n( 'extension/xrowecommerce/productvariation' )}" />
<input class="button" type="submit" name="StoreButton" value="{'Store template'|i18n( 'extension/xrowecommerce/productvariation' )}" title="{'Store template'|i18n( 'extension/xrowecommerce/productvariation' )}" />


</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>
</div>
</div>

</form>