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
	//filename
	$filename = isset($arguments['file--name']) ? $arguments['file--name'] : 'table.csv'; 


	ob_start();

	call_page($nodesource, $arguments, __FILE__);
	
	$table = ob_get_clean();


	//header 
	header("Content-Encoding: UTF-8");
	header('Content-type: application/csv; charset=UTF-8');
	header("Content-disposition: attachment; filename=".$filename);
	header("Pragma: no-cache");
	header("Expires: 0");

	require_once('tree/simple_html_dom.php');
	$dom = str_get_html(preg_replace('/[\\r\\n\\t]/', '', $table));


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