{if eq($module_result.node_id, '2')}
{else}
<p>
{section name=Path loop=$module_result.path}
    {section show=$Path:item.url}
        {section show=is_set($Path:item.url_alias)}
            <a href={$Path:item.url_alias|ezurl}>{$Path:item.text|wash}</a>&nbsp;&nbsp;&nbsp;<span style="color: #aac708;">::</span>&nbsp;&nbsp;
        {section-else}
            <a href={$Path:item.url|ezurl}>{$Path:item.text|wash}</a>&nbsp;<span style="color: #aac708;">::</span>&nbsp;&nbsp;
        {/section}
    {section-else}
        {* $Path:item|attribute(show,1) *}
        <a href={$node.url_alias|ezurl}>{$Path:item.text|wash}</a>
    {/section}
{section-else}
{/section}
</p>
{/if}
