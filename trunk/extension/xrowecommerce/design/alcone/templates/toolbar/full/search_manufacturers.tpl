<form action={"/content/advancedsearch/"|ezurl} method="get">
        <div style="vertical-align: middle;">
         <b><span style="font-family: Times; font-size: 14px;color: #308d9d; font-weight: normal;">SEARCH:</span></b>
    {def $page_limit=15
         $manus=fetch( 'content', 'list', hash( 'parent_node_id', 128,
                       'sort_by', array( array( 'name' ) ),
                       'class_filter_type',  'include',
                       'class_filter_array', array( 'manufacturer' ) ) ) }
    {def $manu_list=array()}
    {foreach $manus as $manu}
      {def $manu_list=$manu_list|append(hash('object_id',$manu.contentobject_id,'name',$manu.name))}
    {/foreach}
     <br />
     <br />

     <select name="brand" style="width: 150px; font-size: 9px; z-index: 0; position: relative;"
     onchange="javascript:window.location.href='{if $node.url_alias|eq( '' )}{'/products/'|ezurl(no)}{/if}/(manu)/'+this.value;">
        <option value="">by manufacture/brand</option>
        {foreach $manu_list as $manu_element}
            <option value="{$manu_element.object_id}"
     {if eq($manu_element.object_id,$view_parameters.manu)} selected {/if}
            >{$manu_element.name}</option>
        {/foreach}
     </select>
     <br />

     <input type="hidden" name="SearchPageLimit" value="5">
     <input type="hidden" name="SearchContentClassID" value="16,20,21,23">

     <input type="text" name="SearchText" value="keyword" size="15" class="searchbox" onfocus="this.select();">&nbsp;
     <input type="image" src={"images/go.gif"|ezdesign()} style="vertical-align:middle;">
     <div id="dottedline"></div>
</form>






{*
  <form action={"/content/advancedsearch/"|ezurl} method="get">
    <div style="vertical-align: middle;">
      <b><span style="font-family: Times; font-size: 14px;color: #308d9d; font-weight: normal;">SEARCH:</span></b>
<!--
      <input type="hidden" name="SearchContentClassID[]" value="16,18">
      <input type="hidden" name="SearchContentClassID[]" value="2,4,16,17,18,20,21,22,23,24">
-->
      <input type="hidden" name="SearchPageLimit" value="5">
      <input type="hidden" name="SearchContentClassID" value="16,18">

      <input type="text" name="SearchText" value="keyword" class="searchbox"
      onfocus="this.select();"
      >&nbsp;
      <input type="image" src={"images/go.gif"|ezdesign()} style="vertical-align:middle;">
      <div id="dottedline"></div>
    </div>
  </form>
*}

