<h3>Adresses marquées comme abonnées à la revue</h3>
<pre>Abonnés par durée d'abonnement
<?php
$sql = "
SELECT DATEDIFF(date_finabn_rezo, date_abn_rezo) DIV 360 AS `Années`
, DATEDIFF(date_finabn_rezo, date_abn_rezo) DIV 30 AS `Mois`
, COUNT(*) AS Nbre
, NULL AS NbreTotal
FROM adresse a
WHERE  a.date_abn_rezo > '1980-01-01'
AND (DATEDIFF(date_finabn_rezo, date_abn_rezo) DIV 30) BETWEEN 1 AND 18
GROUP BY DATEDIFF(date_finabn_rezo, date_abn_rezo) DIV 360
, DATEDIFF(date_finabn_rezo, date_abn_rezo) DIV 30

UNION

SELECT DATEDIFF(date_finabn_rezo, date_abn_rezo) DIV 360
, NULL
, NULL
, COUNT(*)
FROM adresse a
WHERE  a.date_abn_rezo > '1980-01-01'
AND DATEDIFF(date_finabn_rezo, date_abn_rezo) > 0
GROUP BY DATEDIFF(date_finabn_rezo, date_abn_rezo) DIV 360

ORDER BY `Années`, `Mois`
";

$db = get_db();

page::call('/_format/csv/download link', array(
	'csv--node' => $node['id'],
	'csv--file' => $node['nm'],
));?>
<br>
<?php
$uid = uniqid('table');
$args = array( 'rows' => $db->all( $sql ), 'uid' => $uid );
node('/_html/table/rows', $node, 'call', $args);
?>
</pre>
<style>
	#<?=$uid?> td{
		text-align: right;
		padding: 1px 6px;
	}
</style>