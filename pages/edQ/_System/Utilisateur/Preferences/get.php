<?php
if(!userRight()) die('Access denied');
$db = get_db();
$arguments_isset = isset($arguments);
$domain = ($arguments_isset && isset($arguments['domain']))
		? $arguments['domain']
		: (isset($_REQUEST) && isset($_REQUEST['f--domain'])
			? $_REQUEST['f--domain']
			: false);
$param = ($arguments_isset && isset($arguments['param']))
		? $arguments['param']
		: (isset($_REQUEST) && isset($_REQUEST['f--param'])
			? $_REQUEST['f--param']
			: false);
$sql = "
	SELECT domain, param, value, sortIndex
	FROM user_param up
	WHERE up.id = ?";
$params = array();
$params[] = $_SESSION['edq-user']['id'];
if($domain){
	$sql .= " AND domain = ?";
	$params[] = $domain;
}
if($param){
	$sql .= " AND param = ?";
	$params[] = $param;
}
$sql .= "
	ORDER BY domain, param, value, sortIndex";

	$rows = $db->all( $sql, $params );

if($arguments_isset){
	if(isset($arguments['node--get']) && $arguments['node--get']){
		$arguments[ $arguments['node--get'] ] = $rows;
	}
	else if(isset($arguments['return']) && $arguments['return']){
		if($arguments['return'] == 'rows')
			$arguments[ 'rows' ] = $rows;
		else
			$arguments[ is_bool($arguments['return']) ? 'return' : $arguments['return'] ]
				= count($rows) > 0 ? $rows[0]['value'] : null;
	}
	else 
		$arguments[ 'return' ]
			= count($rows) > 0 ? $rows[0]['value'] : null;
	return;
}

echo ( json_encode($rows) );

die();
?>