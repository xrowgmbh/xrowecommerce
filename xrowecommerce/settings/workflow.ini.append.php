<?php /* #?ini charset="iso-8859-1"?

#?ini charset="iso-8859-1"?
# eZ publish configuration file for workflows.

[EventSettings]
RepositoryDirectories[]=extension/googleanalytics/workflowtypes
ExtensionDirectories[]=googleanalytics
AvailableEventTypes[]=event_ezreceipt

[EventSettings]
RepositoryDirectories[]=extension/alcone/workflowtypes
# ExtensionDirectories[]=alcone
ExtensionDirectories[]=staticshipping

AvailableEventTypes[]=event_ezproductcount
AvailableEventTypes[]=event_ezadvancedshipping

[EventSettings]
ExtensionDirectories[]=coupon
AvailableEventTypes[]=event_ezcouponworkflow

[CouponWorkflow]
Description=Coupon

[SimpleShippingWorkflow]
FreeShipping=Disabled
FreeShippingPrice=50.00
FreeShippingDiscount=8.00
eZoption2ProductVariations=Enabled
ShippingVendorName=AlconeCo
DefaultStandardShipping=9.00
Debug=Disabled

# Deprecated
FreeShippingWeightDiscount=5.00

[1OperationSettings]
# Depricated : AvailableOperations. Use AvailableOperationList instead.
# AvailableOperations=content_publish;before_shop_confirmorder;shop_checkout
# AvailableOperations=content_publish;content_read;shop_confirmorder;shop_checkout
AvailableOperations=

# List of available trigger operations.
AvailableOperationList[]
# AvailableOperationList[]=shop_checkout
AvailableOperationList[]=before_shop_checkout
AvailableOperationList[]=after_shop_checkout
AvailableOperationList[]=before_shop_confirmorder
AvailableOperationList[]=shop_addtobasket
AvailableOperationList[]=shop_updatebasket
AvailableOperationList[]=content_publish

*/ ?>
