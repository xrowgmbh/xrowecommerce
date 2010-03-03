<form action={'xrowecommerce/priceexport/'|ezurl} method="post" name="PriceExport" enctype="multipart/form-data">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{"Price export"|i18n( 'extension/xrowecommerce' )}</h1>

{* DESIGN: Mainline *}<div class="header-mainline"></div>

{* DESIGN: Header END *}</div></div></div></div></div></div>

{* DESIGN: Content START *}<div class="box-ml"><div class="box-mr"><div class="box-content">

{* Items per page selector. *}
<div class="context-toolbar">
<div class="block">
<div class="left">
<p></p>
</div>
<div class="break"></div>
</div>
</div>


    {if $error_array|count|gt(0)}
        <h2>{"Export error"|i18n( 'extension/xrowecommerce' )}</h2>
        <ul>
            {foreach $error_array as $error}
                    <li>{$error|wash}</li>
            {/foreach}
        </ul>
    {/if}

<div class="block">

<label>{'Country'|i18n( 'design/standard/content/datatype' )}:</label>
<select name="Country">
{def $country_array=ezini( 'PriceSettings', 'CountryArray', 'xrowproduct.ini' )}
{foreach $country_array as $country => $currency}
    <option value="{$country|wash}">{$country|wash}</option>
{/foreach}
</select>

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <div class="left">

    <input class="button" type="submit" name="ExportButton" value="{'Start price export'|i18n( 'extension/xrowecommerce' )}" title="{'Start the price export.'|i18n( 'extension/xrowecommerce' )|wash}" />
    </div>

    <div class="break"></div>
</div>


{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>

