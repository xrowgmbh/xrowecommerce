{* Product - Line view 
<div class="content-view-line">
    <div class="class-product">
        <h2>{$node.name|wash()}</h2>

            {if $node.data_map.image.content.is_valid}
			     <a href={$node.url_alias|ezurl()}>{attribute_view_gui attribute=$node.data_map.image image_class=small alignment=center}</a>
			     {/if}

        
         <p>
                {undef $var_price}
                {undef $allprice}
                {undef $partprice}
                
                {if count($node.data_map.variation.content.option_list)|eq(1)}
           	        {def $allprice=$node.data_map.variation.content.option_list.0.additional_price}
           	    {elseif count($node.data_map.variation.content.option_list)|gt(1)}
           	        {foreach $node.data_map.variation.content.option_list as $var_price}
           	            {if or( $var_price.additional_price|lt($partprice), is_set($partprice)|not ) }
           	                {def $partprice=$var_price.additional_price}
           	            {/if}
           	        {/foreach}
           	    {/if}
           	{if or( $partprice|gt(0), $allprice|gt(0) ) }
                {if $partprice|gt(0)}
                    <span class="currentprice">from {$partprice|l10n( 'currency' )}</span>
                {/if}
                {if $allprice|gt(0)}
                    <span class="currentprice">{$allprice|l10n( 'currency' )}</span>
                {/if}
           {else}
            {attribute_view_gui attribute=$node.data_map.price}
            {/if}
           
         </p>

        <div class="attribute-link">
            <a href={$node.url_alias|ezurl()} style="text-decoration: none;">Show <img src={"images/arrow.gif"|ezdesign()}></a>
        </div>
        <br /><br />
   </div>
</div>
*}
{* Product - Linesearch view *}
<td valign="top" width="30">
    <a href={$node.url_alias|ezurl()} style="text-decoration: none;">
        <img src={"images/arrow.gif"|ezdesign()}>
    </a>
</td>
<td>
    <div style="float: right; display: inline; margin-right: 8px;">
    {if $node.data_map.image.content.is_valid}
        <a href={$node.url_alias|ezurl()}>{attribute_view_gui attribute=$node.data_map.image image_class=verysmall alignment=center}</a>
    {/if}
    </div>
    <a href={$node.url_alias|ezurl()} style="text-decoration: none;">
        {$node.name|wash()}
    </a>
    <p>
      {undef $var_price}
                {undef $allprice}
                {undef $partprice}
                
                {if count($node.data_map.variation.content.option_list)|eq(1)}
           	        {def $allprice=$node.data_map.variation.content.option_list.0.additional_price}
           	    {elseif count($node.data_map.variation.content.option_list)|gt(1)}
           	        {foreach $node.data_map.variation.content.option_list as $var_price}
           	            {if or( $var_price.additional_price|lt($partprice), is_set($partprice)|not ) }
           	                {def $partprice=$var_price.additional_price}
           	            {/if}
           	        {/foreach}
           	    {/if}
           	{if or( $partprice|gt(0), $allprice|gt(0) ) }
                {if $partprice|gt(0)}
                    <span class="currentprice">from {$partprice|l10n( 'currency' )}</span>
                {/if}
                {if $allprice|gt(0)}
                    <span class="currentprice">{$allprice|l10n( 'currency' )}</span>
                {/if}
           {else}
            {attribute_view_gui attribute=$node.data_map.price}
            {/if}
    </p>
</td>
<td valign="top" width="150">
    Product
</td>
</tr><tr><td colspan="3"><hr></td></tr>
