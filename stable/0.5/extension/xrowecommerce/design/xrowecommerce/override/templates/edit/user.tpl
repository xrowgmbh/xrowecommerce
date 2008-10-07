{* User - Edit *}

    <form enctype="multipart/form-data" method="post" action={concat( "/content/edit/", $object.id, "/", $edit_version, "/", $edit_language|not|choose( concat( $edit_language, "/" ), '' ) )|ezurl}>
    <input type="hidden" name="ContentLanguageCode" value="eng-US" />
        <br />
        {include uri="design:content/edit_validation.tpl"}
        {include uri="design:content/edit_attribute_user.tpl"}
    </form>

