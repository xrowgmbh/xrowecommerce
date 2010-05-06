{def $price_display=ezini( 'Settings', 'ShowPriceAs', 'xrowecommerce.ini' )}
<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">
	<div class="minibasket">
		<div>
		   <h2>{'Cart'|i18n("extension/xrowecommerce")}</h2>
		</div>
		
		{def $basket_rm=fetch( 'shop', 'basket' )
		     $total_price='total_ex_vat'}
		{*$basket_rm.items|attribute(show)*}
		{if $basket_rm.is_empty|not()}
		    {if or( $price_display|eq( 'discount_price_inc_vat' ), $price_display|eq( 'inc_vat_price' ) )}
                {set $total_price='total_inc_vat'}
            {/if}
			<div class="toolbar-basket-total">
			    <p>{'Subtotal'|i18n("extension/xrowecommerce")}: {$basket_rm.$total_price|l10n(currency)}</p>
				<form class="qbasketform" method="post" action={"/shop/basket"|ezurl}>
				    <input type="submit" class="button flat-right" name="CheckoutButton" value="{"Check out"|i18n("extension/xrowecommerce")}" />
				</form>
			</div>
			{foreach $basket_rm.items as $item}
			    {def $item_price=$item.price_ex_vat}
			    {if or( $price_display|eq( 'discount_price_inc_vat' ), $price_display|eq( 'inc_vat_price' ) )}
                    {set $item_price=$item.price_inc_vat}
                {/if}
				<div class="toolbar-basket-item">
                    <div class="item-image">{attribute_view_gui image_class="product_related" attribute=$item.item_object.contentobject.current.data_map.image}</div>
					<p>{$item.object_name}</p>
                    <p>
						{if and( $item.discount_percent, $price_display|begins_with( 'discount_price' ) )}
                            {def $discount = $item_price|div(100)|mul($item.discount_percent)
                                 $item_price = $item_price|sub($discount)}
                            {/if}
						{$item.item_count} x {$item_price|l10n( 'currency' )}
						{if is_set($discount)}{undef $discount}{/if}
                    </p>
				</div>
			{/foreach}
		{else}
			<p class="price">{'Your cart is empty.'|i18n("extension/xrowecommerce")}</p>
		{/if}
		{undef $basket_rm $total_price $item_price}
	</div>
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>