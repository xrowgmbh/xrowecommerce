<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{"Product attribute setup"|i18n( 'extension/xrowecommerce/productvariation' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{* Items per page selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
<p>
{switch match=$number_of_items}
{case match=25}
<a href={'/user/preferences/set/admin_xrow_attribute_list_limit/10'|ezurl}>10</a>
<span class="current">25</span>
<a href={'/user/preferences/set/admin_xrow_attribute_list_limit/50'|ezurl}>50</a>
{/case}

{case match=50}
<a href={'/user/preferences/set/admin_xrow_attribute_list_limit/10'|ezurl}>10</a>
<a href={'/user/preferences/set/admin_xrow_attribute_list_limit/25'|ezurl}>25</a>
<span class="current">50</span>
{/case}

{case}
<span class="current">10</span>
<a href={'/user/preferences/set/admin_xrow_attribute_list_limit/25'|ezurl}>25</a>
<a href={'/user/preferences/set/admin_xrow_attribute_list_limit/50'|ezurl}>50</a>
{/case}

{/switch}
</p>
</div>
<div class="break"></div>
</div>
</div>

<form action={'productvariation/attributelist/'|ezurl} method="post" name="AttributeList">

{if $attribute_count|gt(0)}
<table class="list" cellspacing="0">
<tr>
    <th class="tight">{'ID'|i18n('extension/xrowecommerce/productvariation')}</th>
    <th>{'Name'|i18n('extension/xrowecommerce/productvariation')}</th>
    <th>{'Identifier'|i18n('extension/xrowecommerce/productvariation')}</th>
    <th>{'Description'|i18n('extension/xrowecommerce/productvariation')}</th>
    <th>{'Type'|i18n('extension/xrowecommerce/productvariation')}</th>
    <th>{'Creator'|i18n('extension/xrowecommerce/productvariation')}</th>
    <th>{'Created'|i18n('extension/xrowecommerce/productvariation')}</th>
    <th>{'Active'|i18n('extension/xrowecommerce/productvariation')}</th>

    <th class="tight">&nbsp;</th>
</tr>

{foreach $attribute_list as $key => $item sequence array( bglight, bgdark ) as $seq}
<tr class="{$seq}">
    <td class="number" align="right">{$item.id}</td>
    <td>{$item.name|wash}</td>
    <td>{$item.identifier|wash}</td>
    <td>{$item.desc|wash}</td>
    <td>{$item.data_type|wash}</td>
    <td>{content_view_gui view=text_linked content_object=$item.user.contentobject}</td>
    <td>{$item.created|l10n( shortdatetime )}</td>
    <td>{cond( $item.active, 'Yes'|i18n('extension/xrowecommerce/productvariation'), 'No'|i18n('extension/xrowecommerce/productvariation'))}</td>
    <td><a href={concat( 'productvariation/attributeedit/', $item.id, '/', $item.top_priority_language_locale )|ezurl} title="{'Edit the <%attribute_name> attribute.'|i18n( 'extension/xrowecommerce/productvariation',, hash( '%attribute_name', $item.name ) )|wash}"><img class="button" src={'edit.gif'|ezimage} width="16" height="16" alt="edit" /></a></td>
</tr>
{/foreach}
</table>
{else}
<div class="block">
<p>{'There are no attributes available.'|i18n( 'extension/xrowecommerce/productvariation' )}</p>
</div>
{/if}

{* Navigator. *}
<div class="context-toolbar">
{include name=navigator
         uri='design:navigator/google.tpl'
         page_uri='/productvariation/attributelist'
         item_count=$attribute_count
         view_parameters=$view_parameters
         item_limit=$number_of_items}

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <div class="left">

    {if $attribute_count|gt(0)}
    <input class="button" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'extension/xrowecommerce/productvariation' )}" title="{'Remove selected attributes.'|i18n( 'extension/xrowecommerce/productvariation' )|wash}" />
    {else}
    <input class="button-disabled" type="submit" name="RemoveButton" value="{'Remove selected'|i18n( 'extension/xrowecommerce/productvariation' )}" disabled="disabled" />
    {/if}

    {def $languages=fetch( 'content', 'prioritized_languages' )}
    {if gt( $languages|count, 1 )}
        <select name="LanguageCode" title="{'Use this menu to select the language you to want use then click the "New attribute" button.'|i18n( 'extension/xrowecommerce/productvariation' )|wash()}">
            {foreach $languages as $language}
                <option value="{$language.locale|wash()}">{$language.name|wash()}</option>
            {/foreach}
        </select>
    {else}
        <input type="hidden" name="LanguageCode" value="{$languages[0].locale|wash()}" />
    {/if}
    {undef $languages}

    <input class="button" type="submit" name="NewButton" value="{'New attribute'|i18n( 'extension/xrowecommerce/productvariation' )}" title="{'Create a new attribute.'|i18n( 'extension/xrowecommerce/productvariation' )|wash}" />
    </div>

    <div class="break"></div>
</div>


{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>

