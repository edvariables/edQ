<?php
die (__FILE__ . " : procédure verrouillée");

if(!isset($arguments)) $arguments = array();

$arguments['file'] = 'D:\\Softs\\140908 - La Matrice\\4D\\189000 adresses.2.2.csv';
$arguments['charset'] = 'UTF-8';//'ISO-8859-15';//'UTF-8';
$arguments['table'] = 'adresse';
$arguments['create_table'] = false;
$arguments['truncate_table'] = false;
$arguments['skip_rows'] = 0;
$arguments['max_rows'] = INF;//1000;
$arguments['columns'] = array(
	'Nom' => array(
		'datatype' => 'string',
	),
	'Prénom' => array(
		'datatype' => 'string',
	),
	'ComplémentGéographique' => array(
		'datatype' => 'string',
		'skip' => true,
	),
	'NumeroEtLibelléDeLaVoie' => array(
		'datatype' => 'string',
		'skip' => true,
	),
	'CodePostal' => array(
		'datatype' => 'string',
	),
	'Ville' => array(
		'datatype' => 'string',
	),
	'Ligne5' => array(
		'datatype' => 'string',
		'skip' => true,
	),
	'AdresseLigne2' => array(
		'datatype' => 'string',
		'skip' => true,
	),
	'Telephone' => array(
		'datatype' => 'string',
		'skip' => true,
	),
	'Fax' => array(
		'datatype' => 'string',
		'skip' => true,
	),
	'Groupe' => array(
		'datatype' => 'string',
	),
	'NomAssoEntrepriseAlaPlaceDeNomP' => array(
		'datatype' => 'string',
	),
	'RefFiche' => array(
		'datatype' => 'integer',
	),
	'NbAbosGroupés' => array(
		'datatype' => 'string',
	),
	'Remarque' => array(
		'datatype' => 'string',
	),
	'AssociationCourt' => array(
		'datatype' => 'string',
	),
	'DateCréation' => array(
		'datatype' => 'datetime',
	),
	'ConventionNef' => array(
		'datatype' => 'boolean',
	),
	'DateModification' => array(
		'datatype' => 'datetime',
	),
	'Presse' => array(
		'datatype' => 'boolean',
	),
	'P_militante' => array(
		'datatype' => 'boolean',
	),
	'Abonné_Rezo' => array(
		'datatype' => 'boolean',
	),
	'Date_Abn_Rezo' => array(
		'datatype' => 'datetime',
	),
	'Signataire' => array(
		'datatype' => 'boolean',
	),
	'Cumul_Don' => array(
		'datatype' => 'decimal',
	),
	'NumériqueDevraitPlusServir' => array(
		'datatype' => 'decimal',
	),
	'Sympathisant' => array(
		'datatype' => 'boolean',
	),
	'Don_étalé' => array(
		'datatype' => 'boolean',
	),
	'PasDeRecuFiscal' => array(
		'datatype' => 'boolean',
	),
	'Cumul_Prelevement' => array(
		'datatype' => 'decimal',
	),
	'Portable' => array(
		'datatype' => 'string',
	),
	'Prélèvement' => array(
		'datatype' => 'boolean',
	),
	'Pays' => array(
		'datatype' => 'string',
	),
	'Ancien_TotalDon' => array(
		'datatype' => 'decimal',
	),
	'Ancien_Cumul_Prlvmts' => array(
		'datatype' => 'decimal',
	),
	'APartirNumeroRevueAbn' => array(
		'datatype' => 'integer',
	),
	'Département' => array(
		'datatype' => 'string',
	),
	'SiteWebGroupe' => array(
		'datatype' => 'string',
	),
	'Origine' => array(
		'datatype' => 'string',
	),
	'TypeAbonné' => array(
		'datatype' => 'string',
	),
	'TypeDeGroupe' => array(
		'datatype' => 'string',
	),
	'DescriptifGroupe' => array(
		'datatype' => 'string',
	),
	'Cumul_Nef' => array(
		'datatype' => 'decimal',
	),
	'NombreDadherentsDuGroupe' => array(
		'datatype' => 'integer',
	),
	'EvalAdr' => array(
		'datatype' => 'integer',
	),
	'Charade' => array(
		'datatype' => 'string',
	),
	'StatutNPAI' => array(
		'datatype' => 'integer',
	),
	'DateStatutNPAI' => array(
		'datatype' => 'datetime',
	),
	'Date_FinAbn_Rezo' => array(
		'datatype' => 'datetime',
	),
	'DepotVente' => array(
		'datatype' => 'boolean',
	),
	'DateModifAdresse' => array(
		'datatype' => 'datetime',
	),
	'TypeContact' => array(
		'datatype' => 'integer',
	),
	'PasAutoRNVP' => array(
		'datatype' => 'boolean',
	),
	'PasRelanceFinanciereCourrier' => array(
		'datatype' => 'boolean',
	),
	'PasRelanceAbo' => array(
		'datatype' => 'boolean',
	),
	'DateExportCogilog' => array(
		'datatype' => 'datetime',
	),
	'DateTraitementRNVP' => array(
		'datatype' => 'datetime',
	),
	'Top_RNVP' => array(
		'datatype' => 'integer',
	),
	'PasDeRelanceFinancièreWeb' => array(
		'datatype' => 'boolean',
	),
	'vTiger' => array(
		'datatype' => 'integer',
	),
);

page::call('csv', $arguments, node($node));
?>