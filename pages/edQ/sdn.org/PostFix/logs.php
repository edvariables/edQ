<?php

include(dirname(__FILE__) . '/conf.php');

$dir = $logs_path;

$filename_preg = '/^mail\.log(\W[^\/\\\\]+)?(?<!\.pflogsumm)$/';

$files = array();

if ($handle = opendir($dir)) {
	while (false !== ($f = readdir($handle))) {
    	if(!preg_match($filename_preg, $f))
			continue;
		$files[] = $f;
    }
    closedir($handle);
}

$uid = uniqid('log');

$page_url = page::url( ':sections', $node );
echo '<ul style="list-style-type: none;">';

foreach($files as $f){
?><li><a href="#<?= $f ?>" onclick="$(this).nextAll('.log-section:first')
		.load('<?= $page_url ?>&q--file=<?= $f ?>'); return false;"><?= $f ?></a>
	<div class="log-section ui-widget-content"></div>
</li><?php
}
echo '</ul>';

?>