<?php
$db = get_db();
?><h3>Grands Clients récents</h3>
<pre>
Clients entre 2011 et 2014 qui 
- ont acheté pour plus de 500€ dans l'année
<?php
$sql = "SELECT annee AS `Année`, COUNT(*) AS Nbre, SUM(achats) AS Total
FROM adresse a
JOIN `vw_achats_annees` d
 ON a.reffiche = d.reffiche
WHERE achats > 500
GROUP by annee
ORDER BY annee";

node('/_format/rows/to html', $node, 'call', array('rows' => $db->all($sql)));

?>

Clients entre 2011 et 2014 qui 
- ont acheté pour plus de 500€ dans l'année
<?php
$sql = "SELECT COUNT(*) AS Nbre, SUM(achats) AS Total
FROM (
	SELECT a.reffiche, COUNT(*) AS Nbre, SUM(achats) AS achats
	FROM adresse a
	JOIN `vw_achats_annees` d
	 ON a.reffiche = d.reffiche
	WHERE achats > 500
	GROUP BY a.reffiche
) a
";

node('/_format/rows/to html', $node, 'call', array('rows' => $db->all($sql)));

?>

</pre>