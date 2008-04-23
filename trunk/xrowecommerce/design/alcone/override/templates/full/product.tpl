{* Product - Full view *}
{if count($node.data_map.variation.content.option_list)|eq(1)}
           	    {def $allprice=$node.data_map.variation.content.option_list.0.additional_price}
           	{elseif count($node.data_map.variation.content.option_list)|gt(1)}
           	    {foreach $node.data_map.variation.content.option_list as $var_price}
           	    {if or( $var_price.additional_price|lt($partprice), is_set($partprice)|not ) }
           	        {def $partprice=$var_price.additional_price}
           	    {/if}
           	    {/foreach}
           	{/if}
<div id="product_full">
        <h2>{$node.name|wash()}</h2>
        <br />
        <div id="product_left">
        	<div id="prod_img">
        	{if $node.data_map.image_link.content.current.contentobject_attributes.2.content.is_valid}
        	   <div>
        	       {attribute_view_gui alignment=center image_class=productfull attribute=$node.data_map.image_link.content.current.contentobject_attributes.2}
    	       </div>
        	{elseif $node.data_map.image.content.is_valid}
        	<div>
        	   {attribute_view_gui alignment=center image_class=productfull attribute=$node.data_map.image}
        	   </div>
        	{else}
        	   <img src={"images/no_pic.jpg"|ezdesign} width="200" align="middle">
        	{/if}
        </div>
        	<img style="margin-left: 15px;" align="right" src={"images/freeshipping50.gif"|ezdesign()} alt="Alcone Beauty Company free shipping when you spend fifty dollars or More. Limited to the first 5lbs" />

        	<br /><br />
        	{def $reviews=fetch( 'content', 'list', hash(	'parent_node_id', $node.node_id,
              							  	'sort_by', array( array( 'priority' ) ),
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'press_release' ) ) )}
			{if $reviews|count()}
			<span class="whattheysay">WHAT THEY ARE SAYING:</span><br />
		  	{foreach $reviews as $review}
		  		<div id="review_image">
                	<img src="/{$review.data_map.image.content.current.data_map.image.content.original.url}">
    			</div>
    			<span class="review_content">{attribute_view_gui attribute=$review.data_map.content}</span>
  				<span class="review_title">- {$review.name}</span>
  	  			<br />
  	  			<div class="break"></div>
		  	{/foreach}
		  	{/if}

            {*def $reviews=fetch( 'content', 'list', hash(	'parent_node_id', $node.node_id,
              							  	'sort_by', array( array( 'priority' ) ),
              							  	'class_filter_type',  'include',
              							  	'class_filter_array', array( 'product_review' ) ) )}
			{if $reviews|count()}
			<span class="whattheysay">WHAT THEY ARE SAYING:</span><br />
		  	{foreach $reviews as $review}
		  		<div id="review_image">
                	<img src="/{$review.data_map.image.content.current.data_map.image.content.original.url}">
    			</div>
    			<span class="review_content">{attribute_view_gui attribute=$review.data_map.content}</span>
  				<span class="review_title">- {$review.name}</span>
  	  			<br />
		  	{/foreach}
		  	{/if*}

		</div>
		<div id="product_right">

           {attribute_view_gui attribute=$node.data_map.description}
           <br />
           {def $child=fetch( 'content', 'list', hash(
                'parent_node_id',     $node.node_id,
                'limit', 1,
                'class_filter_type',  'include',
                'class_filter_array', array( 'image' ) ) )}
            {if gt($child|count, 0)}
                {attribute_view_gui alignment=center image_class=productfull attribute=$child.0.data_map.image}
            <br />
            <br />

            {/if}
           <div id="headingp2">{$node.name|wash()}</div>
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
           <br />


{if $node.data_map.variation.content.option_list}
<form name="buy" method="post" action={"shop/multiadd"|ezurl}>
<table width="100%" style="font-size: 12px;margin-top:1em;" cellpadding="3">
<tr>

<th style="background-color: #EBEEEF;">&nbsp;Options</th>
<th align="right" style="background-color: #EBEEEF;">Quantity&nbsp;</th>
<th align="right" style="background-color: #EBEEEF;">Price&nbsp;</th>
</tr>
{section var=Options loop=$node.data_map.variation.content.option_list}
       <tr>
        <td>
            {$Options.item.comment}
        </td>
        <td align="right">

        <input type="hidden" name="AddToBasketList[{$Options.index}][object_id]" value="{$node.object.id}">
            <input type="hidden" name="AddToBasketList[{$Options.index}][variations][{$node.data_map.variation.id}]" value="{$Options.item.id}">
            <input type="text" name="AddToBasketList[{$Options.index}][quantity]" value="{if eq($Options.index,0)}1{else}0{/if}" style="width: 50px; border: 1px solid #565969;">
        </td>
        <td align="right">
            {section show=ne( $Options.item.additional_price, '' )}
                {$Options.item.additional_price|l10n( currency )}
            {/section}
        </td>
        </tr>
    {/section}
</table>
{else}
<form name="buy" method="post" action={"content/action"|ezurl}>
           <p>
           	Quantity: <input type="text" name="ProductItemCountList" value="" style="width: 50px; border: 1px solid #565969;">
           </p>
{/if}



           <br />
           <div style="text-align: right;">
            <span class="rarrow-orange"><input type="submit" name="ActionAddToBasket" value="{"Add to basket"|i18n("design/base")}"></span>
            <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
            <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
            <input type="hidden" name="ViewMode" value="full" />

        
         <div class="break"></div>
        </div>

        {def $user=fetch( 'user', 'current_user' )}
        {if and($node.data_map.recurring.content|not(), $user.is_logged_in)}
                <p>Add your selections to <a id="show_auto_tip">Automatic Delivery</a>?</p>
            <span class="rarrow-orange"><input type="submit" onclick="document.buy.action='{"recurringorders/add"|ezurl(no)}'; document.buy.submit(); return true;" name="ActionAddToRecurring" value="{"Add to Automatic Delivery"|i18n("design/base")}"></span>
            <div class="break"></div>
        {elseif $node.data_map.recurring.content|not()}


        <div id="headingp2">**Note**</div>
        <p>This product is available for <a id="show_auto_tip">Automatic Delivery</a>. To add this product to your Automatic Delivery you have to <a href={'user/login'|ezurl}>login</a>.</p>
        {/if} 
        {literal}
        <style>
.yui-overlay { position:absolute;background:#fff;border:1px dotted black;padding:5px;margin:10px; }
#overlay1 { 
    background-color: white;
    border: 1px solid grey; 
}
        </style>
        <script>
		YAHOO.namespace("example.container");

		function init() {
			// Build overlay1 based on markup, initially hidden, fixed to the center of the viewport, and 300px wide
			YAHOO.example.container.overlay1 = new YAHOO.widget.Overlay("overlay1", { fixedcenter:false,
																					  visible:false,
																					  width:"400px" } );
			YAHOO.example.container.overlay1.render();

			YAHOO.util.Event.addListener("show_auto_tip", "mouseover", YAHOO.example.container.overlay1.show, YAHOO.example.container.overlay1, true);
			YAHOO.util.Event.addListener("show_auto_tip", "mouseout", YAHOO.example.container.overlay1.hide, YAHOO.example.container.overlay1, true);
		}

		YAHOO.util.Event.addListener(window, "load", init);
</script>
{/literal}
<div id="overlay1" style="visibility:hidden;">
<h3>What is Automatic Delivery?</h3>

<p>Use our Automatic Delivery service to have this item sent to you as often as you like.  You’ll get priority on our inventory and save time.</p>

<p>By placing your initial Automatic Delivery order and setting up an Automatic Delivery schedule, you authorize us to charge the same credit card for future Automatic Delivery orders until you cancel.</p>

<p>Since the accuracy of your credit card, shipping and billing information is vital to Automatic Delivery, please promptly submit changes through the my account section.</p>

</div>
<br />
           {if gt($node.data_map.related_products.content.relation_list|count, 0)}
            <div id="headingr1">RELATED PRODUCTS</div>
           {foreach $node.data_map.related_products.content.relation_list as $related}
           {def $related_item=fetch( 'content', 'node', hash( 'node_id',  $related.node_id ) ) }
		       <div id="headingp2"><a href={$related_item.url_alias|ezurl()}>{$related_item.name}</a></div>
		       {if count($related_item.data_map.variation.content.option_list)|eq(1)}
           	    {def $allprice_rp=$related_item.data_map.variation.content.option_list.0.additional_price}
           	{elseif count($related_item.data_map.variation.content.option_list)|gt(1)}
           	    {foreach $related_item.data_map.variation.content.option_list as $var_price_rp}
           	    {if or( $var_price_rp.additional_price|lt($partprice_rp), is_set($partprice_rp)|not ) }
           	        {def $partprice_rp=$var_price_rp.additional_price}
           	    {/if}
           	    {/foreach}
           	{/if}


		    {if or( $partprice_rp|gt(0), $allprice_rp|gt(0) ) }
            {if $partprice_rp|gt(0)}
                <span class="currentprice">from {$partprice_rp|l10n( 'currency' )}</span>
            {/if}
            {if $allprice_rp|gt(0)}
                <span class="currentprice">{$allprice_rp|l10n( 'currency' )}</span>
            {/if}
           {else}
                {attribute_view_gui attribute=$related_item.data_map.price}
            {/if}


            {undef $allprice_rp}
            {undef $partprice_rp}
            {undef $var_price_rp}



		       <br />
		       <br />
           {/foreach}
           {/if}
           <br />

           {def $related_purchase=fetch( shop, related_purchase, hash( contentobject_id, $node.contentobject_id,
                                                               limit, 3 ) )}
        {if $related_purchase}
            <div id="headingr1">PEOPLE WHO BOUGHT THIS ALSO BOUGHT</div>

            <div class="relatedorders">
            
            {foreach $related_purchase as $related}
           {def $related_item=fetch( 'content', 'node', hash( 'node_id',  $related.main_node_id ) ) }
		       <div id="headingp2"><a href={$related_item.url_alias|ezurl()}>{$related_item.name}</a></div>
		       {if count($related_item.data_map.variation.content.option_list)|eq(1)}
           	    {def $allprice_rp=$related_item.data_map.variation.content.option_list.0.additional_price}
           	{elseif count($related_item.data_map.variation.content.option_list)|gt(1)}
           	    {foreach $related_item.data_map.variation.content.option_list as $var_price_rp}
           	    {if or( $var_price_rp.additional_price|lt($partprice_rp), is_set($partprice_rp)|not ) }
           	        {def $partprice_rp=$var_price_rp.additional_price}
           	    {/if}
           	    {/foreach}
           	{/if}


		    {if or( $partprice_rp|gt(0), $allprice_rp|gt(0) ) }
            {if $partprice_rp|gt(0)}
                <span class="currentprice">from {$partprice_rp|l10n( 'currency' )}</span>
            {/if}
            {if $allprice_rp|gt(0)}
                <span class="currentprice">{$allprice_rp|l10n( 'currency' )}</span>
            {/if}
           {else}
                {attribute_view_gui attribute=$related_item.data_map.price}
            {/if}


            {undef $allprice_rp}
            {undef $partprice_rp}
            {undef $var_price_rp}



		       <br />
		       <br />
           {/foreach}
            </div>
       {/if}
       
           
           
           
           
        
        <br />
           <div id="dottedline_long">&nbsp;<br /></div>
           </form>
		</div>

   </div>
   {undef}
