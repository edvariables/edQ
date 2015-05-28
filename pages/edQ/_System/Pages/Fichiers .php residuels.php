<?php /* affiche les fichiers existants de /pages/ et non répertoriés dans l'arborescence mémorisée dans MySQL */

/* actions */
if(isset($_REQUEST['delete'])){
	 if($_SESSION['edq-user']['rights']['Admin'] == 15){
	 	 $file = str_replace('/', DIRECTORY_SEPARATOR, $_REQUEST['delete']);
	 	 if(!file_exists($file)) $file = utf8_decode($file);
	 	 if(!file_exists($file)) die('Le fichier "' . $file . '" n\'existe pas.');
	 	 if($_REQUEST['type'] == 'file') unlink($file);
	 	 else if($_REQUEST['type'] == 'dir') rmdir($file);
	 	 die(1);
	 }
	 die('Action non autorisée');
}

if(isset($_REQUEST['create'])){
	 if($_SESSION['edq-user']['rights']['Admin'] == 15){
	 	 global $tree;
		 $type = $_REQUEST['type'];
		 $file = str_replace('/', DIRECTORY_SEPARATOR, $_REQUEST['create']);
	 	 if(!file_exists($file)) $file = utf8_decode($file);
	 	 if(!file_exists($file)) die('Le fichier "' . $file . '" n\'existe pas.');
		 $path = explode(DIRECTORY_SEPARATOR, substr($file, strlen(helpers::get_pages_path()) + 1));
		 $parent = array('id' => 1);
		 for($i = 1; $i < count($path) - 1; $i++){
			 $children = $tree->get_children($parent['id']);
			 $found = false;
			 foreach($children as $child)
				 if($child['nm'] == $path[$i]){
				 	$parent = $child;
				 	$found = true;
				 	break;
			 	}
			 if(!$found){
				 die('Impossible de trouver le parent ' . $path[$i]);
			 }
				 
		 }
		 if($type == 'file')
			 $name = preg_replace('/\\.[^\\.]+$/', '', $path[count($path) - 1]);
		 else
			 $name = $path[count($path) - 1];
		 $tree->mk($child['id'], 0, array( 'nm' => $name, 'typ' => ( $type == 'file' ? 'php' : 'folder' ) ));
		 die($child['id']);
		 die('Programmation en cours...');
	 	 die(1);
	 }
	 die('Action non autorisée');
}

/* affichage */

$url= page::url($node);

/* répertorie les noeuds MySQL triés depuis les parents vers les enfants */

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
	 if($prev_lvl < $node['lvl']) // noeud enfant
	 	 $parents[] = $prev_node['nm'];  
	 else if($prev_lvl > $node['lvl']) // parent à un niveau supérieur
	 	 $parents = array_slice($parents, 0, $node['lvl']);
	if($node['lvl']) {
    /*echo '<tr><td>' . $node['lvl'] . '</td>';
    echo '<td>' . join('/', $parents) . '</td>'; 
    echo '<td> ' . $node['nm'] . ' </td>';*/
	 $name = (join('/', $parents) . '/' . $node['nm']);
	 //echo '<tr><td>' . $name . '</td>';
    
	 $known[$name] = $node;
	 }
	 $prev_node = $node;
	 $prev_lvl = $node['lvl'];
}

/* parcours des fichiers dans pages/ */
$dir = helpers::get_pages_path();
$files = array();

$uid = uniqid('table');

?><table id="<?=$uid?>"><caption style="white-space: nowrap">Fichiers ne correspondant à aucun noeud<br/>dans <?=$dir?></caption>
<tbody><?php
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::SELF_FIRST) as $f){
	if(strpos(basename($f), '._') === 0)
		continue;
	$php = substr($f, strlen($f) - 4) == '.php';
	$isDir = !$php && is_dir($f);
	if($php
	|| ( $isDir
		&& (substr($f, strlen($f) - 1) != '.'))
	) {
		 $f = str_replace('\\', '/', $f);
		 $name = str_replace('\\', '/', substr($f, strlen($dir) + 1));
		 if(strpos( $name, '/' ) === false)
			 continue;
		 if($php)
			 $name = substr($name, 0, strlen($name) - 4);
		 if(!isset($known[$name])
		   && !isset($known[utf8_encode($name)])){
			 $files[] = $name;
			?><tr f="<?= ( $f ) ?>" t="<?= $php ? 'file' : 'dir' ?>">
			<td><td><?= ( $name . ($php ? '.php' : '/')) ?></td>
			<td><i><a href class="create">créer le noeud</a></i></td>
			<td><i><a href class="delete">supprimer</a></i></td>
		 <?php
		 }
	}
}
?>
</tbody>
<tfoot><tr><td><?=count($files) ? count($files) . ' fichier(s)': 'aucun fichier'?></tfoot>
</table>
<script>
	$(document.body).ready(function(){
		$("#<?=$uid?> a.delete").click(function(){
			var $this = $(this),
			url = $(this),
			$tr = $this.parents('tr:first'),
			f = $tr.attr('f'),
			t = $tr.attr('t');
			if(!confirm('Supprimer définitivement ' + f + ' ?')) return false;
			$.ajax({ url: '<?= $url ?>'
				, data : { 'delete' : f , 'type' : t }
				, success : function(response){
					if(isNaN(response)) $('<div></div>').html(response).dialog( { width: 'auto', height: 'auto' });
					else $tr.children('td').css('text-decoration', 'line-through').end().end().remove();
				} });
			return false;
		});
		$("#<?=$uid?> a.create").click(function(){
			var $this = $(this),
			url = $(this),
			$tr = $this.parents('tr:first'),
			f = $tr.attr('f'),
			t = $tr.attr('t');
			$.ajax({ url: '<?= $url ?>'
				, data : { 'create' : f , 'type' : t }
				, success : function(response){
					if(isNaN(response)) $('<div></div>').html(response).dialog( { width: 'auto', height: 'auto' });
					else $this.css('text-decoration', 'line-through').end().end().remove();
				} });
			return false;
		});
	});
</script>
<style>
	#<?=$uid?> a {
			margin-left: 12px;
	}
</style>