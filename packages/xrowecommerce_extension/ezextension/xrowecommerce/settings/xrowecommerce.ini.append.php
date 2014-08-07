<?php /* #?ini charset="utf-8"?

[Fields]
company_name[required]=false
company_name[enabled]=true
s_company_name[required]=false
s_company_name[enabled]=true
company_additional[required]=false
company_additional[enabled]=true
s_company_additional[required]=false
s_company_additional[enabled]=true
tax_id[required]=true
tax_id[enabled]=true
title[required]=false
title[enabled]=false
s_title[required]=false
s_title[enabled]=false
first_name[required]=true
first_name[enabled]=true
s_first_name[required]=true
s_first_name[enabled]=true
last_name[required]=true
last_name[enabled]=true
s_last_name[required]=true
s_last_name[enabled]=true
mi[enabled]=false
s_mi[enabled]=false
address1[required]=true
address1[enabled]=true
s_address1[required]=true
s_address1[enabled]=true
address2[required]=false
address2[enabled]=true
s_address2[required]=false
s_address2[enabled]=true
zip[required]=true
zip[enabled]=true
s_zip[required]=true
s_zip[enabled]=true
city[enabled]=true
city[required]=true
s_city[required]=true
s_city[enabled]=true
country[required]=true
country[enabled]=true
s_country[required]=true
s_country[enabled]=true
state[required]=true
state[enabled]=true
s_state[required]=true
s_state[enabled]=true
phone[required]=true
phone[enabled]=true
s_phone[required]=true
s_phone[enabled]=true
fax[required]=false
fax[enabled]=false
s_fax[required]=false
s_fax[enabled]=false
email[required]=true
email[enabled]=true
email_confirm[enabled]=false
password[required]=true
password[enabled]=true
s_email[required]=true
s_email[enabled]=true
s_email_confirm[enabled]=false
NoPartialDelivery[enabled]=true
Reference[enabled]=true
Message[enabled]=true
Captcha[enabled]=true
Coupon[enabled]=true
#Newsletter software required
Newsletter[enabled]=false

[Settings]
ShowColumnPosition=enabled
ShowColumnTax=enabled
ShowColumnRemove=enabled
Catalogueorder=disabled
ConditionsOfService=enabled
CountryWithStatesList[]
#CountryWithStatesList[]=USA
#CountryWithStatesList[]=MEX
#CountryWithStatesList[]=CAN
ShopUserClassList[]=client
# Please use an override instead

#use Alpha3 or Alpha2 for country datatype
UseAlpha3=true

# If an order has this IDs it will be shown in the order statistic
# If empty, this setting will be ignored
StatusIncludeArray[]

# If an order has this IDs it will not be shown in the order statistic
# If empty, this setting will be ignored
StatusExcludeArray[]

# Set the display of the price in the templates xrow_product_....tpl, related_products.tpl, product_list.tpl and aftersale.tpl
# price = like in the field in backend
# ex_vat_price = without VAT
# inc_vat_price = with VAT
# discount_price_ex_vat = without VAT with discount
# discount_price_inc_vat = with VAT and discount
ShowPriceAs=discount_price_inc_vat

# Encrypt and decrypt secure data e.g. credit card data
# please configurate, if you like, all settings in the section EncryptionSettings
#[EncryptionSettings]
#Key=YOURSECRETKEYFORENCRYPTANDDECRYPT
Algorithm=tripledes
Mode=cfb
ForceUserRegBeforeCheckout=false

[ShippingSettings]
#fields can be disabled / enabled in the shipping information
DisplayFax=enabled

#ShippingGateways=

[ShippingInterfaceSettings]
#ShippingInterface=xrowDefaultShipping

[MerchantLocations]
# Use ISO 3 letter country locales
#Location[]
#Location[]=USA
#Location[]=DEU
#USA[]=CT
#USA[]=NY


[TaxSettings]
CountryIndentifier=country
CompanyNameIndentifier=company_name

[MailSettings]
#define the receiver of the order confirmation mail
Email=
#Define if mail should be send out on checkout
SendOrderEmail=enabled
HTMLEmail=disabled
#Feedback mail will be sent x Days after the first order
FirstOrderDelay=90

# one ore more bcc receiver of the order confirmation
EmailBCCReceiver[]

# Reply To Address
ReplyToMail=

[BasketInformation]
DisplayTax=disabled
DisplayLogin=enabled
DisplayShipping=enabled
DisplayPaymentmethod=enabled
HazardousItems=disabled

#[InvoiceSettings]
#ShowFooter=enabled
#CompanyName=Example Inc
#CompanyAddress[]
#CompanyWebsite=http://www.example.com/
#CompanyPhone=+1 555 123456
#CompanyEmail=service@example.com

[ShopAccountHandlerDefaults]
# set the country code to which the country select defaults to
#DefaultCountryCode=USA

[EPaymentSettings]
# Payments should be capture right away or later
# values are AUTH_ONLY or AUTH_AND_CAPTURE
PaymentRequestType=AUTH_AND_CAPTURE
# Wheater the gateways should store or not store payment information in the order
StorePaymentInformation=enabled
#List of active creditcards
ActiveCreditcards[]
ActiveCreditcards[2]=Visa
ActiveCreditcards[1]=MasterCard
ActiveCreditcards[4]=American Express
ActiveCreditcards[3]=Discover

[StatusSettings]
# Show payment status
ShowPaymentStatus=enabled

# Relate a Status to a type of xrowOrderStatusDefault
# StatusTypeList[3]=xrowOrderStatusPaid
# StatusTypeList[1000]=xrowOrderStatusCancel
StatusTypeList[]
# Define which status can`t we switched to
#
# StatusDisallowList-1000[]
# StatusDisallowList-1000[]=1
# StatusDisallowList-1000[]=2
#

[GoogleExportSettings]
# Activate all Marketplaces you want to export
# ActivePlugins[]=xrowGoogleExport
ActivePlugins[]
ActivePlugins[]=xrowGoogleExport
# GoogleFileName=google_ger_DE.xml
GoogleFileName=
GoogleFTPUsername=
GoogleFTPPassword=

# Class identifier of the product class that should be exported
ClassIdentifier=xrow_product

# e.g.
# ExportPriceLanguage=USD
# or
# ExportPriceLanguage=GBR
# if ExportPriceLanguage is emtpy, it will be set to EUR
ExportPriceLanguage=

# Which fields to export
# you can only take this fields
# name, link, id, image_link, manufacturer, description, price, options
# you can rename it, example: ExportFieldsArray[description]=short_description
# or add a public variable in file extension\xrowecommerce\classes\export\structs\xrowexportproduct.php
# ExportFieldsArray[]
# ExportFieldsArray[name]=name
# ExportFieldsArray[link]=link
# ExportFieldsArray[id]=product_id
# ExportFieldsArray[description]=description
# ExportFieldsArray[image_link]=image
# ExportFieldsArray[manufacturer]=manufacturer
# ExportFieldsArray[price]=price

# only for xrowproductvariation
# set a general vat here
# VAT=1.19

# only for xrowproductvariation
ExportVariationFieldArray[]
#ExportVariationFieldArray[height]=height
#ExportVariationFieldArray[length]=length
#ExportVariationFieldArray[depth]=depth

# Name of the description field
DescField=description

# export these attributes
#GoogleAttributes[]
#GoogleAttributes[id]=id
#GoogleAttributes[height]=height
#GoogleAttributes[length]=length
#GoogleAttributes[depth]=depth
#GoogleAttributes[price]=price
#GoogleAttributes[image_url]=image_url

#host for links
#BaseURL=www.example.com

#extended links with this siteaccess
#ExportSiteaccess=/eng
#input of product_type attribute
GoogleProductType=new

# Export to var/cache/googleexport directory?
ExportAsFile=false

# Export to ftp
ExportToFTP=true

[SocialNetwork]
socialplugins=disabled
plugins[facebook]=enabled
plugins[twitter]=enabled
account[twitter]=

*/ ?>