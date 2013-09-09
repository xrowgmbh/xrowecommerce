<html>
<head>
<title>Price import</title>
</head>
<body></body>
<h1>{"Import report"|i18n( 'extension/xrowecommerce' )}</h1>
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
</body>
</html>