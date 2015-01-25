<?php

if(!isset($arguments)) {
	$arguments = array();

	$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\Criteres.csv';
	$arguments['charset'] = 'UTF-8';
	$arguments['table'] = 'critere';
	$arguments['create_table'] = true;
	$arguments['truncate_table'] = true;
	$arguments['skip_rows'] = 0;
	$arguments['max_rows'] = INF;//1000;
}					
$arguments['columns'] = array(
	'Critère' => array(
		'datatype' => 'string',
	),
	'LibelléDétail' => array(
		'datatype' => 'string',
	),
	'CritèreOrigine' => array(
		'datatype' => 'string',
	),
	'OrdreDeTri' => array(
		'datatype' => 'integer',
	),
	'Période' => array(
		'datatype' => 'datetime',
	),
	'PériodeFin' => array(
		'datatype' => 'datetime',
	),
	'TypeChampCompl' => array(
		'datatype' => 'string',
	),
	'LibelléChampCompl' => array(
		'datatype' => 'string',
	),
	'NuméroEnCours' => array(
		'datatype' => 'integer',
	),
);
if(isset($arguments['return']) && $arguments['return'] == 'columns')
	return $arguments['columns'];
die (__FILE__ . " : procédure verrouillée");
page::call('csv', $arguments, node($node));
?>