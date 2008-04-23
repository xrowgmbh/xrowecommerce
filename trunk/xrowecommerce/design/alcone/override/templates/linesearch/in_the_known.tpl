{* FAQ - Line view *}
<tr>
    <td valign="top" width="30">
        <a href={concat("/in_the_known/(question)/", $node.node_id)|ezurl()} style="text-decoration: none;">
            <img src={"images/arrow.gif"|ezdesign()}>
        </a>
    </td>
    <td>
        <a href={concat("/in_the_known/(question)/", $node.node_id)|ezurl()} style="text-decoration: none;">
            {$node.name|wash()}
        </a>
    </td>
    <td valign="top" width="150">
        In The Known
    </td>
</tr>
<tr><td colspan="3"><hr></td></tr>
