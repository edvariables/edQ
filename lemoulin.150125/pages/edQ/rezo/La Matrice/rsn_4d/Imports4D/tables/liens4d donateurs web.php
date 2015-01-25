<?php

if(!isset($arguments)) $arguments = array();

$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\LienRef4DdonateursWeb.csv';
$arguments['charset'] = 'UTF-8';
$arguments['table'] = 'LienRef4DdonateursWeb';
$arguments['create_table'] = true;
$arguments['truncate_table'] = true;
$arguments['skip_rows'] = 0;
$arguments['max_rows'] = INF;//1000;
$arguments['columns'] = array(
	'actif' => array(
		'datatype' => 'boolean',
	),
	'id' => array(
		'datatype' => 'integer',
	),
	'reffiche' => array(
		'datatype' => 'integer',
	),
	'valide' => array(
		'datatype' => 'boolean',
	),

);


if(isset($arguments['return']) && $arguments['return'] == 'columns')
	return $arguments['columns'];
die (__FILE__ . " : procédure verrouillée");

page::call('csv', $arguments, node($node));
?>