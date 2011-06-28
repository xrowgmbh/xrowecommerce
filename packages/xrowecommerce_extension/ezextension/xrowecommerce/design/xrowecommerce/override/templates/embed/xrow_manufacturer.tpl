<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">
    <div class="content-view-embed">
        <div class="class-manufacturer">
            <div class="attribute-header">
                <h2>{$object.name|wash()}</h2>
            </div>
            <div class="attribute-logo">
                    {attribute_view_gui image_class=medium attribute=$object.data_map.logo}
            </div>
            <div class="attribute-description">
                    {attribute_view_gui attribute=$object.data_map.description}
            </div>
        </div>
    </div>
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>