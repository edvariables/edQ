<?php

if(!isset($arguments)) $arguments = array();

$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\inscr donateurs web.csv';
$arguments['charset'] = 'UTF-8';
$arguments['table'] = 'inscr_donateurs_web';
$arguments['create_table'] = true;
$arguments['truncate_table'] = true;
$arguments['skip_rows'] = 0;
$arguments['max_rows'] = INF;//1000;
$arguments['columns'] = array(
	'id' => array(
		'datatype' => 'integer',
	),
	'affiche' => array(
		'datatype' => 'entier',
	),
	'nom' => array(
		'datatype' => 'string',
	),
	'prenom' => array(
		'datatype' => 'string',
	),
	'adresse1' => array(
		'datatype' => 'string',
	),
	'adresse' => array(
		'datatype' => 'string',
	),
	'code' => array(
		'datatype' => 'string',
	),
	'ville' => array(
		'datatype' => 'string',
	),
	'pays' => array(
		'datatype' => 'string',
	),
	'email' => array(
		'datatype' => 'string',
	),
	'telephone' => array(
		'datatype' => 'string',
	),
	'mobile' => array(
		'datatype' => 'string',
	),
	'rem' => array(
		'datatype' => 'string',
	),
	'ip' => array(
		'datatype' => 'string',
	),
	'date' => array(
		'datatype' => 'datetime',
	),
	'montant' => array(
		'datatype' => 'decimal',
	),
	'frequence' => array(
		'datatype' => 'integer',
	),
	'ref' => array(
		'datatype' => 'string',
	),
	'abo' => array(
		'datatype' => 'string',
	),
	'fin' => array(
		'datatype' => 'string',
	),
	'recu' => array(
		'datatype' => 'boolean',
	),
	'revue' => array(
		'datatype' => 'boolean',
	),
	'auto' => array(
		'datatype' => 'string',
	),
	'erreur' => array(
		'datatype' => 'boolean',
	),
	'Date_4D' => array(
		'datatype' => 'datetime',
	),
	'modepaiement' => array(
		'datatype' => 'string',
	),
	'source' => array(
		'datatype' => 'string',
	),

);

if(isset($arguments['return']) && $arguments['return'] == 'columns')
	return $arguments['columns'];
die (__FILE__ . " : procédure verrouillée");
page::call('csv', $arguments, node($node));
?>