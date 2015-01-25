<?php
$sql = "SELECT id, typ 
	FROM tree_data
	LIMIT 999";
$db = get_db('/_System/dataSource');

$arguments = array(
	'rows' => $db->all( $sql )
	, 'pivot' => true
	, 'columns' => array(
		'id' => array(
			'label' => 'noeuds',
			'group' => false,
			'count' => true,
		),
		'typ' => array(
			'label' => 'Type',
			'group' => 'x',
		)
	)
);
page::call('/_html/table/rows/jqGrid', $arguments);
?>