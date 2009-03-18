{def $id=$attribute.id
     $settings=cond( is_set( $template.attribute_list.$id ), $template.attribute_list.$id, false() )
     $default_value=cond( is_set( $settings.default_value ), $settings.default_value, '' )
     $column_name=cond( is_set( $settings.column_name ), $settings.column_name, $attribute.name )
     $column_desc=cond( is_set( $settings.column_desc ), $settings.column_desc, $attribute.desc )
     $required=cond( is_set( $settings.required ), $settings.required, false() )
     $frontend=cond( is_set( $settings.frontend ), $settings.frontend, true() )
     $search=cond( is_set( $settings.search ), $settings.search, true() )
     $select=cond( is_set( $settings.select ), $settings.select, false() )
}
<div>
    <label>{"Column name:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
    <input name="XrowProductTemplate_{$id}_column_name" type="text" class="box" value="{$column_name|wash}" maxlength="255" />
    <label>{"Column description:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
    <textarea name="XrowProductTemplate_{$id|wash}_column_desc" rows="5" cols="70" class="box">{$column_desc|wash}</textarea>
    <label>{"Default value:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
    <input name="XrowProductTemplate_{$id}_default" type="text" class="box" value="{$default_value|wash}" maxlength="15" />
    {if is_set( $error.$id.default_value )}
    <div class="warning">
        {"Please enter a valid number or leave the field empty"|i18n( 'extension/xrowecommerce/productvariation' )}
    </div>
    {/if}
    <div class="block inline">
        <label>
            {"Input required:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}
            <input type="checkbox" name="XrowProductTemplate_{$id}_required" value="1"{if $required} checked="checked"{/if} />
        </label>
        <label>
            {"Show on frontend:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}
            <input type="checkbox" name="XrowProductTemplate_{$id}_frontend" value="1"{if $frontend} checked="checked"{/if} />
        </label>
        <label>
            {"Add to search:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}
            <input type="checkbox" name="XrowProductTemplate_{$id}_search" value="1"{if $search} checked="checked"{/if} />
        </label>
        <label>
            {"Show in select box:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}
            <input type="checkbox" name="XrowProductTemplate_{$id}_select" value="1"{if $select} checked="checked"{/if} />
        </label>
    </div>
</div>
