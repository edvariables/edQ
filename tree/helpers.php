<?php
/* ifNull
*/
function ifNull($null, $default = ''){
	if(!isset($null) || $null === null)
		return $default;
	return $null;
}
/* is_associative
*/
function is_associative($array){
	if(!is_array($array)) return false;
	foreach($array as $k=>$v)
		return $k !== null && $k !== 0;
	return false;
}
/* is_design
*/
function is_design(){
	if(!user_right('design'))
		return false;
		
	if(isset($_REQUEST['design']))
		return $_REQUEST['design'] != 'false' && (bool)$_REQUEST['design'];
	
	return false;
}
/* user_right
*/
function user_right($domain = null, $minRight = 1){
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

/* get_temp_dir
	equivalent a sys_get_temp_dir mais controle les droits d'acces et substitue par $_SERVER['DOCUMENT_ROOT'], 'edQ/tmp' si nécessaire.
*/
function get_temp_dir(){
	$dir = sys_get_temp_dir();
	$perms = fileperms( sys_get_temp_dir() );
	if(!($perms & 0x0080)){
		return get_local_temp_dir();
	}
	return $dir;
}
/* get_local_temp_dir
	$_SERVER['DOCUMENT_ROOT'], 'edQ/tmp' si nécessaire.
*/
function get_local_temp_dir(){
	//$dir = helpers::combine($_SERVER['DOCUMENT_ROOT'], 'edQ/tmp');
	$dir = helpers::combine(dirname(dirname(__FILE__)), 'tmp');
	if(!file_exists($dir))
		mkdir($dir);
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
			. (preg_match('/^[\/\\\\]/', $file) ? substr($file, 1) : $file)
			. $extension
		);
	}
	
	/* get_pages_path
		return D:\Wamp\www\edQ\pages
	*/
	public static function get_pages_path(){
		$path = dirname(dirname(__FILE__));
		return str_replace('\\', '/', $path) . '/pages';
	}
	// public static function get_pagesPath(){
		// return self::get_pages_path();
	// }
	/* nodeFile_mv
		déplace un fichier + répertoire d'un noeud
	*/
	public static function nodeFile_mv($oldPath, $oldName, $newPath, $newName, $override = false) {
		
		$root = helpers::get_pages_path();
		
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
		
		$root = self::get_pages_path();
		
		// Répertoire
		$src = $root . '/' . $oldPath . '/' . $oldName;
		$dest = $root . '/' . $newPath . '/' . $newName;
		if(file_exists($src)){		
			if(file_exists($dest)){
				//copie recursive
				throw new Exception("TODO : copie recursive du répertoire");
			}
			else {
				self::rcopy($src, $dest);
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
		
		$root = helpers::get_pages_path();
		
		// Répertoire
		$src = $root . '/' . $oldPath . '/' . $oldName;
		if(file_exists($src)){
			self::rrmdir($src);
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

	// removes files and non-empty directories
	public static function rrmdir($dir) {
	  if (is_dir($dir)) {
		$files = scandir($dir);
		foreach ($files as $file)
		if ($file != "." && $file != "..")
			self::rrmdir("$dir/$file");
		rmdir($dir);
	  }
	  else if (file_exists($dir)) unlink($dir);
	}

	// copies files and non-empty directories
	public static function rcopy($src, $dst) {
	  if (file_exists($dst)) self::rrmdir($dst);
	  if (is_dir($src)) {
		mkdir($dst);
		$files = scandir($src);
		foreach ($files as $file)
			if ($file != "." && $file != "..")
				self::rcopy("$src/$file", "$dst/$file");
	  }
	  else if (file_exists($src))
		copy($src, $dst);
	}
	/* aasort
		tri un tableau associatif sur la propriété fournie
	*/
	public static function aasort (&$array, $key) {
		$sorter=array();
		$ret=array();
		reset($array);
		foreach ($array as $ii => $va) {
			$sorter[$ii]=$va[$key];
		}
		asort($sorter);
		foreach ($sorter as $ii => $va) {
			$ret[$ii]=$array[$ii];
		}
		$array=$ret;
	}

	
	/**
	 * echo call stack
	 * helpers::callstack()
	 */
	public static function callstack($skip = 1, $max = INF){
		echo('<pre>');
		$dt = debug_backtrace();
		foreach ($dt as $t)
			if($skip-- > 0)
				continue;
			elseif ($max-- < 1)
				break;
			else {
				echo $t['file'] . ' line ' . $t['line'] . ' function ' . $t['function'] . "()\n";
			}
		echo('</pre>');
	}
}

/*  url_viewNode
	
*//* cf page::url()
function url_view($node, $arguments = null, $view_name = 'file.call'){
	return 'view.php?id=' . ( is_numeric($node) ? $node : $node['id'] )
		. ( $arguments == null ? '' : '&' . (is_array($arguments) ? implode('&', $arguments) : $arguments) )
		. '&vw=' . $view_name
	;
} */
/* view_node
	call viewer for node
*/
/* cf page::view()
function view_node($viewer, $node){
	$viewer = nodeViewer::fromClass($viewer);
	if(is_numeric($node)){
		global $tree;
		$node = $tree->get_node($node, array('with_path' => false, 'with_children' => false, 'full' => true));
	}
	$html = $viewer->html($node);
	print_r( $html['content'] );
	return true;
}*/

?>