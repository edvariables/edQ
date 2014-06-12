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

// removes files and non-empty directories
function rrmdir($dir) {
  if (is_dir($dir)) {
	$files = scandir($dir);
	foreach ($files as $file)
	if ($file != "." && $file != "..") rrmdir("$dir/$file");
	rmdir($dir);
  }
  else if (file_exists($dir)) unlink($dir);
}

// copies files and non-empty directories
function rcopy($src, $dst) {
  if (file_exists($dst)) rrmdir($dst);
  if (is_dir($src)) {
	mkdir($dst);
	$files = scandir($src);
	foreach ($files as $file)
	if ($file != "." && $file != "..") rcopy("$src/$file", "$dst/$file");
  }
  else if (file_exists($src)) copy($src, $dst);
}

/* static helpers */
class helpers {
	/* get_pagesPath
		return D:\Wamp\www\edQ\pages
	*/
	public static function get_pagesPath(){
		$page = preg_replace('/^http.*\/([^\/\?]+)\/[^\/\?]+\.php(\?.*)?$/', '$1', $_SERVER['HTTP_REFERER']);
		$path = preg_replace('/[\/\\\\]$/', '', $_SERVER['DOCUMENT_ROOT']) . '/';
		$path = $path . $page;
		//var_dump(($path . '/pages'));
		$path = str_replace('\\', '/', realpath($path . '/pages'));
		return $path;
	}
	
	/* nodeFile_mv
		déplace un fichier + répertoire d'un noeud
	*/
	public static function nodeFile_mv($oldPath, $oldName, $newPath, $newName, $override = false) {
		
		$root = helpers::get_pagesPath();
		
		// Répertoire
		$src = $root . '/' . $oldPath . '/' . $oldName;
		$dest = $root . '/' . $newPath . '/' . $newName;			
		//var_dump($root);			
		//var_dump($src);
		//var_dump(file_exists($src));
		if(($src != $dest)
		&& file_exists($src)){		
			if(file_exists($dest)){
				//copie recursive
				throw new Exception("TODO : déplacement recursif du répertoire");
			}
			else {
				rename($src, $dest);
			}
		}
		
		// Fichier .php
		$src = $root . '/' . $oldPath . '/' . $oldName . '.php';
		$destPath = $root . '/' . $newPath;
		$dest = $destPath . '/' . $newName . '.php';
		if(($src != $dest)
		&& file_exists($src)){
			if(!file_exists($destPath))
				mkdir($destPath);
			if(file_exists($dest)){
				if( $override )
					unlink($dest);
				else
					return false;
			}
			rename($src, $dest);
		}
		return true;		
	}
	
	/* nodeFile_cp
		copie un fichier + répertoire d'un noeud
	*/
	public static function nodeFile_cp($oldPath, $oldName, $newPath, $newName, $override = false) {
		
		$root = helpers::get_pagesPath();
		
		// Répertoire
		$src = $root . '/' . $oldPath . '/' . $oldName;
		$dest = $root . '/' . $newPath . '/' . $newName;
		if(file_exists($src)){		
			if(file_exists($dest)){
				//copie recursive
				throw new Exception("TODO : copie recursive du répertoire");
			}
			else {
				rcopy($src, $dest);
			}
		}
		
		// Fichier .php
		$src = $root . '/' . $oldPath . '/' . $oldName . '.php';
		$destPath = $root . '/' . $newPath;
		$dest = $destPath . '/' . $newName . '.php';
		if(file_exists($src)){
			if(!file_exists($destPath))
				mkdir($destPath);
			if(file_exists($dest)){
				if( $override )
					unlink($dest);
				else
					return false;
			}
			copy($src, $dest);
		}
		return true;
		
	}
	
	/* nodeFile_rm
		supprime un fichier + répertoire d'un noeud
	*/
	public static function nodeFile_rm($oldPath, $oldName) {
		
		$root = helpers::get_pagesPath();
		
		// Répertoire
		$src = $root . '/' . $oldPath . '/' . $oldName;
		if(file_exists($src)){
			rrmdir($src);
		}
		
		// Fichier .php
		$src = $root . '/' . $oldPath . '/' . $oldName . '.php';
		//var_dump($root);			
		//var_dump($src);
		//var_dump(file_exists($src));
		if(file_exists($src)){
			unlink($src);
		}
		
	}
}
?>