<?php
if(!isset($arguments))
	$arguments = array();
$arguments['view'] = $view;

node(':html', $node, 'call', $arguments);
?>