<?php
require_once( "kernel/common/template.php" );

$module = $Params['Module'];

$http = eZHTTPTool::instance();

$tpl = templateInit();
if ( isset( $Params['UserParameters']['offset'] ) and is_numeric( $Params['UserParameters']['offset'] ) )
{
    $offset = (int)$Params['UserParameters']['offset'];
}
else 
{
    $offset = 0;
}
if ( isset( $Params['UserParameters']['limit'] ) and is_numeric( $Params['UserParameters']['limit'] ) )
{
    $limit = (int)$Params['UserParameters']['limit'];
}
else 
{
    $limit = 50;
}
$viewParameters = array( 'offset' => $offset, 'limit'  => $limit );

$db = eZDB::instance();
$list_count = $db->arrayQuery( "SELECT count( e1.id ) as counter FROM  ezcontentobject e1,ezcontentobject_attribute e WHERE data_type_string = 'xrowtin' AND e1.id = e.contentobject_id AND e1.current_version = e.version AND ( e.data_int = '1' OR ( e.data_int = '0' AND sort_key_string != '' ) )" );
$list_count = $list_count[0]['counter'];

$listtmp = $db->arrayQuery( "SELECT e1.id FROM  ezcontentobject e1,ezcontentobject_attribute e WHERE data_type_string = 'xrowtin' AND e1.id = e.contentobject_id AND e1.current_version = e.version AND ( e.data_int = '1' OR ( e.data_int = '0' AND sort_key_string != '' ) )", $viewParameters );

$records = array();
foreach ( $listtmp as $item )
{
	$list[] = eZContentObject::fetch( $item['id'] );
}
$tpl->setVariable( "list", $list );
$tpl->setVariable( "list_count", $list_count );
$tpl->setVariable( 'view_parameters', $viewParameters );
$tpl->setVariable( 'limit', $limit );
$Result = array();
$Result['path'] = array( array( 'text' => ezi18n( 'extension/xrowecommerce', 'Tax identification numbers' ),
                                'url' => false ) );

$Result['content'] = $tpl->fetch( "design:shop/tin.tpl" );
?>