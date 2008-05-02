<div class="border-box">
    <form name="buy" id="buy" method="post" action={"xrowecommerce/multiadd"|ezurl}>
        <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
        <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
        <input type="hidden" name="ViewMode" value="full" />

        <div class="content-view-full">
            <div class="class-xrow-commerce">
	            
	            <div class="attribute-header">
		        <h1>{$node.name|wash()}</h1>
		        </div>
            <div class="image-description-wrap">
            {if $node.data_map.image.has_content}
            <div class="attribute-image">
		            {attribute_view_gui image_class=product attribute=$node.data_map.image}
		        {if $node.data_map.caption.has_content}
                <div class="caption">
                    {attribute_view_gui attribute=$node.data_map.caption}
                </div>
		        {/if}
            </div>

            <div class="description-wrap">
	                <div class="attribute-short">
	                   {attribute_view_gui attribute=$node.object.data_map.short_description}
	                </div>
	                <div class="attribute-long">
	                   {attribute_view_gui attribute=$node.object.data_map.description}
	                </div>
	                <div class="attribute-price">
	                <h4>{$node.name|wash()}</h4>
	                <p class="price">
	                    {attribute_view_gui attribute=$node.object.data_map.price}
	                </p>
	                </div>
            </div>
            {/if}
        </div>

        <div class="productwrapper">
            <div class="attribute-short-wide">
            {def $user=fetch( 'user', 'current_user' )}
            {if and($node.data_map.recurring.content|not(), $user.is_logged_in)}
                <p>Add your selections to <a id="show_auto_tip">Automatic Delivery</a>?</p>
                <input class="flat-right2 block" type="submit" onclick="document.buy.action='{"recurringorders/add"|ezurl(no)}'; document.buy.submit(); return true;" name="ActionAddToRecurring" value="{"Add to Automatic Delivery"|i18n("design/base")}" />
            {elseif $node.data_map.recurring.content|not()}
                <div id="headingp2">**Note**</div>
                <p>This product is available for <a id="show_auto_tip">Automatic Delivery</a>. To add this product to your Automatic Delivery you have to <a href={'user/login'|ezurl}>login</a>.</p>
            {/if}
            </div>
    	
            <div id="overlay1" style="visibility:hidden;">
                <h3>What is Automatic Delivery?</h3>
                <p>Use our Automatic Delivery service to have this item sent to you as often as you like.  You’ll get priority on our inventory and save time.</p>
                <p>By placing your initial Automatic Delivery order and setting up an Automatic Delivery schedule, you authorize us to charge the same credit card for future Automatic Delivery orders until you cancel.</p>
                <p>Since the accuracy of your credit card, shipping and billing information is vital to Automatic Delivery, please promptly submit changes through the my account section.</p>
            </div>
            
    {* Related products. *}
            
            {def $related_purchase=fetch( 'shop', 'related_purchase', hash( 'contentobject_id', $node.object.id, 'limit', 2 ) )}
            {if $related_purchase}
            <div class="attribute-short-wide">
                <h2 class="uppercase">{'People who bought this also bought'|i18n( 'design/ezwebin/full/product' )}</h2>
                {foreach $related_purchase as $product}
                    <span>{content_view_gui view=text_linked content_object=$product}</span>
                    <p class="price">{attribute_view_gui attribute=$product.data_map.price}</p>
                {/foreach}
            </div>
	        {/if}
	        {undef $related_purchase}
            <div>
                <table class="list">
	                <tr>
	                    <th>Options</th>
	                    <th>Description</th>
	                    <th>Quantity</th>
	                    <th>Price</th>
	                </tr>
	
	                {if $node.data_map.variation.content.option_list|count|gt(0)}
	                {section var=Options loop=$node.data_map.variation.content.option_list}
                    <tr>
	                   <td>
	                   {$Options.item.comment|wash}
	                   </td>
	                   <td>
	                   {$Options.item.description|wash|nl2br}
	                   </td>
	                   <td align="right">
	                       <input type="hidden" name="AddToBasketList[{$Options.index}][object_id]" value="{$node.object.id}" />
	                       <input type="hidden" name="AddToBasketList[{$Options.index}][variations][{$node.data_map.variation.id}]" value="{$Options.item.id}" />
	                       <input type="text" name="AddToBasketList[{$Options.index}][quantity]" value="{if eq($Options.index,0)}1{else}0{/if}" style="width: 50px; border: 1px solid #565969;"/>
	                   </td>
	                   <td align="right">
	                       {section show=ne( $Options.item.additional_price, '' )}
	                           {$Options.item.additional_price|l10n( currency )}
	                       {/section}
	                   </td>
                    </tr>{/section}
	                {else}
	                <tr>
	                    <td>
	                    no Options
	                    </td>
	                    <td>{attribute_view_gui attribute=$node.object.data_map.description}</td>
	                    <td align="right">
	                        <input type="hidden" name="AddToBasketList[{$Options.index}][object_id]" value="{$node.object.id}" />
	                        <input type="hidden" name="AddToBasketList[{$Options.index}][variations][{$node.data_map.variation.id}]" value="{$Options.item.id}" />
	                        <input type="text" name="AddToBasketList[0][quantity]" value="1" />
	                    </td>
	                    <td align="right">
	                       {$node.object.data_map.price.data_float|l10n(currency)}
	                    </td>
	                </tr>
	                {/if}

	            </table>
                <div class="block">
	                <div class="right">
	                    <input type="submit" class="right-arrow" name="ActionAddToBasket" value="{"Add to Shopping Cart"|i18n("design/ezwebin/full/product")}" />
	                </div>
	            </div>
                <div class="attribute-multi-options">
                </div>
	        </div>
	    </div>
	    

    </div>
    </div>
	</form>
</div>

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
                