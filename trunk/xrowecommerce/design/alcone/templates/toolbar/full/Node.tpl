{def $prod=fetch(content, node, hash( node_id, $parent_node ) )}
		                    {undef $var_price}
        	                    {undef $allprice}
	                            {undef $partprice}
{if $prod}
{if count($prod.data_map.variation.content.option_list)|eq(1)}
  {def $allprice=$prod.data_map.variation.content.option_list.0.additional_price}

{elseif count($prod.data_map.variation.content.option_list)|gt(1)}

  {foreach $prod.data_map.variation.content.option_list as $var_price}
    {if or( $var_price.additional_price|lt($partprice), is_set($partprice)|not ) }
      {def $partprice=$var_price.additional_price}
    {/if}
  {/foreach}
{/if}



{if $prod.data_map.image_link.content.current.contentobject_attributes.2.content.is_valid}
  {def $image_type=2}
  {* link image *}
{elseif $prod.data_map.image.content.is_valid}
  {def $image_type=1}
  {* standard image *}
{else}
  {def $image_type=0}
  {* no image *}
{/if}



{if $image_type|eq(2)}
 {def $related_product_id=$prod.data_map.image_link.content.current.contentobject_attributes.2.contentobject_id}
 {def $related_product=fetch(content, node, hash( contentobject_id, $related_product_id ) )}
 {def $txt=$prod.data_map.image_link.content.current.contentobject_attributes.2.content.smalllarge.text}
 {def $url=$prod.data_map.image_link.content.current.contentobject_attributes.2.content.smalllarge.url}
 {def $url_root=$url|ezroot}
 {def $img_tag=concat('<img src=',$url,' />',$txt)}

{*
 {$img_tag}
 {$prod.data_map.image_link.content.current.contentobject_attributes.2.contentobject_id}
 {$prod.data_map.image_link.content.current.contentobject_attributes.2.content.original|attribute(show,1)}
 <img src={$url|ezroot} border="0" />
 *}
{elseif $image_type|eq(1)}
 {def $txt=$prod.data_map.image.content.smalllarge.text}
 {def $url=$prod.data_map.image.content.smalllarge.url}
 {def $url_root=$url|ezroot}
 {def $img_tag=concat('<img src=',$url,' />',$txt)}
{else}
{* &nbsp; *}
{/if}
{*
     $ol=concat('return overlib(\' ', $str, ' <br />', $img_tag_esc, ' ', '\');')
*}
{def $str=wrap_php_func("str_replace", array("'", "\\'", $prod.name ) )}

{def $str=wrap_php_func("str_replace", array('"', '\\"', $str ) )}

{def $str_desc_raw=$img_tag}

{def $img_tag_esc=wrap_php_func("str_replace", array("'", "\\'", $str_desc_raw ) )
     $img_tag_esc=wrap_php_func("str_replace", array('"', '\\"', $str_desc_raw ) )
}
{def $ol=concat('return overlib(\' ', $str, ' <br />', $img_tag_esc, ' ', '\');')}

		<div id="product_fp" onmouseover="{$ol}" onmouseout='return nd();'>
			<div id="product_fp_window"> 
			{if $image_type|eq(2)}
               <a href={$prod.url_alias|ezurl()}>{attribute_view_gui attribute=$prod.data_map.image_link.content.current.contentobject_attributes.2 image_class=small alignment=center}</a>
			{elseif $image_type|eq(1)}
                <a href={$prod.url_alias|ezurl()}>{attribute_view_gui attribute=$prod.data_map.image image_class=small alignment=center}</a>
{else}<a href={$prod.url_alias|ezurl()}><img src={"images/no_pic.jpg"|ezdesign()} border="0" width="80" style="border: 0px none;" alt="" title="" ></a>
{/if}
</div>
			<br /><a href={$prod.url_alias|ezurl()}>{$prod.name}</a>
			{* $prod.data_map.description|attribute(show,1) *}

				{* <p>*}	 {$prod.data_map.short_description.content.output.output_text} {* </p> *}

			{* attribute_view_gui attribute=keywords_shortened *}
			     {if eq($prod.class_identifier, 'category')}
			        <span class="fp_currentprice">{attribute_view_gui attribute=$prod.data_map.description}</span>
			     {elseif or( $partprice|gt(0), $allprice|gt(0) ) }
                    {if $partprice|gt(0)}
                        <span class="fp_currentprice">from {$partprice|l10n( 'currency' )}</span>
                    {/if}
                    {if $allprice|gt(0)}
                        <span class="fp_currentprice">{$allprice|l10n( 'currency' )}</span>
                    {/if}
                {else}
                    {section show=$prod.data_map.price.content.has_discount}
{"Price"|i18n("design/base")}: <span class="oldprice">{$prod.data_map.price.content.inc_vat_price|l10n(currency)}</span><br/>
{"Your price"|i18n("design/base")}: <span class="fp_currentprice">{$prod.data_map.price.content.discount_price_inc_vat|l10n(currency)}</span><br />
{"You save"|i18n("design/base")}: <span class="pricesave">{sub($prod.data_map.price.content.inc_vat_price,$prod.data_map.price.content.discount_price_inc_vat)|l10n(currency)} ( {$attribute.content.discount_percent} % )</span>
{section-else}
<span class="fp_currentprice">{$prod.data_map.price.content.inc_vat_price|l10n(currency)}</span>
{/section}
                {/if}
{*			</p> *}
		</div>
{*
		<p>

				</p>
*}
{/if}

