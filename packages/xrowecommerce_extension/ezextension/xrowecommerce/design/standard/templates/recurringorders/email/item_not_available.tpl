{def $url=concat('http://', ezini( 'SiteSettings', 'SiteURL', 'site.ini' ))}
{def $subject = concat( 'Order warning from ', $url )}
Dear Customer,

An item of our ordering system is not longer available. We had to remove it from your list of subscribed items.

Yours,
{ezini( 'SiteSettings', 'SiteName', 'site.ini' )}
{$url}