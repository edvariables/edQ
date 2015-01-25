<?php
die (__FILE__ . " : procédure verrouillée");

if(!isset($arguments)) $arguments = array();

$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\Criteres.csv';
$arguments['charset'] = 'UTF-8';
$arguments['table'] = 'critere';
$arguments['create_table'] = true;
$arguments['truncate_table'] = true;
$arguments['skip_rows'] = 0;
$arguments['max_rows'] = INF;//1000;
$arguments['columns'] = array(
	'critere4did' => array(
		'datatype' => 'integer',
	),
	'nom' => array(
		'datatype' => 'string',
	),
	'categorie' => array(
		'datatype' => 'string',
	),
	'origine' => array(
		'datatype' => 'string',
	),
	'ordredetri' => array(
		'datatype' => 'integer',
	),
	'usage_debut' => array(
		'datatype' => 'datetime',
	),
	'usage_fin' => array(
		'datatype' => 'datetime',
	),
	'commentaire' => array(
		'datatype' => 'text',
	),
);

page::call('csv', $arguments, node($node));
?>