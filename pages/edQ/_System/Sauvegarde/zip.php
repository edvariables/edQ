<?php 
/* Sauvegarde des éléments du site : base MySQL, sources ou pages locales (associées à l'arbre MySQL)
 *
 * Extension php nécessaire : zlib output compression pour library ZipArchive
 *
*/
ini_set( "display_errors", 1);
error_reporting (E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);

if(!isset($node)){
	$node = page::node('.', __FILE__);
	if(!$node) throw new Exception('Noeud introuvable');
}
if(isset($arguments) && isset($arguments['backup']))
	$backup = $arguments['backup'];
else if(isset($_REQUEST['backup']))
	$backup = $_REQUEST['backup'];
else
	$backup = null;

if($backup == null){
	$uid = uniqid('zip');
	$url = page::url($node);
	?><form id="<?=$uid?>">
	<fieldset><legend>Téléchargez une sauvegarde compressée</legend>
	<ul class="edq-buttons">
	<li><a href="<?=$url?>&backup=sql+pages+sources">SQL + Pages + Sources</a>
	<li><a href="<?=$url?>&backup=sql+sources">SQL + Sources</a>
	<li><a href="<?=$url?>&backup=sql+pages">SQL + Pages</a>
	<li><a href="<?=$url?>&backup=pages">Pages seules</a>
	<li><a href="<?=$url?>&backup=sql">SQL seul</a>
	<li><a href="<?=$url?>&backup=sources">Sources seules</a>
	</ul></fieldset></form>
	<?php
	return;
}

$tmp = array();
preg_match_all('/\b(\w+)\b/', $backup, $tmp);
if(count($tmp) <= 0)
	die('Argument "backup" manquant ou inconnu ' . print_r($backup, true));
$backup = array_combine($tmp[1], $tmp[1]);

header('Pragma: no-cache');
header("Expires: 0");

$zip = new ZipArchive();

function folderToZip($folder, &$zipFile, $subfolder = null, $preg_exclude = false) {
	if ($zipFile == null) {
		// no resource given, exit
		return false;
	}
	// we check if $folder has a slash at its end, if not, we append one
	$folder .= end(str_split($folder)) == "/" ? "" : "/";
	if($subfolder == null)
		$subfolder = '';
	else
		$subfolder .= end(str_split($subfolder)) == "/" ? "" : "/";
	// we start by going through all files in $folder
	$handle = opendir($folder);
	while ($f = readdir($handle)) {
		if ($f != "." && $f != ".."
		&& (is_bool($preg_exclude) || (preg_match($preg_exclude, $folder . $f) !== 1))
		) {
			//echo $f . " = " . (is_bool($preg_exclude) ? '' : preg_match($preg_exclude, $f)) . '<br>';
			if (is_file($folder . $f)) {
				// if we find a file, store it
				// if we have a subfolder, store it there
				if ($subfolder != null)
					$zipFile->addFile($folder . $f, $subfolder . $f);
				else
					$zipFile->addFile($folder . $f, $f);
				
			} else if (is_dir($folder . $f)) {
				// if we find a folder, create a folder in the zip
				$zipFile->addEmptyDir($subfolder . $f);
				// and call the function again
				folderToZip($folder . $f, $zipFile, $subfolder . $f, $preg_exclude);
				
			}
		}
	}
}

// nom de base
$uid = DBNAME . "." . date("Y-m-d-His");

// zip
$zip_filename = helpers::combine(get_temp_dir(), $uid . ".zip");
if(file_exists($zip_filename))
	unlink($zip_filename);
$zip->open($zip_filename, ZIPARCHIVE::CREATE);

// sql
if(isset($backup['sql'])){
	$filename = helpers::combine(sys_get_temp_dir(), $uid . ".sql");
	$cmd = MYSQLDUMP . " --host=".DBSERVER." --user=".DBUSER." --password=".DBPASSWORD." ".DBNAME//." | gzip --best" TODO
		. " > " . $filename
		;
	passthru( $cmd );
	
    $zip->addEmptyDir('mySQL');
    $zip->addFile($filename, 'MySql/' . basename($filename));
}

// sources
if(isset($backup['sources'])){
	$root = helpers::combine($_SERVER[ 'DOCUMENT_ROOT' ], dirname(substr($_SERVER[ 'REQUEST_URI' ], 1)));
	folderToZip($root, $zip, NULL, '/[\\\\\/](((pages|sessions|backup|tmp)(\.[^\\\\\/]+)?)$|\.)/');
	global $tree;
	// pages sous /edQ/
	$rootPages = $tree->get_children(TREE_ROOT);
	foreach($rootPages as $path)
		if($path['nm'][0] == '_') //commence par '_'
			folderToZip($root . '/pages/' . TREE_ROOT_NAME . '/' . $path['nm']
						, $zip
						, 'pages/' . TREE_ROOT_NAME . '/' . $path['nm']);
}

// pages
if(isset($backup['pages'])){
    $zip->addEmptyDir('pages');
	folderToZip(helpers::get_pages_path(), $zip, 'pages', '/[\\\\\/]pages[\\\\\/]' . TREE_ROOT_NAME . '[\\\\\/]_[^\\\\\/]+$/');
}

$zip->close();

if(isset($arguments) && isset($arguments['get']) && $arguments['get'] == 'filename')
	die($zip_filename);

$mime = "application/zip";
header( "Content-Type: " . $mime );
header( 'Content-Disposition: attachment; filename="' . basename($zip_filename) . '"' );

readfile ($zip_filename);
unlink ($zip_filename);
exit;

?>