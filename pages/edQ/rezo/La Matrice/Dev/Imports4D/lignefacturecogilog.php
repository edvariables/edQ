<?php
if(!isset($arguments)) $arguments = array();

$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\LigneFactureCogilog.csv';
$arguments['charset'] = 'UTF-8';//'ISO-8859-15';//'UTF-8';
$arguments['table'] = 'lignefacturecogilog';
$arguments['create_table'] = false;
$arguments['truncate_table'] = true;
$arguments['skip_rows'] = 0;
$arguments['max_rows'] = INF;//1000;
$arguments['columns'] = array(
	'id_ligne_cogilog' => array(
		'datatype' => 'integer',
	),
	'id_gprodu' => array(
		'datatype' => 'integer',
	),
	'id_piece' => array(
		'datatype' => 'integer',
	),
	'position' => array(
		'datatype' => 'integer',
	),
	'nom' => array(
		'datatype' => 'string',
	),
	'prix' => array(
		'datatype' => 'decimal',
	),
	'unite' => array(
		'datatype' => 'string',
	),
	'quantite' => array(
		'datatype' => 'decimal',
	),
	'remise' => array(
		'datatype' => 'decimal',
	),
	'tvacode' => array(
		'datatype' => 'string',
	),
	'tvataux' => array(
		'datatype' => 'decimal',
	),
	'ht2' => array(
		'datatype' => 'decimal',
	),
	'achat' => array(
		'datatype' => 'decimal',
	),
	'pdate1' => array(
		'datatype' => 'datetime',
	),
	'pdate2' => array(
		'datatype' => 'datetime',
	),
	'section' => array(
		'datatype' => 'string',
	),
	'reference' => array(
		'datatype' => 'string',
	),
	'type' => array(
		'datatype' => 'string',
	),
	'lien' => array(
		'datatype' => 'string',
	),
	'prixttc' => array(
		'datatype' => 'decimal',
	),
	'avancement' => array(
		'datatype' => 'string',
	),
	'coefficient' => array(
		'datatype' => 'decimal',
	),
	'datepiece' => array(
		'datatype' => 'datetime',
	),
	'date_tssaisie' => array(
		'datatype' => 'datetime',
	),
	'date_tsmod' => array(
		'datatype' => 'datetime',
	),
	'RefFiche' => array(
		'datatype' => 'integer',
	),
	'CodeProduit' => array(
		'datatype' => 'string',
	),
	'CodeAffaire' => array(
		'datatype' => 'string',
	),
	'Famille' => array(
		'datatype' => 'string',
	),

);

page::call('csv', $arguments, page::node($node));
?>