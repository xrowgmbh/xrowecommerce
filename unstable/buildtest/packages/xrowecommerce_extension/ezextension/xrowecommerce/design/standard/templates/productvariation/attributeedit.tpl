<form action={concat( "productvariation/attributeedit/", $attribute_id, "/", $language_locale )|ezurl} method="post" name="attributeedit">
{if ne( $language_locale, '' )}
<input type="hidden" name="EditLanguage" value="{$language_locale}" />
{/if}
<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{"Edit product variation attribute"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</h1>

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

{if $edit_data_type}

<h2>{"Choose datatype"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</h2>
{if is_set( $error.datatype )}
<div class="warning">
    <h3 class="message-error">{"Error"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</h3>
    {"No datatype was selected."|i18n( 'extension/xrowecommerce/productvariation' )|wash}
</div>
{/if}

<p>
    {"You need to select a datatype for the attribute.
      It's not possible to change this afterwards."|i18n( 'extension/xrowecommerce/productvariation' )|wash}
</p>
{def $first=true()}
{foreach $data_type_array as $key => $data_type}
<p>
    <label><input type="radio" name="DataTypeString" value="{$data_type.data_type_string|wash}"{if $first} checked="checked"{set $first=false()}{/if}/>
    {$data_type.name|wash}</label>
    {$data_type.desc|wash}
</p>
{/foreach}
{undef $first}

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'extension/xrowecommerce/productvariation' )}" title="{'Cancel'|i18n( 'extension/xrowecommerce/productvariation' )}" />
<input class="button" type="submit" name="StoreButton" value="{'Store datatype'|i18n( 'extension/xrowecommerce/productvariation' )}" title="{'Store datatype'|i18n( 'extension/xrowecommerce/productvariation' )}" />

{else}

{if $error|count|gt(0)}
<div  class="message-error">
    <h2>{"Edit datatype - error"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</h2>
</div>
{else}
    <h2>{"Edit datatype"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</h2>
{/if}

<div class="block">
<label{if is_set( $error.name )} class="message-error"{/if}>{"Name"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
<input class="box" type="text" name="name" value="{if is_set( $error.name )|not}{$name|wash}{/if}" />
{if is_set( $error.name )}
    {"Please enter a name for the attribute."|i18n( 'extension/xrowecommerce/productvariation' )|wash}
{/if}
</div>

<div class="block">
<label{if or( is_set( $error.identifier ), is_set( $error.identifier_dupe ) )} class="message-error"{/if}>{"Identifier"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
<input class="box" type="text" name="identifier" value="{$identifier|wash}" />
{if is_set( $error.identifier )}
    {"Please enter a valid identifier for the attribute."|i18n( 'extension/xrowecommerce/productvariation' )|wash}
{elseif is_set( $error.identifier_dupe )}
    {"This identifier is already in use. Enter another one."|i18n( 'extension/xrowecommerce/productvariation' )|wash}
{/if}

</div>

<div class="block">
<label>{"Description"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
<textarea class="box" name="description" rows="5" cols="70">{$desc|wash}</textarea>
</div>
<label><input type="checkbox" name="active" value="1" {cond( $active, 'checked="checked"', '' )}/>
    {"Active"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>

{* Include the attribute specific settings *}
{include uri=concat( "design:productvariation/attribute/", $attribute.data_type, ".tpl" )
         attribute=$attribute
         error=$error}

{* DESIGN: Content END *}</div></div></div>

{* Buttons. *}
<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
<input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n( 'extension/xrowecommerce/productvariation' )}" title="{'Cancel'|i18n( 'extension/xrowecommerce/productvariation' )}" />
<input class="button" type="submit" name="StoreButton" value="{'Store datatype'|i18n( 'extension/xrowecommerce/productvariation' )}" title="{'Store datatype'|i18n( 'extension/xrowecommerce/productvariation' )}" />

{/if}

</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>
</div>
</div>

</form>