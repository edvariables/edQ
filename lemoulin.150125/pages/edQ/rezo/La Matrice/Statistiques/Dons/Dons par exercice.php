<h3>Dons par exercice</h3>
<pre>
<i></i>
<?php
$sql = "SELECT CONCAT(Annee, '-', Annee + 1) AS Exercice
, SUM(d.montant) AS Cumul
, SUM(d.`Nb de dons`) AS `Dons`
, COUNT(d.reffiche) AS `Donateurs`
, CONCAT(ROUND(SUM(IF(d.montant >= 10, 1, 0))/COUNT(`reffiche`) * 100), ' %') AS `Nb >= 10€`
, CONCAT(ROUND(SUM(IF(d.montant < 10, 1, 0))/COUNT(`reffiche`) * 100), ' %') AS `Nb < 10€`
, ROUND(SUM(d.montant)/SUM(`Nb de dons`) * 100) / 100 AS `Don moyen`
, ROUND(AVG(d.montant) * 100) / 100 AS `Moyenne par donateur`
, ROUND((SUM(d.montant)/SUM(`Nb de dons`) + 2 * STDDEV(d.montant/`Nb de dons`)) * 100) / 100 AS `95% des dons < à`
FROM (
	SELECT reffiche
	, YEAR( DATE_SUB(d.datedudon, INTERVAL 8 MONTH) ) AS `Annee`
	, SUM(d.montant) AS montant
	, COUNT(d.montant) AS `Nb de dons`
 	FROM don_adr d
	GROUP BY YEAR(DATE_SUB(d.datedudon, INTERVAL 8 MONTH)), reffiche
) d
GROUP BY Annee

ORDER BY `Annee` DESC";


$db = get_db();

page::call('/_format/csv/download link', array(
	'csv--node' => $node['id'],
	'csv--file' => $node['nm'],
));?>
<br>
<?php
$args = array( 'rows' => $db->all( $sql ) );
node('/_html/table/rows', $node, 'call', $args);
?>
</pre>