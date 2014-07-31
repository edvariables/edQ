<?php
$sql = "SELECT * 
	FROM contact
	LIMIT 20";
$db = get_db('/edQ/_System/dataSource');

$arguments = array(
	'rows' => $db->all( $sql )
	, 'columns' => array(
		'*' => true
		, 'Enabled' => array(
			'type' => 'boolean'
		)
	)
);
page::call('/edQ/_Exemples/html/table/rows', $arguments);
?>