<?php /* Conversion de données en fichier .csv
	Les données peuvent être fournies sous forme de tableau ou sous forme html.
	Le fournisseur est désigné par la propriété $arguments['node'].
	Ce noeud est appelé en transmettant la variable $arguments.
	Ce noeud fournit les données en retour d'après l'existence de l'argument 'node--get' :
		$arguments['node--get'] = 'rows';
		//il faut, bien sûr, que le noeud qui fournit les données traite lui-même $arguments['node--get'];
		$rows = page::call($arguments['node'], $arguments, __FILE__);
				
	$arguments['csv--separ--column'] : séparateur de colonnes. Par défaut ';'
	$arguments['csv--separ--row'] : séparateur de lignes. Par défaut '\n'
	$arguments['csv--separ--field'] : caractères englobant les textes des champs. Par défaut chr(0), le standard étant '"'.
	$arguments['csv--numeric--precision'] : nombre de chiffres après la décimale. Par défaut, 3;
	
	Sinon, le noeud retourne du html et les données sont extraites de <table/>. Le code html doit être rigoureux (fermetures de balises).
*/


//echo(' csv entree $arguments : '); var_dump($arguments);
if(!isset($arguments))
	$arguments = array();

$rows = isset($arguments['rows']) ? $arguments['rows'] : false;

if(!is_array($rows)){
	$arguments['q--limit'] = 99999;

	//node source 
	$nodesource = isset($arguments['node'])
		? $arguments['node']
		: (isset($_REQUEST['node']) ? $_REQUEST['node'] : '/_Exemples/data/table'); 
	if(is_numeric($nodesource)){
		global $tree;
		$nodesource = $tree->get_path_string($nodesource);
	}
	$arguments['node'] = $nodesource;

	if(!isset($arguments['node--get']))
		if(isset($_REQUEST['node--get']))
			$arguments['node--get'] = $_REQUEST['node--get'];
		else
			$arguments['node--get'] = 'rows';
	unset($arguments[$arguments['node--get']]);

	/* execution */
	ob_start();

	$rows = page::call($nodesource, $arguments, __FILE__);
	
	$table = ob_get_clean(); /* cf 2ème cas : la page a retournée le html de la table */

	/*var_dump( $nodesource);
	echo ' returns $rows = ';
	var_dump($rows);
	var_dump($arguments);*/
	
	if(!is_array($rows)){
		if(stripos($table, '<table') === FALSE) 
			return;
		
		$arguments['html'] = $table;
		return page::call( node('from html', $node), $arguments);
	}
	if(is_associative($rows)){
		$arguments = $rows;
		$columns = $arguments['columns'];
		$rows = $arguments['rows'];
	}
}

//filename
$filename = isset($arguments['file--name'])
	? $arguments['file--name']
	: (isset($_REQUEST['file--name']) ? $_REQUEST['file--name'] : 'table'); 
if(!preg_match('/\.(csv|txt|dat)$/', $filename))
	$filename .= '.csv';

$csvSeparCols = isset($arguments['csv--separ--column']) ? $arguments['csv--separ--column'] : ';';
$csvSeparRows = isset($arguments['csv--separ--row']) ? $arguments['csv--separ--row'] : "\r";
$csvSeparFields = isset($arguments['csv--separ--field']) ? $arguments['csv--separ--field'] : '"';
$csvSeparChars = array( $csvSeparCols, "\n", "\r", $csvSeparFields );

$csv_num_precision = isset($arguments['csv--numeric--precision']) ? (int)$arguments['csv--numeric--precision'] : 3;

header("Content-Encoding: UTF-8");
header('Pragma: no-cache');
header("Expires: 0");

// $is_associative
$is_associative = count($rows) > 0 && is_associative($rows[0]);
if(!$is_associative)
	$columns = $arguments['columns'];
//header 
header('Content-type: application/csv; charset=UTF-8');
header('Content-disposition: attachment; filename="'.$filename.'"');

$fp = fopen("php://output", "w");

$nRow = 0;

foreach($rows as $row){
	if($row){

		// 1st row : column names
		if(($nRow++ == 0)
		   && ( $arguments['table--columns--header'] !== FALSE) ){

			$td = array();
			if($is_associative)
				foreach($row as $column => $value)
					$td[] = str_replace($csvSeparChars, '', $column);
			else if($columns)
				foreach($columns as $column)
					$td[] = str_replace($csvSeparChars, '', $column['id']);
			fputcsv($fp, $td, $csvSeparCols, $csvSeparFields);
		}

		// data
		$td = array();

		if(!is_array($row))
			$row = array($row);
		foreach($row as $value){
			switch(gettype($value)){
				case "double":
				case "float":
					$td[] = number_format($value, $csv_num_precision, ',', '');
					break;
				case "boolean":
					$td[] = $value ? '1' : '0';
					break;
				default:
					$td[] = str_replace($csvSeparChars, '', (string)$value);
					break;
			}
		}
				
		fputcsv($fp, $td, $csvSeparCols, $csvSeparFields);
	}
}
fclose($fp);
exit;
?>