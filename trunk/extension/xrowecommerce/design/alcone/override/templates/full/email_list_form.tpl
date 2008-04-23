<div class="shop-basket">
        <div style="border: 1px solid #ebeeef; width: 490px; float: left; display: inline; margin:5px; padding: 8px;">
        <span class="heading1">{$node.name|upcase()}</span>
	<br />{attribute_view_gui attribute=$node.data_map.description}
        <p><span class="required">* Required field</span></p>

	{include name=Validation uri='design:content/collectedinfo_validation.tpl'
                 class='message-warning'
                 validation=$validation collection_attributes=$collection_attributes}

        <div style="border: 1px solid #ebeeef; width: 290px; float: left; display: inline; margin:5px; padding: 8px;">
        <form method="post" action={"content/action"|ezurl}>
         <div class="block">
          <label><span class="required">*</span>{"First Name / Last Name"|i18n("design/base")}</label>
          <div class="labelbreak"></div>
          {attribute_view_gui attribute=$node.data_map.f_name_l_name}
         </div>
         <div class="break"></div>
         <div class="block">
           <label><span class="required">*</span>{"Your E-mail address"|i18n("design/base")}</label>
           <div class="labelbreak"></div>
           {attribute_view_gui attribute=$node.data_map.email}
         </div>
         <div class="break"></div>
         <div class="block">
          <label><span class="required">*</span>{"Add me to Alcone Company's mailing list."|i18n("design/base")}</label>
          <div class="labelbreak"></div>
          {attribute_view_gui attribute=$node.data_map.newsletter}
         </div>

        <div class="content-action" align="right">
        {* <input type="submit" class="defaultbutton" name="ActionCollectInformation" value="{"Send form"|i18n("design/base")}" /> *}
            <input type="image" src={"images/emailbutton.gif"|ezdesign()} name="ActionCollectInformation" value="{"Send form"|i18n("design/base")}">

            <input type="hidden" name="ContentObjectAttribute_ezstring_data_text_36582" value="" />
            <input type="hidden" name="ContentObjectAttribute_ezstring_data_text_36584" value="" />
            <input type="hidden" name="ContentObjectAttribute_ezstring_data_text_36586" value="" />
            <input type="hidden" name="ContentObjectAttribute_ezstring_data_text_36588" value="" />
            <input type="hidden" name="ContentObjectAttribute_ezstring_data_text_36590" value="" />

            <input type="hidden" name="ContentNodeID" value="{$node.node_id}" />
            <input type="hidden" name="ContentObjectID" value="{$node.object.id}" />
            <input type="hidden" name="ViewMode" value="full" />
        </div>
        </form>
        </div>
      </div>
</div>
