<?php
$db = get_db();
?><h3>Grands Clients récents</h3>
<pre>

Clients entre 2011 et 2014 qui 
- ont acheté pour plus de 500€ dans l'année
<?php
$sql = "SELECT a.reffiche, a.nom, a.prenom, SUM(achats) AS Cumuls, AVG(achats) AS `Moyenne annuelle`, MIN(annee) AS `1ère année`, MAX(annee) AS `Dernière année`
	FROM adresse a
	JOIN `vw_achats_annees` d
	 ON a.reffiche = d.reffiche
	WHERE achats > 500
	GROUP BY a.reffiche, a.nom, a.prenom
	ORDER BY `Moyenne annuelle` DESC
";

node('/_format/rows/to html', $node, 'call', array('rows' => $db->all($sql)));

?>

</pre>