<div class="block">

{* NodeID *}
<div class="block">
<fieldset>
<legend>{'Node which includes shelf warmer'|i18n( 'design/standard/workflow/eventtype' )}</legend>
{if $event.data_int1|gt( 0 )}
    <table class="list" cellspacing="0">
    <tr>
        <th class="tight">&nbsp;</th>
        <th>{'Node'|i18n( 'design/admin/workflow/eventtype/edit' )}</th>
    </tr>
    <tr class="bglight">
        <td>
            <input type="checkbox" name="DeleteObjectIDArray_{$event.id}[]" value="{$event.data_int1}" />
            <input type="hidden" name="WorkflowEvent_event_object_id_{$event.id}[]" value="{$event.data_int1}" />
        </td>
        <td>{fetch( 'content', 'node', hash( 'node_id', $event.data_int1)).name|wash}</td>
    </tr>
    </table>
{else}
    <p>{'No objects selected.'|i18n( 'design/standard/workflow/eventtype' )}</p>
{/if}
<input class="button{if $event.data_int1|eq( 0 )}-disabled{/if}" type="submit" name="CustomActionButton[{$event.id}_RemoveObject]" value="{'Remove selected'|i18n( 'design/admin/workflow/eventtype/edit' )}"
       {if $event.data_int1|eq( 0 )}disabled="disabled"{/if} />
<input class="button{if and( $event.data_int1|ne( '' ), $event.data_int1|gt( 0 ) )}-disabled{/if}" type="submit" name="CustomActionButton[{$event.id}_AddObject]" value="{'Add objects'|i18n( 'design/admin/workflow/eventtype/edit' )}"
       {if and( $event.data_int1|ne( '' ), $event.data_int1|gt( 0 ) )}disabled="disabled"{/if} />
</fieldset>
</div>

</div>