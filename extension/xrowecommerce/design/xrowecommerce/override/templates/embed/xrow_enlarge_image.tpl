<div class="content-view-embeddedmedia">
    <div class="class-image">
        <div class="attribute-image">
            <p>
                {if $object.reverse_related_contentobject_array.0.class_identifier|eq('xrow_product_category')}
                    <a href="javascript:;" onclick="return enlargeImage('/{$object.data_map.image.content.reference.full_path}',{$object.data_map.image.content.reference.width},{$object.data_map.image.content.reference.height},'{$object.data_map.image.content.reference.text|wash(javascript)}');" title="{$object.data_map.image.content.reference.text|wash} | {"A click on the image enlarges the image in a popup"|i18n( 'kaiser')}">
                {/if}
                {section show=is_set($link_parameters.href)}
                    {section show=is_set($object_parameters.size)}
                    {attribute_view_gui attribute=$object.data_map.image image_class=$object_parameters.size href=$link_parameters.href|ezurl target=$link_parameters.target link_class=$link_parameters.classification link_id=$link_parameters.id}
                  {section-else}
                    {attribute_view_gui attribute=$object.data_map.image image_class=ezini( 'ImageSettings', 'DefaultEmbedAlias', 'content.ini' ) href=$link_parameters.href|ezurl target=$link_parameters.target link_class=$link_parameters.classification link_id=$link_parameters.id}
                  {/section}
                {section-else}
                  {section show=is_set($object_parameters.size)}
                    {attribute_view_gui attribute=$object.data_map.image image_class=$object_parameters.size}
                  {section-else}
                    {attribute_view_gui attribute=$object.data_map.image image_class=ezini( 'ImageSettings', 'DefaultEmbedAlias', 'content.ini' )}
                  {/section}
                {/section}
                {if $object.reverse_related_contentobject_array.0.class_identifier|eq('xrow_product_category')}
                    </a>
                {/if}
            </p>
        </div>
    </div>
</div>