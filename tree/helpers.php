<?php
/* ifNull
*/
function ifNull($null, $default = ''){
	if(!isset($null) || $null === null)
		return $default;
	return $null;
}
function isAssociative($array){
	if(!is_array($array)) return false;
	foreach($array as $k=>$v)
		return $k != null;
	return false;
}

?>