<?php /* #?ini charset="utf-8"?

[MIMETypeSettings]
Quality[]
Quality[]=image/jpeg;90
# Set JPEG quality from 0 (worst quality, smallest file) to 100 (best quality, biggest file)

[ImageMagick]
Filters[]=kaiser/crop=-gravity center -crop %1x%2+0+0 +repage

[AliasSettings]
AliasList[]=product_full
AliasList[]=product_line
AliasList[]=product_thumb
AliasList[]=image_of_the_week

[product_full]
Reference=
Filters[]
Filters[]=geometry/scaledownonly=400;300

[product_thumb]
Reference=
Filters[]
Filters[]=geometry/scaledownonly=70;70

[image_of_the_week]
Reference=
Filters[]
Filters[]=geometry/scalewidth=95

[product_line]
Reference=
Filters[]
Filters[]=geometry/scalewidth=170
Filters[]=kaiser/crop=170;96

*/ ?>