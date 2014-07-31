<?php
$arguments = array_merge($_REQUEST, isset($arguments) ? $arguments : array());
$root = isset($arguments['f--root']) ? $arguments['f--root'] : '/edQ';


$db = get_db();
$nodes = $db->all('SELECT d.*, s.lvl
	 FROM tree_struct s
	 JOIN tree_data d
	 	 ON s.id = d.id
	 ORDER BY s.lft');
$dir = helpers::get_pagesPath();
$uid = uniqid('nodes');
$uidform = uniqid('form');
?>
<form id="<?=$uidform?>" method="POST" action="<?=url_view( $node )?>" autocomplete="off">
racine : <input size="32" value="<?=$root?>" name="f--root"/>		
<input type="submit" value="Recharger" style="margin-left: 2em;"/>
	</form>
<?= isset($view) ? $view->searchScript($uidform) : '$view no set'?>
<table id="<?=$uid?>" class="stripe"><caption>Arborescence des noeuds en base de données</caption>
<thead><tr>
	<th></th>
	<th>chemin</th>
	<th></th>
	<th></th>
	<th>nom</th>
	<th>modifié</th>
	</tr></thead>
	<tbody><?php
$file = $dir;
$prev_lvl = 0;
$prev_node = null;
$parents = array();
$counter = 0;
$search_lvl = count( explode( '/', preg_replace('/^\/|\/$/', '', $root) )) - 1;
$search = $dir . $root;
$search_len = strlen($search);
foreach ($nodes as $node) {
	 if($prev_lvl < $node['lvl'])
	 	 $parents[] = $prev_node['nm'];  
	 else if($prev_lvl > $node['lvl'])
	 	 $parents = array_slice($parents, 0, $node['lvl']);
	$file = $dir . '/' . join('/', $parents) . '/' . $node['nm'];
    $dir_exists = file_exists( $file );
	$php_exists = file_exists( $file . '.php' );
	if( substr( $file, 0, $search_len ) == $search ){
		echo '<tr class="'. ($dir_exists ? 'is-dir' : '') . ($php_exists ? ' is-php' : '')
			. ($counter % 2 ? " event" : " odd") . '">';
		echo '<td>' . $counter . '</td>'; 
		echo '<td class="dir">' . implode('/', array_slice ($parents, $search_lvl )) . '/</td>'; 
		echo '<td class="is-dir jstree-default"> ' . ( $dir_exists ? ' <i class="jstree-icon file file-folder"></i>' : '' ). ' </td>';
		echo '<td class="is-file jstree-default"> ' . ( $php_exists ? ' <i class="jstree-icon file file-file"></i>' : '' ). ' </td>';
		echo '<td class="nm"> ' . $node['nm'] . ' </td>';
		echo '<td class="date"> ' . ( $php_exists ? date('d/m/Y H:i:s', filemtime( realpath($file . '.php')) ) : '' ). ' </td>';
		$counter++;
	}
	$prev_node = $node;
	$prev_lvl = $node['lvl'];
}
?>
</tbody>
	</table>

<style>
table#<?=$uid?>.dataTable tbody td {
	padding: 4px;
}
table#<?=$uid?> tbody td:nth-child(1){
	color: transparent;
}
/*#<?=$uid?> tbody tr {
	 background-color: #DAEAEA;
}*/
#<?=$uid?> tbody tr:hover {
	 font-weight: bold;
}
/*#<?=$uid?> tbody td.dir, #<?=$uid?> td.nm {
	font-family: Courier New;
}*/
#<?=$uid?> tbody td.dir {
	padding-left: 12px;
	 font-size: smaller;
}
#<?=$uid?> tbody td.nm {
	 padding-right: 12px;
}
/*#<?=$uid?> tbody tr.is-dir {
	background-color: yellow;
}
#<?=$uid?> tbody tr.is-dir.is-php {
	 background-color: lightgreen;
}
#<?=$uid?> tr.is-php {
	 background-color: lightblue;
}*/
#<?=$uid?> tbody td.is-file, #<?=$uid?> td.is-dir {
	font-style: italic;
	padding-right: 4px;
	text-align: right;
	/* background-color: white;*/
}
#<?=$uid?> tbody td.date {
	font-size: smaller;
	padding-left: 1em;
}
</style>
<script>
$(document).ready(function() {
    $('#<?=$uid?>').dataTable( {
		"language": {
			"url": "jquery/dataTables/lang/dataTables.french.json"
		},
		"aLengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "tous"]],
		"iDisplayLength": 100
		//"order": [[ 0, "asc" ]]
    } );
} );
</script>