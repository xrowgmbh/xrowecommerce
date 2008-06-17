<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$site.http_equiv.Content-language|wash}" lang="{$site.http_equiv.Content-language|wash}">

<head>
{include uri='design:page_head.tpl'}

{* cache-block keys=array('navigation_tabs',$navigation_part.identifier,$current_user.contentobject_id) *}
{* Cache header for each navigation part *}

<script language="JavaScript" type="text/javascript" src={'javascript/tools/ezjsselection.js'|ezdesign}></script>
{section name=JavaScript loop=ezini( 'JavaScriptSettings', 'JavaScriptList', 'design.ini' ) }
<script language="JavaScript" type="text/javascript" src={concat( 'javascript/',$:item )|ezdesign}></script>
{/section}

<style type="text/css">
    @import url({'stylesheets/core.css'|ezdesign});
    @import url({'stylesheets/site.css'|ezdesign});
    @import url({'stylesheets/debug.css'|ezdesign});
{section var=css_file loop=ezini( 'StylesheetSettings', 'CSSFileList', 'design.ini' )}
    @import url({concat( 'stylesheets/',$css_file )|ezdesign});
{/section}
</style>

{literal}
<script language="JavaScript" type="text/javascript">
<!--
document.write("<style type='text/css'>div#contentstructure ul#content_tree_menu ul li { padding-left: 0; }div#contentstructure ul#content_tree_menu ul ul { margin-left: 20px; }</style>");
-->
</script>
{/literal}

{literal}
<!--[if IE]>
<style type="text/css">
div#leftmenu div.box-bc, div#rightmenu div.box-bc { border-bottom: 1px solid #bfbeb6; /* Strange IE bug fix */ }
div#contentstructure { overflow-x: auto; overflow-y: hidden; } /* hide vertical scrollbar in IE */
div.menu-block li { width: 19%; } /* Avoid width bug in IE */
div.notranslations li { width: 24%; } /* Avoid width bug in IE */
div.context-user div.menu-block li { width: 14%; } /* Avoid width bug in IE */
input.button, input.button-disabled { padding: 0 0.5em 0 0.5em; overflow: visible; }
input.box, textarea.box { width: 98%; }
td input.box, td textarea.box { width: 97%; }
div#search p.select { margin-top: 0; }
div#search p.advanced { margin-top: 0.3em; }
div.content-navigation div.mainobject-window div.fixedsize { float: none; overflow: scroll; }
div.fixedsize input.box, div.fixedsize textarea.box, div.fixedsize table.list { width: 95%; }
a.openclose img, span.openclose img { margin-right: 4px; }
div#fix { overflow: hidden; }
</style>
<![endif]-->
<!--[if lt IE 6.0]>
<style type="text/css">
div#maincontent div.context-block { width: 100%; } /* Avoid width bug in IE 5.5 */
div#maincontent div#maincontent-design { width: 98%; } /* Avoid width bug in IE 5.5 */
</style>
<![endif]-->
<!--[if IE 6.0]>
<style type="text/css">
div#maincontent div.box-bc { border-bottom: 1px solid #bfbfb7; /* Strange IE bug fix */ }
div#leftmenu-design { margin: 0.5em 4px 0.5em 0.5em; }
</style>
<![endif]-->
{/literal}

{section show=ezpreference( 'admin_left_menu_width' )}
{let left_menu_widths=ezini( 'LeftMenuSettings', 'MenuWidth', 'menu.ini' )
     left_menu_width=$left_menu_widths[ezpreference( 'admin_left_menu_width' )]}
<style type="text/css">
div#leftmenu {ldelim} width: {$left_menu_width}em; {rdelim}
div#maincontent {ldelim} margin-left: {sum( $left_menu_width, 0.5 )}em; {rdelim}
</style>
{/let}
{/section}

<!--[if gte IE 5.5000]>
<script type="text/javascript">
    var emptyIcon16 = {'16x16.gif'|ezimage};
    var emptyIcon32 = {'32x32.gif'|ezimage};
</script>
<script type="text/javascript" src={'javascript/tools/eziepngfix.js'|ezdesign}></script>
<![endif]-->

</head>

<body>
<a name="top" class="context-title"></a>
<div id="allcontent">
<div id="header">
<div id="header-design">

<div id="logo">
<img src={'ezpublish-logo-200x40.gif'|ezimage} width="200" height="40" alt="eZ publish" border="0" />
<p>version {fetch( setup, version )}</p>
</div>

{* --- Search ---*}
<div id="search">
<form action={'/content/search/'|ezurl} method="get">

{section show=or( eq( $ui_context, 'edit' ), eq( $ui_context, 'browse' ) )}
    <input id="searchtext" name="SearchText" type="text" size="20" value="{section show=is_set( $search_text )}{$search_text|wash}{/section}" disabled="disabled" />
    <input id="searchbutton" class="button-disabled" name="SearchButton" type="submit" value="{'Search'|i18n( 'design/admin/pagelayout' )}" disabled="disabled" />
{section-else}
    <input id="searchtext" name="SearchText" type="text" size="20" value="{section show=is_set( $search_text )}{$search_text|wash}{/section}" />
    <input id="searchbutton" class="button" name="SearchButton" type="submit" value="{'Search'|i18n( 'design/admin/pagelayout' )}" />
{/section}
    <p class="select">
    {let disabled=false()
         nd=1
         left_checked=true()
         current_loc=true()}
    {section show=or( eq( $ui_context, 'edit' ), eq( $ui_context, 'browse' ) )}
        {set disabled=true()}
    {section-else}
        {section show=is_set($module_result.node_id)}
            {set nd=$module_result.node_id}
        {section-else}
            {section show=is_set($search_subtree_array)}
                {section show=count($search_subtree_array)|eq(1)}
                    {section show=$search_subtree_array.0|ne(1)}
                        {set nd=$search_subtree_array.0}
                        {set left_checked=false()}
                    {section-else}
                        {set disabled=true()}
                    {/section}
                    {set current_loc=false()}
                {section-else}
                    {set disabled=true()}
                {/section}
            {section-else}
                {set disabled=true()}
            {/section}
        {/section}
    {/section}
    <label{section show=$disabled} class="disabled"{/section}><input type="radio" name="SubTreeArray" value="1" checked="checked"{section show=$disabled} disabled="disabled"{section-else} title="{'Search all content.'|i18n( 'design/admin/pagelayout' )}"{/section} />{'All content'|i18n( 'design/admin/pagelayout' )}</label>
    <label{section show=$disabled} class="disabled"{/section}><input type="radio" name="SubTreeArray" value="{$nd}"{section show=$disabled} disabled="disabled"{section-else} title="{'Search only from the current location.'|i18n( 'design/admin/pagelayout' )}"{/section} />{section show=$current_loc}{'Current location'|i18n( 'design/admin/pagelayout' )}{section-else}{'The same location'|i18n( 'design/admin/pagelayout' )}{/section}</label>
    {/let}
    </p>
    <p class="advanced">
    {section show=or( eq( $ui_context, 'edit' ), eq( $ui_context, 'browse' ) )}
    <span class="disabled">{'Advanced'|i18n( 'design/admin/pagelayout' )}</span>
    {section-else}
        <a href={'/content/advancedsearch'|ezurl} title="{'Advanced search.'|i18n( 'design/admin/pagelayout' )}">{'Advanced'|i18n( 'design/admin/pagelayout' )}</a>
    {/section}
    </p>
</form>
</div>

<div class="break"></div>

</div>
</div>

<hr class="hide" />

<div id="topmenu">
<div id="topmenu-design">

<h3 class="hide">Top menu</h3>
<ul>
{section var=Menu loop=topmenu($ui_context)}
    {include uri='design:page_topmenuitem.tpl' menu_item=$Menu navigationpart_identifier=$navigation_part.identifier}
{/section}
</ul>
<div class="break"></div>
</div>
</div>

{* /cache-block *}

<hr class="hide" />


<div id="path">
<div id="path-design">

{include uri='design:page_toppath.tpl'}

</div>
</div>


<hr class="hide" />

<div id="columns">

{section show=and( eq( $ui_context, 'edit' ), eq( $ui_component, 'content' ) )}
{section-else}
<div id="leftmenu">
<div id="leftmenu-design">

{section show=and( $ui_context|eq( 'edit' ), $ui_component|eq( 'content' ) )}
    {include uri='design:edit_menu.tpl'}
{section-else}

{section show=eq( $navigation_part.identifier, 'ezcontentnavigationpart' )}
    {include uri='design:parts/content/menu.tpl'}
{/section}

{section show=eq( $navigation_part.identifier, 'ezmedianavigationpart' )}
    {include uri='design:parts/media/menu.tpl'}
{/section}

{section show=eq( $navigation_part.identifier, 'ezshopnavigationpart' )}
    {include uri='design:parts/shop/menu.tpl'}
{/section}

{section show=eq( $navigation_part.identifier, 'ezusernavigationpart' )}
    {include uri='design:parts/user/menu.tpl'}
{/section}

{section show=eq( $navigation_part.identifier, 'ezvisualnavigationpart' )}
    {include uri='design:parts/visual/menu.tpl'}
{/section}

{section show=eq( $navigation_part.identifier, 'ezsetupnavigationpart' )}
    {include uri='design:parts/setup/menu.tpl'}
{/section}

{section show=eq( $navigation_part.identifier, 'ezmynavigationpart' )}
    {include uri='design:parts/my/menu.tpl'}
{/section}

{section show=is_set( $module_result.left_menu )}
    {include uri=$module_result.left_menu}
{/section}

{/section}

</div>
</div>

<hr class="hide" />

{/section}

<div id="rightmenu">
<div id="rightmenu-design">

<h3 class="hide">Right</h3>

{tool_bar name='admin_right' view=full}
{tool_bar name='admin_developer' view=full}

</div>
</div>

<hr class="hide" />

{section show=and( eq( $ui_context, 'edit' ), eq( $ui_component, 'content' ) )}

{* Main area START *}

{include uri='design:page_mainarea.tpl'}

{* Main area END *}

{section-else}

<div id="maincontent"><div id="fix">
<div id="maincontent-design">
<!-- Maincontent START -->
{* Main area START *}

{include uri='design:page_mainarea.tpl'}

{* Main area END *}

<!-- Maincontent END -->
</div>
<div class="break"></div>
</div></div>

{/section}

<div class="break"></div>
</div>

<hr class="hide" />

<div id="footer">
<div id="footer-design">

<a name="base" style="text-decoration: none;"></a><a href="#top">Return to Top</a>

{include uri='design:page_copyright.tpl'}

</div>
</div>

<div class="break"></div>
</div>

{* The popup menu include must be outside all divs. It is hidden by default. *}
{include uri='design:popupmenu/popup_menu.tpl'}

{* This comment will be replaced with actual debug report (if debug is on). *}
<!--DEBUG_REPORT-->

</body>
</html>
