<?php

include_once( 'kernel/common/template.php' );
include_once( 'kernel/classes/eznodeviewfunctions.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );
include_once( 'lib/ezutils/classes/ezhttptool.php' );

$Module  =& $Params['Module'];
$http =& eZHTTPTool::instance();
$tpl =& templateInit();

$db = eZDB::instance();
$query = '
SELECT e.contentobject_id, e1.name, e.data_text FROM alcone.ezcontentobject_attribute e, alcone.ezcontentobject e1
WHERE  e1.current_version = e.version AND e1.id =e.contentobject_id AND e1.status = 1 AND e1.contentclass_id = 16 AND contentclassattribute_id = 216 GROUP BY contentobject_id;
';
$result = $db->arrayQuery( $query );

$products_all = eZContentObject::fetchList(true, array('contentclass_id' => 16, 'status' => EZ_CONTENT_OBJECT_STATUS_PUBLISHED), flase, false);

foreach ( $products_all as $prod )
{
    $prod->DataMap();
}
$tpl->setVariable( 'products', $result );
    
$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/inventory.tpl" );
$path = array();
$path[] = array( 'url' => '/orderedit/product',
                 'text' => ezi18n( 'kernel/shop', 'Zero weight prods' ) );
$Result['path'] = $path;
?>