<div id="col_main_search">
    <form enctype="multipart/form-data" method="post" action={concat( "/content/edit/", $object.id, "/", $edit_version, "/", $edit_language|not|choose( concat( $edit_language, "/" ), '' ) )|ezurl}>
        <h1 style="color: rgb(245, 121, 0); font-family: 'Arial';">Submit your question</h1>
        <p>
            {include uri="design:content/edit_validation.tpl"}
        </p>
        <input type="hidden" name="MainNodeID" value="{$main_node_id}" />
        <br/>
<p>
    Please type your question in the field. We'll try to update our "Know it all" section as soon as possible. Thanks a lot for being interested and the help you provide us.
</p>    
        {section name=ContentObjectAttribute loop=$content_attributes sequence=array(bglight,bgdark)}
            {if $ContentObjectAttribute:item.contentclass_attribute.identifier|eq("question")}
                <label{section show=$ContentObjectAttribute:item.has_validation_error} class="validation-error"{/section}>{$ContentObjectAttribute:item.contentclass_attribute.name|wash}</label><div class="labelbreak"></div>
                <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ContentObjectAttribute:item.id}" />
                {attribute_edit_gui attribute_base=$attribute_base attribute=$ContentObjectAttribute:item}
            {/if}
        {/section}
    
        <br/>
    <br/>
        <div class="buttonblock">
        {*<input type="image" name="DiscardButton" value="{'Discard'|i18n('design/base')}"  src={"images/cancel_small.gif"|ezdesign()}/>*}
        <input type="image" name="PublishButton" value="{'Send for publishing'|i18n('design/base')}" src={"images/continue_small.gif"|ezdesign()}/>
        <input type="hidden" name="DiscardConfirm" value="0" />
        <input type="hidden" name="RedirectURI" value={"/know_it_all"|ezurl()} />
        </div>
    </form>
</div>