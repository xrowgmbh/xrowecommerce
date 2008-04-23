{* Sitemap *}
{def $children=fetch('content','list',hash( parent_node_id,$node.node_id, 'sort_by', array( 'priority', true() ) ) ) }
<div id="product_full">
    <h2>{"Site map"|i18n("design/standard/node/view")}</h2>
    <br />
       
    <div style="float:left; width: 48%;">
        {foreach $children as $index => $child}
            <h3><a href={$child.url_alias|ezurl} style="text-decoration: none;">{$child.name}</a></h3>
            {def $sub_children=fetch('content','list',hash(parent_node_id,$child.node_id))}
            <ul style="list-style-image:url({"images/arrow.gif"|ezdesign(no)})">
                {foreach $sub_children as $sub_child}
                    <li ><a href={$sub_child.url_alias|ezurl}>{$sub_child.name}</a></li>
                {/foreach}
            </ul>
            <div id="dottedline_long">&nbsp;<br /></div>
            {delimiter modulo=sum(div($children|count,2),1)}
                </div><div style="float:right; width: 48%;">
            {/delimiter}
        {/foreach}
    </div>
</div>