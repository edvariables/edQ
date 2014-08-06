<?php /* Conversion de données en fichier .csv
	Les données peuvent être fournies sous forme de tableau ou sous forme html.
	Le fournisseur est désigné par la propriété $arguments['node'].
	Ce noeud est appelé en transmettant la variable $arguments.
	Ce noeud fournit les données en retour d'$arguments dans la propriété désignée par la propriété $arguments['node--get'].
		$arguments['node--get'] = 'rows';
		page::call($arguments['node'], $arguments, __FILE__);
		var_dump($arguments['rows']); //il faut, bien sûr, que le noeud qui fournit les données traite lui-même $arguments['node--get'];
		
		
	$arguments['csv--separ--column'] : séparateur de colonnes. Par défaut ';'
	$arguments['csv--separ--row'] : séparateur de lignes. Par défaut '\n'
	$arguments['csv--separ--field'] : caractères englobant les textes des champs. Par défaut chr(0), le standard étant '"'.
	$arguments['csv--numeric--precision'] : nombre de chiffres après la décimale. Par défaut, 3;
	
	Sinon, le noeud retourne du html et les données sont extraites de <table/>. Le code html doit être rigoureux (fermetures de balises).
*/


//echo(' csv entree $arguments : '); var_dump($arguments);
	if(!isset($arguments))
		$arguments = array();

	$arguments['q--limit'] = 99999;

	//node source 
	$nodesource = isset($arguments['node']) ? $arguments['node'] : (isset($_REQUEST['node']) ? $_REQUEST['node'] : '..'); 
	if(is_numeric($nodesource)){
		global $tree;
		$nodesource = $tree->get_path_string($nodesource);
	}
	$arguments['node'] = $nodesource;
	//filename
	$filename = isset($arguments['file--name']) ? $arguments['file--name'] : (isset($_REQUEST['file--name']) ? $_REQUEST['file--name'] : 'table'); 
	if(!preg_match('/\.csv$/', $filename))
		$filename .= '.csv';

	if(!isset($arguments['node--get']))
		if(isset($_REQUEST['node--get']))
			$arguments['node--get'] = $_REQUEST['node--get'];
		else
			$arguments['node--get'] = 'rows';
	unset($arguments[$arguments['node--get']]);

//echo(' csv $nodesource : '); var_dump($nodesource);
//global $tree;
//echo(' tree::get_parent() : '); var_dump($tree->get_parent( page::node(null) ));

	/* execution */
	ob_start();

	page::call($nodesource, $arguments, __FILE__);
	
	$table = ob_get_clean();

//echo(' csv post call $arguments : '); var_dump($arguments);

	$csvSeparCols = isset($arguments['csv--separ--column']) ? $arguments['csv--separ--column'] : ';';
	$csvSeparRows = isset($arguments['csv--separ--row']) ? $arguments['csv--separ--row'] : '\n';
	$csvSeparFields = isset($arguments['csv--separ--field']) ? $arguments['csv--separ--field'] : chr(0);
	$csvSeparChars = array( $csvSeparCols, $csvSeparRows, $csvSeparFields );

	$csv_num_precision = isset($arguments['csv--numeric--precision']) ? (int)$arguments['csv--numeric--precision'] : 3;

	header("Content-Encoding: UTF-8");
	header('Pragma: no-cache');
	header("Expires: 0");
	
	/* 1er cas : la page a retournée les données */
	if(isset($arguments['rows'])) {

		$rows = $arguments['rows'];
		
		if($rows == null)
			die('aucun r&eacute;sultat');

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

				foreach($row as $value)
				{
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
	}

	/* 2ème cas : la page a retournée le html de la table */


	require_once('tree/simple_html_dom.php');
	try{
		$dom = str_get_html($table);//preg_replace('/[\\r\\n\\t]/', '', $table));
	}
	catch(Exception $e){

		var_dump($e);
		die('Format HTML incorrect. Html : ' . strlen($table) . ' car.');

	}
	//header 
	header('Content-type: application/csv; charset=UTF-8');
	header('Content-disposition: attachment; filename="'.$filename.'"');
	
	$fp = fopen("php://output", "w");
	
	$nRow = 0;

	$td = array();
	$tag = 'th';

	foreach($dom->find('table > thead > tr, table > tbody > tr') as $element)
	{
		$td = array();

		foreach( $element->find('th') as $row)  
		{
			$td [] = str_replace($csvSeparChars, '', $row->plaintext);
		}

		foreach( $element->find('td') as $row)  
		{
			$td [] = str_replace($csvSeparChars, '', $row->plaintext);
		}

		fputcsv($fp, $td, $csvSeparCols, $csvSeparFields);
		
		$nRow++;
	}
	if($nRow == 0)
		echo "Aucune donnee pour le .csv. Html : " . strlen($table) . " car.";

	fclose($fp);
	exit;
?>