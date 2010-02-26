<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">
	<div class="minibasket">
		<div>
		   <h2>{'Cart'|i18n("extension/xrowecommerce")}</h2>
		</div>
		
		{def $basket_rm=fetch( 'shop', 'basket') }
		{if $basket_rm.is_empty|not()}
			<div class="toolbar-basket-total">
			    <p>Subtotal: {$basket_rm.total_inc_vat|l10n(currency)}</p>
				<form class="qbasketform" method="post" action={"/shop/basket"|ezurl}>
				    <input type="submit" class="button flat-right" name="CheckoutButton" value="{"Check out"|i18n("extension/xrowecommerce")}" />
				</form>
			</div>
			{foreach $basket_rm.items as $item}
				<div class="toolbar-basket-item">
					{if $item.item_object.option_list|count|gt(0)}
                        <div class="item-image">{attribute_view_gui image_class="product_related" attribute=$item.item_object.contentobject.current.data_map.image}</div>
						<p>{$item.object_name}</p>
						<p>{$item.item_count} x {$item.price_inc_vat|l10n(currency)}</p>
					{else}
                        <div class="item-image">{attribute_view_gui image_class="product_related" attribute=$item.item_object.contentobject.current.data_map.image}</div>
						<p>{$item.object_name}<p>
						<p>{$item.item_count} x {$item.price_inc_vat|l10n(currency)}</p>
					{/if}
				</div>
			{/foreach}
		{else}
			<p class="price">{'Your cart is empty.'|i18n("extension/xrowecommerce")}</p>
		{/if}
		{undef $basket_rm}
	</div>
</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>