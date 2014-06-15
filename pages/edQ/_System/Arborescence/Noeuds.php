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
    echo '<tr>';
    echo '<td>' . join('/', $parents) . '/</td>'; 
    echo '<td> ' . $node['nm'] . ' </td>';
	 $file = $dir . '/' . join('/', $parents) . '/' . $node['nm'];
    echo '<td> ' . ( file_exists( $file ) ? ' répertoire' : '' ). ' </td>';
    echo '<td> ' . ( file_exists( $file . '.php' ) ? ' fichier ' : '' ). ' </td>';
	 $counter++;
	 $prev_node = $node;
	 $prev_lvl = $node['lvl'];
}
?>
</tbody>
<tfoot><tr><td><?=$counter?> noeuds</tfoot>
</table>
<style>
#<?=$uid?> td:nth-child(3), #<?=$uid?> td:nth-child(4) {
	 font-style: italic;
}
#<?=$uid?> td:nth-child(2), #<?=$uid?> td:nth-child(3) {
	 padding-right: 12px;
}
</style>