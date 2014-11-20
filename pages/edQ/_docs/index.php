<?php
$node = node($node, __FILE__);
$args = array(
	"domain" => '_docs/index'
	, "param" => 'form'
	, "return" => 'value'
);
$defaults = array(
	'root' => node($node, null, 'page'),
	'content' => false,
	'exclude_paths' => 'index', 
	'content' => false
);
//var_dump($defaults);
$arguments = array_merge($_REQUEST, isset($arguments) ? $arguments : array());
if(!isset($arguments['f--submit'])){
	$args = page::call('/_System/Utilisateur/Preferences/get', $args);
	if(isset($args['value'])){
		$prefs = json_decode($args['value'], true);
		if(!is_object($prefs) && !is_array($prefs))
			$prefs = $defaults;
	}
	else
		$prefs = $defaults;
}
else {
	$prefs = array(
		'root' => $arguments['f--root'],
		'content' => isset($arguments['f--content']) ? $arguments['f--content'] : false,
		'exclude_paths' => isset($arguments['f--exclude_paths']) ? $arguments['f--exclude_paths'] : $defaults['exclude_paths'],
	);
	$args['value'] = $prefs;
	page::call('/_System/Utilisateur/Preferences/set', $args);
}

$db = get_db('/_System/dataSource');
$nodes = $db->all('SELECT d.*, s.lvl
	 FROM tree_struct s
	 JOIN tree_data d
	 	 ON s.id = d.id
	 ORDER BY s.lft');
$dir = helpers::get_pages_path();
$uidform = uniqid('form');
?>
<form id="<?=$uidform?>" method="POST" action="<?=page::url( $node )?>&f--submit=1" autocomplete="off" style="margin-bottom: 2em;">
<fieldset><legend>Recherche dans les noeuds et leur fichier de page</legend>
	racine : <input size="32" value="<?=$prefs['root']?>" name="f--root"/>
<br/>contient : <input size="48" value="<?=htmlentities($prefs['content'])?>" name="f--content"/>
<input type="submit" value="Chercher dans la base et dans les fichiers" style="margin-left: 2em;"/>
</fieldset></form>
<?= page::form_submit_script($uidform) ?>
<?php
$content = $prefs['content'];

$exclude_paths = $prefs['exclude_paths'];
if($exclude_paths === null)
	$exclude_paths = $root_from_pages ? $defaults['exclude_paths_pages'] : $defaults['exclude_paths_system'];

$root_dir_exclude_preg = '/(\..*|' . $exclude_paths . ')$/'; //TODO chemins pages

$files = array();
$file = $dir;
$prev_lvl = 0;
$prev_node = null;
$parents = array();
$counter = 0;
$search_lvl = count( explode( '/', preg_replace('/^\/|\/$/', '', $prefs['root']) )) - 1;
$search = $dir . $prefs['root'];
if(DIRECTORY_SEPARATOR == '\\'){
	$dir = str_replace('/', DIRECTORY_SEPARATOR, $dir);	
	$search = str_replace('/', DIRECTORY_SEPARATOR, $search);
}
$search_len = strlen($search);
$pos = null;
//$columns
$columns = array(
	array( 'title' => 'index'
		  	, 'visible'=> false
			, 'type' => 'num' )
	, array( 'title' => 'id'
			, 'type' => 'num'
		  	, 'visible'=> false)
	, array( 'title' => 'path' )
	, array( 'title' => 'name'
			, 'render' => 'function ( data, type, full, meta ) {
				  return tree_select_node_alink( full[ 1 ], full[ 2 ], data );
			}'
	)
	, array( 'title' => 'date'
			, 'type' => 'date' )
	, array( 'title' => 'size'
			, 'type' => 'num-fmt' )
);
if($content)
	$columns[] = array( 'title' => 'trouvé' );

foreach ($nodes as $node) {
	if($prev_lvl < $node['lvl'])
	 	 $parents[] = $prev_node['nm'];  
	else if($prev_lvl >= $node['lvl'])
	 	 $parents = array_slice($parents, 0, $node['lvl']);
	
	$file = $dir . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parents) . DIRECTORY_SEPARATOR . $node['nm'];
    $dir_exists = file_exists( $file );
	$php_exists = file_exists( $file . '.php' );
	
	/*if((substr( $file, 0, $search_len ) == $search) ){
		var_dump($node['lvl']);
		var_dump($file);
		var_dump($php_exists);
	}*/
	$enabled = $php_exists && (substr( $file, 0, $search_len ) == $search);
	
	if($enabled && $content){
		$pos = stripos(file_get_contents( $file . '.php' ), $content);
		if($pos === FALSE)
			$enabled = false;
	}
	if($enabled){
		$node_path = implode(DIRECTORY_SEPARATOR, array_slice ($parents, $search_lvl + 1 ));
		if($node_path === '' && $node['nm'] == 'index')
			$enabled = false;
	}	
	if( $enabled ){
		$filesize = filesize( realpath($file . '.php'));
		if($filesize >= 1024)
			$filesize = number_format($filesize / 1024, 0 ) . ' ko';
		else
			$filesize = $filesize . ' o';
		$files[] = array(
			/*'index' =>*/ $counter
			, /*'id' =>*/ $node['id']
			, /*'path' =>*/ $node_path
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
	
	function tree_select_node_alink( id, path, name ){
		var design = /[\?&]design=(1|true)/.test(window.location.href);
		var url = 'index.php'
				+ '?id=' + id
				+ (design ? '&design=1' : '');
			
		$a = $('<a href="' + url + '#' + path + '/' + name + '" node_id="' + id + '"/>').html( name );
		return $a.click( tree_select_node_click );
	}
	function tree_select_node_click(event){
		if( event.ctrlKey ){
			return;
		}
		var $dom = $(this);
		if ($dom.hasClass('noclick')) {
			$dom.removeClass('noclick');
			return;
		}
		var self = $.jstree.reference('#tree');
		self.deselect_all();

		var $node = self.get_node($dom.attr('node_id'), true);
		if($node){
			if(self.select_node($node))
				return;
		}
		$.get( 'tree/db.php?op=get_view'
			  + '&id=' + $dom.attr('node_id')
			  + '&vw=viewers'
			  + (self.settings.design ? '&design=true' : '')
			  , function (d) {
				  $('#data .default').html(d.content).show();
			  }
			 );
		return false;
	}
	
$(document).ready(function() {
	$('#<?=$uid?>').dataTable( {
		"language": {
			"url": "res/jquery/dataTables/lang/dataTables.french.json"
		}
		, "aLengthMenu": [[10, 25, 50, 100, 500, -1], [10, 25, 50, 100, 500, "tous"]]
		, "iDisplayLength": 100
		, "order": [[ 4, "desc" ]]
		, "data" : <?= json_encode($files) ?>
		, "columns" : edQ.eval_functions( <?= json_encode($columns) ?> )
    } );
} );
</script>