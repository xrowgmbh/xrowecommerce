<?php /* #?ini charset="utf8"?
[Settings]
# appends the invoice id with a space infront
# The total can`t be longer as 27 chars
# alllowed only a-zA-Z0-9._-
BookingString=INVOICE
# Is used instead of  BookingString is order isn`t active yet
TransactionString=ORDERID

[ServerSettings]
ServerRequestLink=https://coposweb.companydirect.de/posh/cmd/posh/tpl/txn_result.tpl
Username=test
Password=123456
# timeout in seconds
RequestTimeout=30

TestMode=enabled
*/ ?>