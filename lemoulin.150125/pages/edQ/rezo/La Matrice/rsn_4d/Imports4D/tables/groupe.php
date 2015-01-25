<?php

if(!isset($arguments)) {
	$arguments = array();

	$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\groupe.csv';
	$arguments['charset'] = 'UTF-8';
	$arguments['table'] = 'groupe';
	$arguments['create_table'] = true;
	$arguments['truncate_table'] = true;
	$arguments['skip_rows'] = 0;
	$arguments['max_rows'] = INF;//1000;
}
	
$arguments['columns'] = array(
	'RefFiche' => array(
		'datatype' => 'integer',
	),
	'PasDeRelanceAdhésion' => array(
		'datatype' => 'boolean',
	),
	'PasDeCourrierAG' => array(
		'datatype' => 'boolean',
	),
	'AffichageSurLeWebSpécifique' => array(
		'datatype' => 'boolean',
	),
	'AffichageSurLeWebSpécifique' => array(
		'datatype' => 'boolean',
	),
	'GroupeSignataire' => array(
		'datatype' => 'boolean',
	),
	'DateValidationSignatureCharte' => array(
		'datatype' => 'datetime',
	),
	'CacherNomEtPrénom' => array(
		'datatype' => 'boolean',
	),
	'CacherAdressePostale' => array(
		'datatype' => 'boolean',
	),
	'CacherTél' => array(
		'datatype' => 'boolean',
	),
	'CacherFax' => array(
		'datatype' => 'boolean',
	),
	'CacherPortable' => array(
		'datatype' => 'boolean',
	),
	'CacherMail' => array(
		'datatype' => 'boolean',
	),
	'webInutilisé1' => array(
		'datatype' => 'string',
		'skip' => true,
	),
	'webInutilisé2' => array(
		'datatype' => 'string',
		'skip' => true,
	),
	'ContactWeb' => array(
		'datatype' => 'string',
	),
	'NomLongDuGroupe' => array(
		'datatype' => 'string',
	),
);
if(isset($arguments['return']) && $arguments['return'] == 'columns')
	return $arguments['columns'];
die (__FILE__ . " : procédure verrouillée");
page::call('csv', $arguments, node($node));
?>