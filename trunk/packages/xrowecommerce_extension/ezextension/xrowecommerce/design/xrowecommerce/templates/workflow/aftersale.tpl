{def $price_display=ezini( 'Settings', 'ShowPriceAs', 'xrowecommerce.ini' )}
<div class="shop shop-payment shop-payment-selectgateway">

{include uri='design:shop/basket_navigator.tpl' step='4'}

<h1>{'Select a product'|i18n('extension/xrowecommerce')}</h1>

{if $errors|count|ne( 0 )}
<div class='payment_error'>
    <h5>{'Errors'|i18n('extension/xrowecommerce')}: </h5>
    {foreach $errors as $errmsg}
        <div>{$errmsg}</div>
    {/foreach}
</div>
{/if}
{if $product_list|gt(0)}
    <p>{'Please select your product below.'|i18n('extension/xrowecommerce')}</p>
    <form method="post" action={"xrowecommerce/checkout"|ezurl}>
        
    {foreach $product_list as $product}
    	<div>
            <div>
                <div>
                    <div>
                        {if and( $product.data_map.image.has_content, $product.data_map.image.content.is_valid )}
                        <div class="after_sale_image">
                            <div class="attribute-image">
                                {attribute_view_gui attribute=$product.data_map.image image_class='aftersale' id='product_image'}
                            </div>
                            <!-- Wenn das Produkt mehrere Optionen hat -->
                            {if $product.data_map.options.content.option_list|count|gt( 0 )}
                                <h4>{$product.name|wash}</h4>
                                {attribute_view_gui attribute=$product.data_map.short_description}
                            {/if}
                        </div>
                        {/if}
                        <!-- Wenn das Produkt keine Optionen hat -->
                        {if $product.data_map.options.content.option_list|count|eq( 0 )}
                        <div class="after_sale_name">
                            <p>
                                <h4>{$product.name|wash}</h4>
                                {if $price_display|eq( 'discount_price_ex_vat' )}
                                	{set $price_display='ex_vat_price'}
                                {elseif $price_display|eq( 'discount_price_inc_vat' )}
                                	{set $price_display='inc_vat_price'}
                                {/if}
                                
                                {def $price=$product.data_map.price.content.$price_display}
                                {if $product.data_map.price.content.discount_percent|gt( 0 )}
                                    {def $discount = $price|div(100)|mul($product.data_map.price.content.discount_percent)
                                         $discount_price = $price|sub($discount)}
                                {/if}
                                
                              	<p class="retail_price">{'Regular Retail Price'|i18n('extension/xrowecommerce')}: {$price|l10n( currency )}</p>
                                <p class="thanks_price">{'Our Thank You Price'|i18n('extension/xrowecommerce')}: {$discount_price|l10n( currency )}</p>
                                {undef $price $discount $discount_price}
                                <p class="quantity">{'Quantity'|i18n('extension/xrowecommerce')}&nbsp;<input class="quantity" type="text" name="ProductItemCountList[{$product.contentobject_id}]" value="0" size="5" maxlength="4" /></p>
                            </p>
                        </div>
                        <div class="after_sale_description">
                           {attribute_view_gui attribute=$product.data_map.short_description}
                        </div>
                        {/if}
                    	<input type="hidden" name="ProductItemIDList[]" value="{$product.contentobject_id}" />
                    
                    <!-- Wenn das Produkt mehrere Optionen hat -->
                    {if $product.data_map.options.content.option_list|count|gt( 0 )}
                    <table class="after_sale_opt_table">
                        {foreach $product.data_map.options.content.option_list as $Options}
                        <tr>
        				    <td class="after_sale_opt_image">
        					{if $Options.image.current.data_map.image.content.is_valid}
        					    {attribute_view_gui attribute=$Options.image.current.data_map.image image_class='galleryline'}
        					{else}
        						<p><img src={'shop/nopic_tiny.jpg'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" /></p>
        					{/if}
        					</td>
        					<td class="after_sale_opt_name">
        					    <h5>{$Options.comment|wash()}</h5>
        					   	{if $Options.multi_price}
        					   		{if $price_display|eq( 'discount_price_ex_vat' )}
                                	    {set $price_display='ex_vat_price'}
                                    {elseif $price_display|eq( 'discount_price_inc_vat' )}
                                	    {set $price_display='inc_vat_price'}
                                    {/if}
                                
                                    {def $multi_price=get_multiprice( $product.data_map.price, $Options.multi_price, $price_display )}
                                    {if $product.data_map.price.content.discount_percent|gt( 0 )}
                                        {def $discount = $multi_price|div(100)|mul($product.data_map.price.content.discount_percent)
                                             $multi_discount_price = $multi_price|sub($discount)}
                                    {/if}
                                {/if}
                                <p class="retail_price">{'Regular Retail Price'|i18n('extension/xrowecommerce')}: {$multi_price|l10n( currency )}</p>
                                <p class="thanks_price">{'Our Thank You Price'|i18n('extension/xrowecommerce')}: {$multi_discount_price|l10n( currency )}</p>
                                {undef $multi_price $discount $multi_discount_price}
        	                    <p class="quantity">{'Quantity'|i18n('extension/xrowecommerce')}&nbsp;<input class="quantity" type="text" name="ProductItemCountList[{$product.contentobject_id}][options][{$Options.id}]" value="0" size="5" maxlength="4" /></p>
        					</td>
        					<td class="after_sale_opt_description">
        					    {$Options.description|wash|nl2br}
        					</td>
        	            </tr>
        	            {/foreach}
        	        </table>
                    {/if}
                   </div> 
                </div>
            </div>
        </div>
        {delimiter}
        <div class="breakline"></div>
        {/delimiter}
    {/foreach}
    <div id="buttonblock-bottom" class="buttonblock">
        <input id="cancel-button" class="button left-arrow2" type="submit" name="SelectMoreProducts" value="{'Add to my order'|i18n('extension/xrowecommerce')}" title="{'Add to my order'|i18n('extension/xrowecommerce')}"/>
        <input id="continue-button" class="button right-arrow2" type="submit" name="WithoutMoreProducts" value="{'No. Thanks'|i18n('extension/xrowecommerce')}" title="{'No. Thanks'|i18n('extension/xrowecommerce')}"/>
    </div>
</form>
{/if}
</div>
{undef $price_display}