<?php
$node = node($node, __FILE__);

$consts = node(':config', $node, 'call');
/*
if(user_right('Admin'))
	node('/_format/ods/to html/upload', $node, 'call'
		 , array_merge($consts, array( 'submit-node' => $node
									  , 'cache-reset' => true )));
*/
if(!isset($arguments))
	$arguments = $_REQUEST;
node('/_format/ods/to html/getSheets', $node, 'call'
	 , array_merge($consts, $arguments));
?>