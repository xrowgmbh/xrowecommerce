<?php

include_once( 'kernel/common/template.php' );


$Module  =& $Params['Module'];
$http = eZHTTPTool::instance();
$tpl = templateInit();

$db = eZDB::instance();
$query = 'SELECT e.contentobject_id as contentobject_id, e1.name, count(e1.id) as counter  FROM alcone.ezcontentobject_attribute e, alcone.ezcontentobject e1
WHERE  ( ( e.contentclassattribute_id = 216  AND ( e.data_text REGEXP \'weight="0"\' OR e.data_text NOT REGEXP \'<option(.*)weight(.*)</option>\'  ) )
or ( e.contentclassattribute_id = 246  and e.data_float = 0 ) )
AND e1.current_version = e.version AND e1.id =e.contentobject_id AND e1.status = 1 GROUP BY contentobject_id having counter = 2 ORDER BY e1.name;
';
$result = $db->arrayQuery( $query );

$tpl->setVariable( 'products', $result );
    
$Result = array();
$Result['content'] =& $tpl->fetch( "design:shop/productedit.tpl" );
$path = array();
$path[] = array( 'url' => '/orderedit/product',
                 'text' => ezi18n( 'kernel/shop', 'Zero weight prods' ) );
$Result['path'] = $path;
?>