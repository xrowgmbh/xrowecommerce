<?php /* #?ini charset="utf-8"?

[XrowProductDataTypes]
ProductDataTypeArray[]
ProductDataTypeArray[string]=xrowProductStringType
ProductDataTypeArray[integer]=xrowProductIntegerType
ProductDataTypeArray[boolean]=xrowProductBooleanType
ProductDataTypeArray[text]=xrowProductTextType
ProductDataTypeArray[float]=xrowProductFloatType
#ProductDataTypeArray[relation]=xrowProductRelationType
ProductDataTypeArray[price]=xrowProductPriceType
ProductDataTypeArray[sku]=xrowProductSkuType
ProductDataTypeArray[date]=xrowProductDateType
# coming soon
#ProductDataTypeArray[]=selection

# Delimiter for the price option name
NameDelimiter=,

[PriceSettings]
IsVATInclude=false
VATTypeID=1

# Which price of which country should be shown
# PreferedCountry=AUT

# Country which has the default price (input is required)
#DefaultCountry=AUT

# A list of countries for all supported currencies / values
# CountryArray[GER]=EUR
# CountryArray[AUT]=EUR
#CountryArray[]

# A list of priorized countries
# PrioCountryArray[0]=GER
# PrioCountryArray[1]=AUT
# In that case the default country/currency is GER.
# If no value is given, the fallback is AUT
# If there is no fallback, the array needs to be filled with the default
# country/currency
# PrioCountryArray[]

# Show hidden fields, necerssary on admin site
# ShowHiddenFields=true

# attribute variation identifier of the price column
PriceIdentifier=price

[ImportSettings]
# Separator for csv import
Separator=;

[ExportSettings]
# Separator for csv export
Separator=;
# Precision - round the prices
Precision=2
# which content classes should be exported?
# ExportClassArray[<classidentifier>]=<attributeidentifier>
ExportClassArray[]
ExportNodeID=2

# identifier of SKU field
SKUIdentifier=sku

# Line ending for export
LineEnding=\r\n

# Decimal point
DecimalPoint=,

*/ ?>