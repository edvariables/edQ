<?php
$node = node($node, __FILE__);
page::call('class.dev', array(
	'homeNode' => $node,
	'dataNodeId' => node(':data', $node, 'id'),
	'reponseNodeId' => node(':reponse', $node, 'id'),
), __FILE__);
?>