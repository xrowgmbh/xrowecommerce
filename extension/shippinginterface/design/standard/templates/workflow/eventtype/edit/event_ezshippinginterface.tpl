<label>Active shipping methods</label>
<input type="hidden" name="WorkflowEvent_ezshippinginterface_active_shipping_{$event.id}_flag" value="true">
<select name="WorkflowEvent_ezshippinginterface_active_shipping_{$event.id}[]" multiple="multiple">
{foreach $event.workflow_type.methods as $item}
<option value="{$item.identifier}" {if $event.content|contains($item.identifier)} selected="selected" {/if}>{$item.name}</option>
{/foreach}
</select>

