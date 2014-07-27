<?php
$db = get_db();
$domain = isset($arguments) && isset($arguments['domain'])
		? $arguments['domain']
		: isset($_REQUEST) && isset($_REQUEST['f--domain'])
			? $_REQUEST['f--domain']
			: false;
$param = isset($arguments) && isset($arguments['param'])
		? $arguments['param']
		: isset($_REQUEST) && isset($_REQUEST['f--param'])
			? $_REQUEST['f--param']
			: false;
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

if(isset($arguments) && $arguments['node--get'] == 'rows'){
	$arguments['rows'] = $rows;
	return;
}

die ( json_encode($rows) );

return;
?>