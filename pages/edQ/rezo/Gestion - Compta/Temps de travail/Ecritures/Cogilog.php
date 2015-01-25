<?php

$arguments_isset = isset($arguments);
$arguments = array_merge($_REQUEST, $arguments_isset ? $arguments : array());

if(!isset($node))
	$node = page::node(null);
$args = $arguments_isset ? $arguments : array();
$args['node--get'] = 'rows';

/* execution */
$source_node = page::node('..', $node);
/*var_dump($source_node);*/

ob_start();

page::call($source_node, $args);

$table = ob_get_clean();

/*echo "apres";
echo $table;*/

/* mise en forme des données pour Cogilog
Exportation vers Cogilog au format
    - 1ère ligne : **Compta{tab}Ecritures
    - puis : Journal	Date	Pièce	Compte	Section	Libellé	Échéance	Débit	Crédit	Intitulé_journal	Intitulé_compte	Intitulé_section	Lettrage	Pointage	Code fin	Référence	Informations
*/
$rowsSrc = $args['rows'];
//var_dump($rowsSrc);
$columns = $args['columns'];
$columnsSrc = array();
for($i = 0; $i < count($columns); $i++){
	$columns[$i]['index'] = $i;
	$columnsSrc[$columns[$i]['id']] = $columns[$i];
}

$rowsCog = array();
// en-tête
$rowsCog[] = array( '**Compta', 'Ecritures');
$sum_debit = 0.0;
$sum_credit = 0.0;
foreach($rowsSrc as $rowSrc){
	$rowsCog[] = array(
		$rowSrc[ $columnsSrc['Journal']['index'] ]
		, $rowSrc[ $columnsSrc['Date']['index'] ]
		, ''//pièce
		, $rowSrc[ $columnsSrc['Compte']['index'] ]
		, $rowSrc[ $columnsSrc['Analytique']['index'] ]
		, $rowSrc[ $columnsSrc['Libellé']['index'] ]
		, ''//échéance
		, is_numeric($rowSrc[ $columnsSrc['Débit']['index'] ])
			? round($rowSrc[ $columnsSrc['Débit']['index'] ] * 100) / 100
			: ''
		, is_numeric($rowSrc[ $columnsSrc['Crédit']['index'] ])
			? round($rowSrc[ $columnsSrc['Crédit']['index'] ] * 100) / 100
			: ''
		, ''//lib. journal
		, ''//lib. compte
	);
	if(is_numeric($rowSrc[ $columnsSrc['Débit']['index'] ]))
		$sum_debit += $rowSrc[ $columnsSrc['Débit']['index'] ];
	if(is_numeric($rowSrc[ $columnsSrc['Crédit']['index'] ]))
		$sum_credit += $rowSrc[ $columnsSrc['Crédit']['index'] ];
}

/*if(abs($sum_credit - $sum_debit) >= 0.001)
	die('Les écritures ne sont pas équilibrées : ($sum_credit - $sum_debit) = ' . ($sum_credit - $sum_debit));
*/
// complète à 22 colonnes
for($i = 0; $i < count($rowsCog); $i++)
	while( count($rowsCog[$i]) < 22)
		$rowsCog[$i][] = '';

/* retour au .csv */
if($arguments_isset){
	$arguments['csv--separ--column'] = ';';
	$arguments['csv--separ--row'] = '\n';
	$arguments['csv--separ--field'] = chr(0);
	$arguments['csv--numeric--precision'] = 5;
	
	$arguments['rows'] = $rowsCog;
	$arguments['table--columns--header'] = false;
	return;
}
$args['rows'] = $rowsCog;
unset($args['columns']);
page::call('/_html/table/rows', $args);
?>