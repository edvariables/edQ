<?php
ini_set( "display_errors", 1);
error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

	if(!isset($arguments))
		$arguments = array();

	$arguments['q--limit'] = 99999;

	//node source 
	$nodesource = isset($arguments['node']) ? $arguments['node'] : isset($_REQUEST['node']) ? $_REQUEST['node'] : '..'; 
	if(is_numeric($nodesource)){
		global $tree;
		$nodesource = $tree->get_path_string($nodesource);
	}
	$arguments['node'] = $nodesource;
	//filename
	$filename = isset($arguments['file--name']) ? $arguments['file--name'] : isset($_REQUEST['file--name']) ? $_REQUEST['file--name'] : 'table'; 
	if(!preg_match('/\.csv$/', $filename))
		$filename .= '.csv';

	if(!isset($arguments['node--get']))
		if(isset($_REQUEST['node--get']))
			$arguments['node--get'] = $_REQUEST['node--get'];
		else
			$arguments['node--get'] = 'rows';
	unset($arguments[$arguments['node--get']]);

	/* execution */
	ob_start();

	page::call($nodesource, $arguments, __FILE__);
	
	$table = ob_get_clean();

	header("Content-Encoding: UTF-8");
	header('Pragma: no-cache');
	header("Expires: 0");
	
	/* 1er cas : la page a retournée les données */
	if(isset($arguments['rows'])) {

		$rows = $arguments['rows'];
		
		if($rows == null)
			die('aucun r&eacute;sultat');

		// $is_associative
		$is_associative = count($rows) > 0 && isAssociative($rows[0]);
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
				if($nRow++ == 0){
					$td = array();
					if($is_associative)
						foreach($row as $column => $value)
							$td[] = $column;
					else if($columns)
						foreach($columns as $column)
							$td[] = $column['id'];
					fputcsv($fp, $td, ";");
				}
				
				// data
				$td = array();

				foreach($row as $value)
				{
					switch(gettype($value)){
						case "double":
						case "float":
							$td[] = number_format($value, 3, ',', '');
							break;
						case "boolean":
							$td[] = $value ? '1' : '0';
							break;
						default:
							$td[] = (string)$value;
							break;
					}
				}
				fputcsv($fp, $td, ";");
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
		die('Format HTML incorrect');

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
			$td [] = $row->plaintext;
		}

		foreach( $element->find('td') as $row)  
		{
			$td [] = $row->plaintext;
		}

		fputcsv($fp, $td, ";");
		
		$nRow++;
	}
	if($nRow == 0)
		echo "Aucune donnee";
	fclose($fp);
	exit;
?>