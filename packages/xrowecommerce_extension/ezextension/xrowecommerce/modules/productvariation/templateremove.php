<?php
/*
 * List the columns of the product variation datatype
 */



$Module = $Params['Module'];
$Module->setTitle( 'Product variation - column edit' );

$tpl = eZTemplate::factory();
$http = eZHTTPTool::instance();

$Result = array();
$Result['left_menu'] = "design:parts/shop/menu.tpl";
$Result['content'] = $tpl->fetch( "design:producvariation/columnlist.tpl" );
$Result['path'] = array( array( 'text' => ezpI18n::tr( 'extension/xrowecommerce/productvariation', 'Product variation columns' ),
                                'url' => false ) );
?>