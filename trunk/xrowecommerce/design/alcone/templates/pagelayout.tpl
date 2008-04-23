<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="{$site.http_equiv.Content-language|wash}" lang="{$site.http_equiv.Content-language|wash}">

<head>
<meta name="verify-v1" content="+fmSMl7yrLHA2bKq0+IDkqyoCFb2gd9RzAQpiS9FUhI=" />
<meta name="y_key" content="b8d8e1ce5ffec31e" />
<style type="text/css">
    @import url({"stylesheets/core.css"|ezdesign});
    @import url({"stylesheets/classes.css"|ezdesign});
    @import url({"stylesheets/classes-colors.css"|ezdesign});
    @import url({"stylesheets/alcone.css"|ezdesign});
    @import url({"stylesheets/debug.css"|ezdesign});
</style>
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
<script type="text/javascript" src={"javascript/yui/yahoo-dom-event/yahoo-dom-event.js"|ezdesign}></script>
<script type="text/javascript" src={"javascript/yui/container/container.js"|ezdesign}></script>
{cache-block keys=array( $uri_string )}
{include uri="design:page_head.tpl"}
{/cache-block}
</head>
<body style="text-align: left;">

<div id="overDiv" style="position:absolute; visibility:hidden; z-index:1000;"></div>

<div style="text-align: left;">
<div id="allcontent" style="text-align: left;">

    <div id="topcontent">

        <div id="subheader">
            	<a href={"/"|ezurl}><img src={"images/alcone_logo_new.gif"|ezdesign()} alt="Alcone Beauty Company" /></a>
        </div>{* id="subheader" *}
<div id="subheader-design-right-top">
<div style="float:right;">
    <ul id="nav2">
    <LI class="toolbar-item">
                    <a href={"/shop/basket"|ezurl}>
                    	<img src={"images/cart.gif"|ezdesign()} alt="Alcone Company Shopping Basket" />
                    </a>

                    <a href={"/shop/basket"|ezurl}>View cart</a>
    </LI>
    <LI class="toolbar-item">
                    {if $current_user.is_logged_in}
                    <a href={concat("/content/edit/",$current_user.contentobject_id)|ezurl}>{'My account'|i18n('design/standard/user')}</a>
       				<ul>
       				    <LI><a href={concat("/content/edit/",$current_user.contentobject_id)|ezurl} class="subitem">{'Login, Billing and Shipping'|i18n('design/standard/user')}</a></LI>
       				    {if fetch( 'user', 'has_access_to', hash( 'module', 'recurringorders', 'function', 'use' ) )}
       				    <LI><a href={"recurringorders/list"|ezurl} class="subitem">{'Automatic Delivery'|i18n('design/standard/user')}</a></LI>
       				    {/if}
       				    {if fetch( 'user', 'has_access_to', hash( 'module', 'order', 'function', 'invoice' ) )}
       				    <LI><a href={"order/history"|ezurl} class="subitem">{'Order History'|i18n('design/standard/user')}</a></LI>
       				    {/if}
              	   	</ul>

         		    </LI>
                    
                    <LI class="toolbar-item"><a href={"/user/logout"|ezurl}>Logout</a></LI>
                    {else}
                    <LI class="toolbar-item"><a href={"/user/login"|ezurl}>Login</a></LI>
                    {/if}
                    <LI class="toolbar-item last"><a href={"/contact_us"|ezurl}>Contact us</a></LI>
                    <form name="useredit" action={concat("/user/edit/", $current_user.contentobject_id)|ezurl} method="post">
                    <input type="hidden" name="EditButton" value="EditButton" />
                    <input type="hidden" name="ContentObjectLanguageCode" value="eng-US" />
                    </form>

</ul>

{cache-block}
</div>
<div class="break"></div>
<div id="search">
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
         <input type="hidden" name="SearchPageLimit" value="5" />
         <input type="hidden" name="SearchContentClassID" value="16,20,21,23" />

         <input type="text" name="SearchText" value="keyword" size="15" class="searchbox" onfocus="this.select();" style="font-size: 9px;" />&nbsp;
         <input type="image" src={"images/go.gif"|ezdesign()} style="vertical-align:middle;" />
     </div>
     </form>
</div>
    </div>






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
{cache-block}
    {include uri="design:topnavigation.tpl"}
{/cache-block}
    {default current_user=fetch('user','current_user')}
        <div class="break"></div>
    </div>{* id="topcontent" *}
<hr class="hide" />
    <hr class="hide" />


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

            <div id="maincontent">
                <div id="fix">
                    <div id="maincontent-design">

{if or( $uri_string|begins_with('content/edit'), $uri_string|begins_with('order/history'),$uri_string|begins_with('recurringorders/list') )} 
    <div class="shop-basket">
    <h1>My Account</h1>
    <ul id="my-account">
        <li{if $uri_string|begins_with('content/edit')} id="my-account-active"{/if}><a href={concat("/content/edit/",$current_user.contentobject_id)|ezurl}>Login, Billing and Shipping</a></li>
        <li{if $uri_string|begins_with('recurringorders/list')} id="my-account-active"{/if}><a href={"recurringorders/list"|ezurl}>Automatic Delivery</a></li>
        <li{if $uri_string|begins_with('order/history')} id="my-account-active"{/if}><a href={"order/history"|ezurl}>Order History</a></li>
    </ul>
    {$module_result.content}

    </div>
{else}
    {$module_result.content}
{/if}
                    </div>{* id="maincontent-design" *}
                    <div class="break"></div>
                </div>{* id="fix" *}
            </div>{* id="maincontent" *}
            <div class="break"></div>
        </div>{* id="columns1" *}
    </div>{* id="columns" *}

    <hr class="hide" />
{cache-block}
    {section show=ezini('Toolbar_bottom','Tool','toolbar.ini')|count}
        <div id="toolbar-bottom">
            <div class="toolbar-design">
                {tool_bar name=bottom view=line}
            </div>{* id="toolbar-design" *}
            <div class="break"></div>
        </div>{* id="toolbar-bottom" *}
    {/section}

<div id="footerwrap">
{*
<div style="float: right; margin-top: 1em;margin-right: 1em;margin-bottom: 1em;">
 <!-- (c) 2006. Authorize.Net is a registered trademark of Lightbridge, Inc. --> <div class="AuthorizeNetSeal"> <script type="text/javascript" language="javascript">var ANS_customer_id="01379008-1eaa-42e0-a3a7-2e62fb76be50";</script> <script type="text/javascript" language="javascript" src="//VERIFY.AUTHORIZE.NET/anetseal/seal.js" ></script> <a href="http://www.authorize.net/" id="AuthorizeNetText" target="_blank">Transaction Processing</a> </div>
</div>
<div style="float: right; margin-top: 1em;margin-right: 1em;margin-bottom: 1em;">
<script src="https://siteseal.thawte.com/cgi/server/thawte_seal_generator.exe"></script>
</div>
*}
    <div style="float: left;margin-top: 1em;margin-left: 1em;">
        <div id="footer-design">
<address>&copy; 2006 - {currentdate()|datetime(custom, '%Y')} by <a href="http://alconeco.com">Alcone</a> | A joint project by <a href="http://www.clicktechnologies.com">Click Technologies</a> and <a href="http://xrow.de">xrow</a> on <a href="http://ez.no">eZ publish</a></address>

        </div>{* id="footer-design" *}
    </div>{* id="footer" *}
    <div class="break"></div>
</div>{* id="allcontent" *}

</div>

</div>

{include uri="design:google/analytics/stats.tpl"}
{/cache-block}
</body>
</html>
