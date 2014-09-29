<h3>Dons par année</h3>
<pre>
<i></i>
<?php
$sql = "SELECT YEAR(d.datedudon) AS `Annee`
, SUM(d.montant) AS Cumul
, COUNT(d.montant) AS `Nb de dons`
, CONCAT(ROUND(SUM(IF(d.montant >= 10, 1, 0))/COUNT(d.montant) * 100), ' %') AS `Nb >= 10€`
, CONCAT(ROUND(SUM(IF(d.montant < 10, 1, 0))/COUNT(d.montant) * 100), ' %') AS `Nb < 10€`
, ROUND(AVG(d.montant) * 100) / 100 AS `Don moyen`
, ROUND((AVG(d.montant) + 2 * STDDEV(d.montant)) * 100) / 100 AS `95% des dons < à`
FROM don_adr d
GROUP BY YEAR(d.datedudon)/*, reffiche*/

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