{* User - Edit *}

    <form enctype="multipart/form-data" method="post" action={concat( "/content/edit/", $object.id, "/", $edit_version, "/", $edit_language|not|choose( concat( $edit_language, "/" ), '' ) )|ezurl}>
    <input type="hidden" name="ContentLanguageCode" value="{$edit_language}" />
        <br />
        {include uri="design:content/edit_validation.tpl"}
        {include uri="design:content/edit_attribute_user.tpl"}
    </form>

{* attribute creditcard needed
        <div class="creditcard">
        <h2>Creditcard Information</h2>
        <p>Your credit card information is needed, if you want to make use of our recurring order option.</p>
        <label{section show=$ca.creditcard.has_validation_error} class="validation-error"{/section}>{$ca.creditcard.contentclass_attribute.name|wash}</label><div class="labelbreak"></div>
        <input type="hidden" name="ContentObjectAttribute_id[]" value="{$ca.creditcard.id}" />
        {attribute_edit_gui attribute_base=$attribute_base attribute=$ca.creditcard}
        </div>
        #{$content_attributes_data_map|attribute(show)}#
*}