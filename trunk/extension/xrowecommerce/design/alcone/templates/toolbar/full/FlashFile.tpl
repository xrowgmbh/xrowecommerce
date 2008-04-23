
 {* //////////////////////////////////////////////////////////////////////////////// *}

 {def $file=fetch( 'content', 'list', hash( 'parent_node_id', $parent_node,
              'sort_by', array( 'priority', false() ),
              'limit', 1,
              'class_filter_type',  'include',
              'class_filter_array', array( 'image','flash' )
               ) )
}

{* $file|attribute(show,1) *}
{*
{foreach $file as $index => $object }
{$index} : {$object.name}<br />
{/foreach}
*}
{*
              'class_filter_array', array( 'image', 'flash' ) x *}
  {* $file|attribute(show,1) *}

{*
,
              'sort_by', array( 'priority', false() ),
              'limit', 1
,
              'class_filter_type',  'include',
              'class_filter_array', array( 'image' )
              'class_filter_array', array( 'image', 'flash' )
*}
  {* //////////////////////////////////////////////////////////////////////////////// *}


 {if $file.0.class_identifier|eq('image')}
  {def $image=$file}
  {attribute_view_gui attribute=$image.0.object.data_map.image image_class=original href="/products/tools/makeup_bags"|ezurl()}
 {* /if *}
 {else}

  {def $flash_file=$file.0}
    {let attribute=$flash_file.data_map.file}
    <div id="flashheader" style="margin-left: 3px; margin-top: 10px;">
        <object codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=5,0,0,0"
                {section show=$attribute.content.width|gt( 0 )}width="{$attribute.content.width}"{/section} {section show=$attribute.content.height|gt( 0 )}height="{$attribute.content.height}"{/section} id="objectid{$flash_file.object.id}">
        <param name="wmode" value="transparent">
        <param name="movie" value={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl} />
        <param name="quality" value="{$attribute.content.quality}" />
        <param name="play" value="{section show=$attribute.content.is_autoplay}true{/section}" />
        <param name="loop" value="{section show=$attribute.content.is_loop}true{/section}" />
        <embed src={concat("content/download/",$attribute.contentobject_id,"/",$attribute.content.contentobject_attribute_id,"/",$attribute.content.original_filename)|ezurl}
               type="application/x-shockwave-flash"
               quality="{$attribute.content.quality}" wmode="transparent" pluginspage="{$attribute.content.pluginspage}"
               {section show=$attribute.content.width|gt( 0 )}width="{$attribute.content.width}"{/section} {section show=$attribute.content.height|gt( 0 )}height="{$attribute.content.height}"{/section} play="{section show=$attribute.content.is_autoplay}true{/section}"
               loop="{section show=$attribute.content.is_loop}true{/section}" name="objectid{$flash_file.object.id}">

        </embed>
        </object>
	</div>
    {/let}

 {/if}

  {* //////////////////////////////////////////////////////////////////////////////// *}
