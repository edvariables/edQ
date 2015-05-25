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
else
	$sheets = array(0);
$node = node($node, __FILE__);
require_once(node('..', $node, 'file')); //include the class and wrappers

$object=parseOds($fileName, $sheets); //load the ods file//Suivi budgetaire - copie //150525-TEST
//echo('<pre>'.print_r($object->columns, true).'</pre>');
$object->parseToHtml(false, node('getSheet', $node, 'id'), $fileName);
	
?>