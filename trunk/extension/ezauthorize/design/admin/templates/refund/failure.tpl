{*
 eZ publish + Authorize.net - Order Refund


<h1>Congratulations</h1>
<p>Order {$order_id} successfully refunded! Return to <a href="/shop/orderlist/">order list</a></p> 
*}
{*
 Consider redirect to order list
*}

<h1>Sorry..</h1>

<p>Your order could not be refunded successfully at this time.</p>

<p>{$friendly_error_message}</p>

<p>{$actual_error_message}. {$error_code}</p>

<p>You can review the <a href="/shop/orderview/{$order_id}">order details</a> or <a href="/shop/orderlist">order list</a></p>
