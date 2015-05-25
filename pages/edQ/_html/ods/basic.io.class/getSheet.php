<?php
if(!isset($arguments))
	$arguments = $_REQUEST;
if(is_string($arguments))
	$fileName = $arguments;
if(!isset($arguments['file'])){
	echo '$arguments[\'file\'] manquant';
	return;
}
$fileName = $arguments['file'];
if(isset($arguments['sheet']))
	$sheet = $arguments['sheet'];
else
	$sheet = 0;
$node = node($node, __FILE__);
require_once(node('..', $node, 'file')); //include the class and wrappers

$object=parseOds($fileName, $sheet); //load the ods file//Suivi budgetaire - copie //150525-TEST
//echo('<pre>'.print_r($object->columns, true).'</pre>');
$object->parseSheetToAllHtml($sheet);
	
?>