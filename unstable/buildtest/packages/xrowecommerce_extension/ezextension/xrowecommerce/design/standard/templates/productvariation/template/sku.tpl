{def $id=$attribute.id
     $settings=cond( is_set( $template.attribute_list.$id ), $template.attribute_list.$id, false() )
     $default_value=cond( is_set( $settings.default_value ), $settings.default_value, '' )
     $column_name=cond( is_set( $settings.column_name ), $settings.column_name, $attribute.name )
     $column_desc=cond( is_set( $settings.column_desc ), $settings.column_desc, $attribute.desc )
     $frontend=cond( is_set( $settings.frontend ), $settings.frontend, true() )
     $search=cond( is_set( $settings.search ), $settings.search, true() )
     $unique_sku=cond( is_set( $settings.unique_sku ), $settings.unique_sku, true() )
}
<div>
    <label>{"Column name:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
    <input name="XrowProductTemplate_{$id}_column_name" type="text" class="box" value="{$column_name|wash}" maxlength="255" />
    <label>{"Column description:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
    <textarea name="XrowProductTemplate_{$id|wash}_column_desc" rows="5" cols="70" class="box">{$column_desc|wash}</textarea>
    <label>{"Default value:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
    <input name="XrowProductTemplate_{$id}_default" type="text" class="box" value="{$default_value|wash}" maxlength="255" />
    <div class="block inline">
        <label>
            {"Show on frontend:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}
            <input type="checkbox" name="XrowProductTemplate_{$id}_frontend" value="1"{if $frontend} checked="checked"{/if} />
        </label>
        <label>
            {"Add to search:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}
            <input type="checkbox" name="XrowProductTemplate_{$id}_search" value="1"{if $search} checked="checked"{/if} />
        </label>
        <label>
            {"Unique SKU required:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}
            <input type="checkbox" name="XrowProductTemplate_{$id}_unique_sku" value="1"{if $unique_sku} checked="checked"{/if} />
        </label>
    </div>
</div>
