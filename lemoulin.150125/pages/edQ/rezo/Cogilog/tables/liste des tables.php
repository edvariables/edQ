<?php
$node = node($node, __FILE__);

$sql = 'select table_name from information_schema.tables WHERE table_schema=\'public\'';

$rows = node('..postgresql', $node, 'call', array(
	'sql' => $sql
));


node('/_html/table/rows/dataTable', $node, 'call', array('rows' => $rows));

?>