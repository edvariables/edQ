<?php

if(!isset($arguments)) {
	$arguments = array();

	$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\codepostal.csv';
	$arguments['charset'] = 'UTF-8';
	$arguments['table'] = 'codepostal';
	$arguments['create_table'] = true;
	$arguments['truncate_table'] = true;
	$arguments['skip_rows'] = 0;
	$arguments['max_rows'] = INF;//1000;
}					
$arguments['columns'] = array(
	'CodePostal' => array(
		'datatype' => 'string',
	),
	'Ville' => array(
		'datatype' => 'string',
	),
	'Recensement' => array(
		'datatype' => 'integer',
	),
);
if(isset($arguments['return']) && $arguments['return'] == 'columns')
	return $arguments['columns'];
die (__FILE__ . " : procédure verrouillée");
page::call('csv', $arguments, node($node));
?>