{def $best_sellers=fetch( 'shop', 'best_sell_list',
                          hash( 'top_parent_node_id', 2,
                                'limit', 5,
                                'offset', 0,
                                'start_time', maketime( 0, 0, 0 ),
                                'duration', mul( 60, 60, 24 ),
                                'extended', true() ) ) }


<h2>Best Sellers</h2>
{* <span class="headingitk">Common Questions</span><br /><br /> *}

<ul>
{if $best_sellers}
{foreach $best_sellers as $product}
    {* $product.object.current| attribute(show,1) *}
    <li><a href={$product.object.main_node.path_identification_string|ezurl}>{$product.object.name}</a></li>
    {* <td>{$product.count}</td> *}
{/foreach}
{else}
<li>None at this time</li>
{/if}
</ul>
{undef}
