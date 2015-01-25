<?php
$node = node($node, __FILE__);

$sql = 'SELECT "datepiece", "id", "nom2", "netht" + "nettva" AS "Montant"
FROM "public"."gfactu00002" AS "gfactu00002"
ORDER BY datepiece DESC
LIMIT 50';

$rows = node('..postgresql', $node, 'call', array(
	'sql' => $sql
));


node('/_html/table/rows/dataTable', $node, 'call', array('rows' => $rows));

?>