<h3>Revues</h3>
<pre>Nombre d'adresses par revue
<?php
$sql = "
SELECT critere AS `Revue`
, COUNT(*) AS Nbre
, COUNT(a.reffiche) AS Abonnés
, COUNT(*) - COUNT(a.reffiche) AS `Non abonnés`
FROM critere_adr ca
LEFT JOIN adresse a
	ON ca.reffiche = a.reffiche
	AND ca.dateapplication BETWEEN a.date_abn_rezo AND a.date_finabn_rezo
WHERE ca.critere LIKE 'REVUE\___'
GROUP BY critere
ORDER BY `Revue`
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
		padding: 0 4px;
		text-align: right;
	}
	#<?=$uid?> td:nth-child(1){
		text-align: left;
	}
</style>