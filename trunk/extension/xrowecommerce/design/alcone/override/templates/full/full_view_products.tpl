<div id="col_left">
    {*    <br /><br /> *}
    {def $cats=fetch( 'content', 'list', hash(	'parent_node_id', $node.node_id,
         				  	'sort_by', array( array( 'priority' ) ),
                			  	'class_filter_type',  'include',
              	                         	'class_filter_array', array( 'category' ) ) )
    }
    {if gt($cats|count,0)}
	       <ul>
	           {foreach $cats as $cat}
	               <li>
	                  {if eq( $cat.node_id, $node_subcat1)}
		                  <span style="color: #f57900; font-weight: bold;">{$cat.name}</span>
		              {else}
		                  <a href={$cat.url_alias|ezurl}>{$cat.name}</a>
		              {/if}
	               </li>
	           {/foreach}
	       </ul>
	   {/if}
</div>


{if and( is_set($view_parameters.manu), $view_parameters.manu|gt(0) )}
    {def  $prods=fetch( 'content', 'tree', hash('parent_node_id', '2',
            					'sort_by', array( array( 'priority' ) ),
              					'class_filter_type',  'include',
              					'class_filter_array', array( 'product' ),
              					'attribute_filter', array( array( 'product/manufacturer',
                                                '=',
                                                $view_parameters.manu ) ),
                			  	'limit', $page_limit,
              				  	'offset', $view_parameters.offset ) )
          $prods_count=fetch( 'content', 'tree_count', hash('parent_node_id', '2',
              					   	    'class_filter_type',  'include',
              						    'class_filter_array', array( 'product' ),
              					            'attribute_filter', array( array( 'product/manufacturer',
                                                            '=',
                                                            $view_parameters.manu ) ) ) )
    }

    <div id="col_middle">
      <h2>{$node.name|upcase()}</h2>
    <div>

    <div style="float: left; display: inline; margin-right: 10px;">
        {if $node.data_map.image.content.is_valid}
            {attribute_view_gui attribute=$node.data_map.image image_class=productthumbnail alignment=left}
        {else}
        {*<div class="imageleft">
                <img src={"images/no_pic.jpg"|ezdesign} width="140">
            </div>*}
        {/if}
    </div>

    <div style="font-weight: bold; color: #565969;">
        {if $node.data_map.description.content.is_empty|not}
            {attribute_view_gui attribute=$node.data_map.description}
        {else}
        {/if}
    </div>
</div>
<div class="break"></div>
<br />

{def $featuredprods=fetch( 'content', 'tree', hash('parent_node_id', $node.node_id,
              					   'sort_by', array( array( 'priority' ) ),
              				    	   'class_filter_type',  'include',
              					   'class_filter_array', array( 'product' ),
              					   'attribute_filter', array( array( 'product/featured', '=', true() ) ),
              					   'limit', 3 ) ) }
{if gt($featuredprods|count, 0)}
    <h3>FEATURED PRODUCTS</h3>
{/if}

{foreach $featuredprods as $fp}
<div id="fp_category" style="float: left; height: 185px; width: 130px; border: 1px solid #d3d5d6; margin-right: 6px;">
    <div style="padding-top: 5px; height: 115px;"><a href={$fp.url_alias|ezurl()}>
	{if $fp.data_map.image_link.content.current.contentobject_attributes.2.content.is_valid}
        {attribute_view_gui attribute=$fp.data_map.image_link.content.current.contentobject_attributes.2 image_class=small alignment=center}
    {elseif $fp.data_map.image.content.is_valid}
      {attribute_view_gui attribute=$fp.data_map.image image_class=small alignment=center}
    {else} {/if}</a></div>
    <div style="color: #565969; text-align: center; text-decoration: underlined; font-weight: bold; font-size: 11px; height: 4em; margin-left: 4px; margin-right: 4px;overflow:hidden;">
        <a href={$fp.url_alias|ezurl()}>{$fp.name}</a><br />
    </div>
    {undef $var_price}
    {undef $allprice}
    {undef $partprice}

    {if count($fp.data_map.variation.content.option_list)|eq(1)}
        {def $allprice=$fp.data_map.variation.content.option_list.0.additional_price}
    {elseif count ($fp.data_map.variation.content.option_list)|gt(1)}
        {foreach $fp.data_map.variation.content.option_list as $var_price}
            {if or( $var_price.additional_price|lt($partprice), is_set($partprice)|not ) }
                {def $partprice=$var_price.additional_price}
            {/if}
        {/foreach}
    {/if}

    <div style="height: 1em; text-align: center;">
        <p>
            {if or( $partprice|gt(0), $allprice|gt(0) ) }
                {if $partprice|gt(0)}
                    <span class="currentprice">from {$partprice|l10n( 'currency' )}</span>
                {/if}
            {if $allprice|gt(0)}
                <span class="currentprice">{$allprice|l10n( 'currency' )}</span>
            {/if}
            {else}
                {attribute_view_gui attribute=$fp.data_map.price}
            {/if}
        </p>
    </div>
</div>
{/foreach}
    <div class="break"></div>
    <br /><br />
    <div style="width: 100%; color: black;">
        	{include name=navigator
                     uri='design:navigator/google.tpl'
                     page_uri=$node.url_alias
                     item_count=$prods_count
                     view_parameters=$view_parameters
                     item_limit=$page_limit}
    </div>
    <br />
        {if gt($prods|count(),0)}
	       {foreach $prods as $prod}
                {undef $var_price}
                {undef $allprice}
                {undef $partprice}

                {if count($prod.data_map.variation.content.option_list)|eq(1)}
           	        {def $allprice=$prod.data_map.variation.content.option_list.0.additional_price}
           	    {elseif count ($prod.data_map.variation.content.option_list)|gt(1)}
           	        {foreach $prod.data_map.variation.content.option_list as $var_price}
           	            {if or( $var_price.additional_price|lt($partprice), is_set($partprice)|not ) }
           	                {def $partprice=$var_price.additional_price}
           	            {/if}
           	        {/foreach}
           	    {/if}
<div style="width: 100%; margin-right: 5px; border-top: 2px solid #d3d5d6; height: 3px;"></div>
    <div style="min-height: 85px; height: auto !important; height:85px; width: 100%; margin-top: 5px;">

		<div style="float: left; min-height: 75px; height: auto !important; height:75px; width: 100px; margin-right: 5px;">
                <a href={$prod.url_alias|ezurl()}>
                    {if $prod.data_map.image_link.content.current.contentobject_attributes.2.content.is_valid}
                        {attribute_view_gui attribute=$prod.data_map.image_link.content.current.contentobject_attributes.2 image_class=gallerythumbnail alignment=left}
                    {elseif $prod.data_map.image.content.is_valid}
                        {attribute_view_gui attribute=$prod.data_map.image image_class=gallerythumbnail alignment=center}
                    {else}
                        <img src={"images/no_pic.jpg"|ezdesign} height="70">
                    {/if}
                </a>
        </div>
        <div style="float: left; min-height: 80px; height: auto !important; height:80px; width: 300px;">
            <a href={$prod.url_alias|ezurl()}>{$prod.name}</a>
            <div style="width: 300px;">

				{if $prod.data_map.keywords.content.is_valid}
				{attribute_view_gui attribute=$prod.data_map.keywords}
				{else}
				{attribute_view_gui attribute=$prod.data_map.description}

				{/if}

            </div>
            <p>
                {if or( $partprice|gt(0), $allprice|gt(0) ) }
                    {if $partprice|gt(0)}
                        <span class="currentprice">from {$partprice|l10n( 'currency' )}</span>
                    {/if}
                {if $allprice|gt(0)}
                    <span class="currentprice">{$allprice|l10n( 'currency' )}</span>
                {/if}
                {else}
                    {attribute_view_gui attribute=$prod.data_map.price}
                {/if}
            </p>

        </div>
    </div>
<div class="break"></div>

        	{/foreach}
        	<div class="break"></div>




        	<div style="width: 100%; color: black;">
        	{include name=navigator
                     uri='design:navigator/google.tpl'
                     page_uri=$node.url_alias
                     item_count=$prods_count
                     view_parameters=$view_parameters
                     item_limit=$page_limit}
            </div>

	    {else}
    	   <br />	<br />
    	   <b>Sorry, there are actually no prodcuts in this category.</b>
    	   {if and(is_set($view_parameters.manu),gt($view_parameters.manu,0) ) }<br /><b>At least not for the selected manufacturer.</b>{/if}
    	   <br />
    	   <br />
	    {/if}
</div>


  {* <hr /> *}
{else}
    {* Fallback else case, displays anything but search results selection *}
{*
    {def $prods=fetch( 'content', 'tree', hash('parent_node_id', '2',
             	       'sort_by', array( array( 'priority' ) ),
              	       'class_filter_type',  'include',
              	       'class_filter_array', array( 'product' ),
              	       'limit', $page_limit,
              	       'offset', $view_parameters.offset ) )
         $prods_count=fetch( 'content', 'tree_count', hash('parent_node_id', '2',
              					 	   'class_filter_type',  'include',
              					 	   'class_filter_array', array( 'product' ) ) )
    }
*}
<div id="col_middle">
  <h2>FEATURED PRODUCTS</h2>
  <br />
  {foreach $cats as $cat}
    {def $products=fetch( 'content', 'tree', hash(	'parent_node_id', $cat.node_id,
                          'class_filter_type',  'include',
     		  	  'class_filter_array', array( 'product' ),
              		  'attribute_filter', array( array( 'product/featured', '=', true() ) ),
              		  'sort_by', array( array( 'published') ),
              		  'limit', 1 ) )}
       		          {* 'attribute_filter', array( array( 'product/featured', '=', '1' ) ), *}

    {foreach $products as $prod}
	     {undef $var_price}
	     {undef $allprice}
	     {undef $partprice}

             {if count($prod.data_map.variation.content.option_list)|eq(1)}
       	        {def $allprice=$prod.data_map.variation.content.option_list.0.additional_price}
             {elseif count ($prod.data_map.variation.content.option_list)|gt(1)}
           	{foreach $prod.data_map.variation.content.option_list as $var_price}
           	   {if or( $var_price.additional_price|lt($partprice), is_set($partprice)|not ) }
           	     {def $partprice=$var_price.additional_price}
           	   {/if}
           	{/foreach}
             {/if}

              <div id="product">
              {if $prod.data_map.image.content.is_valid}
      		      <div style="height: 200px;">
        		      <a href={$prod.parent.url_alias|ezurl()}>See more in this Category</a><br />
        		      <a href={$prod.url_alias|ezurl()}>{attribute_view_gui attribute=$prod.data_map.image image_class=productthumbnail alignment=center}</a></div>
                      <br />
       	              <a href={$prod.url_alias|ezurl()}>{$prod.name}</a><br />
        	      <p>
        		{if or( $partprice|gt(0), $allprice|gt(0) ) }
                          {if $partprice|gt(0)}
                            <span class="currentprice">from {$partprice|l10n( 'currency' )}</span>
                          {/if}
                          {if $allprice|gt(0)}
                            <span class="currentprice">{$allprice|l10n( 'currency' )}</span>
                          {/if}
                        {else}
                          {attribute_view_gui attribute=$prod.data_map.price}
                        {/if}
        	      </p>
              {else}
        	      <div style="height: 200px;">
        		<a href={$prod.parent.url_alias|ezurl()}>See more in this Category</a><br />
        		<a href={$prod.url_alias|ezurl()}><img src={"images/no_pic.jpg"|ezdesign} width="130"></a></div>
        		<br />
        		<a href={$prod.url_alias|ezurl()}>{$prod.name}</a><br />
            		<p>
            		{if or( $partprice|gt(0), $allprice|gt(0) ) }
                          {if $partprice|gt(0)}
                             <span class="currentprice">from {$partprice|l10n( 'currency' )}</span>
                          {/if}
                          {if $allprice|gt(0)}
                             <span class="currentprice">{$allprice|l10n( 'currency' )}</span>
                          {/if}
                        {else}
                          {attribute_view_gui attribute=$prod.data_map.price}
                        {/if}
            	        </p>
              {/if}
              </div>
     {/foreach}             							  	
   {/foreach}
   <div class="break"></div>
</div>
{/if}



{* Sidebar - Right *}
<div id="col_right">
{*include uri="design:toolbar/full/search_manufacturers.tpl"*}

{* End: 'Search Box' block *}





 <h2>In the know</h2>
 <span class="headingitk">Common Makeup Questions</span><br />
 <br />
 {def $intheknown=fetch( 'content', 'tree', hash( 'parent_node_id', 119,
              					  'sort_by', array( array( 'priority' ) ),
              				   	  'class_filter_type',  'include',
              					  'class_filter_array', array( 'in_the_known' ),
              					  'limit', 9 ) )}
 {foreach $intheknown as $known}<a href={concat("/know_it_all/(question)/",$known.node_id)|ezurl()}>{$known.name}</a><br /><br />{/foreach}
 <br />
</div>
