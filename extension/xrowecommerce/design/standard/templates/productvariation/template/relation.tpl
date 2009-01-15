{def $id=$attribute.id
     $settings=cond( is_set( $template.attribute_list.$id ), $template.attribute_list.$id, false() )
     $default_value=cond( is_set( $settings.default_value ), $settings.default_value, 0 )
     $class_array=cond( is_set( $settings.class_array ), $settings.class_array, array() )
     $required=cond( is_set( $settings.required ), $settings.required, false() )
     $translation=cond( is_set( $settings.translation ), $settings.translation, false() )
     $frontend=cond( is_set( $settings.frontend ), $settings.frontend, true() )
     $search=cond( is_set( $settings.search ), $settings.search, true() )
}
<div>
    <label>{"Start point:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
    {if $default_value|gt(0)}
        {def $node = fetch( 'content', 'node', hash( 'node_id', $default_value ) )}
        {if $node}
        <div><a href={$node.url_alias|ezurl} target="_blank">{$node.name|wash}</a></div>
        {/if}
    {else}
        <div>{"Nothing selected."|i18n( 'extension/xrowecommerce/productvariation' )|wash}</div>
    {/if}
        <input type="submit" name="TemplateCustomActionButton[{$id}][browse]" class="button" value="{"Browse"|i18n( 'extension/xrowecommerce/productvariation' )|wash}" />
        <input type="submit" name="TemplateCustomActionButton[{$id}][remove]" class="button" value="{"Remove"|i18n( 'extension/xrowecommerce/productvariation' )|wash}" />
   <label>{"Content class"|i18n( 'extension/xrowecommerce/productvariation' )|wash}</label>
   <select name="XrowProductTemplate_{$id}_class[]" multiple="multiple" size="10" title="{'Select which nodes from which classes can be selected'|i18n( 'design/standard/class/datatype' )}">
     {def $classes=fetch( 'class', 'list' )}
     <option value="-1"{if $class_array|count|eq(0)} selected="selected"{/if}>{'All classes'|i18n('extension/xrowecommerce/productvariation')}</option>
     {foreach $classes as $item}
           <option value="{$item.id}"{if and( $class_array|count|gt(0), $class_array|contains( $item.id ) )} selected="selected"{/if}>{$item.name|wash}</option>
     {/foreach}

     </select>

    <div class="block inline">
        <label>
            {"Input required:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}
            <input type="checkbox" name="XrowProductTemplate_{$id}_required" value="1"{if $required} checked="checked"{/if} />
        </label>
        <label>
            {"Needs translation:"|i18n( 'extension/xrowecommerce/productvariation' )|wash}
            <input type="checkbox" name="XrowProductTemplate_{$id}_translation" value="1"{if $translation} checked="checked"{/if} />
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
