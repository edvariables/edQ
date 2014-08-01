<?php
include(dirname(__FILE__) . '/../conf.php');

$dir = $logs_path;

if(!isset($_REQUEST['q--file'])){
	$file = $dir . '/' . ($f = 'mail.log');
	//die('Fichier (q--file) non fourni');
}
else
	$file = $dir . '/' . ($f = $_REQUEST['q--file']);


$file .= '.pflogsumm';
if(!file_exists($file)){
	die('Fichier ' . $file . ' inconnu');
}

$section_preg = '/^\w/';
$handle = fopen($file, "r");
if ($handle) {
	$nLine = 0;
	
	$uid = uniqid('sect');

	$page_url = page::url('section', $node );
	
	echo '<ul style="list-style-type: none;">';
	
	while (($line = fgets($handle)) !== false) {
		if($line[0] != ' ' && preg_match($section_preg, $line)){
			?><li><a href="#<?= $f ?>" onclick="var $dest = $(this).nextAll('.log-data:first');
					if($dest.is(':empty')){ 
						$dest.load('<?= $page_url ?>&q--file=<?= $f ?>&q--line=<?=$nLine?>');
					} else $dest.toggle();
					return false;"><?= $line ?></a>
				<div class="log-data ui-widget-content"></div>
			</li><?php
		}
		$nLine++;
    }
	echo '</ul>';
} else {
    die('Erreur d\'ouverture du fichier');
} 
fclose($handle);

?>