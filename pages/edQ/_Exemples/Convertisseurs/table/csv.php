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

	call_page($nodesource, $arguments, __FILE__);
	
	$table = ob_get_clean();

	header("Content-Encoding: UTF-8");
	header('Pragma: no-cache');
	header("Expires: 0");
	
	/* 1er cas : la page a retournée les données */
	if(isset($arguments['rows'])) {

		$rows = $arguments['rows'];

		if($rows == null)
			die('aucun r&eacute;sultat');

		//header 
		header('Content-type: application/csv; charset=UTF-8');
		header('Content-disposition: attachment; filename="'.$filename.'"');

		$fp = fopen("php://output", "w");

		$nRow = 0;

		foreach($rows as $row)
		{

			if($nRow++ == 0){
				$td = array();
				foreach($row as $column => $value)
				{
					$td[] = $column;
				}

				fputcsv($fp, $td, ";");
			}

			$td = array();

			foreach($row as $column => $value)
			{
				$td[] = $value;
			}

			fputcsv($fp, $td, ";");
		}
		fclose($fp);
		exit;
	}

	/* 2ème cas : la page a retournée le html de la table */

	require_once('tree/simple_html_dom.php');
	$dom = str_get_html(preg_replace('/[\\r\\n\\t]/', '', $table));

	//header 
	header('Content-type: application/csv; charset=UTF-8');
	header('Content-disposition: attachment; filename="'.$filename.'"');
	

	$fp = fopen("php://output", "w");
	
	$nRow = 0;

	$td = array();
	$tag = 'th';

	foreach($dom->find('form > table > thead > tr, form > table > tbody > tr') as $element)
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
	}
	fclose($fp);
	exit;
?>