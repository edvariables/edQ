<?php
$db = get_db();
?><h3>Grands Donateurs récents</h3>
<pre>

Donateurs entre 2011 et 2014 qui 
- ont donné plus de 500€ dans l'année
<?php
$sql = "SELECT a.reffiche, a.nom, a.prenom, SUM(dons) AS Cumuls, AVG(dons) AS `Moyenne annuelle`, MIN(annee) AS `1ère année`, MAX(annee) AS `Dernière année`
	FROM adresse a
	JOIN `vw_dons_annees` d
	 ON a.reffiche = d.reffiche
	WHERE dons > 500
	GROUP BY a.reffiche, a.nom, a.prenom
	ORDER BY `Moyenne annuelle` DESC
";

node('/_format/rows/to html', $node, 'call', array('rows' => $db->all($sql)));

?>

</pre>