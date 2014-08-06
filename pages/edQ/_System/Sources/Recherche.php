<?php
$args = array(
	"domain" => '_Sources/Recherche'
	, "param" => 'form'
	, "return" => 'value'
);

$defaults = array(
	'root' => '/',
	'extensions' => 'php|js|css|html?',
	'content' => false
);
$arguments = array_merge($_REQUEST, isset($arguments) ? $arguments : array());
if(!isset($arguments['f--submit'])){
	page::call('/_System/Utilisateur/Preferences/get', $args);
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
		'content' => isset($arguments['f--content']) ? $arguments['f--content'] : $defaults['content'],
		'extensions' => isset($arguments['f--extensions']) ? $arguments['f--extensions'] : $defaults['extensions']
	);
	$args['value'] = $prefs;
	page::call('/_System/Utilisateur/Preferences/set', $args);
}


$root = $prefs['root'];
$extensions = $prefs['extensions'];

$content = $prefs['content'];

$dir = dirname(helpers::get_pages_path()) . $root; //TODO do better than dirname
$dir_len = strlen($dir);

$uidform = uniqid('form');
?>
<form id="<?=$uidform?>" method="POST" action="<?=page::url( $node )?>&f--submit=1" autocomplete="off" style="margin-bottom: 2em;">
	<fieldset><legend>Recherche dans les fichiers Sources</legend>
	racine : <input size="32" value="<?=$root?>" name="f--root"/>
	extensions : <input size="32" value="<?=$extensions?>" name="f--extensions"/> exple : <code>php|js|css|html?</code>
<br/>contient : <input size="48" value="<?=$content?>" name="f--content"/>
<input type="submit" value="Chercher (dans) les fichiers" style="margin-left: 2em;"/>
</fieldset></form>
<?= isset($view) ? $view->searchScript($uidform) : '$view no set'?>
<?php
$files = array();
if($extensions)
	$extensions = '/\.(' . $extensions . ')$/';
$root_dir_exclude_preg = '/(\..*|pages|tmp|sessions)$/';

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
	, array( 'title' => 'name' )
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