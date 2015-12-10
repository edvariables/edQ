<?php
return function($reponseNodeId){
	$node = node($node, __FILE__);
	$strValues = node($reponseNodeId, $node, 'content');
	$values = preg_split('/\r\n?|\r?\n/', $strValues);
	$aValues = array();
	foreach($values as $value){
		$id = preg_replace('/^(\d+).*$/', '$1', $value);
		$aValues[] = array('id' => $id, 'text' => $value);
	}
	return $aValues;
};
?>