{*
    $attribute.content.text - text string of the billing cycle
    $attribute.content.quantity - quantity
    $attribute.content.text_array - array of text strings of the billing cycle
    $attribute.content.text_adj - adjective text of the billing cycle, e.g. weekly
    $attribute.content.text_adj_array - array of adjective texts of the billing cycle
*}
{if $attribute.has_content}
{if $attribute.content.period|eq(0)}
	{$attribute.content.text|wash()}
{else}
	{if $attribute.content.quantity|eq(1)}
		{$attribute.content.text_adj|wash()}
	{else}
		{$attribute.content.quantity|wash()} {$attribute.content.text|wash()}
	{/if}
{/if}
{/if}