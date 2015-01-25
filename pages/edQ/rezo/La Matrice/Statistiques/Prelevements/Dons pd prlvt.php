<h3>Dons alors que aussi en prélèvements</h3>
<pre>
<i>Attention : quelqu'un qui aurait été en prlvnt de 2005 à 2007 et de 2010 à 2013
apparait comme étant en prélèvement de 2005 à 2013.</i>
<?php
$sql = "SELECT YEAR(Date_Min) AS `Debut de prlvnt`, YEAR(Date_Max) AS `Fin de prlvnt`/*, d.reffiche*/
, SUM(d.montant) AS Montant
, COUNT(d.montant) AS `Nb de dons`
, CONCAT(ROUND(SUM(IF(d.montant >= 10, 1, 0))/COUNT(d.montant) * 100), ' %') AS `Nb >= 10€`
, CONCAT(ROUND(SUM(IF(d.montant < 10, 1, 0))/COUNT(d.montant) * 100), ' %') AS `Nb < 10€`
, ROUND(AVG(d.montant) * 100) / 100 AS `Don moyen`
, ROUND((AVG(d.montant) + 2 * STDDEV(d.montant)) * 100) / 100 AS `95% des dons < à`
FROM don_adr d
JOIN (SELECT MIN(datepvt) AS Date_Min, MAX(datepvt) AS Date_Max, idfiche
	FROM `prlv_histo_detail`
	GROUP BY idfiche) p
ON d.reffiche = p.idfiche
AND d.datedudon BETWEEN p.Date_Min AND p.Date_Max
GROUP BY YEAR(Date_Max)/*, reffiche*/

ORDER BY `Debut de prlvnt`, `Fin de prlvnt` DESC";


$db = get_db();

?>
<?php 
page::call('/_format/csv/download link', array(
	'csv--node' => $node['id'],
	'csv--file' => $node['nm'],
));
$args = array( 'rows' => $db->all( $sql ) );
node('/_html/table/rows', $node, 'call', $args);
?>
</pre>