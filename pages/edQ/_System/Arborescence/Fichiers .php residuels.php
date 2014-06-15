<?php
if(isset($_REQUEST['delete'])){
	 session_start();
	 if($_SESSION['edq-user']['rights']['Admin'] == 15){
	 	 $file = utf8_decode(str_replace('/', DIRECTORY_SEPARATOR, $_REQUEST['delete']));
	 	 if(!file_exists($file)) die('Le fichier "' . $file . '" n\'existe pas.');
	 	 if($_REQUEST['type'] == 'file') unlink($file);
	 	 else if($_REQUEST['type'] == 'dir') rmdir($file);
	 	 die(1);
	 }
	 die('Action non autorisée');
}
$url= url_page('', $node);
$db = get_db();
$nodes = $db->all('SELECT d.*, s.lvl
	 FROM tree_struct s
	 JOIN tree_data d
	 	 ON s.id = d.id
	 ORDER BY s.lft');
$known = array();
$prev_lvl = 0;
$prev_node = null;
$parents = array();
foreach ($nodes as $node) {
	 if($prev_lvl < $node['lvl'])
	 	 $parents[] = $prev_node['nm'];  
	 else if($prev_lvl > $node['lvl'])
	 	 $parents = array_slice($parents, 0, $node['lvl']);
	if($node['lvl']) {
    /*echo '<tr><td>' . $node['lvl'] . '</td>';
    echo '<td>' . join('/', $parents) . '</td>'; 
    echo '<td> ' . $node['nm'] . ' </td>';*/
	 $name = join('/', $parents) . '/' . $node['nm'];
	 //echo '<tr><td>' . $name . '</td>';
    
	 $known[$name] = $node;
	 }
	 $prev_node = $node;
	 $prev_lvl = $node['lvl'];
}

$dir = helpers::get_pagesPath();
$files = array();
?><table><caption style="white-space: nowrap">Fichiers ne correspondant à aucun noeud<br/>dans <?=$dir?></caption>
<tbody><?php
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::SELF_FIRST) as $f){
$php = substr($f, strlen($f) - 4) == '.php';
$isDir = !$php && is_dir($f);
if($php || $isDir) {
	 $f = utf8_encode(str_replace('\\', '/', $f));
	 $name = str_replace('\\', '/', substr($f, strlen($dir) + 1));
	 if(strpos( $name, '/' ) === false)
	 	 continue;
	 if($php)
	 	 $name = substr($name, 0, strlen($name) - 4);
	 if(!isset($known[$name])){
	 	 $files[] = $name;
	     ?><tr><td><td><?= $name . ($php ? '.php' : '/') ?></td>
	 	 <td><i><i><a href onclick="if(!confirm('Supprimer définitivement <?= $f ?> ?')) return false;
	 	 	 var $this = $(this);
	 	 	 $.ajax({ url: '<?= $url ?>', data : { 'delete' : '<?= $f ?>', 'type' : '<?= $php ? 'file' : 'dir' ?>' }
	 	 	 	 , success : function(response){
	 	 	 	 	 if(isNaN(response)) $('<div></div>').html(response).dialog( { width: 'auto', height: 'auto' });
	 	 	 	 	 else $this.parents('tr:first').children('td').css('text-decoration', 'line-through').end().end().remove();
	 	 	 	 } });
	 	 	 return false;">supprimer<a></i>
	 <?php
	 }
}}
?>
</tbody>
<tfoot><tr><td><?=count($files)?> fichier(s)</tfoot>
</table>