<?php
require_once( "kernel/common/template.php" );

$module = $Params['Module'];

$http = eZHTTPTool::instance();

if ( $http->hasPostVariable( "Save" ) )
{
	$listedit = $http->postVariable( "ContentObject" );
    foreach ( $listedit as $id => $values )
    {
    	$obj = eZContentObject::fetch( $id );
    	if ( $obj instanceof eZContentObject )
    	{
    		$dm = $obj->dataMap();

    		if( $dm['tax_id'] and $dm['tax_id']->attribute( 'data_type_string' ) == xrowTINType::DATA_TYPE_STRING )
    		{
    			if( isset( $values['status'] ) )
    			{
                    $dm['tax_id']->setAttribute( 'data_int', (int)$values['status'] );
    			}
    			$dm['tax_id']->setAttribute( 'data_text', strtoupper( trim( $values['tax_id'] ) ) );
    			$dm['tax_id']->store();
    		}
    	}
    }
    eZContentObject::clearCache();
}

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
$list_count = $db->arrayQuery( "SELECT count( e.contentobject_id ) as counter FROM ezcontentobject_attribute e
RIGHT JOIN ezcontentclass_attribute ec ON ec.data_type_string = 'xrowtin' AND ec.id = e.contentclassattribute_id  AND ec.version = 0
RIGHT JOIN ezcontentobject co ON co.id = e.contentobject_id AND co.current_version = e.version
WHERE e.data_int = '1' OR ( e.data_int = '0' AND sort_key_string != '' )" );
$list_count = $list_count[0]['counter'];

$listtmp = $db->arrayQuery( "SELECT e.contentobject_id FROM ezcontentobject_attribute e
RIGHT JOIN ezcontentclass_attribute ec ON ec.data_type_string = 'xrowtin' AND ec.id = e.contentclassattribute_id  AND ec.version = 0
RIGHT JOIN ezcontentobject co ON co.id = e.contentobject_id AND co.current_version = e.version
WHERE e.data_int = '1' OR ( e.data_int = '0' AND sort_key_string != '' )", $viewParameters );

$records = array();
foreach ( $listtmp as $item )
{
	$list[] = eZContentObject::fetch( $item['contentobject_id'] );
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