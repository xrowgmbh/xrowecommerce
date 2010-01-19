<?php
//
//
// Created on: <01-Mar-2004 15:35:18 wy>
//
// ## BEGIN COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
// SOFTWARE NAME: eZ Publish
// SOFTWARE RELEASE: 4.1.x
// COPYRIGHT NOTICE: Copyright (C) 1999-2008 eZ Systems AS
// SOFTWARE LICENSE: GNU General Public License v2.0
// NOTICE: >
//   This program is free software; you can redistribute it and/or
//   modify it under the terms of version 2.0  of the GNU General
//   Public License as published by the Free Software Foundation.
//
//   This program is distributed in the hope that it will be useful,
//   but WITHOUT ANY WARRANTY; without even the implied warranty of
//   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//   GNU General Public License for more details.
//
//   You should have received a copy of version 2.0 of the GNU General
//   Public License along with this program; if not, write to the Free
//   Software Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
//   MA 02110-1301, USA.
//
//
// ## END COPYRIGHT, LICENSE AND WARRANTY NOTICE ##
//

/*! \file orderstatistics.php
*/


require_once( "kernel/common/template.php" );

$module = $Params['Module'];

$year = $Params['StartYear'];
$startMonth = $Params['StartMonth'];
$stopMonth = $Params['StopMonth'];
$startDay = $Params['StartDay'];
$stopDay = $Params['StopDay'];

$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( "StartYear" ) )
{
    $year = $http->postVariable( "StartYear" );
    if ( $year == '0' )
    {   $currentDate = new eZDate();
        $year = $currentDate->attribute( 'year' );
    }
}

if ( $http->hasPostVariable( "StartMonth" ) )
{
    $startMonth = $http->postVariable( "StartMonth" );
}

if ( $http->hasPostVariable( "StopMonth" ) )
{
    $stopMonth = $http->postVariable( "StopMonth" );
}

if ( $http->hasPostVariable( "StartDay" ) )
{
    $startDay = $http->postVariable( "StartDay" );
}

if ( $http->hasPostVariable( "StopDay" ) )
{
    $stopDay = $http->postVariable( "StopDay" );
}

if ( $http->hasPostVariable( "View" ) )
{
    $module->redirectTo( "/xrowecommerce/statistics/" . $year . '/' . $startMonth . '/' . $stopMonth . '/' . $startDay . '/' . $stopDay );
}

$statisticArray = xrowECommerce::orderStatistics( $year, $startMonth, $stopMonth, $startDay, $stopDay );
$yearList = array();
$currentDate = new eZDate();
$currentYear = $currentDate->attribute( 'year' );
for ( $index = 0; $index < 10; $index++ )
{
    $yearList[] = $currentYear - $index;
}

$locale = eZLocale::instance();
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

$tpl = templateInit();
$tpl->setVariable( "year", $year );
$tpl->setVariable( "startMonth", $startMonth );
$tpl->setVariable( "stopMonth", $stopMonth );
$tpl->setVariable( "year_list", $yearList );
$tpl->setVariable( "month_list", $monthList );
$tpl->setVariable( "day_list", $dayList );
$tpl->setVariable( "startDay", $startDay );
$tpl->setVariable( "stopDay", $stopDay );
$tpl->setVariable( "statistic_result", $statisticArray );

$path = array();
$path[] = array( 'text' => ezi18n( 'kernel/shop', 'Statistics' ),
                 'url' => false );

$Result = array();
$Result['path'] = array( array( 'text' => ezi18n( 'kernel/shop', 'Statistics' ),
                                'url' => false ) );

$Result['content'] = $tpl->fetch( "design:xrowecommerce/orderstatistics.tpl" );

?>
