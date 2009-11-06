{def $embedded_category=fetch( 'content', 'list',
                         hash( 'parent_node_id', $object.main_node.node_id,
                               'sort_by', array( 'priority', false() ),
                               'limit', $object_parameters.limit,
                               'class_filter_type', 'include',
                               'class_filter_array', array('xrow_product') ) )}

<div class="horizontally_listed_xrow_products">
		<h2>{'Take a look at our'|i18n( 'extension/xrowecommerce')} {$object.name|wash()}:</h2>
		<div class="border-box box-3">
		<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
		<div class="border-ml"><div class="border-mr"><div class="border-mc float-break">
			<div class="xrow-product-category">
				{if $object.data_map.image}
					<div class="product-category">
	                    <div class="caption">
	                        <h2>{content_view_gui view=text_linked content_object=$object}</h2>
	                    </div>
						<div class="class-image">
							<div class="content-image">
							{attribute_view_gui image_class='listitem' attribute=$object.data_map.image href=$object.main_node.url_alias|ezurl()}
							</div>
						</div>
					</div>
				{else}
				         <h2>{content_view_gui view=text_linked content_object=$object}</h2>
	            {/if}
				{foreach $embedded_category as $item}
					<div class="xrow-product-line">
						<div class="class-image">
							<div class="content-image">
								{if $item.data_map.image.content.is_valid}
								   {attribute_view_gui image_class='product_related' attribute=$item.data_map.image href=$item.url_alias|ezurl()}
								{else}
									<a href={$item.url_alias|ezurl('double', 'full')}>
									  <img src={'shop/nopic_tiny.jpg'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" />
									</a>
								{/if}
							</div>
						</div>
						<div class="caption">
							<p>{content_view_gui view=text_linked content_object=$item}</p>
							<p class="price">{$item.data_map.price.content.price|l10n(currency)}</p>
						</div>
					</div>
				{/foreach}
				<div class="break"></div>
			</div>
		</div></div></div>
		<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
		</div>
	</div>
{undef $embedded_category}