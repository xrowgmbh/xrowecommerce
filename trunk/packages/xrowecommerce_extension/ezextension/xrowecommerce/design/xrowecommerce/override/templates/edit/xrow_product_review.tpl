<hr />
<form enctype="multipart/form-data" id="editform" name="editform" method="post" action={concat("/content/edit/",$object.id,"/",$edit_version,"/",$edit_language|not|choose(concat($edit_language,"/"),''))|ezurl}>
    <div class="border-box">
    <div class="border-tl"><div class="border-tr"><div class="border-tc"></div></div></div>
    <div class="border-ml"><div class="border-mr"><div class="border-mc float-break">

        <div class="content-edit">
            <div class="attribute-header">
                <h1 class="long">{'Product review'|i18n('extension/xrowexommerce')}</h1>
            </div>
            <div class="block edit-authoe">
                <label>Name <span class="required">(Required)</span>:</label>
                {attribute_edit_gui attribute=$object.contentobject_attributes.0}
            </div>
            <div class="block edit-name">
                <label>Title <span class="required">(Required)</span>:</label>
                {attribute_edit_gui attribute=$object.contentobject_attributes.1}
            </div>
            <div class="block edit-review">
                <label>Review <span class="required">(Required)</span>:</label>
                {attribute_edit_gui attribute=$object.contentobject_attributes.2}
            </div>
            <div class="buttonblock">
                <input class="defaultbutton" type="submit" name="PublishButton" value="{'Send'|i18n('extension/xrowexommerce')}" />
                <input class="button" type="submit" name="DiscardButton" value="{'Discard'|i18n('extension/xrowexommerce')}" />
                <input type="hidden" name="DiscardConfirm" value="0" />
                <input type="hidden" name="RedirectIfDiscarded" value="{ezhttp( 'LastAccessesURI', 'session' )}" />
                <input type="hidden" name="RedirectURIAfterPublish" value="{ezhttp( 'LastAccessesURI', 'session' )}" />
            </div>
        </div>

    </div></div></div>
    <div class="border-bl"><div class="border-br"><div class="border-bc"></div></div></div>
    </div>
</form>