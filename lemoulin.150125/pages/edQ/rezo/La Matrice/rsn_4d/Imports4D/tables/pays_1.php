<?php

if(!isset($arguments)) {
	$arguments = array();

	$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\Député.csv';
	$arguments['charset'] = 'UTF-8';
	$arguments['table'] = 'Député';
	$arguments['create_table'] = true;
	$arguments['truncate_table'] = true;
	$arguments['skip_rows'] = 0;
	$arguments['max_rows'] = INF;//1000;
}
		Civilité	Nom	Prénom	Adresse1	Adresse2	CodePostal	Ville	Téléphone	Fax	EMail	EMail2	Titre	Adresse32car	
$arguments['columns'] = array(
	'Département' => array(
		'datatype' => 'string',
	),
	'Circonscription' => array(
		'datatype' => 'integer',
	),
	'Civilité' => array(
		'datatype' => 'string',
	),
	'Nom' => array(
		'datatype' => 'string',
	),
	'Prénom' => array(
		'datatype' => 'string',
	),
	'Adresse1' => array(
		'datatype' => 'string',
	),
	'Adresse2' => array(
		'datatype' => 'string',
	),
	'CodePostal' => array(
		'datatype' => 'string',
	),
	'Ville' => array(
		'datatype' => 'string',
	),
	'Téléphone' => array(
		'datatype' => 'string',
	),
	'Fax' => array(
		'datatype' => 'string',
	),
	'EMail' => array(
		'datatype' => 'string',
	),
	'EMail2' => array(
		'datatype' => 'string',
	),
	'Titre' => array(
		'datatype' => 'string',
	),
	'Adresse32car' => array(
		'datatype' => 'string',
	),
);
if(isset($arguments['return']) && $arguments['return'] == 'columns')
	return $arguments['columns'];
die (__FILE__ . " : procédure verrouillée");
page::call('csv', $arguments, node($node));
?>