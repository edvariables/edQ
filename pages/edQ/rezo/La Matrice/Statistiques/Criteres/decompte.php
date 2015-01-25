<h3>Critères</h3>
<pre>Liste des critères et leur nombre d'applications
<?php
$sql = "SELECT ca.critere, c.origine, COUNT(*) AS `Nbre de critères`
, MIN(dateapplication) AS `A partir du`
, MAX(dateapplication) AS `Dernier le`
, NbContacts AS `Nbre de contacts`
FROM critere_adr ca
LEFT JOIN critere c
	ON c.nom = ca.critere
LEFT JOIN (SELECT critere, COUNT(*) AS NbContacts
	FROM (
		SELECT DISTINCT critere, reffiche
		FROM critere_adr 
		) cccon
	GROUP BY critere
	) ccon
	ON ccon.critere = ca.critere
GROUP BY ca.critere, c.origine, NbContacts
ORDER BY ca.critere, `Nbre de critères` DESC
";

?></pre><?php

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
<style>
	#<?=$uid?> td{
		padding: 1px 6px;
		text-align: right;
	}
	#<?=$uid?> td:nth-child(1){
		text-align: left;
	}
</style>