<?php
if(!isset($arguments)
|| isset($arguments['list'])){
	if(isset($arguments))
		$node = node($arguments['list'], __FILE__);
	else
		$node = node($node, __FILE__);
	$dataNode = node('..', $node);
	$file = node($dataNode, $node, 'file');
	$url = node($dataNode, $node, 'file_url');
	$dir = dirname($file) . '/' . basename($file, '.php') . '/backups';
	if(file_exists($dir)){
		$pages_path = dirname(helpers::get_pages_path());
		$backups_urls = helpers::combine($url, 'backups') . '/';
		?><ul><h3>Répertoire <a title="<?=$dir?>"><?=substr($dir, strlen($pages_path))?></a></h3><?php
		try{
			$it = new FilesystemIterator($dir);
			foreach ($it as $fileinfo) {
				?><li><a href="<?=$backups_urls . basename($fileinfo)?>" target="_blank">
					<?=preg_replace('/\.bak$/', '', $fileinfo->getFilename());?></a>
				- modifié le <?=date("d/m/Y H:i:s.",filemtime($fileinfo))?><?php
			}
			?></ul><?php
		}
		catch(Exception $ex){
			echo $ex;
		}
	}
	else {
		?>aucun fichier<?php
	}
	return;
}
/* Crée une copie du fichier de données.
Une seule et même copie pour une adresse IP.
*/
return function($dataNodeId, $user){
	$node = node($node, __FILE__);
	$file = node($dataNodeId, $node, 'file');
	if(file_exists($file)){
		$client = $_SERVER['REMOTE_ADDR'];
		if($client == '::1')
			$client = 'localhost';
		$dest = dirname($file) . '/' . basename($file, '.php') . '/backups/'
			. basename(dirname($file)) . '.'
			. ($user ? 'u' . $user['id'] . '.' : '')
			. $client . '.bak';
		if(!file_exists(dirname($dest)))
		   mkdir(dirname($dest));
		copy($file, $dest);
	}
};
?>