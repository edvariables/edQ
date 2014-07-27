<?php
$db = get_db();
$nodes = $db->all('SELECT d.*, s.lvl
	 FROM tree_struct s
	 JOIN tree_data d
	 	 ON s.id = d.id
	 ORDER BY s.lft');
$dir = helpers::get_pagesPath();
$uid = uniqid('nodes');
?><table id="<?=$uid?>"><caption>tree</caption>
<tbody><?php
$prev_lvl = 0;
$prev_node = null;
$parents = array();
$counter = 0;
foreach ($nodes as $node) {
	 if($prev_lvl < $node['lvl'])
	 	 $parents[] = $prev_node['nm'];  
	 else if($prev_lvl > $node['lvl'])
	 	 $parents = array_slice($parents, 0, $node['lvl']);
	$dir_exists = file_exists( $file );
	$php_exists = file_exists( $file . '.php' );
    echo '<tr class="'. ($dir_exists ? 'is-dir' : '') . ($php_exists ? ' is-php' : '') . '">';
    echo '<td>' . join('/', $parents) . '/</td>'; 
    echo '<td> ' . $node['nm'] . ' </td>';
	$file = $dir . '/' . join('/', $parents) . '/' . $node['nm'];
    echo '<td> ' . ( $dir_exists ? ' répertoire' : '' ). ' </td>';
    echo '<td> ' . ( $php_exists ? ' fichier ' : '' ). ' </td>';
	$counter++;
	$prev_node = $node;
	$prev_lvl = $node['lvl'];
}
?>
</tbody>
<tfoot><tr><td><?=$counter?> noeuds</tfoot>
</table>
<style>
#<?=$uid?> tr {
	 background-color: #EAEAEA;
}
#<?=$uid?> tr.is-dir {
	 background-color: yellow;
}
#<?=$uid?> tr.is-dir.is-php {
	 background-color: lightgreen;
}
#<?=$uid?> tr.is-php {
	 background-color: lightblue;
}
#<?=$uid?> td:nth-child(3), #<?=$uid?> td:nth-child(4) {
	 font-style: italic;
}
#<?=$uid?> td:nth-child(2), #<?=$uid?> td:nth-child(3) {
	 padding-right: 12px;
}
</style>