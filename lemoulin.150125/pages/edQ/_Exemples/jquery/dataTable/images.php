<?php
$dir = preg_replace('/\.php$/', '', __FILE__);
$url = preg_replace('/\.php$/', '', page::file_url($node));
$filename_preg = '/\.(png|gif|jpe?g|bmp|tiff)$/';
if ($handle = opendir($dir)) {
	while (false !== ($f = readdir($handle))) {
    	if(!preg_match($filename_preg, $f))
			continue;
		?><img src="<?=$url?>/<?=$f?>"/><?=$f?><br/><?php
    }
    closedir($handle);
}
?>