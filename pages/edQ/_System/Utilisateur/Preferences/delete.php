<?php
if(!user_right()) die('Access denied ' .__FILE__);
if(isset($arguments) && isset($arguments['domain']))
	$domain = $arguments['domain'];
else if((isset($_REQUEST) && isset($_REQUEST['domain'])))
	$domain = $_REQUEST['domain'];
else
	die('Aucune donnée');
if(isset($arguments) && isset($arguments['param']))
	$param = $arguments['param'];
else if((isset($_REQUEST) && isset($_REQUEST['param'])))
	$param = $_REQUEST['param'];
else die('Aucune donnée');

$db = get_db();
$sql = "
	DELETE FROM user_param WHERE id = ? AND domain = ? AND param = ?";
$params = array();
$params[] = $_SESSION['edq-user']['id'];
$params[] = $domain;
$params[] = $param;

$db->query( $sql, $params );
return;
?>