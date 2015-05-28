<?php
$node = node($node, __FILE__);

$consts = node(':config', $node, 'call');
/*
if(user_right('Admin'))
	node('/_html/ods/toHtml/upload', $node, 'call'
		 , array_merge($consts, array( 'submit-node' => $node
									  , 'cache-reset' => true )));
*/
if(!isset($arguments))
	$arguments = $_REQUEST;
node('/_html/ods/toHtml/getSheets', $node, 'call'
	 , array_merge($consts, $arguments));
?>