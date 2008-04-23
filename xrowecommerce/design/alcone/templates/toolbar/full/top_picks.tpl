{def $no=fetch('content', 'node', hash( node_id, 3575, 'sort_by', array( 'priority', true() ) ) ) }
{* Node 
{def $no=fetch('content', 'node', hash( node_id, 3575 ) ) }
{def $no=fetch('content', 'node', hash( node_id, 3575, 'sort_by', array( 'priority', true() ) ) ) }
{$no|attribute(show,1)}
{$no.data_map.description.content.output.output_text}
{$no.data_map|attribute(show,1)}
*}
{$no.data_map.intro.content.output.output_text}
