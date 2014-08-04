<?php
$dir = helpers::get_pages_path();
foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($dir), RecursiveIteratorIterator::SELF_FIRST) as $f=>$cur){
echo(print_r($cur, true));
echo('<br>');
}
?>