<?php
$node = node($node, __FILE__);
$consts = node('..config', $node, 'call');
return array_merge($consts, array(
	'cacheId' => basename(dirname(__FILE__)),
	'sheets' => array("Feuille2"),
));
?>