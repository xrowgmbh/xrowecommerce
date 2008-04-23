{def $site_url=ezini( 'SiteSettings', 'SiteURL', 'site.ini' )
     $img_url=$node.data_map.background.content[original].url}


<div id="col_left_Front">
<div id="toolbar-front-left">
   {tool_bar name=leftcat view=full}
</div>
</div>

<div id="col_main_3">
{def $urlback=$node.data_map.background.content.original.url}
<div id="SpecialCatDecription" style="background-image: url({$urlback|ezimage(full)});">
</div>
{if $node.data_map.image.content.is_valid}
            {attribute_view_gui attribute=$node.data_map.image image_class=original}
        {else}
        {*<div class="imageleft">
                <img src={"images/no_pic.jpg"|ezdesign} width="140">
            </div>*}
        {/if}

<div id="SpecialCatDecription" style="background-image: url('{concat( 'http://',$site_url,'/', $img_url )}');">
        {if $node.data_map.description.content.is_empty|not}
            {attribute_view_gui attribute=$node.data_map.description}
        {else}
        {/if}
</div>
	<br />
<div class="break"></div>
{attribute_view_gui attribute=$node.data_map.devider_image image_class=original}
{def $sp_prod=fetch( 'content', 'tree', hash(	'parent_node_id', $node.main_node_id,
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

				<div id="product_sp_window">
			{if $prod.data_map.image_link.content.current.contentobject_attributes.2.content.is_valid}
               <a href={$prod.url_alias|ezurl()}>{attribute_view_gui attribute=$prod.data_map.image_link.content.current.contentobject_attributes.2 image_class=productthumbnail alignment=center}</a>
			{elseif $prod.data_map.image.content.is_valid}
                <a href={$prod.url_alias|ezurl()}>{attribute_view_gui attribute=$prod.data_map.image image_class=productthumbnail alignment=center}</a>
            {else}
                <a href={$prod.url_alias|ezurl()}>
                <img src={"images/no_pic.jpg"|ezdesign()} width="80">
                </a>
            {/if}
				</div>
			<br />
			<a href={$prod.url_alias|ezurl()}>
		  <h4 class="sp_prod_title" style="color: #{$node.object.data_map.font_color.content};">{$prod.name}  {if eq($prod.class_identifier, 'category')}
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
				{/if}</h4> </a>

			{* <a href={$prod.url_alias|ezurl()}>{$prod.name}</a>*}
			{* $prod.data_map.description|attribute(show,1) *}

            {$prod.data_map.short_description.content.output.output_text}

			<a href={$prod.url_alias|ezurl()}>View Details...</a>

{if $prod.data_map.variation.has_content}

{else}

            <form method="post" action="/content/action">
<table style="margin-left: 14px; margin-top: 6px;"><tr style="height:25px;"><td>

					   QTY: <input type="text" name="ProductItemCountList" value="" style="width: 27px; border: 1px solid #000000; margin-right: 5px;">
					</td>

					<td><div class="right">

            			<input type="image" src="/extension/alcone/design/alcone/images/addtocartpurple.jpg" name="ActionAddToBasket" value="Add to basket">
            			<input type="hidden" name="ContentNodeID" value="{$prod.node_id}" />
           				 <input type="hidden" name="ContentObjectID" value="{$prod.contentobject_id}" />
            			<input type="hidden" name="ViewMode" value="full" />
        		 	</div></td> </tr></table>
          	 </form>
{/if}

		 </div>
		</div>
{/section}
	</div>
<br />
<div id="col_right">
   {include uri="design:parts/right_menu_standard.tpl"}
</div>
