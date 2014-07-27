<?php
$rows = array();
$maxRows = 30;
for($i = 0; $i < $maxRows; $i++){
	$rows[] = array(
		'id' => $i
		, 'name' => "Test " . $i
	);
}
if(isset($arguments) && ($arguments['node--get'] == 'rows')){
	$arguments['rows'] = $rows;
	return;
}
$viewer = tree::get_node_by_name('/_Exemples/Convertisseurs/table/csv')['id'];
$viewer_options = "&node=" . $node['id']
				. "&file--name=" . urlencode($node['nm']);
?><h1>requete</h1>
<a class="file-download" href="view.php?id=<?=$viewer?><?=$viewer_options?>&vw=file.call" style="margin-left: 2em;">télécharger</a>
<?php
	var_dump($rows);
?>