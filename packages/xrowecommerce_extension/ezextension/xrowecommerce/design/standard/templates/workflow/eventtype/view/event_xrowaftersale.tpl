<div class="element">
{if $event.data_int1|gt( 0 )}
{def $node=fetch( 'content', 'node', hash( 'node_id', $event.data_int1 ) )}
{'Node which includes shelf warmer'|i18n( 'design/standard/workflow/eventtype' )}: <strong>{fetch( 'content', 'node', hash( 'node_id', $event.data_int1)).name|wash}</strong>
{else}
{'No objects selected.'|i18n( 'design/standard/workflow/eventtype' )}
{/if}
</div>