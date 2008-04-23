{def $products=fetch('content', 'list',  hash( 'parent_node_id', 61, 
                                               'sort_by', array( 'published', false() ), 
                                               'limit',          3,
                                               'class_filter_type',  'include',
                                               'class_filter_array', array( 'product' ),
                                               'depth',          3
 ) )
}
<h2>Latest Products</h2>
{* <span class="headingitk">Common Questions</span><br /><br /> *}

<ul class="paw">
{foreach $products as $product}
    {* $product.object|attribute(show,1) *}
    <li><a href={$product.url_alias|ezurl}>{$product.name}</a><br />&nbsp;{$product.object.modified|datetime('custom', '%m/%d/%Y @ %G:%i')}</li>
    {* <td>{$product.count}</td> *}
{/foreach}
</ul>
{undef}
