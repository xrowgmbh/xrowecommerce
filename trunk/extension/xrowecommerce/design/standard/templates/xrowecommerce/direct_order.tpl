<script type="text/javascript">
<!--
{literal}

function insertAfter( newElement, targetElement )
{
    var parent = targetElement.parentNode;
    if ( parent.lastChild == targetElement )
    {
        parent.appendChild(newElement);
    }
    else
    {
        parent.insertBefore(newElement, targetElement.nextSibling);
    }
}

function addrow( fromid, toid )
{
    var fromtr = document.getElementById( fromid );
    var to_tbody = document.getElementById( toid );
    var newindex = to_tbody.rows.length;
    var newRow = document.createElement( 'TR' );
    if ( newindex % 2 == 0 )
        newRow.className = 'bgdark';
    else
        newRow.className = 'bglight';

    if ( newindex > 0 )
        insertAfter( newRow, to_tbody.lastChild );
    else
        to_tbody.appendChild( newRow );

    newRow.innerHTML = fromtr.innerHTML;
}

function addrows( fromid, toid, amount )
{
    for ( var i=0; i < amount; i++ )
    {
        addrow( fromid, toid );
    }
}

function delRows( tbody, row )
{
    if ( tbody.rows.length > 1 )
        tbody.removeChild( row );
    return true;
}

{/literal}
-->
</script>

<div class="hidden">
    <table>
    <tbody>
    <tr id="orderrow">
        <td class="sku"><input class="sku" type="text" maxlength="10" length="5" name="SKUArray[]" value="" /></td>
        <td class="amount"><input class="amount" type="text" maxlength="5" length="3" name="AmountArray[]" value="" /></td>
        <td class="description">&nbsp;</td>
        <td class="delete nowrap"><input type="button" class="button" name="Delete[]" value="{"Delete"|i18n( 'extension/xrowecommerce/directorder')|wash}" title="{"Delete this row"|i18n( 'extension/xrowecommerce/directorder')|wash}" onclick="return delRows( this.parentNode.parentNode.parentNode.parentNode, this.parentNode.parentNode.parentNode );" /></td>
    </tr>
    </tbody>
    </table>
</div>

<form method="post" action={'xrowecommerce/directorder'|ezurl} id="directorderform">

<div class="content-view-full">
    <div class="content-view-full">
        <div class="class-xrow-commerce">
            <div class="attribute-header">
                <h1>{"Add online order from catalogue"|i18n( 'extension/xrowecommerce/directorder')|wash}</h1>
            </div>
            <div class="description">
                <p>{"Add your desired products to the shopping cart."|i18n( 'extension/xrowecommerce/directorder')|wash|nl2br}</p>
            </div>
            <table class="directorder">
            <thead>
            <tr>
                <th>{"SKU"|i18n( 'extension/xrowecommerce/directorder')|wash}</th>
                <th>{"Amount"|i18n( 'extension/xrowecommerce/directorder')|wash}</th>
                <th>{"Description"|i18n( 'extension/xrowecommerce/directorder')|wash}</th>
                <th>&nbsp;</th>
            </tr>
            </thead>
            <tbody id="ordertablebody">
{if $sku_array|count|gt(0)}
            {foreach $sku_array as $key => $item}
            <tr>
                <td class="sku"><input class="sku" type="text" maxlength="10" length="5" name="SKUArray[]" value="{$item|wash}" /></td>
                <td class="amount"><input class="amount" type="text" maxlength="5" length="3" name="AmountArray[]" value="{$amount_array.$key|wash}" /></td>
                <td class="description">
                {if and( $item|count_chars|gt(0), $desc_array.$key )}
                    {def $var_object=fetch( 'content', 'object', hash( 'object_id', $desc_array.$key.object_id ))}

                    <div class="product-image">
                    {if $var_object.data_map.image.has_content}
                        {attribute_view_gui attribute=$var_object.data_map.image image_class='product_tiny' href=$var_object.main_node.url_alias|ezurl}
                    {else}
                        <div class="product-image-unavailable"><img src={'shop/unavailable.png'|ezimage()} alt="{'No image available'|i18n('extension/xrowecommerce')}" /></div>
                    {/if}
                    </div>
                    <p class="product-link">
                        <a class="basketlink" href={$var_object.main_node.url_alias|ezurl}>{attribute_view_gui attribute=$var_object.data_map.name}</a>
                   </p>

                   <p class="product-id">
                       {'SKU number'|i18n("extension/xrowecommerce")}: {$item|wash}
                   </p>
                   <p>
                        {$desc_array.$key.variation_name|wash}
                   </p>
                   {undef $var_object}
                {/if}
                {if and( $item|count_chars|gt(0), $desc_array.$key|not() )}
                    <p class="error">{'Product not found. Please correct the SKU.'|i18n("extension/xrowecommerce")}</p>
                {/if}
                </td>
                <td class="delete nowrap"><input type="button" class="button" name="Delete[]" value="{"Delete"|i18n( 'extension/xrowecommerce/directorder')|wash}" title="{"Delete this row"|i18n( 'extension/xrowecommerce/directorder')|wash}" onclick="return delRows( this.parentNode.parentNode.parentNode.parentNode, this.parentNode.parentNode.parentNode );" /></td>
            </tr>
            {/foreach}

{/if}
            </tbody>
            </table>
            <div class="buttonblock">
                <input type="button" class="button" name="AddRows" value="{"Add rows"|i18n( 'extension/xrowecommerce/directorder')|wash}" onclick="return addrows( 'orderrow', 'ordertablebody', 5 )" />
                 <input type="submit" class="button" name="SearchButton" value="{"Search products"|i18n( 'extension/xrowecommerce/directorder')|wash}" title="{"Search products"|i18n( 'extension/xrowecommerce/directorder')|wash}" />
                <input type="submit" class="button" name="OrderButton" value="{"Add to shopping cart"|i18n( 'extension/xrowecommerce/directorder')|wash}" title="{"Add products to shopping cart"|i18n( 'extension/xrowecommerce/directorder')|wash}" />
            </div>
        </div>
    </div>
</div>

</form>
<script type="text/javascript">
<!--
    addrows( 'orderrow', 'ordertablebody', {sub( 10, $sku_array|count )} );
-->
</script>