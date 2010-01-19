<?php /* #?ini charset="utf-8"?

[MIMETypeSettings]
# Deprecated we do not want to to set this here
#Quality[]
#Quality[]=image/jpeg;90
# Set JPEG quality from 0 (worst quality, smallest file) to 100 (best quality, biggest file)
#

[AliasSettings]
AliasList[]=product_full
AliasList[]=product_tiny

[product_full]
Reference=
Filters[]
Filters[]=geometry/scaledownonly=400;300

[product_tiny]
Reference=
Filters[]
Filters[]=geometry/scalewidthdownonly=60

*/ ?>
