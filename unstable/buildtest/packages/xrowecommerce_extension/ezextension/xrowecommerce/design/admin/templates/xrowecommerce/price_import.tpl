<form action={'xrowecommerce/priceimport/'|ezurl} method="post" name="PriceImport" enctype="multipart/form-data">

<div class="context-block">
{* DESIGN: Header START *}<div class="box-header"><div class="box-tc"><div class="box-ml"><div class="box-mr"><div class="box-tl"><div class="box-tr">
<h1 class="context-title">{"Price import"|i18n( 'extension/xrowecommerce' )}</h1>

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
        <h2>{"Import error"|i18n( 'extension/xrowecommerce' )}</h2>
        <ul>
            {foreach $error_array as $error}
                    <li>{$error|wash}</li>
            {/foreach}
        </ul>
    {/if}

<div class="block">
<label>{'CSV File for import'|i18n( 'design/standard/content/datatype' )}:</label>
<input class="box" name="UploadCSVFile" type="file" />

<label>{'Country'|i18n( 'design/standard/content/datatype' )}:</label>
<select name="Country">
{def $country_array=ezini( 'PriceSettings', 'CountryArray', 'xrowproduct.ini' )}
{foreach $country_array as $country => $currency}
    <option value="{$country|wash}">{$country|wash}</option>
{/foreach}
</select>

{if $upload}
<h2>{"Import report"|i18n( 'extension/xrowecommerce' )}</h2>
<table class="list">
    <tr>
        <td>{"Total lines in file"|i18n( 'extension/xrowecommerce' )}:</td>
        <td>{$report_array.total_lines|l10n('number')}</td>
    </tr>
    <tr>
        <td>{"Prices updated"|i18n( 'extension/xrowecommerce' )}:</td>
        <td>{$report_array.update_ok|l10n('number')}</td>
    </tr>
    <tr>
        <td>{"Same price"|i18n( 'extension/xrowecommerce' )}:</td>
        <td>{$report_array.same_price|l10n('number')}</td>
    </tr>
    <tr>
        <td>{"Empty lines"|i18n( 'extension/xrowecommerce' )}:</td>
        <td>{$report_array.empty_line|l10n('number')}</td>
    </tr>
    <tr>
        <td>{"SKU not found"|i18n( 'extension/xrowecommerce' )}:</td>
        <td>{$report_array.sku_not_found|l10n('number')}</td>
    </tr>
    <tr>
        <td>{"Price was not a valid number"|i18n( 'extension/xrowecommerce' )}:</td>
        <td>{$report_array.no_number|l10n('number')}</td>
    </tr>
    <tr>
        <td>{"New price created"|i18n( 'extension/xrowecommerce' )}:</td>
        <td>{$report_array.new_price|l10n('number')}</td>
    </tr>
    <tr>
        <td>{"New sliding price (not imported)"|i18n( 'extension/xrowecommerce' )}:</td>
        <td>{$report_array.new_sliding_price|l10n('number')}</td>
    </tr>
</table>

{if $report_array.sku_not_found|gt(0)}
<label>{"These SKUs were not found, prices couldn't be imported:"|i18n( 'extension/xrowecommerce' )}</label>
<ul>
    <li>
{$report_array.sku_not_found_array|implode( '</li><li>' )}
    </li>
</ul>
{/if}

{/if}

</div>

{* DESIGN: Content END *}</div></div></div>

<div class="controlbar">
{* DESIGN: Control bar START *}<div class="box-bc"><div class="box-ml"><div class="box-mr"><div class="box-tc"><div class="box-bl"><div class="box-br">
<div class="block">
    <div class="left">

    <input class="button" type="submit" name="ImportButton" value="{'Start price import'|i18n( 'extension/xrowecommerce' )}" title="{'Start the price import.'|i18n( 'extension/xrowecommerce' )|wash}" />
    </div>

    <div class="break"></div>
</div>


{* DESIGN: Control bar END *}</div></div></div></div></div></div>
</div>

</div>

</form>

