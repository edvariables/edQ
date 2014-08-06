<?php
global $tree;

$node = page::node();

$source = page::node('/rezo/Gestion - Compta/Temps de travail/Analytique');
/*echo(' $source : ');
var_dump(( $source ));*/

$arguments = array(
	'node' => $source
	, 'node--get' => 'rows'
	
	);
	page::call('/_Exemples/Convertisseurs/table/csv', $arguments);
?>