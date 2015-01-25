<h3>Donateurs par type de don et par année</h3>
<pre>
<?php
$sql = "SELECT CONCAT(' Année ', Annee) AS Annee, typedon
, COUNT(*) AS `Nbre de donateurs`
, SUM(`Nbre de dons`) AS `Nbre de dons`
, ROUND(AVG(`Nbre de dons`) * 100) / 100 AS `Nbre moyen de dons`
, SUM(montant) AS Montant
, ROUND(AVG(montant) * 100) / 100 AS `Montant moyen`
FROM (
	SELECT YEAR(datedudon) AS Annee, typedon
	, COUNT(*) AS `Nbre de dons`
	, SUM(montant) AS Montant
	, reffiche
	FROM `don_adr`
	GROUP BY typedon, YEAR(datedudon), reffiche
) d
GROUP BY typedon, Annee

UNION

SELECT CONCAT(' Année ', Annee) AS Annee, 'GLOBAL ='
, COUNT(*) AS `Nbre de donateurs`
, SUM(`Nbre de dons`) AS `Nbre de dons`
, ROUND(AVG(`Nbre de dons`) * 100) / 100 AS `Nbre moyen de dons`
, SUM(montant) AS Montant
, ROUND(AVG(montant) * 100) / 100 AS `Montant moyen`
FROM (
	SELECT YEAR(datedudon) AS Annee
	, COUNT(*) AS `Nbre de dons`
	, SUM(montant) AS Montant
	, reffiche
	FROM `don_adr`
	GROUP BY YEAR(datedudon), reffiche
) d
GROUP BY Annee

UNION

SELECT 'Années (2011->2014)', typedon
, COUNT(d.reffiche) AS `Nbre de donateurs`
, SUM(`Nbre de dons`) AS `Nbre de dons`
, ROUND(AVG(`Nbre de dons`) * 100) / 100 AS `Nbre moyen de dons`
, SUM(montant) AS Montant
, ROUND(AVG(montant) * 100) / 100 AS `Montant moyen`
FROM (
	SELECT typedon
	, COUNT(*) AS `Nbre de dons`
	, SUM(montant) AS Montant
	, reffiche
	FROM `don_adr`
	WHERE datedudon >= '2011-01-01'
	GROUP BY typedon, reffiche
) d
GROUP BY typedon

UNION

SELECT 'Années (2011->2014)', 'GLOBAL = '
, COUNT(d.reffiche) AS `Nbre de donateurs`
, SUM(`Nbre de dons`) AS `Nbre de dons`
, ROUND(AVG(`Nbre de dons`) * 100) / 100 AS `Nbre moyen de dons`
, SUM(montant) AS Montant
, ROUND((`Nbre de dons` * montant) / SUM(montant) * 100) / 100 AS `Montant moyen`
FROM (
	SELECT COUNT(*) AS `Nbre de dons`
	, SUM(montant) AS Montant
	, reffiche
	FROM `don_adr`
	WHERE datedudon >= '2011-01-01'
	GROUP BY reffiche
) d
ORDER BY Annee DESC, typedon ASC";

$db = get_db();


page::call('/_format/csv/download link', array(
	'csv--node' => $node['id'],
	'csv--file' => $node['nm'],
));?>

 <?php 
$args = array( 'rows' => $db->all( $sql ) );
node('/_html/table/rows', $node, 'call', $args);
?>
</pre>