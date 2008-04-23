{* FAQ - Linesearcg view *}
<td valign="top" width="30"><a href={concat("/faqs/(question)/", $node.node_id)|ezurl()} style="text-decoration: none;">
            <img src={"images/arrow.gif"|ezdesign()}></a>
</td>
<td>
<a href={concat("/faqs/(question)/", $node.node_id)|ezurl()} style="text-decoration: none;">{$node.name|wash()}</a>
</td>
<td valign="top" width="150">
FAQ
</td>
</tr><tr>
<td colspan="3">{$:item.name}<hr></td>
</tr>
