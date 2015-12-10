<?php

if(!is_array($arguments)){
	$arguments = array(
		'node' => '/_docs/edQ - php',
		'node_arguments' => false,
		'title' => 'pour exemple : /_docs/edQ - php',
		'viewer' => 'file.call',
	);
}
$arguments['collapsed'] = true;
node('..', node($node), 'call', $arguments);
?>