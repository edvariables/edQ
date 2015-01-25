<?php

if(!isset($arguments)) {
	die (__FILE__ . " : procédure verrouillée");
	//page::call('critere', $arguments, $node);//adresse //critere_adr
	return;
}

$node = node($node);
include_once(page::file('helpers', $node));

$simulate = isset($arguments['simulate']) ? $arguments['simulate'] : true;
if($simulate){
	echo '<h3>Simulation - Indexation des tables</h3>';
	echo '<pre>$arguments = '; var_dump($arguments); echo '</pre>';
}

$columns = $arguments["columns"];
$pks = array();
foreach($columns as $column_name => $column)
	if($column['primary_key']){
		$pks[$column_name] = $column;
	}
if(count($pks) === 0){
	echo "<li>Aucune colonne n'a l'attribut 'primary_key'.</li>";
	return;
}

var_dump($pks);
?>