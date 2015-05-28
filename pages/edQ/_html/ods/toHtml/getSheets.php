<?php
if(!isset($arguments))
	return '$arguments manquant';
if(is_string($arguments))
	$fileName = $arguments;
if(!isset($arguments['file']))
	return '$arguments[\'file\'] manquant';
$fileName = $arguments['file'];
if(isset($arguments['sheet']))
	$sheets = $arguments['sheet'];
elseif(isset($arguments['sheets']) && $arguments['sheets'])
	$sheets = array();
else
	$sheets = array(0);

if(!file_exists($fileName)){
	echo "<pre>Fichier introuvable $fileName</pre>";
	return false;
}

$node = node($node, __FILE__);
require_once(node('..', $node, 'file')); //include the class and wrappers

$object=new ods($fileName, $sheets); //load the ods file
//echo('<pre>'.print_r($object->columns, true).'</pre>');
if(isset($arguments['cacheId']))
	$object->setCacheId($arguments['cacheId']);
$object->setHiddenSheets($arguments['hiddenSheets']);
$object->setShownSheets($arguments['sheets']);
//echo('<pre>'.print_r($object->hiddenSheets, true).'</pre>');
$object->parseToHtml(false, node('getSheet', $node, 'id'), $fileName);
?>