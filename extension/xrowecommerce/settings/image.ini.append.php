<?php /* #?ini charset="utf-8"?

[MIMETypeSettings]
Quality[]
Quality[]=image/jpeg;90
# Set JPEG quality from 0 (worst quality, smallest file) to 100 (best quality, biggest file)

[ImageMagick]
Filters[]=geometry/crop=-gravity center -crop %1x%2+0+0 +repage

[AliasSettings]
AliasList[]=product_full
AliasList[]=product_line

[product_full]
Reference=
Filters[]
Filters[]=geometry/scaledownonly=400;300

[product_line]
Reference=
Filters[]
Filters[]=geometry/scaledownonly=180;350
Filters[]=geometry/crop=170;95

*/ ?>