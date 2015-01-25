<h3>Critères Revue en doublons</h3>
<pre>
<?php
$sql = "SELECT critere, COUNT(Nbre) AS Adresses, AVG(Nbre) AS Moyenne

FROM (SELECT `reffiche`, critere, COUNT(dateapplication) AS Nbre
		FROM critere_adr
		WHERE critere LIKE 'Revue\_%'
		GROUP BY `reffiche`, critere
		HAVING COUNT(dateapplication) > 1
	) c
GROUP BY critere
ORDER BY critere
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
		padding: 1px 6px;
		text-align: right;
	}
	#<?=$uid?> td:nth-child(1){
		text-align: left;
	}
</style>