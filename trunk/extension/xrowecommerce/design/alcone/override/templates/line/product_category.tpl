{* Product category - Line view *}
<div class="content-view-line">
    <div class="class-folder">
        <h2>{$node.name|wash()}</h2>
       {section show=$node.data_map.description.content.is_empty|not}
        <div class="attribute-short">
        {attribute_view_gui attribute=$node.data_map.description}
        </div>
       {/section}
        <div class="attribute-link">
            <a href={$node.url_alias|ezurl()} style="text-decoration: none;">Show <img src={"images/arrow.gif"|ezdesign()}></a>
        </div>
        <br /><br />
    </div>
</div>
