<?php
die (__FILE__ . " : procédure verrouillée");

if(!isset($arguments)) $arguments = array();

$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\prelevements4D.csv';
$arguments['charset'] = 'UTF-8';
$arguments['table'] = 'prelevement';
$arguments['create_table'] = true;
$arguments['truncate_table'] = true;
$arguments['skip_rows'] = 0;
$arguments['max_rows'] = INF;//1000;
$arguments['columns'] = array(
	'RefFiche' => array(
		'datatype' => 'integer',
	),
	'Nom' => array(
		'datatype' => 'string',
	),
	'NuméroCompte' => array(
		'datatype' => 'string',
	),
	'CodeBanque' => array(
		'datatype' => 'string',
	),
	'CodeGuichet' => array(
		'datatype' => 'string',
	),
	'Clé' => array(
		'datatype' => 'string',
	),
	'Montant' => array(
		'datatype' => 'decimal',
	),
	'Périodicité' => array(
		'datatype' => 'integer',
	),
	'Msg' => array(
		'datatype' => 'string',
	),
	'DateDernModif' => array(
		'datatype' => 'datetime',
	),
	'HeureDernModif' => array(
		'datatype' => 'datetime',
	),
	'DateCréation' => array(
		'datatype' => 'datetime',
	),
	'DéjàPrélevé' => array(
		'datatype' => 'boolean',
	),
	'Etat' => array(
		'datatype' => 'string',
	),
	'DateDernModifEtat' => array(
		'datatype' => 'datetime',
	),
	'DateDernModifMontant' => array(
		'datatype' => 'datetime',
	),
	'PrélèvementEnLigne' => array(
		'datatype' => 'boolean',
	),
	'Origine' => array(
		'datatype' => 'string',
	),
	'OrigineDernModif' => array(
		'datatype' => 'string',
	),
	'SEPAibanPays' => array(
		'datatype' => 'string',
	),
	'SEPAibanClé' => array(
		'datatype' => 'string',
	),
	'SEPAibanBBAN' => array(
		'datatype' => 'string',
	),
	'SEPAbic' => array(
		'datatype' => 'string',
	),
	'SEPAdateSignature' => array(
		'datatype' => 'datetime',
	),
	'SEPArum' => array(
		'datatype' => 'string',
	),
	'dateDernierPVT' => array(
		'datatype' => 'datetime',
	),
	'HeureTraitementPVT' => array(
		'datatype' => 'datetime',
	),
	
);

page::call('csv', $arguments, node($node));
?>