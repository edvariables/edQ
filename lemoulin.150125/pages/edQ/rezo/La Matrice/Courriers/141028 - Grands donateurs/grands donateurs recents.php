<?php
$db = get_db();
?><h3>Grands donateurs récents</h3>
<pre>
Donateurs entre 2011 et 2014 qui 
- ont donné plus de 500€ dans l'année
<?php
$sql = "SELECT annee AS `Année`, COUNT(*) AS Nbre, SUM(dons) AS Total
FROM adresse a
JOIN `vw_dons_annee` d
 ON a.reffiche = d.reffiche
WHERE dons > 500
GROUP by annee
ORDER BY annee";

node('/_format/rows/to html', $node, 'call', array('rows' => $db->all($sql)));

?>

Donateurs entre 2011 et 2014 qui 
- ont donné plus de 500€ dans l'année
<?php
$sql = "SELECT COUNT(*) AS Nbre, SUM(dons) AS Total
FROM (
	SELECT a.reffiche, COUNT(*) AS Nbre, SUM(dons) AS dons
	FROM adresse a
	JOIN `vw_dons_annee` d
	 ON a.reffiche = d.reffiche
	WHERE dons > 500
	GROUP BY a.reffiche
) a
";

node('/_format/rows/to html', $node, 'call', array('rows' => $db->all($sql)));

?>

</pre>