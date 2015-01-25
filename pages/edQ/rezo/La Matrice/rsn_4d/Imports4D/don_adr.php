<?php
//die (__FILE__ . " : procédure verrouillée");

if(!isset($arguments)) $arguments = array();

$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\don_adr.csv';
$arguments['charset'] = 'UTF-8';//'ISO-8859-15';//'UTF-8';
$arguments['table'] = 'don_adr';
$arguments['create_table'] = false;
$arguments['truncate_table'] = true;
$arguments['skip_rows'] = 0;
$arguments['max_rows'] = INF;//1000;
$arguments['columns'] = array(
	'RefFiche' => array(
		'datatype' => 'integer',
	),
	'DateDuDon' => array(
		'datatype' => 'datetime',
	),
	'Montant' => array(
		'datatype' => 'decimal',
	),
	'TypeDon' => array(
		'datatype' => 'string',
	),
);

page::call('csv', $arguments, node($node));
?>