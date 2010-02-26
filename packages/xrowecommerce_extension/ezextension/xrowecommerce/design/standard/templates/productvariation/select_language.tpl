<form action={$formurl|ezurl} method="post" name="attributeedit">

<div class="context-block">

{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">

<h2 class="context-title">{"Select language"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</h2>

{* DESIGN: Subline *}<div class="header-subline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

<div class="block">
    <fieldset>
    <legend>{'Language'|i18n('extension/xrowecommerce/productvariation')}</legend>
    <p>{'Select the language you want to edit the attribute'|i18n('extension/xrowecommerce/productvariation')}:</p>

    {def $languages=fetch( 'content', 'prioritized_languages' )
         $first=true()}
    {if gt( $languages|count, 1 )}

        {foreach $languages as $language}
            <label><input name="EditLanguage" type="radio" {if $first}checked="checked" {set $first=false()}{/if}value="{$language.locale|wash()}" /> {$language.name|wash()}

        {/foreach}

    {else}
        <input type="hidden" name="EditLanguage" value="{$languages[0].locale|wash()}" />
    {/if}
    {undef $languages}


    </fieldset>
</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <input class="button" type="submit" name="SelectLanguageButton" value="{'Edit'|i18n('design/admin/class/edit_language')}" />
    <input class="button" type="submit" name="CancelButton" value="{'Cancel'|i18n('design/admin/class/select_language')}" />
</div>
{* DESIGN: Control bar END *}</div></div></div></div></div></div>

</div>

</div>
</form>


