<h3>Abonnements à relancer</h3>
<pre>Dons effectués par les anciens abonnés

<?php
$date_abo = '2010-09-01';
$fin_abo = '2011-09-01';
$fin_rev = '2012-03-01';
?>

<?php
$sql = "SELECT SUM(montant) AS Cumul, AVG(montant) AS Moyenne
	, COUNT(d.reffiche) AS Nbre
	, IF(montant < 20, 20, IF(montant < 40, 40, IF(montant < 60, 60, 9999))) AS Max
FROM don_adr d
WHERE reffiche IN (
SELECT reffiche FROM adresse
WHERE date_finabn_rezo BETWEEN '1900-01-01' AND '$fin_abo'
AND DATEDIFF(date_finabn_rezo, date_abn_rezo) > 360
AND adresse.reffiche NOT IN (
    SELECT `reffiche`
	FROM lignefacturecogilog
	WHERE datepiece >= '$fin_rev'
) 
AND adresse.reffiche NOT IN (
    SELECT `reffiche`
	FROM critere_adr
	WHERE critere LIKE 'relanceF_%_AD2'
	OR critere LIKE 'relanceF_%_AD3'
	OR critere LIKE 'relanceF_%_AD4'
) 

AND pasrelanceabo = 0
AND codepostal <> ''
AND statutnpai = 0
)
GROUP BY IF(montant < 20, 20, IF(montant < 40, 40, IF(montant < 60, 60, 9999)))
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
	}
	#<?=$uid?> td:nth-child(2){
		text-align: right;
	}
</style>