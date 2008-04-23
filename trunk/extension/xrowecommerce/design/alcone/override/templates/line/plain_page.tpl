{* Plain page - Line view *}
<div class="content-view-line">
    <div class="class-folder">
        <h2>{$node.name|wash()}</h2>
        <div class="attribute-link">
            <a href={$node.url_alias|ezurl()} style="text-decoration: none;">Show <img src={"images/arrow.gif"|ezdesign()}></a>
        </div>
        <br />
    </div>
</div>
