<?php
$node = node($node, __FILE__);
//bouton de recherche
/*?><input type="submit" value="Rechercher" style="margin-left: 2em;"/><?php*/
//lien de téléchargement
// source de donnees
if(isset($arguments) && isset($arguments['csv--node']))
	$csv_node = $arguments['csv--node'];
else if(isset($_REQUEST['csv--node']))
	$csv_node = $_REQUEST['csv--node'];
else
	$csv_node = false;
if($csv_node){
	// type de donnees a demander
	if(isset($arguments) && isset($arguments['csv--rows']))
		$csv_rows = $arguments['csv--rows'];
	else if(isset($_REQUEST['csv--rows']))
		$csv_rows = $_REQUEST['csv--rows'];
	else 
		$csv_rows = 'rows';
	//nom du fichier
	if(isset($arguments) && isset($arguments['csv--file']))
		$csv_file = $arguments['csv--file'];
	else if(isset($_REQUEST['csv--file']))
		$csv_file = $_REQUEST['csv--file'];
	else
		$csv_file = is_array($csv_node) ? $csv_node['nm'] : "donnees";
	
	//$node_arguments
	if(isset($arguments) && isset($arguments['node--arguments']))
		$node_arguments = $arguments['node--arguments'];
	else if(isset($_REQUEST['node--arguments']))
		$node_arguments = $_REQUEST['node--arguments'];
	else
		$node_arguments = '';

	$viewer = node('from rows', $node, 'id');
	$viewer_options = "&node=" . (is_array($csv_node) ? $csv_node['id'] : $csv_node)
		. "&file--name=" . urlencode($csv_file)
		. "&node--get=" . $csv_rows
		. ($node_arguments ? "&" . $node_arguments : '');
	?><a class="file-download" href="view.php?id=<?=$viewer?><?=$viewer_options?>&vw=file.call" style="margin-left: 2em;">télécharger</a><?php
}?>