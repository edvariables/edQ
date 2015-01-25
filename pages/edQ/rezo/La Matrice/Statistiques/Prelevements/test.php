<h3>Arrêts de prélèvements</h3>
<pre>
<i>Attention : quelqu'un qui aurait été en prlvnt de 2005 à 2007 et de 2010 à 2013
apparait comme étant en prélèvement de 2005 à 2013.</i>
<?php
$sql = "SELECT typedon
	, COUNT(*) AS `Nbre de dons`
	, SUM(montant) AS Montant
	, reffiche
	FROM `don_adr`
	WHERE datedudon >= '2011-01-01'
	GROUP BY typedon, reffiche
";

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