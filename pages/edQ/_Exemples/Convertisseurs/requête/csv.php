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
		$nodesource = utf8_decode($tree->get_path_string($nodesource));
	}
	//filename
	$filename = isset($arguments['file--name']) ? $arguments['file--name'] : 'table.csv'; 


	ob_start();

	call_page($nodesource, $arguments, __FILE__);
	
	$table = ob_get_clean();

	if(!isset($arguments['data'])){
		var_dump($nodesource);
		var_dump($table);
		die('$arguments[\'data\'] absent');
	}

	//header 
	header("Content-Encoding: UTF-8");
	header('Content-type: application/csv; charset=UTF-8');
	header("Content-disposition: attachment; filename=".$filename);
	header("Pragma: no-cache");
	header("Expires: 0");

	$rows = $arguments['data'];

	if($rows == null)
			die('Erreur dans $arguments[\'data\']');

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
?>