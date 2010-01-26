{def $item_limit='3'
     $children=fetch( 'content', 'list', hash( 'parent_node_id', $node.node_id,
                                               'offset', $view_parameters.offset,
                                               'sort_by', array( 'published', false() ),
                                               'class_filter_type', 'include',
                                               'class_filter_array', array('xrow_product_review'),
                                               'limit', $item_limit ) )
     $children_count=fetch( 'content', 'list_count', hash( 'parent_node_id', $node.node_id,
                                                           'class_filter_type', 'include',
                                                           'class_filter_array', array('xrow_product_review') ) )}
                                                           

<div class="xrow-product-review">
    <form method="post" action={"content/action/"|ezurl}>
        {if $children_count|eq('0')}
            <h2>{'There are no reviews yet.'|i18n( 'extension/xrowecommerce' )}</h2>
            {if is_unset( $versionview_mode )}
                {if $node.object.can_create}
                    {def $notification_access=fetch( 'user', 'has_access_to', hash( 'module', 'notification', 'function', 'addtonotification' ) )}
                        <input class="button product-new-review" type="submit" name="NewButton" value="{'Create your review'|i18n( 'design/ezwebin/full/forum' )}" />
                        <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
                        <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id}" />
                        <input type="hidden" name="ContentLanguageCode" value="{ezini( 'RegionalSettings', 'ContentObjectLocale', 'site.ini')}" />
                        <input type="hidden" name="NodeID" value="{$node.node_id}" />
                        <input type="hidden" name="ClassIdentifier" value="xrow_product_review" />
                {else}
                    <p>{"You need to be logged in to create reviews. You can do so %login_link_start%here%login_link_end%"|i18n( "extension/xrowecommerce",, hash( '%login_link_start%', concat( '<a href=', '/user/login/'|ezurl, '>' ), '%login_link_end%', '</a>' ) )}</p>
                {/if}
            {/if}
        {else}
            <h2>{$children_count|wash()} {'Customer Reviews'|i18n('extension/xrowecommerce')}</h2>
                {if is_unset( $versionview_mode )}
                    {if $node.object.can_create}
                        {def $notification_access=fetch( 'user', 'has_access_to', hash( 'module', 'notification', 'function', 'addtonotification' ) )}
                            <input class="button product-new-review" type="submit" name="NewButton" value="{'Create your review'|i18n( 'design/ezwebin/full/forum' )}" />
                            <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
                            <input type="hidden" name="ContentObjectID" value="{$node.contentobject_id}" />
                            <input type="hidden" name="ContentLanguageCode" value="{ezini( 'RegionalSettings', 'ContentObjectLocale', 'site.ini')}" />
                            <input type="hidden" name="NodeID" value="{$node.node_id}" />
                            <input type="hidden" name="ClassIdentifier" value="xrow_product_review" />
                    {else}
                        <p>{"You need to be logged in to create reviews. You can do so %login_link_start%here%login_link_end%"|i18n( "extension/xrowecommerce",, hash( '%login_link_start%', concat( '<a href=', '/user/login/'|ezurl, '>' ), '%login_link_end%', '</a>' ) )}</p>
                    {/if}
                {/if}
                        
            {include name=navigator                     uri='design:navigator/google.tpl'                     page_uri=concat('/content/view','/full/',$node.node_id)                     item_count=$children_count                     view_parameters=$view_parameters                     item_limit=$item_limit}
                                    
            {foreach $children as $child}                <div class="review-child">                    <h3>{attribute_view_gui attribute=$child.data_map.author}: <br />{$child.name|wash()}</h3>                    <div class="review-rating">                        <span class="rate-me">{'Rate this review:'|i18n( 'extension/xrowecommerce' )}</span>                        {attribute_view_gui attribute=$child.data_map.rating}                    </div>                    <p>{attribute_view_gui attribute=$child.data_map.review}</p>                    <p>({attribute_view_gui attribute=$child.data_map.author} )</p>                </div>            {/foreach}
                        {include name=navigator                     uri='design:navigator/google.tpl'                     page_uri=concat('/content/view','/full/',$node.node_id)                     item_count=$children_count                     view_parameters=$view_parameters                     item_limit=$item_limit}                        
            {if is_unset( $versionview_mode )}                {if $node.object.can_create}                    {def $notification_access=fetch( 'user', 'has_access_to', hash( 'module', 'notification', 'function', 'addtonotification' ) )}                    <input class="button product-new-review" type="submit" name="NewButton" value="{'Create your review'|i18n( 'extension/xrowecommerce' )}" />                {else}                    <p>{"You need to be logged in to create reviews. You can do so %login_link_start%here%login_link_end%"|i18n( "extension/xrowecommerce",, hash( '%login_link_start%', concat( '<a href=', '/user/login/'|ezurl, '>' ), '%login_link_end%', '</a>' ) )}</p>                {/if}            {/if}        {/if}
    </form>
</div>