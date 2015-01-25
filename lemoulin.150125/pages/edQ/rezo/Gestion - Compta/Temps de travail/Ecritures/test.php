<?php

$viewer = '/_Exemples/Convertisseurs/table/csv';//tree::get_id_by_name('/_Exemples/Convertisseurs/table/csv');
$cogilog = page::node('Cogilog', $node);
$args = array(
	"node" => $cogilog['id']
	, "file--name" => $node['nm']
	, "node--get" => 'rows'
	);
echo "<br>TEST Avant";
var_dump($args);
	//ob_start();
	page::call($viewer, $args);

	//$table = ob_get_clean();
echo "<br>TEST APRES";
var_dump($args);
?>