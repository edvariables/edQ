<?php

if(!isset($arguments)) $arguments = array();

$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\prlnthistodetail.csv';
$arguments['charset'] = 'UTF-8';
$arguments['table'] = 'prlv_histo_detail';
$arguments['create_table'] = true;
$arguments['truncate_table'] = true;
$arguments['skip_rows'] = 0;
$arguments['max_rows'] = INF;//1000;
$arguments['columns'] = array(
	'Année' => array(
		'datatype' => 'integer',
	),
	'Mois' => array(
		'datatype' => 'integer',
	),
	'idFiche' => array(
		'datatype' => 'integer',
	),
	'Montant' => array(
		'datatype' => 'decimal',
	),
	'Périodicité' => array(
		'datatype' => 'integer',
	),
	'RUM' => array(
		'datatype' => 'string',
	),
	'datePVT' => array(
		'datatype' => 'datetime',
	),
	'IBAN' => array(
		'datatype' => 'string',
	),
	'BIC' => array(
		'datatype' => 'string',
	),
);

if(isset($arguments['return']) && $arguments['return'] == 'columns')
	return $arguments['columns'];
die (__FILE__ . " : procédure verrouillée");

page::call('csv', $arguments, node($node));
?>