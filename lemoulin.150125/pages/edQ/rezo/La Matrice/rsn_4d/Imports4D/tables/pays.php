<?php

if(!isset($arguments)) {
	$arguments = array();

	$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\pays.csv';
	$arguments['charset'] = 'UTF-8';
	$arguments['table'] = 'pays';
	$arguments['create_table'] = true;
	$arguments['truncate_table'] = true;
	$arguments['skip_rows'] = 0;
	$arguments['max_rows'] = INF;//1000;
}					
$arguments['columns'] = array(
	'CodeISO' => array(
		'datatype' => 'string',
	),
	'Pays' => array(
		'datatype' => 'string',
	),
);
if(isset($arguments['return']) && $arguments['return'] == 'columns')
	return $arguments['columns'];
die (__FILE__ . " : procédure verrouillée");
page::call('csv', $arguments, node($node));
?>