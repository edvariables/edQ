<?php

if(!isset($arguments)) {
	$arguments = array();

	$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\Courrier.csv';
	$arguments['charset'] = 'UTF-8';
	$arguments['table'] = 'courrier';
	$arguments['create_table'] = true;
	$arguments['truncate_table'] = true;
	$arguments['skip_rows'] = 0;
	$arguments['max_rows'] = INF;//1000;
}					
$arguments['columns'] = array(
	'Nom' => array(
		'datatype' => 'string',
	),
	'Date' => array(
		'datatype' => 'datetime',
	),
	'NomCourt' => array(
		'datatype' => 'string',
	),
	'CritèreDEnvoi' => array(
		'datatype' => 'string',
	),
	'CritèreNPAI' => array(
		'datatype' => 'string',
	),
);
if(isset($arguments['return']) && $arguments['return'] == 'columns')
	return $arguments['columns'];
die (__FILE__ . " : procédure verrouillée");
page::call('csv', $arguments, node($node));
?>