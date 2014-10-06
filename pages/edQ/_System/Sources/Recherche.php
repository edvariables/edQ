<?php
$args = array(
	"domain" => '_Sources/Recherche'
	, "param" => 'form'
	, "return" => 'value'
);

$defaults = array(
	'root' => '/',
	'root_from_pages' => true,
	'extensions' => 'php|js|css|html?',
	'exclude_paths' => null, 
	'exclude_paths_pages' => 'pages|logs|cache|tmp|sessions', 
	'exclude_paths_system' => 'test|pkg|logs|cache|tmp|sessions', 
	'content' => false
);
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
		'root' => $arguments['f--root'] ? $arguments['f--root'] : $defaults['root'],
		'root_from_pages' => isset($arguments['f--root_from_pages']) ? filter_var($arguments['f--root_from_pages'], FILTER_VALIDATE_BOOLEAN) : $defaults['root_from_pages'],
		'content' => isset($arguments['f--content']) ? $arguments['f--content'] : $defaults['content'],
		'extensions' => isset($arguments['f--extensions']) ? $arguments['f--extensions'] : $defaults['extensions'],
		'exclude_paths' => isset($arguments['f--exclude_paths']) ? $arguments['f--exclude_paths'] : $defaults['exclude_paths'],
	);
	$args['value'] = $prefs;
	page::call('/_System/Utilisateur/Preferences/set', $args);
}


$root = $prefs['root'];
if(substr($root, -1) != '/'
&& substr($root, -1) != '\\')
	$root .= DIRECTORY_SEPARATOR;
$root_from_pages = $prefs['root_from_pages'];
$extensions = $prefs['extensions'];
$exclude_paths = $prefs['exclude_paths'];
if($exclude_paths === null)
	$exclude_paths = $root_from_pages ? $defaults['exclude_paths_pages'] : $defaults['exclude_paths_system'];

$content = $prefs['content'];

if($root_from_pages)
	$dir = dirname(helpers::get_pages_path()) . $root; //TODO do better than dirname
else
	$dir = $root;

$dir_len = strlen($dir);

$uidform = uniqid('form');
?>
<form id="<?=$uidform?>" method="POST" action="<?=page::url( $node )?>&f--submit=1" autocomplete="off" style="margin-bottom: 2em;">
	<fieldset><legend>Recherche dans les fichiers Sources</legend>
	racine : <select name="f--root_from_pages">
		<option value="true" <?=$root_from_pages ? ' selected=selected"' : ''?>>pages edQ</option>
		<option value="false" <?=!$root_from_pages ? ' selected=selected"' : ''?>>système</option>
		</select>
		<input size="48" value="<?=$root?>" name="f--root"/>
<br/>extensions : <input size="48" value="<?=$extensions?>" name="f--extensions"/> exple : <code>php|js|css|html?</code>
<br/>exclure : <input size="48" value="<?=$exclude_paths?>" name="f--exclude_paths"/> exple : <code>tmp|sessions|cache</code>
<br/>contient : <input size="48" value="<?=$content?>" name="f--content"/>
<input type="submit" value="Chercher (dans) les fichiers" style="margin-left: 2em;"/>
</fieldset></form>
<?= page::form_submit_script($uidform) ?>
<?php
$files = array();
if($extensions)
	$extensions = '/\.(' . $extensions . ')$/';
$root_dir_exclude_preg = '/(\..*|' . $exclude_paths . ')$/'; //TODO chemins racines

if(!isset($_REQUEST['f--content'])){
	echo("Cliquer sur Chercher");
	return;
}
$add_file = function($file) use(&$files, &$extensions, &$content){
	$enabled = is_file($file)
		&& $file[0] != '.'
		&& (!$extensions
			|| preg_match( $extensions, $file ));

	if($enabled && $content){
		$pos = stripos(file_get_contents( $file ), $content);
		if($pos === FALSE)
			$enabled = false;
	}
	if( $enabled ){
		$filesize = filesize( $file);
		if($filesize >= 1024)
			$filesize = number_format($filesize / 1024, 0 ) . ' ko';
		else
			$filesize = $filesize . ' o';
		$dirname = dirname($file);
		$files[] = array(
			/*'index' =>*/ $counter
			, /*'path' =>*/ $dirname
			, /*'name' =>*/ substr($file, strlen($dirname) + 1)
			, /*'date' =>*/ date('d/m/Y H:i:s', filemtime( $file ) )
			, /*'filesize' =>*/ $filesize
		);
		if($content)
			$files[count($files) - 1][] = $pos;

	}
};
foreach(scandir($dir) as $root_dir){
	if($root_dir[0] != '.')
		if(! preg_match($root_dir_exclude_preg, $root_dir)){
			foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir . $root_dir)
												   , RecursiveIteratorIterator::SELF_FIRST) as $f){
				$add_file((string)$f);

			}
	
		}
		else 
			$add_file($dir . $root_dir);
		
}
//$columns
$columns = array(
	array( 'title' => 'index'
		  , 'visible'=> false
			, 'type' => 'num' )
	, array( 'title' => 'path' )
	, array( 'title' => 'name'
			, 'render' => 'function ( data, type, row, meta ) {
				 return tree_select_node_alink( data, row[1] + "/" + data );
			}'
	)
	, array( 'title' => 'date'
			, 'type' => 'date' )
	, array( 'title' => 'size'
			, 'type' => 'num-fmt' )
);
if($content)
	$columns[] = array( 'title' => 'trouvé' );

$uid = uniqid('nodes');
?><table id="<?=$uid?>" cellpadding="0" cellspacing="0" border="0" 
		 class="display"></table>
<script>
		
	function tree_select_node_alink( data, file ){
		$a = $('<a href="#' + data + '"/>')
			.html( data )
			.click( tree_select_node_click )
		;
		return $('<div></div>')
			.append($('<a href="file://' + file + '" style="padding-left: 1.3em; position: relative;"/>')
				.html( '<span title="' + file + '" class="ui-icon ui-icon-script"'
					 	+ ' style="position: absolute; margin-left: 1px;"> </span>' )
				.append( data )
			)
			//.append( $a )
		;
	}
	function tree_select_node_click(){
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
		$.get('tree/db.php?op=get_view'
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
		/*, "order": [[ 0, "asc" ]]*/
		, "data" : <?= json_encode($files) ?>
		, "columns" : edQ.eval_functions( <?= json_encode($columns) ?> )
    } );
} );
</script>