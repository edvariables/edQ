<?php
$rows = array();
$maxRows = 30;
for($i = 0; $i < $maxRows; $i++){
	$rows[] = array(
		'id' => $i
		, 'name' => "Test " . $i
	);
}
if(isset($arguments))
	$arguments['data'] = $rows;
else {
	
	global $tree;
	$csv = $tree->get_child_by_name($node['id'], 'csv');
	if(is_array($csv))
		$csv = $csv['id'];
	else
		$csv = 'ERROR-' . $csv;
	$csv_options = "&node=" . $node['id'];
	
	?><a href="view.php?id=<?=$csv?><?=$csv_options?>&vw=file.call" style="margin-left: 2em;">télécharger</a>
	<?php
	
	var_dump($rows);
}
?>