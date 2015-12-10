<?php
return function($dataNodeId, $tables, $user){
	$node = node($node, __FILE__);
	if(!$tables){
		echo __FILE__ . ' : $tables manquant.';
		return;
	}
	$funcBackup = node('backup', $node, 'call');
	$funcBackup($dataNodeId, $user);

	return file_put_contents( node($dataNodeId, $node, 'file'), var_export($tables, true));
};
?>