<?php
if(!user_right()) die('Access denied');
if(isset($arguments) && isset($arguments['value']))
	$value = $arguments['value'];
else if(isset($_REQUEST) && isset($_REQUEST['value']))
	$value = $_REQUEST['value'];
else
	die('Aucune donnée');
if($value == 'reset')
	$value = '';
else if(is_array($value))
	$value = '' . json_encode($value) . '';
if(isset($arguments) && isset($arguments['domain']))
	$domain = $arguments['domain'];
else if((isset($_REQUEST) && isset($_REQUEST['domain'])))
	$domain = $_REQUEST['domain'];
else $domain = false;
if(isset($arguments) && isset($arguments['param']))
	$param = $arguments['param'];
else if((isset($_REQUEST) && isset($_REQUEST['param'])))
	$param = $_REQUEST['param'];
else $param = false;
if(isset($arguments) && isset($arguments['sortIndex']))
	$sortIndex = $arguments['sortIndex'];
else if((isset($_REQUEST) && isset($_REQUEST['sortIndex'])))
	$sortIndex = $_REQUEST['sortIndex'];
else $sortIndex = 0;

$db = get_db();
$sql = "
	INSERT INTO user_param (id, domain, param, sortIndex, value)
	VALUES(?, ?, ?, ?, ?)
	ON DUPLICATE KEY UPDATE value = ?";
$params = array();
$params[] = $_SESSION['edq-user']['id'];
$params[] = $domain;
$params[] = $param;
$params[] = $sortIndex;
$params[] = $value;
$params[] = $value;

$db->query( $sql, $params );
return;
?>