{def $url=concat('http://', ezini( 'SiteSettings', 'SiteURL', 'site.ini' ))}
{def $subject = concat( 'Order warning from ', $url )}
Dear Customer,

We had noticed too many failures with your auto delivery. We have paused the delivery. 

Yours,
{ezini( 'SiteSettings', 'SiteName', 'site.ini' )}
{$url}