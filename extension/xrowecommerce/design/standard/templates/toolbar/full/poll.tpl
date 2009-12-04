{def $node=fetch( 'content', 'node', hash( 'node_id', $parent_node ))}

<div class="border-box">
<div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
<div class="border-ml"><div class="border-mr"><div class="border-mc">

<div class="content-view-toolbar">
    <div class="class-poll">
        <div class="poll-result">
        <div class="class-poll">
        
            <div class="attribute-header">
                <h2>{$node.name|wash()}</h2>
            </div>

            <div class="attribute-short">
            {attribute_view_gui attribute=$node.data_map.description}
            </div>
            
{foreach $node.object.contentobject_attributes as $contentobject_attribute_item}
            {def  $attribute=$contentobject_attribute_item
                  $contentobject_attribute_id=cond( $attribute|get_class|eq( 'ezinformationcollectionattribute' ),$attribute.contentobject_attribute_id,
                                                   $attribute|get_class|eq( 'ezcontentobjectattribute' ),$attribute.id )
                  $contentobject_attribute=cond( $attribute|get_class|eq( 'ezinformationcollectionattribute' ),$attribute.contentobject_attribute,
                                                $attribute|get_class|eq( 'ezcontentobjectattribute' ),$attribute )
                  $total_count=fetch( 'content', 'collected_info_count', hash( 'object_attribute_id', $contentobject_attribute_id ) )
                  $item_counts=fetch( 'content', 'collected_info_count_list', hash( 'object_attribute_id', $contentobject_attribute_id  ) )}

                <table class="poll-resultlist">

                {foreach $contentobject_attribute.content.option_list as $option}
                    {def $item_count=0}
                    {if is_set( $item_counts[$option.id] )}
                        {set $item_count=$item_counts[$option.id]}
                    {/if}

                    {def $percentage=cond( $total_count|gt( 0 ), round( div( mul( $item_count, 100 ), $total_count ) ), 0 )
                         $tenth=cond( $total_count|gt( 0 ), round( div( mul( $item_count, 10 ), $total_count ) ), 0 )}
                <tr>
                    <td class="poll-resultname">
                        <p>{$option.value} ({$item_count})</p>
                    </td>
                </tr>
                <tr>
                    <td class="poll-resultbar">
                        <table class="poll-resultbar">
                        <tr>
                            <td style="width: 100%;">
                                <div class="chart-bar-edge-start">
                                    <div class="chart-bar-edge-end">
                                        <div class="chart-bar-resultbox">
                                            <div class="chart-bar-resultbar chart-bar-{$percentage}-of-100 chart-bar-{$tenth}-of-10" style="width: {$percentage}%;">
                                                <div class="chart-bar-result-divider"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="poll-percentage">
                                <i>{$percentage}%</i>
                            </td>
                        </tr>
                        </table>
                    </td>
                    {delimiter}
                </tr>
                <tr>
                    {/delimiter}
                    {undef $item_count $percentage $tenth}
                {/foreach}
                </tr>
                </table>
        {/foreach}
        
        
        {'%count total votes'|i18n( 'design/ezwebin/collectedinfo/poll' ,,
                                     hash( '%count', fetch( content, collected_info_count, hash( object_id, $object.id ) ) ) )}

        <br/>
		
		    <form method="post" action={"content/action"|ezurl}>
		    <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
		    <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
		    <input type="hidden" name="ViewMode" value="full" />
		
		    <div class="content-question">
		    {attribute_view_gui attribute=$node.data_map.question}
		    </div>
		
		    {if is_unset( $versionview_mode )}
		    <input class="button" type="submit" name="ActionCollectInformation" value="{"Vote"|i18n("design/ezwebin/full/poll")}" />
		    {/if}
		
		    </form>
		</div>
        </div>
    </div>
</div>

</div></div></div>
<div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
</div>

