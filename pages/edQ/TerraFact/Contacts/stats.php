<?php
$sql = "SELECT IdContact, CliType
	, SUBSTRING(ZipCode, 1, 2) AS Departement
	FROM contact
	JOIN client
		ON client.IdCLient = contact.IdContact
	WHERE IdContactRef IS NULL OR IdContactRef = 0
	LIMIT 9999";
$db = get_db();

$arguments = array(
	'rows' => $db->all( $sql )
	, 'pivot' => array(
		//groupSummaryPos => 'footer',
		rowTotals => true,
		colTotals => true,
	)
	, 'grid' => array(
		rowNum => 9999,
	)
	, 'columns' => array(
		'IdContact' => array(
			'label' => 'contacts',
			'group' => false,
			'count' => true,
		),
		'CliType' => array(
			'label' => 'Type',
			'group' => 'x',
		),
		'Departement' => array(
			'label' => 'Département',
			'group' => 'x',
		),
	)
);
page::call('/_html/table/rows/jqGrid', $arguments);
?>