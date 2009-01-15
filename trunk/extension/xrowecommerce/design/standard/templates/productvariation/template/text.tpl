{def $id=$attribute.id
     $settings=cond( is_set( $template.attribute_list.$id ), $template.attribute_list.$id, false() )
     $default_value=cond( is_set( $settings.default_value ), $settings.default_value, '')
     $required=cond( is_set( $settings.required ), $settings.required, false() )
     $translation=cond( is_set( $settings.translation ), $settings.translation, false() )
     $frontend=cond( is_set( $settings.frontend ), $settings.frontend, true() )
     $search=cond( is_set( $settings.search ), $settings.search, true() )
}
<div>
    <label>{"Default value:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
    <textarea name="XrowProductTemplate_{$id|wash}_default" rows="2" cols="70" class="box">{$default_value|wash}</textarea>

    <div class="block inline">
        <label>
            {"Input required:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}
            <input type="checkbox" name="XrowProductTemplate_{$id|wash}_required" value="1"{if $required} checked="checked"{/if} />
        </label>
        <label>
            {"Needs translation:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}
            <input type="checkbox" name="XrowProductTemplate_{$id|wash}_translation" value="1"{if $translation} checked="checked"{/if} />
        </label>
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
