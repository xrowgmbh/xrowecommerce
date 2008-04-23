<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$site.http_equiv.Content-language|wash}" lang="{$site.http_equiv.Content-language|wash}">

<head>
<META name="verify-v1" content="+fmSMl7yrLHA2bKq0+IDkqyoCFb2gd9RzAQpiS9FUhI=" />
<style type="text/css">
    @import url({"stylesheets/core.css"|ezdesign});
    {*@import url({"stylesheets/site-colors.css"|ezdesign});*}
    @import url({"stylesheets/classes.css"|ezdesign});
    @import url({"stylesheets/classes-colors.css"|ezdesign});
    @import url({"stylesheets/alcone.css"|ezdesign});
    @import url({"stylesheets/debug.css"|ezdesign});
</style>

{* literal}
<script src="https://ssl.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-169400-3";
urchinTracker();
</script>
{/literal *}

{* Google Analytics Page Statistics Tracking Template Code *}
{* def $submit=ezini( 'GoogleAnalyticsWorkflow', 'PageSubmitToGoogle', 'googleanalytics.ini' )
     $uacct=ezini( 'GoogleAnalyticsWorkflow', 'Urchin', 'googleanalytics.ini' )
     $udn=ezini( 'GoogleAnalyticsWorkflow', 'HostName', 'googleanalytics.ini' )
     $js_url=ezini( 'GoogleAnalyticsWorkflow', 'Script', 'googleanalytics.ini' )}

{if and( eq( $submit, 'enabled' ), is_set( $uacct ), is_set( $js_url ) )}<script src="{$js_url}" type="text/javascript"></script>
<script type="text/javascript">
 _uacct = "{$uacct}";
{if $udn|ne('disabled')} _udn="{$udn}";{/if}
{literal} urchinTracker();
</script>{/literal}
{/if *}

{include uri="design:google/analytics/stats.tpl"}

{section name=JavaScript loop=ezini( 'JavaScriptSettings', 'JavaScriptList', 'design.ini' ) }
    <script language="JavaScript" type="text/javascript" src={concat( 'javascript/',$:item )|ezdesign}></script>
{/section}


{literal}
<!--[if lt IE 6.0]>
<style>
div#maincontent-design { width: 100%; } /* This is needed to avoid width bug in IE 5.5 */
</style>
<![endif]-->
{/literal}

{include uri="design:page_head.tpl"}

{literal}
<script type="text/javascript"><!--//--><![CDATA[//><!--
startList = function() {
	if (document.all&&document.getElementById) {
		navRoot = document.getElementById("nav");
		for (i=0; i<navRoot.childNodes.length; i++) {
			node = navRoot.childNodes[i];
			if (node.nodeName=="LI") {
				node.onmouseover=function() {
					this.className+=" over";
				}
				node.onmouseout=function() {
					this.className=this.className.replace(" over", "");
				}
			}
		}
	}
}
window.onload=startList;

//--><!]]>
</script>
{/literal}


  <meta http-equiv="Content-Script-Type" content="text/javascript">
</head>
<body onload="__utmSetTrans();" style="text-align: left;">

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<div style="text-align: left;">
<div id="allcontent" style="text-align: left;">

{cache-block keys=array($uri_string, $current_user.role_id_list|implode( ',' ), $current_user.limited_assignment_value_list|implode( ',' ))}
    <div id="topcontent">



    <div id="subheader-design-right-top">


                    <a href={"/shop/basket"|ezurl}>
                    	<img src={"images/cart.gif"|ezdesign()} style="position: relative; top: 4px;" alt="Alcone Company Shopping Basket" />
                    </a>
                    <a href={"/shop/basket"|ezurl}>View cart</a>&nbsp;&nbsp;|&nbsp;
                    {if $current_user.is_logged_in}
                    <a href={concat("/content/edit/", $current_user.contentobject_id,"/f/eng-US/")|ezurl}>My account</a>&nbsp;&nbsp;|&nbsp;
                    <a href={"/user/logout"|ezurl}>Logout</a>&nbsp;&nbsp;|&nbsp;
                    {else}
                    <a href={"/user/login"|ezurl}>Login</a>&nbsp;&nbsp;|&nbsp;
                    {/if}
                    <a href={"/contact_us"|ezurl}>Contact us</a>
    <br /><br />



        <form action={"/content/advancedsearch/"|ezurl} method="get">
        <div style="text-align: left !important;">
        <b><span style="font-family: Times; font-size: 14px;color: #308d9d; font-weight: normal; ">SEARCH:</span></b><br />
        </div>
        {def $page_limit=15
         $manus=fetch( 'content', 'list', hash( 'parent_node_id', 128,
                       'sort_by', array( array( 'name' ) ),
                       'class_filter_type',  'include',
                       'class_filter_array', array( 'manufacturer' ) ) ) }
    {def $manu_list=array()}
    <div style="float: left; height: 40px; text-align: left;">
    <label>By manufacturer</label>
     <select name="brand" style="font-size: 9px; margin-top: 3px;"
     onchange="javascript:window.location.href=this.value;">
        <option value="">by manufacture/brand</option>
        {foreach $manus as $manu_element}
            <option value="{$manu_element.url_alias|ezurl(no)}"
     {if eq($module_result.content_info.node_id, $manu_element.node_id)} selected {/if}
            >{$manu_element.name}</option>
        {/foreach}
     </select>
     </div>

     <div style="float: left; height: 40px; padding-left: 10px; text-align: left;">
        <label>By keyword:</label>
         <input type="hidden" name="SearchPageLimit" value="5">
         <input type="hidden" name="SearchContentClassID" value="16,20,21,23">

         <input type="text" name="SearchText" value="keyword" size="15" class="searchbox" onfocus="this.select();" style="font-size: 9px;">&nbsp;
         <input type="image" src={"images/go.gif"|ezdesign()} style="vertical-align:middle;">
         </form>
     </div>

    </div>





        <div id="subheader" style="padding: 7px;">
            <div id="subheader-design" style="padding: 0; margin: 0;">
            	<a href={"/"|ezurl}><img src={"images/alcone_logo_new.gif"|ezdesign()} alt="Alcone Beauty Company" /></a>
            </div>{* id="subheader-design" *}

        </div>{* id="subheader" *}
            <div class="break"></div>
{/cache-block}

    {*section show=ezini('Toolbar_top','Tool','toolbar.ini')|count}
        <div id="toolbar-top">
            <div class="toolbar-design1">
                {tool_bar name=top view=line}
            </div>
             <div class="break"></div>
        </div>{id="toolbar-top"}
    {/section*}
    {include uri="design:topnavigation.tpl"}

    {default current_user=fetch('user','current_user')}
    {cache-block keys=array($uri_string, $current_user.role_id_list|implode( ',' ), $current_user.limited_assignment_value_list|implode( ',' ))}
        <div class="break"></div>
    </div>{* id="topcontent" *}
<hr class="hide" />
    <hr class="hide" />
    {/cache-block}



    {cache-block keys=array($uri_string, $current_user.role_id_list|implode( ',' ), $current_user.limited_assignment_value_list|implode( ',' ))}
    <hr class="hide" />

    <div id="columns">
    	{if eq($module_result.node_id, '2')}
		<div id="path" style="padding: 0; margin: 0; height: 5px; overflow:hidden;">
				<div id="path-design">&nbsp;
		        </div>{* id="path-design" *}
			{else}
			<div id="path">
		        <div id="path-design">
		            {include uri="design:parts/path.tpl"}
		        </div>{* id="path-design" *}
		    {/if}
		</div>{* id="path" *}
    <div id="columns1">

    {/cache-block}
    {/default}


        {cache-block}


            <div id="maincontent">
                <div id="fix">
                    <div id="maincontent-design">


        {/cache-block}

                        {* wrap_user_func('tidy', array( $module_result.content ) ) *}
{$module_result.content}

                    </div>{* id="maincontent-design" *}
                    <div class="break"></div>
                </div>{* id="fix" *}
            </div>{* id="maincontent" *}

            <div class="break"></div>
    </div>{* id="columns1" *}
            </div>{* id="columns" *}

    <hr class="hide" />

    {section show=ezini('Toolbar_bottom','Tool','toolbar.ini')|count}
        <div id="toolbar-bottom">
            <div class="toolbar-design">
                {tool_bar name=bottom view=line}
            </div>{* id="toolbar-design" *}
            <div class="break"></div>
        </div>{* id="toolbar-bottom" *}
    {/section}

<div style="float: right; margin-top: 1em;margin-right: 1em;margin-bottom: 1em;">
 <!-- (c) 2006. Authorize.Net is a registered trademark of Lightbridge, Inc. --> <div class="AuthorizeNetSeal"> <script type="text/javascript" language="javascript">var ANS_customer_id="01379008-1eaa-42e0-a3a7-2e62fb76be50";</script> <script type="text/javascript" language="javascript" src="//VERIFY.AUTHORIZE.NET/anetseal/seal.js" ></script> <a href="http://www.authorize.net/" id="AuthorizeNetText" target="_blank">Transaction Processing</a> </div>
</div>
<div style="float: right; margin-top: 1em;margin-right: 1em;margin-bottom: 1em;">
<script src="https://siteseal.thawte.com/cgi/server/thawte_seal_generator.exe"></script>
</div>

    <div style="float: left;margin-top: 1em;margin-left: 1em;">
        <div id="footer-design">
<address>&copy; 2006 by <a href="http://www.alconeco.com">alcone</a> | A joint project by <a href="http://www.clicktechnologies.com">Click Technologies</a> and <a href="http://www.xrow.de">xrow</a> on <a href="http://ez.no">eZ publish</a></address>

        </div>{* id="footer-design" *}
    </div>{* id="footer" *}
    <div class="break"></div>
</div>{* id="allcontent" *}
</div>
</body>
</html>
