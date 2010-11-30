<?php

$Module = & $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = eZTemplate::factory();
$locale = eZLocale::instance();
$yearList = array();
$currentDate = new eZDate();
$currentYear = $currentDate->attribute( 'year' );
for ( $index = 0; $index < 5; $index ++ )
{
    $yearList[] = $currentYear - $index;
}

$monthList = array();
for ( $monthIndex = 1; $monthIndex <= 12; $monthIndex ++ )
{
    $monthList[] = array( 
        'value' => $monthIndex , 
        'name' => $locale->longMonthName( $monthIndex ) 
    );
}

$dayList = array();
for ( $dayIndex = 1; $dayIndex <= 31; $dayIndex ++ )
{
    $dayList[] = array( 
        'value' => $dayIndex , 
        'name' => $dayIndex 
    );
}

$http = eZHttpTool::instance();
if ( $http->hasPostVariable( "country" ) )
{
    $country = $http->postVariable( "country" );
}

if ( $http->hasPostVariable( "state" ) )
{
    $state = $http->postVariable( "state" );
}
if ( $http->hasPostVariable( "StartYear" ) )
{
    $startYear = $http->postVariable( "StartYear" );
}
if ( $http->hasPostVariable( "StartMonth" ) )
{
    $startMonth = $http->postVariable( "StartMonth" );
}
if ( $http->hasPostVariable( "StartDay" ) )
{
    $startDay = $http->postVariable( "StartDay" );
}

if ( $http->hasPostVariable( "StopYear" ) )
{
    $stopYear = $http->postVariable( "StopYear" );
}
if ( $http->hasPostVariable( "StopMonth" ) )
{
    $stopMonth = $http->postVariable( "StopMonth" );
}
if ( $http->hasPostVariable( "StopDay" ) )
{
    $stopDay = $http->postVariable( "StopDay" );
}

$currentDate = new eZDate();
if ( $startYear == '0' or empty( $startYear ) )
    $startYear = $currentDate->attribute( 'year' );
if ( $startMonth == '0' or empty( $startMonth ) )
    $startMonth = $currentDate->attribute( 'month' );
if ( $startDay == '0' or empty( $startDay ) )
    $startDay = 1;

if ( $stopYear == '0' or empty( $stopYear ) )
    $stopYear = $currentDate->attribute( 'year' );
if ( $stopMonth == '0' or empty( $stopMonth ) )
    $stopMonth = $currentDate->attribute( 'month' );
if ( $stopDay == '0' or empty( $stopDay ) )
    $stopDay = $currentDate->attribute( 'day' );

$start = array( 
    "day" => $startDay , 
    "month" => $startMonth , 
    "year" => $startYear , 
    "hour" => 0 , 
    "minute" => 0 , 
    "second" => 0 
);
$stop = array( 
    "day" => $stopDay , 
    "month" => $stopMonth , 
    "year" => $stopYear , 
    "hour" => 23 , 
    "minute" => 59 , 
    "second" => 59 
);

$startDate = mktime( $start["hour"], $start["minute"], $start["second"], $start["month"], $start["day"], $start["year"] );
$stopDate = mktime( $stop["hour"], $stop["minute"], $stop["second"], $stop["month"], $stop["day"], $stop["year"] );
$order_result["time"]["start_stamp"] = $startDate;
$order_result["time"]["start"] = $start;
$order_result["time"]["stop_stamp"] = $stopDate;
$order_result["time"]["stop"] = $stop;

$db = eZDB::instance();
/**
 * Exclude status from calculation
 */
$xINI = eZINI::instance( 'xrowecommerce.ini' );
$includeArray = $xINI->variable( 'Settings', 'StatusIncludeArray' );
$sql = "";
if ( count( $includeArray ) > 0 )
{
    $sql .= " AND ezorder.status_id IN ( " . implode( ",", $includeArray ) . " ) ";
}

$excludeArray = $xINI->variable( 'Settings', 'StatusExcludeArray' );
if ( count( $excludeArray ) > 0 )
{
    $sql .= " AND ezorder.status_id NOT IN ( " . implode( ",", $excludeArray ) . " ) ";
}
if ( $country )
{
    $sql .= " AND data_text_1 like '%<s_country>" . $country . "</s_country>%' ";
}
if ( $state )
{
    $sql .= " AND data_text_1 like '%<s_state>" . $state . "</s_state>%' ";
}

$orderArray = $db->arrayQuery( "SELECT created, id, order_nr, productcollection_id, data_text_1
FROM ezorder
WHERE ezorder.created >= '" . $startDate . "' AND ezorder.created < '" . $stopDate . "'
AND is_temporary = 0
AND ignore_vat = 0
$sql" );
// AND status_id = 2


$order_result["price"]["total_inc_price"] = 0;
$order_result["price"]["total_ex_price"] = 0;
$order_result["price"]["total_collection_ex_price"] = 0;
$order_result["price"]["total_collection_inc_price"] = 0;
$order_result["price"]["total_order_ex_price"] = 0;
$order_result["price"]["total_order_inc_price"] = 0;

foreach ( $orderArray as $item )
{
    $accountName = "";
    $xml = new SimpleXMLElement( $item["data_text_1"] );

    if ( $xml != null )
    {
        $shipping = (string) $xml->shipping;

        if ( (string) $xml->s_country )
        {

            $orderstate = (string) $xml->s_state;
            $ordercountry = (string) $xml->s_country;
        }
        else
        {
        	$orderstate = (string) $xml->state;
        	$ordercountry = (string) $xml->country;
        }
        
    }
/**
    $tmpcountry = xrowGeonames::getCountry( $country );
    $tmpstate = xrowGeonames::getSubdivisionName( $country, $state );
    if ( $tmpstate )
    {
    	$destination =  $tmpcountry['Name'] . " / " . $tmpstate ;
    }
    else
    {
    	$destination =  $tmpcountry['Name'] ;
    }
*/
    if ( $orderstate )
    {
        $destination =  $ordercountry . " / " . $orderstate ;
    }
    else
    {
        $destination =  $ordercountry;
    }
    unset( $ordercountry );
    unset( $orderstate );
    unset( $shipping );
    $productitemsArray = $db->arrayQuery( "SELECT e.vat_value, e.item_count, e.price
FROM ezproductcollection_item e
WHERE e.productcollection_id = '" . $item["productcollection_id"] . "';" );
    $orderitemsArray = $db->arrayQuery( "SELECT * FROM ezorder_item e
WHERE e.order_id = '" . $item["id"] . "';" );
    
    $order_item[$item["id"]] = array( 
        "order_items" => $orderitemsArray , 
        "collection" => $productitemsArray , 
        "destination" => $destination , 
        "order_nr" => $item["order_nr"] , 
        "created" => $item["created"] 
    );
    $order_item[$item["id"]]["collectionsum"]["price_inc_tax"] = 0;
    $order_item[$item["id"]]["collectionsum"]["price_ex_tax"] = 0;
    foreach ( $order_item[$item["id"]]["collection"] as $collect )
    {
        
        $itemextax = (double) $collect["item_count"] * (double) $collect["price"];
        $iteminctax = $itemextax;
        if ( $collect["vat_value"] != 0 )
        {
            $percentvalue = (double) $collect["vat_value"] / 100 + 1;
            $iteminctax = (double) $itemextax * $percentvalue;
        }
        $order_item[$item["id"]]["collectionsum"]["price_inc_tax"] = $order_item[$item["id"]]["collectionsum"]["price_inc_tax"] + $iteminctax;
        $order_item[$item["id"]]["collectionsum"]["price_ex_tax"] = $order_item[$item["id"]]["collectionsum"]["price_ex_tax"] + $itemextax;
    }
    $order_item[$item["id"]]["ordersum"]["price_ex_tax"] = 0;
    $order_item[$item["id"]]["ordersum"]["price_inc_tax"] = 0;
    
    $taxmap = xrowECommerceVATHandler::taxMapping();
    foreach ( $order_item[$item["id"]]["order_items"] as $orderitem )
    {
        if ( $orderitem["type"] == "shippingcostvat" or $orderitem["price"] == 0 or substr( $orderitem["description"], 0, 3 ) == "TAX" )
            continue;
        $orderitemextax = (double) $orderitem["price"];
        $orderiteminctax = $orderitemextax;
        $percentvalue = 0;
        
        if ( $orderitem["vat_value"] != 0 )
        {
            $percentvalue = (double) $orderitem["vat_value"] / 100 + 1;
            $orderiteminctax = (double) $orderitemextax * $percentvalue;
        }
        $order_item[$item["id"]]["ordersum"]["price_inc_tax"] = $order_item[$item["id"]]["ordersum"]["price_inc_tax"] + $orderiteminctax;
        $order_item[$item["id"]]["ordersum"]["price_ex_tax"] = $order_item[$item["id"]]["ordersum"]["price_ex_tax"] + $orderitemextax;
    }
    $order_item[$item["id"]]["total_ex_vat"] = $order_item[$item["id"]]["ordersum"]["price_ex_tax"] + $order_item[$item["id"]]["collectionsum"]["price_ex_tax"];
    $order_item[$item["id"]]["total_inc_vat"] = $order_item[$item["id"]]["ordersum"]["price_inc_tax"] + $order_item[$item["id"]]["collectionsum"]["price_inc_tax"];
    $difference = $order_item[$item["id"]]["total_inc_vat"] - $order_item[$item["id"]]["total_ex_vat"];
    $order_result_state = $order_item[$item["id"]]["state"];
    
    $order_result["price"][$order_result_state] = $order_result["price"][$order_result_state] + $difference;
    $order_result["price"]["total_inc_price"] = $order_result["price"]["total_inc_price"] + $order_item[$item["id"]]["total_inc_vat"];
    $order_result["price"]["total_ex_price"] = $order_result["price"]["total_ex_price"] + $order_item[$item["id"]]["total_ex_vat"];
    
    $order_result["price"]["total_collection_ex_price"] = $order_result["price"]["total_collection_ex_price"] + $order_item[$item["id"]]["collectionsum"]["price_ex_tax"];
    $order_result["price"]["total_collection_inc_price"] = $order_result["price"]["total_collection_inc_price"] + $order_item[$item["id"]]["collectionsum"]["price_inc_tax"];
    
    $order_result["price"]["total_order_ex_price"] = $order_result["price"]["total_order_ex_price"] + $order_item[$item["id"]]["ordersum"]["price_ex_tax"];
    $order_result["price"]["total_order_inc_price"] = $order_result["price"]["total_order_inc_price"] + $order_item[$item["id"]]["ordersum"]["price_inc_tax"];
    ;
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
$tpl->setVariable( "country", $country );
$tpl->setVariable( "countries", xrowGeonames::getCountries() );

$tpl->setVariable( "states", xrowGeonames::getSubdivisions( $country ) );
$tpl->setVariable( "state", $state );

$Result = array();
$Result['content'] = $tpl->fetch( "design:shop/taxes.tpl" );
$path = array();
$path[] = array( 
    'url' => '/orderedit/taxes' , 
    'text' => ezpI18n::tr( 'kernel/shop', 'Tax listing' ) 
);
$Result['path'] = $path;
?>