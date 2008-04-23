<div id="col_main" >
	<h2>{$node.name|upcase()}</h2>
	<br />
	{attribute_view_gui attribute=$node.data_map.description}
	
	{include name=Validation uri='design:content/collectedinfo_validation.tpl'
                 class='message-warning'
                 validation=$validation collection_attributes=$collection_attributes}

        
        <form method="post" action={"content/action"|ezurl}>

        <h3>{"Your E-mail address"|i18n("design/base")}</h3>
        <div class="attribute-email">
                {attribute_view_gui attribute=$node.data_map.email}
        </div>

        <h3>{"Subject"|i18n("design/base")}</h3>
        <div class="attribute-subject">
                {attribute_view_gui attribute=$node.data_map.subject}
        </div>

        <h3>{"Message"|i18n("design/base")}</h3>
        <div class="attribute-message">
                {attribute_view_gui attribute=$node.data_map.message}
        </div>
<br />
        <div class="content-action">
        {*<input type="submit" class="defaultbutton" name="ActionCollectInformation" value="{"Send form"|i18n("design/base")}" />*}
            <input type="image" src={"images/emailbutton.gif"|ezdesign()} name="ActionCollectInformation" value="{"Send form"|i18n("design/base")}">
            <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
            <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
            <input type="hidden" name="ViewMode" value="full" />
        </div>
        </form>
</div>


