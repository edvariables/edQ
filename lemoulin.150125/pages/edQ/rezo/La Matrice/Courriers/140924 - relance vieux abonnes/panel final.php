<div class="stats-0">
	
<?php
$date_abo = '2010-09-01';
$fin_abo = '2011-09-01';
$fin_rev = '2012-03-01';
?>
<pre>
<h4>Vieux abonnés : fin d'abonnement avant le <?=$fin_rev?></h4>
Critère d'abonné d'après le champs date_[fin]abn_rezo de la fiche adresse
Avec un écart d'un an mini entre le début et la fin de l'abonnement
Pas de ligne de facture cogilog après <?=$fin_abo?>,
Qui n'ont pas reçu l'appel à dons AD2 à AD4
NPAI = 0
<?php
$sql = "SELECT YEAR(datecreation) AS 'Fin d\'abo', COUNT(*) AS Nbre
FROM adresse
WHERE date_finabn_rezo BETWEEN '1900-01-01' AND '$fin_abo'
AND DATEDIFF(date_finabn_rezo, date_abn_rezo) > 360
AND reffiche NOT IN (
    SELECT `reffiche`
	FROM lignefacturecogilog
	WHERE datepiece >= '$fin_rev'
) 
AND reffiche NOT IN (
    SELECT `reffiche`
	FROM critere_adr
	WHERE critere LIKE 'relanceF_%_AD2'
	OR critere LIKE 'relanceF_%_AD3'
	OR critere LIKE 'relanceF_%_AD4'
) 

AND pasrelanceabo = 0
AND codepostal <> ''
AND statutnpai = 0

GROUP BY YEAR(datecreation)
";
$sql .= " UNION
SELECT '-- total --', SUM(Nbre)
FROM (" . $sql . ") z
";
$sql .= " ORDER BY 'Fin d\'abo'";


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