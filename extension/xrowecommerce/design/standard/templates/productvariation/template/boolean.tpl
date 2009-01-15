{def $id=$attribute.id
     $settings=cond( is_set( $template.attribute_list.$id ), $template.attribute_list.$id, false() )
     $default_value=cond( is_set( $settings.default_value ), $settings.default_value, false() )
}
<div>
    <label>{"Default value:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
    <input type="checkbox" name="XrowProductTemplate_{$attribute.id}_default" value="1"{if $default_value} checked="checked"{/if} />
    <div class="block inline">
        <label>
            {"Show on frontend:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}
            <input type="checkbox" name="XrowProductTemplate_{$id}_frontend" value="1"{if $frontend} checked="checked"{/if} />
        </label>
        <label>
            {"Add to search:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}
            <input type="checkbox" name="XrowProductTemplate_{$id}_search" value="1"{if $search} checked="checked"{/if} />
        </label>
    </div>
</div>
