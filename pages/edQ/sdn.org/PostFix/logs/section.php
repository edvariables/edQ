<?php
include(dirname(__FILE__) . '/../conf.php');

$dir = $logs_path;

if(!isset($_REQUEST['q--file'])){
	die('Fichier (q--file) non fourni');
}
$file = $dir . '/' . $_REQUEST['q--file'] . '.pflogsumm';

if(!file_exists($file)){
	die('Fichier ' . $file . ' inconnu');
}

if(!isset($_REQUEST['q--line'])){
	die('Ligne (q--line) non fournie');
}
$nFirstLine = intval(($_REQUEST['q--line'])) + 1;
	
$section_preg = '/^\w/';
$handle = fopen($file, "r");
if ($handle) {
	$nLine = 0;
	$nLineSent = 0;
	$uid = uniqid('lines');
	echo '<ul style="list-style-type: none; font-family: Courier New;">';
	while (($line = fgets($handle)) !== false) {
		if($nLine++ <= $nFirstLine)
			continue;
		if(($nLine > $nFirstLine + 1)
		&& $line[0] != ' '
		&& preg_match($section_preg, $line)){
			break;
		}
		?><li><?= $line ?></li><?php
		
		if( $nLineSent++ > 300 ){
			?><li>il existe d'autres lignes..</li><?php
			break;
		}
    }
	echo '</ul>';
} else {
    die('Erreur d\'ouverture du fichier');
} 
fclose($handle);

?>