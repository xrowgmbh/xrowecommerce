<a name="cos"></a>
<label id="cos-content-label" class="cos" for="cos" ><input id="cos" name="cos" class="cos" value="1" type="checkbox" />
    <span title="{'Show the terms and conditions.'|i18n('extension/xrowecommerce')}">
        {'I have read the %linkstart%general terms and conditions%linkend% and accept them.'|i18n('extension/xrowecommerce',,hash('%linkstart%', '<a onclick="toggleCOS(); ">', '%linkend%', '</a>' ))}
    </span>
</label>
<div id="cos-content" style="display: none;">
    {include uri="design:shop/terms_and_conditions_text.tpl"}
</div>