<?php
if(!isset($arguments)) $arguments = array();

$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\critere_adr.csv';
$arguments['charset'] = 'ISO-8859-15';
$arguments['table'] = 'critere_adr';
$arguments['columns'] = array(
	'RefFiche' => array(
		'datatype' => 'integer'
	),
	'DateApplication' => array(
		'datatype' => 'datetime'
	),
	'DateComplémentaire' => array(
		'datatype' => 'datetime'
	),
	'Inutilisé' => array(
		'datatype' => 'boolean'
	),
);
$arguments['skip_rows'] = 99999;
$node = page::node($node);
page::call('csv', $arguments, $node);
?>