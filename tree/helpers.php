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

function isDesign(){
	if(!userRight('design'))
		return false;
		
	if(isset($_REQUEST['design']))
		return $_REQUEST['design'] != 'false' && (bool)$_REQUEST['design'];
	
	return false;
}
/* userRight
*/
function userRight($domain = null, $minRight = 1){
	if(!isset($_SESSION['edq-user'])
	|| !isset($_SESSION['edq-user']['id'])
	|| !isset($_SESSION['edq-user']['rights']))
		return false;
	if($domain == null)
		return true;	//logged
	if(!isset($_SESSION['edq-user']['rights'][$domain])
	|| !$_SESSION['edq-user']['rights'][$domain]
	|| (($_SESSION['edq-user']['rights'][$domain] & $minRight) != $minRight)){
		return false;
	}
	return $_SESSION['edq-user']['rights'][$domain];
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

/* get_temp_dir
	equivalent a sys_get_temp_dir mais controle les droits d'acces et substitue par $_SERVER['DOCUMENT_ROOT'], 'edQ/tmp' si nécessaire.
*/
function get_temp_dir(){
	$dir = sys_get_temp_dir();
	$perms = fileperms( sys_get_temp_dir() );
	if(!($perms & 0x0080)){
		$dir = helpers::combine($_SERVER['DOCUMENT_ROOT'], 'edQ/tmp');
		if(!file_exists($dir))
			mkdir($dir);
	}
	return $dir;
}

/* get_db
	include_page ('.dataSource.php')
	return global $db
	e.g : $db = get_db(); //find the first dataSource.php in tree starting from dirname(__FILE__)
*/
function get_db($search = '.dataSource', $__FILE__ = null){
	if($__FILE__ == null){
		$dt = debug_backtrace();
		for($i = 0; $i < count($dt); $i++)
			if($dt[$i]['file'] != __FILE__)
				break;
		$__FILE__ = $dt[$i]['file'];
	}
	page::execute($search, $__FILE__, '.php');
	global $db;
	return $db;
}

/* static helpers */
class helpers {
	/* combine
		return $root . '/' . $file . $extension
	*/
	public static function combine($root, $file, $extension = ''){
		return utf8_encode(
			$root
			. (preg_match('/[\/\\\\]$/', $root) ? '' : DIRECTORY_SEPARATOR)
			. $file
			. $extension
		);
	}
	
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

class oldies {
	/* include_page
		include rel($__FILE__, $search, '.php')
		exécute une page relative en définissant la variables $arguments
		Recherche
			..dataSource : chez les parents
			.dataSource : au niveau de la référence ou chez les parents
			:dataSource : dans la descendance
			/_System/dataSource : à partir de la racine
	*/
	function include_page($search, $__FILE__ = null, $extension = ".php", &$arguments = null){
		if($search !== null && $search !== '' && $search[0] == '/')
			$file = helpers::get_pagesPath() . $search . $extension;
		else {
			//Pas de fichier de référence fourni, on l'extrait depuis la trace
			if($__FILE__ == null){
				//$__FILE__ = helpers::get_pagesPath();
				$dt = debug_backtrace();
				for($i = 0; $i < count($dt); $i++)
					if($dt[$i]['file'] != __FILE__)
						break;
				$__FILE__ = $dt[$i]['file'];
			}
			//Fichiers de référence multiples
			else if(is_array($__FILE__)){ //$node
				$node = $__FILE__;
				$__FILE__ = helpers::combine(
					helpers::get_pagesPath()
					, implode('/',array_map(function ($v) { return $v['nm']; }, $node['path'])). '/'.$node['nm']  . '.php'
				);
				//require('nodeType/__class.php');
			}
			//Pas de recherche fournie
			if($search === null || $search === '')
				$file = $__FILE__;
			//Recherche relative
			else {
				$ref = dirname($__FILE__);
				
				//var_dump($ref);
				//var_dump($search);
				
				if($search[0] == '.')
					if($search[1] == '.'){ // eg : '..dataSource' on cherche chez les parents
						if(strlen($search) == 2){ // ..
							$file = $ref . ($extension == null ? '' : $extension);
						}
						else {
							$ref = dirname($ref);
							$file = helpers::combine($ref, substr($search, 2), $extension);
							//var_dump($file);
							//var_dump(file_exists($file));
							if(!file_exists($file))
								return include_page($search, $ref, $extension, $arguments);
						}
					}
					else { // eg : '.dataSource' : on cherche ici et chez les parents
						$file = helpers::combine($ref, substr($search, 1), $extension);
						//var_dump($file);
						//var_dump(file_exists($file));
						if(!file_exists($file)
						&& dirname($ref) != $ref)
							return include_page( $search, $ref, $extension, $arguments); //recursive chez les parents
					}
				else if($search[0] == ':'){ // eg : ':Liste' : dans la descendance
					$file = helpers::combine(substr($__FILE__, 0, strlen($__FILE__) - 4), substr($search, 1), $extension); // $__FILE__ moins l'extension .php
				}
				else // eg : 'dataSource'
					$file = helpers::combine($ref, $search, $extension);
			}
		}
		
		// include
		if(file_exists($file)){
			if($arguments == null)
				$arguments = array();
			include($file);
			return $file;
		}
		else if(file_exists(utf8_decode($file))){
			if($arguments == null)
				$arguments = array();
			include(utf8_decode($file));
			return utf8_decode($file);
		}
		// fichier inconnu
		echo('<pre class="edq-error">');
		echo "[include_page] Fichier introuvable : " . $file . "\r\n";
		echo('<small>');
		$dt = debug_backtrace();
		$thiscall = null;
		foreach ($dt as $t)
			if($thiscall === null)
				$thiscall = $t;
			else if($thiscall['file'] != $t['file']
			|| $thiscall['line'] != $t['line'])
				echo $t['file'] . ' line ' . $t['line'] . ' function ' . $t['function'] . "()\n";
		
		echo('</small>');
		echo('</pre>');
		return $file;
	}
	/* call_page with $arguments defined
		include rel($__FILE__, $search, '.php')
		exécute une page relative en définissant la variables $arguments
	*/
	function call_page($search, &$arguments = null, $__FILE__ = null, $extension = ".php"){
		return include_page($search, $__FILE__, $extension, $arguments);
	}
	/* url_page
		retourne l'url de la page relative
		Recherche
			..dataSource : chez les parents
			.dataSource : au niveau de la référence ou chez les parents
			:dataSource : dans la descendance
			/_System/dataSource : à partir de la racine
	*/
	function url_page($search, $__FILE__ = null, $extension = ".php"){
		if($__FILE__ == null){
			$dt = debug_backtrace();
			for($i = 0; $i < count($dt); $i++)
				if($dt[$i]['file'] != __FILE__)
					break;
			$__FILE__ = $dt[$i]['file'];
		}
		else if(is_array($__FILE__)){ //$node
			$node = $__FILE__;
			$__FILE__ = helpers::combine(
				helpers::get_pagesPath()
				, implode('/',array_map(function ($v) { return $v['nm']; }, $node['path'])). '/'.$node['nm']  . '.php'
			);
		}
		if($search === null || $search === '')
			$file = $__FILE__;
		else {
			$ref = dirname($__FILE__);
			
			if($search[0] == '.')
				if($search[1] == '.'){ // eg : '..dataSource' : parents
					if(strlen($search) == 2){ // ..
						$file = $ref . ($extension == null ? '' : $extension);
					}
					else {
						$file = helpers::combine($ref, substr($search, 2), $extension);
						if(!file_exists($file))
							return url_page($search, $ref, $extension);
					}
				}
				else { // eg : '.dataSource' : here or parents
					$file = helpers::combine($ref, substr($search, 1), $extension);
					if(!file_exists($file)
					&& dirname($ref) != $ref)
						return url_page( $search, $ref, $extension);
				}
			else if($search[0] == ':'){ // eg : ':Liste'
				$file = helpers::combine(substr($__FILE__, 0, strlen($__FILE__) - 4), substr($search, 1), $extension); // $__FILE__ moins l'extension .php
			}
			else // eg : 'dataSource' or '/_System/dataSource'
				$file = helpers::combine($ref, $search, $extension);
		}
		
		// include
		if(file_exists(utf8_decode($file))){
			$root = preg_replace('/\\$/', '', $_SERVER['DOCUMENT_ROOT']);
			return '/' . str_replace('\\', '/', substr($file, strlen($root)));
		}
		// fichier inconnu
		echo('<pre class="edq-error">');
		echo "[url_page] Fichier introuvable : " . $file . "\r\n";
		echo('<small>');
		$dt = debug_backtrace();
		$thiscall = null;
		foreach ($dt as $t)
			if($thiscall === null)
				$thiscall = $t;
			else if($thiscall['file'] != $t['file']
			|| $thiscall['line'] != $t['line'])
				echo $t['file'] . ' line ' . $t['line'] . ' function ' . $t['function'] . "()\n";
		
		echo('</small>');
		echo('</pre>');
		return $file;
	}
}

/* url_viewNode
	
*/
function url_view($node, $arguments = null, $view_name = 'file.call'){
	return 'view.php?id=' . ( is_numeric($node) ? $node : $node['id'] )
		. ( $arguments == null ? '' : '&' . (is_array($arguments) ? implode('&', $arguments) : $arguments) )
		. '&vw=' . $view_name
	;
}
/* view_node
	call viewer for node
*/
function view_node($viewer, $node){
	$viewer = nodeViewer::fromClass($viewer);
	if(is_numeric($node)){
		global $tree;
		$node = $tree->get_node($node, array('with_path' => false, 'with_children' => false, 'full' => true));
	}
	$html = $viewer->html($node);
	print_r( $html['content'] );
	return true;
}

?>