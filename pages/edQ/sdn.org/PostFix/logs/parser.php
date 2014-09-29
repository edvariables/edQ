<?php
/* contrôle de l'existence du fichier de stat d'un fichier de log */

if(!isset($arguments) || !isset($arguments['q--logfile']))
	return;
$fileSrc = $arguments['q--logfile'];
$extension = LOG_PARSER_EXTENSION;

if(file_exists($fileSrc . $extension)){
	$src_date = filectime($fileSrc);
	$dest_date = filectime($fileSrc . $extension);
	if($src_date > $dest_date){
		echo '<br/>Suppression de l\analyse du fichier car plus ancienne que le log.<br/>';
		unlink($fileSrc . $extension);
	}
	else
		return;//existe deja
}


$parser = dirname(__FILE__) . '/../pflogsumm/pflogsumm.pl';
if(!file_exists($parser))
	die(sprintf('%s n\'existe pas.', $parser));
$cmd = sprintf('perl %s %s > %s%s', realpath($parser), $fileSrc, $fileSrc, $extension);

$return = 0;
$result = passthru($cmd, $return);
if($return){//err
	die('Exécutez la commande suivante :<br/><textarea cols="120" rows="2"> ' . htmlspecialchars($cmd) . '</textarea>');
}
//var_dump($return);
var_dump(strlen($result));
?>