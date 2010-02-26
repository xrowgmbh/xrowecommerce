{def $url=concat('http://', ezini( 'SiteSettings', 'SiteURL', 'site.ini' ))}
{def $subject = concat( ezini( 'SiteSettings', 'SiteName', 'site.ini' ), ':', 'Automatic Delivery Failure' )}
Dear Customer,

You had an Automatic Delivery scheduled; unfortunately we were unable to process your delivery.

Please check your payment options.

- Your credit card is not stored.
- Your credit card might be out of funds.
- Your credit card might be expired.
- Your credit card might expire soon.

Yours,
{ezini( 'SiteSettings', 'SiteName', 'site.ini' )}
{$url}

Login:
{ezini( 'SiteSettings', 'SiteName', 'site.ini' )}/user/login