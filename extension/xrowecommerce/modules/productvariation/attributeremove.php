<?php
/*
 * List the columns of the product variation datatype
 */

require_once( "kernel/common/template.php" );

$Module = $Params['Module'];
$Module->setTitle( 'Product variation - column edit' );

$tpl = templateInit();
$http = eZHTTPTool::instance();

$Result = array();
$Result['left_menu'] = "design:parts/shop/menu.tpl";
$Result['content'] = $tpl->fetch( "design:producvariation/columnlist.tpl" );
$Result['path'] = array( array( 'text' => ezi18n( 'extension/xrowecommerce/productvariation', 'Product variation columns' ),
                                'url' => false ) );
?>