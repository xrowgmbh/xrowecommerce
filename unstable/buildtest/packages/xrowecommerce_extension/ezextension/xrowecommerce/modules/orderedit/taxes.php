<?php

require_once( 'kernel/common/template.php' );

$Module  =& $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = templateInit();
$locale = eZLocale::instance();
$yearList = array();
$currentDate = new eZDate();
$currentYear = $currentDate->attribute( 'year' );
for ( $index = 0; $index < 5; $index++ )
{
    $yearList[] = $currentYear - $index;
}

$monthList = array();
for ( $monthIndex = 1; $monthIndex <= 12; $monthIndex++ )
{
    $monthList[] = array( 'value' => $monthIndex, 'name' => $locale->longMonthName( $monthIndex ) );
}

$dayList = array();
for ( $dayIndex = 1; $dayIndex <= 31; $dayIndex++ )
{
    $dayList[] = array( 'value' => $dayIndex, 'name' => $dayIndex );
}

$http = eZHttpTool::instance();
if ( $http->hasPostVariable( "StartYear" ) ) { $startYear = $http->postVariable( "StartYear" ); }
if ( $http->hasPostVariable( "StartMonth" ) ) { $startMonth = $http->postVariable( "StartMonth" ); }
if ( $http->hasPostVariable( "StartDay" ) ) { $startDay = $http->postVariable( "StartDay" ); }

if ( $http->hasPostVariable( "StopYear" ) ) { $stopYear = $http->postVariable( "StopYear" ); }
if ( $http->hasPostVariable( "StopMonth" ) ) { $stopMonth = $http->postVariable( "StopMonth" ); }
if ( $http->hasPostVariable( "StopDay" ) ) { $stopDay = $http->postVariable( "StopDay" ); }

$currentDate = new eZDate();
if ( $startYear == '0' or empty($startYear))
    $startYear = $currentDate->attribute( 'year' );
if ( $startMonth == '0' or empty($startMonth))
    $startMonth = $currentDate->attribute( 'month' );
if ( $startDay == '0' or empty($startDay))
    $startDay = 1;
    #$startDay = $currentDate->attribute( 'Day' );

if ( $stopYear == '0' or empty($stopYear))
    $stopYear = $currentDate->attribute( 'year' );
if ( $stopMonth == '0' or empty($stopMonth))
    $stopMonth = $currentDate->attribute( 'month' );
if ( $stopDay == '0' or empty($stopDay))
    $stopDay = $currentDate->attribute( 'day' );


$start = array( "day" => $startDay, "month" => $startMonth, "year" => $startYear, "hour" => 0, "minute" => 0, "second" => 0 );
$stop =  array( "day" => $stopDay,  "month" => $stopMonth,  "year" => $stopYear,  "hour" => 23, "minute" => 59, "second" => 59 );

$startDate = mktime( $start["hour"], $start["minute"], $start["second"], $start["month"], $start["day"], $start["year"] );
$stopDate  = mktime( $stop["hour"],  $stop["minute"],  $stop["second"],  $stop["month"],  $stop["day"],  $stop["year"] );
$order_result["time"]["start_stamp"] = $startDate;
$order_result["time"]["start"] = $start;
$order_result["time"]["stop_stamp"] = $stopDate;
$order_result["time"]["stop"] = $stop;

/**
  * Exclude status from calculation
  */
$xINI = eZINI::instance( 'xrowecommerce.ini' );
$includeArray = $xINI->variable( 'Settings', 'StatusIncludeArray' );
$incSql = "";
if ( count( $includeArray ) > 0 )
{
	$incSql = " AND ezorder.status_id IN ( " . implode( ",", $includeArray ) . " ) ";
}

$excludeArray = $xINI->variable( 'Settings', 'StatusExcludeArray' );
$exSql = "";
if ( count( $excludeArray ) > 0 )
{
    $exSql = " AND ezorder.status_id NOT IN ( " . implode( ",", $excludeArray ) . " ) ";
}


$db = eZDB::instance();
$orderArray = $db->arrayQuery(
"SELECT created, id, order_nr, productcollection_id, data_text_1
FROM ezorder
WHERE ezorder.created >= '".$startDate."' AND ezorder.created < '".$stopDate."'
AND is_temporary = 0
AND ignore_vat = 0
$incSql
$exSql" );
// AND status_id = 2

    $order_result["price"]["NY"] = 0;
    $order_result["price"]["CT"] = 0;
    $order_result["price"]["other"] = 0;
    $order_result["price"]["total_inc_price"] = 0;
    $order_result["price"]["total_ex_price"] = 0;
    $order_result["price"]["total_collection_ex_price"] = 0;
    $order_result["price"]["total_collection_inc_price"] = 0;
    $order_result["price"]["total_order_ex_price"] = 0;
    $order_result["price"]["total_order_inc_price"]= 0;

foreach ( $orderArray as $item )
{
        $accountName = "";
        $xml = new eZXML();
        $xmlDoc =& $item["data_text_1"];
        if( $xmlDoc != null )
        {
            $dom =& $xml->domTree( $xmlDoc );
            $shipping = $dom->elementsByName( "shipping" );
            if ( count($shipping) > 0 && is_object($shipping[0]))
                $shipping = $shipping[0]->textContent();
            else $shipping = 0;
            $country = $dom->elementsByName( "country" );
            if ( count( $country ) > 0 && is_object( $country[0] ) )
                $country = $country[0]->textContent();
            $state = $dom->elementsByName( "state" );
            if ( count( $state ) > 0 && is_object( $state[0] ) )
                $state = $state[0]->textContent();
            $s_country = $dom->elementsByName( "s_country" );
            if ( count( $s_country ) > 0 && is_object( $s_country[0] ) )
                $s_country = $s_country[0]->textContent();
            $s_state = $dom->elementsByName( "s_state" );
            if ( count( $s_state ) > 0 && is_object($s_state[0]) )
                $s_state = $s_state[0]->textContent();
        }
        $order_state = "other";
        $order_nr = $item["order_nr"];
        $order_created = $item["created"];
        if ( $shipping == "1" and !empty($country) AND $country == "USA" AND !empty($state) )
        {
            if ( $state == "NY" )
                $order_state = $state;
            elseif ( $state == "CT" )
                $order_state = $state;
            else $order_state = "other";
        }
        elseif ( $shipping == "0" and !empty($s_country) AND $s_country == "USA" AND !empty($s_state) )
        {
            if ( $s_state == "NY" )
                $order_state = $s_state;
            elseif ( $s_state == "CT" )
                $order_state = $s_state;
            else $order_state = "other";
        }
        unset($s_state );
        unset($s_country);
        unset($country);
        unset($state);
        unset($shipping);
        $productitemsArray = $db->arrayQuery(
"SELECT e.vat_value, e.item_count, e.price
FROM ezproductcollection_item e
WHERE e.productcollection_id = '".$item["productcollection_id"]."';" );
$orderitemsArray = $db->arrayQuery(
"SELECT * FROM ezorder_item e
WHERE e.order_id = '".$item["id"]."';" );


    $order_item[$item["id"]] = array ( "order_items" => $orderitemsArray, "collection" => $productitemsArray , "state" => $order_state, "order_nr" => $order_nr, "created" => $order_created );
    $order_item[$item["id"]]["collectionsum"]["price_inc_tax"] = 0;
    $order_item[$item["id"]]["collectionsum"]["price_ex_tax"]  = 0;
    foreach ( $order_item[$item["id"]]["collection"] as $collect )
    {
        // $collect["item_count"]
        // $collect["price"]
        // $collect["vat_value"]
        $itemextax = (double)$collect["item_count"] * (double)$collect["price"];
        $iteminctax = $itemextax;
        if ( $collect["vat_value"] != 0)
        {
            $percentvalue = (double)$collect["vat_value"] / 100 + 1;
            $iteminctax = (double)$itemextax * $percentvalue;
        }
        $order_item[$item["id"]]["collectionsum"]["price_inc_tax"] = $order_item[$item["id"]]["collectionsum"]["price_inc_tax"] + $iteminctax;
        $order_item[$item["id"]]["collectionsum"]["price_ex_tax"] = $order_item[$item["id"]]["collectionsum"]["price_ex_tax"] + $itemextax;
    }
    $order_item[$item["id"]]["ordersum"]["price_ex_tax"] = 0;
    $order_item[$item["id"]]["ordersum"]["price_inc_tax"] = 0;


    foreach ( $order_item[$item["id"]]["order_items"] as $orderitem )
    {
        // $collect["type"]
        // $collect["price"]
        // $collect["vat_value"]
        if ( $orderitem["type"]  == "shippingcostvat" OR $orderitem["price"] == 0 OR substr($orderitem["description"],0,3) == "TAX" )
            continue;
        $orderitemextax = (double)$orderitem["price"];
        $orderiteminctax = $orderitemextax;
        $percentvalue = 0;
        if ( $order_item[$item["id"]]["state"] != "other" OR $order_item[$item["id"]]["state"] == "CT" )
        {
            if ( $order_item[$item["id"]]["state"] == "NY")
                $percentvalue = 1.0825;
                $orderiteminctax = (double)$orderitemextax * $percentvalue;

            if ( $order_item[$item["id"]]["state"] == "CT" )
                $percentvalue = 1.06;
                $orderiteminctax = (double)$orderitemextax * $percentvalue;

        }
        if ( $orderitem["vat_value"] != 0)
        {
            $percentvalue = (double)$orderitem["vat_value"] / 100 + 1;
            $orderiteminctax = (double)$orderitemextax * $percentvalue;
        }
        $order_item[$item["id"]]["ordersum"]["price_inc_tax"] = $order_item[$item["id"]]["ordersum"]["price_inc_tax"] + $orderiteminctax;
        $order_item[$item["id"]]["ordersum"]["price_ex_tax"]  = $order_item[$item["id"]]["ordersum"]["price_ex_tax"]  + $orderitemextax;
    }
    $order_item[$item["id"]]["total_ex_vat"] = $order_item[$item["id"]]["ordersum"]["price_ex_tax"] + $order_item[$item["id"]]["collectionsum"]["price_ex_tax"];
    $order_item[$item["id"]]["total_inc_vat"] = $order_item[$item["id"]]["ordersum"]["price_inc_tax"] + $order_item[$item["id"]]["collectionsum"]["price_inc_tax"];
    $difference = $order_item[$item["id"]]["total_inc_vat"] - $order_item[$item["id"]]["total_ex_vat"];
    $order_result_state = $order_item[$item["id"]]["state"];

    $order_result["price"][$order_result_state] = $order_result["price"][$order_result_state] + $difference;
    $order_result["price"]["total_inc_price"] = $order_result["price"]["total_inc_price"] + $order_item[$item["id"]]["total_inc_vat"];
    $order_result["price"]["total_ex_price"] = $order_result["price"]["total_ex_price"] + $order_item[$item["id"]]["total_ex_vat"];

    $order_result["price"]["total_collection_ex_price"]  = $order_result["price"]["total_collection_ex_price"]  + $order_item[$item["id"]]["collectionsum"]["price_ex_tax"];
    $order_result["price"]["total_collection_inc_price"] = $order_result["price"]["total_collection_inc_price"] + $order_item[$item["id"]]["collectionsum"]["price_inc_tax"];

    $order_result["price"]["total_order_ex_price"]       = $order_result["price"]["total_order_ex_price"]  + $order_item[$item["id"]]["ordersum"]["price_ex_tax"];
    $order_result["price"]["total_order_inc_price"]      = $order_result["price"]["total_order_inc_price"] + $order_item[$item["id"]]["ordersum"]["price_inc_tax"];;
}

$tpl->setVariable( 'order_array', $order_item );
$tpl->setVariable( 'order_result', $order_result );
$tpl->setVariable( "startYear", $startYear );
$tpl->setVariable( "stopYear", $stopYear );
$tpl->setVariable( "startMonth", $startMonth );
$tpl->setVariable( "stopMonth", $stopMonth );
$tpl->setVariable( "year_list", $yearList );
$tpl->setVariable( "month_list", $monthList );
$tpl->setVariable( "day_list", $dayList );
$tpl->setVariable( "startDay", $startDay );
$tpl->setVariable( "stopDay", $stopDay );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/taxes.tpl" );
$path = array();
$path[] = array( 'url' => '/orderedit/taxes',
                 'text' => ezi18n( 'kernel/shop', 'Tax listing' ) );
$Result['path'] = $path;
?>