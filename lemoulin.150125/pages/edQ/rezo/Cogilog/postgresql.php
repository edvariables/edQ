<?php
// Connexion, sélection de la base de données
$dbconn = pg_connect("host=10.0.0.6 port=64998 dbname=serveur user=consultation password=cogi4d")
    or die('Connexion impossible : ' . pg_last_error());

if(!isset($arguments)){
	$query = 'SELECT * FROM ccompt00002';
	$result = pg_query($query) or die('Échec de la requête : ' . pg_last_error());

	// Affichage des résultats en HTML
	echo "<table>\n";
	while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
		echo "\t<tr>\n";
		foreach ($line as $col_value) {
			echo "\t\t<td>$col_value</td>\n";
		}
		echo "\t</tr>\n";
	}
	echo "</table>\n";

	// Libère le résultat
	pg_free_result($result);

	// Ferme la connexion
	pg_close($dbconn);
	
	return;
}
if(!isset($arguments['sql'])){
	die('$arguments[\'sql\'] manquant.');
}

// Exécution de la requête SQL
$query = $arguments['sql'];
$result = pg_query($query);
if(!$result)
	return array('error' => 'Échec de la requête : ' . pg_last_error());

$rows = array();

while ($line = pg_fetch_array($result, null, PGSQL_ASSOC)) {
    $rows[] = $line;
}

// Libère le résultat
pg_free_result($result);

// Ferme la connexion
pg_close($dbconn);


return $rows;

?>