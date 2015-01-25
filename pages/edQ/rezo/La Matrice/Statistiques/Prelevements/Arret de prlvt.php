<h3>Arrêts de prélèvements</h3>
<pre>
<i>Attention : quelqu'un qui aurait été en prlvnt de 2005 à 2007 et de 2010 à 2013
apparait comme étant en prélèvement de 2005 à 2013.</i>
<?php
$sql = "SELECT CONCAT(Première_Année, '') AS Première_Année, Dernière_Année, COUNT(*) AS Nombre_de_Contacts
FROM (SELECT MAX(annee) As Dernière_Année, MIN(annee) As Première_Année, idfiche
FROM `prlv_histo_detail`
WHERE annee <= YEAR(NOW())
GROUP BY idfiche
) c
GROUP BY Dernière_Année, Première_Année

UNION

SELECT '(total)', Dernière_Année, COUNT(*)
FROM (SELECT MAX(annee) As Dernière_Année, idfiche
FROM `prlv_histo_detail`
WHERE annee <= YEAR(NOW())
GROUP BY idfiche
) c
GROUP BY Dernière_Année

ORDER BY Dernière_Année DESC, Première_Année DESC";

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