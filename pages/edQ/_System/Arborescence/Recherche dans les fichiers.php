<?php
$arguments = array_merge($_REQUEST, isset($arguments) ? $arguments : array());
$root = isset($arguments['f--root']) ? $arguments['f--root'] : '/edQ';
$content = isset($arguments['f--content']) ? $arguments['f--content'] : false;
/*if($content)
	$preg_content = "/" . preg_quote($content) . "/";*/

$db = get_db();
$nodes = $db->all('SELECT d.*, s.lvl
	 FROM tree_struct s
	 JOIN tree_data d
	 	 ON s.id = d.id
	 ORDER BY s.lft');
$dir = helpers::get_pagesPath();
$uidform = uniqid('form');
?>
<form id="<?=$uidform?>" method="POST" action="<?=url_view( $node )?>" autocomplete="off" style="margin-bottom: 2em;">
<fieldset>racine : <input size="32" value="<?=$root?>" name="f--root"/>
<br/>contient : <input size="48" value="<?=$content?>" name="f--content"/>
<input type="submit" value="Chercher dans la base et dans les fichiers" style="margin-left: 2em;"/>
</fieldset></form>

<?= isset($view) ? $view->searchScript($uidform) : '$view no set'?>
<?php
$files = array();
$file = $dir;
$prev_lvl = 0;
$prev_node = null;
$parents = array();
$counter = 0;
$search_lvl = count( explode( '/', preg_replace('/^\/|\/$/', '', $root) )) - 1;
$search = $dir . $root;
if(DIRECTORY_SEPARATOR == '\\'){
	$dir = str_replace('/', DIRECTORY_SEPARATOR, $dir);	
	$search = str_replace('/', DIRECTORY_SEPARATOR, $search);
}
$search_len = strlen($search);
$pos = null;
//$columns
$columns = array(
	array( 'title' => 'index'
		  , 'visible'=> false )
	, array( 'title' => 'path' )
	, array( 'title' => 'name' )
	, array( 'title' => 'date' )
	, array( 'title' => 'size' )
);
if($content)
	$columns[] = array( 'title' => 'trouvé' );

foreach ($nodes as $node) {
	if($prev_lvl < $node['lvl'])
	 	 $parents[] = $prev_node['nm'];  
	else if($prev_lvl >= $node['lvl'])
	 	 $parents = array_slice($parents, 0, $node['lvl']);
	
	$file = $dir . DIRECTORY_SEPARATOR . join(DIRECTORY_SEPARATOR, $parents) . DIRECTORY_SEPARATOR . $node['nm'];
    $dir_exists = file_exists( $file );
	$php_exists = file_exists( $file . '.php' );
	if($php_exists
	&& (substr( $file, 0, $search_len ) == $search) ){
		if($content){
			$pos = strpos(file_get_contents( $file . '.php' ), $content);
			if($pos === FALSE){
				continue;
			}
		}
		$filesize = filesize( realpath($file . '.php'));
		if($filesize >= 1024)
			$filesize = number_format($filesize / 1024, 0 ) . ' ko';
		else
			$filesize = $filesize . ' o';
		
		$files[] = array(
			/*'index' =>*/ $counter
			, /*'path' =>*/ implode(DIRECTORY_SEPARATOR, array_slice ($parents, $search_lvl ))
			, /*'name' =>*/ $node['nm']
			, /*'date' =>*/ date('d/m/Y H:i:s', filemtime( realpath($file . '.php')) )
			, /*'filesize' =>*/ $filesize
		);
		if($content)
			$files[count($files) - 1][] = $pos;
	
		$counter++;
	}
	$prev_node = $node;
	$prev_lvl = $node['lvl'];
}
$uid = uniqid('nodes');
?><table id="<?=$uid?>" cellpadding="0" cellspacing="0" border="0" 
		 class="display"></table>
<script>
$(document).ready(function() {
	$('#<?=$uid?>').dataTable( {
		"language": {
			"url": "jquery/dataTables/lang/dataTables.french.json"
		}
		, "aLengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "tous"]]
		, "iDisplayLength": 100
		/*, "order": [[ 0, "asc" ]]*/
		, "data" : <?= json_encode($files) ?>
		, "columns" : <?= json_encode($columns) ?>
    } );
} );
</script>