<?php

include(dirname(__FILE__) . '/conf.php');

$dir = $logs_path;

$filename_preg = '/^mail\.log(\W[^\/\\\\]+)?(?<!\.pflogsumm)$/';

$files = array();

if ($handle = opendir($dir)) {
	//liste de fichiers de log
	while (false !== ($f = readdir($handle))) {
    	if(!preg_match($filename_preg, $f))
			continue;
		$files[] = $f;
    }
    closedir($handle);
}
arsort($files);

function file_size_string($file) {
	$size = filesize($file);
	switch ($size) {
		case ($size / 1073741824) > 1:
			return round(($size/1073741824), 2) . "Gb";
		case ($size / 1048576) > 1:
			return round(($size/1048576), 2) . "Mb";
		case ($size / 1024) > 1:
			return round(($size/1024), 2) . "Kb";
		default:
			return $size . ' bytes';
	}
}

$uid = uniqid('log');

// url des détails
$page_url = page::url( ':sections', $node );


echo '<ul style="list-style-type: none;">';

foreach($files as $f){
?><li><a href="#<?= $f ?>" onclick="$(this).nextAll('.log-section:first')
		.load('<?= $page_url ?>&q--file=<?= $f ?>'); return false;"><?= $f ?>
	&nbsp;(<?=file_size_string($dir . DIRECTORY_SEPARATOR. $f)?>)</a>
	<div class="log-section ui-widget-content"></div>
</li><?php
}
echo '</ul>';

?>