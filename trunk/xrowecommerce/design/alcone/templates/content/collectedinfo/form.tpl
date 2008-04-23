<div id="col_main">
{default collection=cond( $collection_id, fetch( content, collected_info_collection, hash( collection_id, $collection_id ) ),
                          fetch( content, collected_info_collection, hash( contentobject_id, $node.contentobject_id ) ) )}

{set-block scope=global variable=title}{'Form %formname'|i18n('design/standard/content/form',,hash('%formname',$node.name|wash))}{/set-block}

{* <h1>{'Collected information'|i18n('design/standard/content/form')}</h1> *}

<h2>{$object.name|wash} - {'Collected information'|i18n('design/standard/content/form')}</h2>

{section show=$error}

{section show=$error_existing_data}
<p>{'You have already submitted data to this form. The previously submitted data was the following.'|i18n('design/standard/content/form')}</p>
{/section}

{/section}

{section loop=$collection.attributes}

<h3>{$:item.contentclass_attribute_name|wash}</h3>

{attribute_result_gui view=info attribute=$:item}

{/section}

<p/>

<a href={$node.parent.url|ezurl}>{'Return to site'|i18n('design/standard/content/form')}</a> 
{* <a href="javascript:history.go(-1)">{'Return to site'|i18n('design/standard/content/form')}</a>
<a href="{ezhttp('LastAccessesURI','session')|ezurl}">{'Return to site'|i18n('design/standard/content/form')}</a>
<a href="javascript:history.go(-1)">{'Return to site'|i18n('design/standard/content/form')}</a> *}
{/default}

</div>

