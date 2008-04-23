<div id="col_left_Front"> 
<div id="toolbar-front-left" style="margin-left: 0px; margin-top: 10px;">
{tool_bar name=leftcat view=full}
</div>
{literal}<style type="text/css" media="screen">
div#columns1{{/literal}background-image:url({'images/main-bg.jpg'|ezdesign});{literal}}
</style>{/literal}
</div>

<div id="col_main_3">
{tool_bar name=flashheader view=full} {*  <br /> *}
  <div style="margin-left: 3px; margin-top: 10px;"><span class="headinghome">Featured Products</span></div>
  <div class="break"></div> {*  <br /> *}
{tool_bar name=frontpage view=full}
  <div class="break"></div>
  <div style="padding-left: 3px;">{$node.data_map.description.content.output.output_text}</div>
</div>

<div id="col_right" style="margin-left: -10px;">
{include uri="design:parts/right_menu_standard.tpl"}
</div>
