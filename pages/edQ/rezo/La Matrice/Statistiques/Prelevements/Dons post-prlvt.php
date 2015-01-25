<h3>Dons ultérieurs à l'arrêt de prélèvement</h3>
<pre>
<i>Attention : quelqu'un qui aurait été en prlvnt de 2005 à 2007 et de 2010 à 2013
apparait comme étant en prélèvement de 2005 à 2013.</i>
<?php
$sql = "SELECT YEAR(Date_Max) AS `Annee de fin de prlvnt`/*, d.reffiche*/
, SUM(d.montant) AS Montant
, COUNT(d.reffiche) AS `Nb dons`
, ROUND(AVG(d.montant) * 100) / 100 AS Moyenne
, ROUND((AVG(d.montant) + 2 * STDDEV(d.montant)) * 100) / 100 AS `95% des dons < à`
FROM don_adr d
JOIN (SELECT MAX(datepvt) AS Date_Max, idfiche
	FROM `prlv_histo_detail`
	GROUP BY idfiche) p
ON d.reffiche = p.idfiche
AND d.datedudon > p.Date_Max
GROUP BY YEAR(Date_Max)/*, reffiche*/

ORDER BY `Annee de fin de prlvnt` DESC

LIMIT 1,100";


$db = get_db();

?>

---> Résultat : <?php 
page::call('/_format/csv/download link', array(
	'csv--node' => $node['id'],
	'csv--file' => $node['nm'],
));
$args = array( 'rows' => $db->all( $sql ) );
node('/_html/table/rows', $node, 'call', $args);
?>
</pre>