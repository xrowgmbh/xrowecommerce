[eZAuthorizeSettings]
# Extension Debug
Debug=false

# Better Security avialable from curl version > 7.1
# Respose gets verified
SSLVerify=true

# (Required) Authorize.Net Merchant Login User Name
MerchantLogin=false

# (Required) Authorize.Net Transaction Key
TransactionKey=false

# Enable or Disable Authorize.Net Test Mode
# TestMode disables charing credit card

TestMode=true

## Optional

# Provide Customer CVV2 Code Input Verification
CustomerCVV2Check=false

# Perform Authorize.Net Hash Verification

MD5HashVerification=true
MD5HashSecretWord=publish

# Send Customer Authorize.Net Payment Confirmation Email
CustomerConfirmationEmail=false

# Stock eZ publish provides customer information in order,
# if enabled this sends the address information to Authorize.Net

GetOrderCustomerInformation=true
CustomerAddressVerification=true
SendCustomerShippingAddress=true

# Currency Code of Payment
# Defaults to 'US Dollar/USD'
# See AIM_Guide.pdf Appendix I for a complete list

# CurrencyCode=USD

# The eZAuthorize extension can optionally set status codes on the 
# order to record the interaction with the payment gateway in the 
# order's status history. 
# The Start Status Code will be set just before connecting to 
# Authorize.net, and the Success and Failure Codes will be 
# set depending on the provided response. 

# To use these, create new Order Statuses using the Order Status
# Link in the Webshop tab of the eZ Publish Admin

# StartStatusCode=1000
# SuccessStatusCode=1001
# FailStatusCode=1002


OrderStatusRefundCode=1000
OrderStatusAssignment=true

# Custom Shop Account Handeler
CustomShopAccountHandeler=true

# Custom Shop Account Handeler Variable Names
ShopAccountHandelerFirstName=first-name
ShopAccountHandelerLastName=last-name
ShopAccountHandelerEmail=email
ShopAccountHandelerStreet1=address1
ShopAccountHandelerStreet2=address2
ShopAccountHandelerZip=zip
ShopAccountHandelerFirstPlace=city
ShopAccountHandelerFirstState=state
ShopAccountHandelerAddressPhone=phone

# ShopAccountHandelerCountry=country
# ShopAccountHandelerComment=comment

# Shop Admin Email Address
ShopAdminEmailAddress=info@example.com

# Display Link from eZ publish admin to display link to transaction information on authorize.net merchant pannel
ShowAuthorizeDotNetMerchantTransactionDetailHTTPLink=true

# Display Refund Order Button from eZ publish admin
ShowAuthorizeDotNetRefundButton=true

# Store Transaction Information
StoreTransactionInformation=true

# Repost or 'carry' variables on error + input display.
RepostVariablesOnError=true

# Display Payment Help
DisplayHelp=false
