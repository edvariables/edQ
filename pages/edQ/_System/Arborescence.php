<?php
$dir = helpers::get_pagesPath();
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::SELF_FIRST) as $f=>$cur){
echo(print_r($cur, true));
echo('<br>');
}
?>