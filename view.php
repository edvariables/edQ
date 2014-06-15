<?php
	$_REQUEST['operation'] = 'get_view';
	$_GET['operation'] = $_REQUEST['operation'];
	$_REQUEST['get'] = 'content';
	if(!isset($_GET['vw'])){
		$_GET['vw'] = 'file.call';
		$_REQUEST['vw'] = $_GET['vw'];
	}
	include('tree/db.php');
?>