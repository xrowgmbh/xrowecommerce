<div id="mu-right-column">
                {if eq(ezpreference( 'quick_order' ), 1)}
                {include uri='design:quick_basket.tpl'}
                {else}
                    {tool_bar name=right view=full}
                {/if}
</div>