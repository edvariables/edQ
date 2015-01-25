<?php
if(!isset($arguments)) $arguments = array();

$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\critere_adr.2.2.csv';
$arguments['charset'] = 'UTF-8';
$arguments['table'] = 'critere_adr';
$arguments['create_table'] = false;
$arguments['truncate_table'] = false;
$arguments['skip_rows'] = 0;
$arguments['max_rows'] = INF;//1000;
$arguments['columns'] = array(
	'RefFiche' => array(
		'datatype' => 'integer'
	),
	'Critère' => array(
		'datatype' => 'string'
	),
	'DateApplication' => array(
		'datatype' => 'datetime'
	),
	'DateComplémentaire' => array(
		'datatype' => 'datetime'
	),
	'ChampComplémentaire' => array(
		'datatype' => 'text'
	),
	'Inutilisé' => array(
		'datatype' => 'boolean'
	),
);
if(isset($arguments['return']) && $arguments['return'] == 'columns')
	return $arguments['columns'];
die (__FILE__ . " : procédure verrouillée");
$node = node($node);
page::call('csv', $arguments, $node);
?>