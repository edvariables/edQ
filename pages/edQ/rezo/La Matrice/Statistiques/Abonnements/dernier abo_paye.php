<h3>Abonnements payés</h3>
<pre>Derniers abonnements payés par tranche de date
<?php
$sql = "SELECT CONCAT(
	YEAR(dateapplication)
	, ' sem '
	, (MONTH(dateapplication) - 1) DIV 6 + 1) AS `Date`
, COUNT(*) AS Nbre

FROM (SELECT `reffiche`, MAX(dateapplication) AS dateapplication
		FROM critere_adr
		WHERE critere = 'Abo_payé'
		GROUP BY `reffiche`
	) c
GROUP BY CONCAT(YEAR(dateapplication), ' sem ', (MONTH(dateapplication) - 1) DIV 6 + 1)
ORDER BY Date
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