<?php
$node = node($node, __FILE__);

$sql = 'SELECT * FROM "public"."ccompt00002" AS "ccompt00002"';

$rowsCog = node('..Cogilog/postgresql', $node, 'call', array(
	'sql' => $sql
));
$sources = array();
foreach($rowsCog as $row)
	$sources[$row['code']] = $row;

$sqlInsert = 'INSERT INTO vtiger_glacct (glacct, presence)';
$insertParams = array();

$db = get_db();
$rowsLamat = $db->all("SELECT `glacctid` AS Id, `glacct` AS Code FROM vtiger_glacct");
foreach($rowsLamat as $row)
	if(!isset($sources[$row['Code']])){
		if(!$insertParams)
			$sqlInsert .= ' VALUES(?, 1)';
		else
			$sqlInsert .= ', (?, 1)';
		$insertParams[] = $row['Code'];
	}
if($insertParams){
$result = $db->query($sqlInsert, $insertParams);

var_dump($result, $insertParams);
}
else {
	echo 'Aucun compte à importer';
}
//node('/_html/table/rows/dataTable', $node, 'call', array('rows' => $rowsLamat));

?>