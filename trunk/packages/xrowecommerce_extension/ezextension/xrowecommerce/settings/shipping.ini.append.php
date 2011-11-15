<?php /* #?ini charset="utf-8"?

[Settings]
ShippingGateways[]=UPS
ShippingGateways[]=USPS
ShippingGateways[]=fixedprice

# List of products that do not have shipping and/or handling costs (contentobject ID)
FreeShippingProducts[]
##example:
##FreeShippingProducts[]=1234

# Allows free shipping and/or handling even with additional non free shipping products
FreeShippingAdditionalProducts=disabled

# Amount of products when shipping and/or handling is free
FreeShippingProductConditions[]
##example: Shipping is free, when 2 or more items from contentobject 1234 are in the cart
##FreeShippingProductConditions[1234]=2

# List of gateways where free shipping and/or handling is allowed (gateway identifiers)
FreeShippingHandlingGateways[]

# List of countries where free shipping and/or handling is allowed (Alpha3)
FreeShippingHandlingCountries[]
##example:
##FreeShippingHandlingCountries[GER]

# Switch to enable or disable Handling
HandlingFee=enabled
# Amount added to the cart
HandlingFeeAmount=2.00
# Name displayed
HandlingFeeName=Handling
# Defines if the handling fee should be included or not
HandlingFeeInclude=disabled
FreeShippingitemReduce=9.00
eZoption2ProductVariations=Enabled

# Enter weight unit
# Possible values: lbs | kg
WeightUnit=kg
ShowShippingWeight=disabled

[FixedPrice]
Amount=6

*/ ?>