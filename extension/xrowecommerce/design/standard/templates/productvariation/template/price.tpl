{def $id=$attribute.id
     $settings=cond( is_set( $template.attribute_list.$id ), $template.attribute_list.$id, false() )
     $default_value=cond( is_set( $settings.default_value ), $settings.default_value, '' )
     $sliding=cond( is_set( $settings.sliding ), $settings.sliding, false() )
}
<div>
    <label>{"Default value:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
    <input name="XrowProductTemplate_{$id}_default" type="text" class="box" value="{$default_value|wash}" maxlength="255" />
    <div class="block inline">
        <label>
            {"Sliding price:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}
            <input type="checkbox" name="XrowProductTemplate_{$id}_sliding" value="1"{if $sliding} checked="checked"{/if} />
        </label>
    </div>
</div>
