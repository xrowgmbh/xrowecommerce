<div id="col_left_Front">
<div id="toolbar-front-left">
   {tool_bar name=leftcat view=full}
</div>
</div>

<div id="col_main">

{if $node.data_map.image.content.is_valid}
            {attribute_view_gui attribute=$node.data_map.image image_class=original}
        {else}
        {*<div class="imageleft">
                <img src={"images/no_pic.jpg"|ezdesign} width="140">
            </div>*}
        {/if}

<div id="SpecialCatDecription">
        {if $node.data_map.description.content.is_empty|not}
            {attribute_view_gui attribute=$node.data_map.description}
        {else}
        {/if}
</div>
	<br />
<div class="break"></div>
<br />
<img src="/extension/alcone/design/alcone/images/purpleline.gif" />
{def $sp_prod=fetch( 'content', 'tree', hash(	'parent_node_id', 3163,
              							  	'sort_by', array( array( 'priority' ) ),
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'product' ),
              							  	'limit', 20) ) }

{if gt($sp_prod|count(),0)}

{section var=$prod loop=$sp_prod sequence=array( leftdecoration , rightdecoration)}
		           		{undef $var_price}
	                    {undef $allprice}
	                    {undef $partprice}

                {if count($prod.data_map.variation.content.option_list)|eq(1)}
           	        {def $allprice=$prod.data_map.variation.content.option_list.0.additional_price}
           	    {elseif count ($prod.data_map.variation.content.option_list)|gt(1)}
           	        {foreach $prod.data_map.variation.content.option_list as $var_price}
           	            {if or( $var_price.additional_price|lt($partprice), is_set($partprice)|not ) }
           	                {def $partprice=$var_price.additional_price}
           	            {/if}
           	        {/foreach}
           	    {/if}

	<div id="product_sp">
		<div id="product_sp" class="{$prod.sequence}">
			<div id="product_sp_margins">
				<div id="product_sp_window">
			{if $prod.data_map.image_link.content.current.contentobject_attributes.2.content.is_valid}
               <a href={$prod.url_alias|ezurl()}>{attribute_view_gui attribute=$prod.data_map.image_link.content.current.contentobject_attributes.2 image_class=small alignment=center}</a>
			{elseif $prod.data_map.image.content.is_valid}
                <a href={$prod.url_alias|ezurl()}>{attribute_view_gui attribute=$prod.data_map.image image_class=small alignment=center}</a>
            {else}
                <a href={$prod.url_alias|ezurl()}>
                <img src={"images/no_pic.jpg"|ezdesign()} width="80">
                </a>
            {/if}
				</div>
			<br />
		  <h4 class="sp_prod_title">{$prod.name}  {if eq($prod.class_identifier, 'category')}
			      {attribute_view_gui attribute=$prod.data_map.description}
			     {elseif or( $partprice|gt(0), $allprice|gt(0) ) }
                    {if $partprice|gt(0)}
                        {$partprice|l10n( 'currency' )}
                    {/if}
                    {if $allprice|gt(0)}
                      {$allprice|l10n( 'currency' )}
                    {/if}
                {else}
						{section show=$prod.data_map.price.content.has_discount}
							{"Price"|i18n("design/base")}: <span class="oldprice">{$prod.data_map.price.content.inc_vat_price|l10n(currency)}</span><br/>
							{"Your price"|i18n("design/base")}: {$prod.data_map.price.content.discount_price_inc_vat|l10n(currency)}<br />
							{"You save"|i18n("design/base")}: <span class="pricesave">{sub($prod.data_map.price.content.inc_vat_price,$attribute.content.discount_price_inc_vat)|l10n(currency)} ( {$attribute.content.discount_percent} % )</span>
						{section-else}
							{$prod.data_map.price.content.inc_vat_price|l10n(currency)}
						{/section}
				{/if}</h4>

			{* <a href={$prod.url_alias|ezurl()}>{$prod.name}</a>*}
			{* $prod.data_map.description|attribute(show,1) *}

            {$prod.data_map.short_description.content.output.output_text}

			<a href={$prod.url_alias|ezurl()}>View Details...</a>
<div class="sp_currentprice">
            <form method="post" action="/content/action">
           		<p>
          		 	QTY: <input type="text" name="ProductItemCountList" value="" style="width: 25px; border: 1px solid #CC0066;">


            			<input type="image" src="/extension/alcone/design/alcone/images/addtocartpurple.jpg" name="ActionAddToBasket" value="Add to basket">
            			<input type="hidden" name="ContentNodeID" value="{$prod.node_id}" />
           				 <input type="hidden" name="ContentObjectID" value="{$prod.contentobject_id}" />
            			<input type="hidden" name="ViewMode" value="full" />
        		  	</div>
          	 </form>
	    </div>
		 </div>
		</div>
{/section}
	</div>
<br />
<div id="col_right">
    <form action={"/content/search/"|ezurl} method="get">
        <div style="vertical-align: middle;">
            <b><span style="font-family: Times; font-size: 14px;color: #308d9d; font-weight: normal;">SEARCH:</span></b>
            <input type="text" name="SearchText" value="keyword" class="searchbox"
            onfocus="this.select();"
            >&nbsp;
            <input type="image" src={"images/go.gif"|ezdesign()} style="vertical-align:middle;">
            <div id="dottedline"></div>
        </div>
    </form>

<h2>KNOW IT ALL</h2>
<p>
Yes we do. And if we don't, we definitely know who does.<br />
<form action={"/content/search/"|ezurl} method="get">
<input type="hidden" name="SubTreeArray[]" value="70">
<input type="hidden" name="SubTreeArray[]" value="119">
<input type="text" name="SearchText" class="who" value="Enter your question?">&nbsp;
 <input type="image" src={"images/go.gif"|ezdesign()} style="vertical-align:middle;">
<a href={"/know_it_all"|ezurl()}>CLICK HERE</a>&nbsp;<img src={"images/arrow.gif"|ezdesign()}></p>
</form>
</p>
<br />
{*
<h2>In the know</h2>
<p>
Get the best of "Know it All" and our makeup artist tips
and gossip each month.
<a href={"user/login"|ezurl}>
<img src={"images/arrow.gif"|ezdesign()}></a></p>
<br /><br />
<h2>Free Samples</h2>
<p>
Curious why our sponges and makeup remover clothes are used by top makeup artists... we'll let you take them for a spin.<br />
<a href={"/"}>CLICK HERE</a>&nbsp;<img src={"images/arrow.gif"|ezdesign()}></p>
*}
<br />
{*
<h2>Get the look</h2>
<p>
Tell us a little about yourself, and find out what our makeup artist council recommends you wear.<br />
<a href={"/"}>GET STARTED</a>&nbsp;<img src={"images/arrow.gif"|ezdesign()}></p>
<br />

<h2>KNOW IT ALL</h2>
<p>
Yes we do. And if we don't, we definately know how does.<br /><br />
{def $intheknown=fetch( 'content', 'tree', hash(	'parent_node_id', 119,
              							  	'sort_by', array( array( 'priority' ) ),
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'in_the_known' ),
              							  	'limit', 4 ) )}
{foreach $intheknown as $known}
	<a href={concat("/in_the_know/(question)/",$known.node_id)|ezurl()}>{$known.name}</a> <br /><br />
{/foreach}

<a href={"/faqs"|ezurl()}>CLICK HERE</a>&nbsp;<img src={"images/arrow.gif"|ezdesign()}>
<br /><br /><br />

<h2>know it all</h2>
<span class="headingitk">Common Makeup Questions</span><br />
<br />
{def $intheknown=fetch( 'content', 'tree', hash(	'parent_node_id', 119,
              							  	'sort_by', array( array( 'priority' ) ),
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'in_the_known' ),
              							  	'limit', 4 ) )}
{foreach $intheknown as $known}
	<a href={concat("/know_it_all/(question)/",$known.node_id)|ezurl()}>{$known.name}</a> <br /><br />
{/foreach}

<a href={"/know_it_all"|ezurl()}>CLICK HERE</a>&nbsp;<img src={"images/arrow.gif"|ezdesign()}>
*}
</div>
