{* DO NOT EDIT THIS FILE! Use an override template instead. *}

<div class="block">

{foreach $class_attribute.content.card_gateway_array as $card_key => $card}
    <div class="element">
        <p><strong>{$class_attribute.content.card_array[$card_key]|wash}</strong><br />
          {"using payment gateway"|i18n( 'design/standard/class/datatype' )}:</label><div class="labelbreak"></div>
            <select name="ContentClass_ezcreditcard_gateway_{$class_attribute.id}[{$card_key}]">
                <option value=""{if $class_attribute.content.gateway[$card_key]|eq('')} selected="selected"{/if}>{"Not used"|i18n('standard/datatype/ezcreditcard')}</option>
            {foreach $card as $gateway_key => $gateway}
                <option value="{$gateway.value|wash}"{if $gateway.value|eq($class_attribute.content.gateway[$card_key])} selected="selected"{/if}>{$gateway.Name|wash}</option>
            {/foreach}
            </select>
        </p>
    </div>
{/foreach}

</div>
