<div class="content-view-full">
<div>
   <h2>{'Cart'|i18n("extension/xrowecommerce")}</h2>
</div>

<form class="qbasketform" method="post" action={"/shop/basket"|ezurl}>
{def $basket=fetch( 'shop', 'basket')}
{if $basket.is_empty|not()}

   <table class="quickbasket">

    <tr>
        <th width=200px>{'Item'|i18n("extension/xrowecommerce")}</th>
        <th width=70px>{'Qty'|i18n("extension/xrowecommerce")}</th>
        <th width=70px>{'Price'|i18n("extension/xrowecommerce")}</th>
        <th width=70px>{'Total'|i18n("extension/xrowecommerce")}</th>
    </tr>
    {foreach $basket.items as $item}
    {if $item.item_object.option_list|count|gt(0)}
        
        <input type="hidden" name="ProductItemIDList[]" value="{$item.id}" />
        <tr>{*$item.item_object.contentobject|attribute(show)*}
            <td class="bold" colspan="2">{$item.object_name}</td>
		  <td rowspan="2">{$item.price_inc_vat|l10n(currency)}</td>
		  <td rowspan="2">{$item.item_count|mul($item.price_inc_vat)|l10n(currency)}</td>
        </tr>      
        <tr>
            <td>{$item.item_object.contentobject.current.data_map.product_id.content|wash()}{$item.item_object.option_list.0.value|wash()}
            </td>
            <td><input type="text" name="ProductItemCountList[]" value="{$item.item_count}" size="3" /></td>
        </tr>
     {else}
        <input type="hidden" name="ProductItemIDList[]" value="{$item.id}" />
        <tr>
            <td class="bold" colspan="2">{$item.object_name}</td>
            <td rowspan="2">{$item.price_inc_vat|l10n(currency)}</td>
            <td rowspan="2">{$item.item_count|mul($item.price_inc_vat)|l10n(currency)}</td>
        </tr>      
        <tr>
            <td>{$item.item_object.contentobject.current.data_map.product_id.content|wash()}{$item.item_object.contentobject.current.contentobject_attributes.11.value.name|wash()}
            {foreach $item.item_object.option_list as $option}
            {/foreach}
            </td>
            <td><input type="text" name="ProductItemCountList[]" value="{$item.item_count}" size="3" /></td>
        </tr>
   {/if}
{/foreach}
   
      <tr>
      <td class="bold" colspan="3">Subtotal:</td>
      <td><b>{$basket.total_inc_vat|l10n(currency)}</b></td>
      </tr>
    </table><br />
    <input class="flat-right2" type="submit" name="StoreChangesButton" value="{'Update Cart'|i18n('extension/xrowecommerce')}" />
    <input type="submit" class="flat-right" name="CheckoutButton" value="{"Check out"|i18n("extension/xrowecommerce")}" />
</form>
{else}
<p class="price">{'Your cart is empty.'|i18n("extension/xrowecommerce")}</p><br />
<p>{'Please'|i18n("extension/xrowecommerce")} <b>{'select the product-categories'|i18n("extension/xrowecommerce")}</b> {'on the left to view and order products.'|i18n("extension/xrowecommerce")}</p>
{/if}
{/def}
</div>