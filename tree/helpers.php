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
	
	public static function include_edQ_conf(){
        $file = dirname(dirname(__FILE__)) . '/conf/edQ.conf.php';
        include_once($file);
    }
	
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
			self::rrmdir($dir.DIRECTORY_SEPARATOR.$file);
		rmdir($dir);
	  }
	  else if (file_exists($dir)) unlink($dir);
	}

	// copies files and non-empty directories
	public static function rcopy($src, $dst) {
	  if (file_exists($dst)) self::rrmdir($dst);
	  if (is_dir($src)) {
		if (!file_exists($dst))
			try { mkdir($dst); }
			catch(Exception $ex){ echo "Erreur de copie de $src vers $dest : $ex"; } //problème de délai qui déclenche parfois un " Permission denied " (en warning, du coup le try ne fonctionne pas )
		$files = scandir($src);
		foreach ($files as $file)
			if ($file != "." && $file != "..")
				self::rcopy($src.DIRECTORY_SEPARATOR.$file, $dst.DIRECTORY_SEPARATOR.$file);
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
	
	/***
	 * affiche le login, die() et retourne à l'url après login
	 */
	public static function login_needed($msg = FALSE, $redirection = NULL){
		if($redirection === NULL){
			$redirection = $_SERVER['REQUEST_URI'];// . '?' . $_SERVER['QUERY_STRING'];
		}
		$_REQUEST['edq--alert'] = $msg;
		$_REQUEST['redir'] = $redirection;
		include('inc/login.php');
		die();
	}
	
	/* plugins à charger dans index.php
	 *
	 * d'après index.php
	 * d'après la session de l'utilisateur
	 * d'après l'url ?q--plugins[]=dataTables,jqGrid
	 * */
	public static $plugins = FALSE;
	public static $plugins_defaults = array(
		'jstree' => true,
		'flot' => true,
		'jqGrid' => true,
		'colorpicker' => true,
		'dataTables' => true,
		'markitup' => true,
		'codemirror' => true,
	    );
	
	/**
	 * initialisation des plugins actifs
	 * 	$plugins
	 * 	+ $_REQUEST
	 * 	+ $_SESSION['edq-user']['plugins'] (previous)
	 * 	+ $_REQUEST['q--plugins']
	 * 	+ $_COOKIE['q--plugins']
	 * */
	/* TODO : configurable dans edQ.conf par $PLUGINS et initialisable d'après variables de session */
	/* TODO : transposer dans class.plugins */
	public static function init_plugins($plugins = FALSE){
		$defaults = self::$plugins_defaults;
		if(!is_array($plugins))
			$plugins = $defaults;
		else
			/* valeurs par défaut (sinon plantage de index.php) */
			foreach($defaults as $key=>$value)
				if(!isset($plugins[$key]))
					$plugins[$key] = $defaults[$key];
		/* valeurs issues de la session */
		if(isset($_SESSION)
		   && isset($_SESSION['edq-user'])
		   && isset($_SESSION['edq-user']['plugins'])
		)
			foreach($_SESSION['edq-user']['plugins'] as $key=>$value)
				$plugins[$key] = $value;
				
		/* valeurs issues de la requête
			url = ?q--plugins[]=dataTables,jqGrid
		*/
		if(isset($_REQUEST['q--plugins'])
		   && is_array($_REQUEST['q--plugins'])
		){
			foreach($_REQUEST['q--plugins'] as $key)
				if(strpos($key, ',') !== FALSE){
					foreach(explode(',',$key) as $subkey)
						$plugins[trim($subkey)] = true;
				}
				else
					$plugins[trim($key)] = true;
		}
	
		/* debug */
		//$plugins['dataTables'] = false; //debug
		//$plugins['jqGrid'] = false; //debug
		
		/* valeurs issues du cookie
			q--plugins=dataTables,jqGrid
		*/
		if(isset($_COOKIE)
		   && isset($_COOKIE['q--plugins'])
		   && $_COOKIE['q--plugins']
		){
			$cookies = preg_split('/\s*,+\s*/', $_COOKIE['q--plugins']);
			foreach($cookies as $key)
				$plugins[trim($key)] = true;
		}
		
		/* local settings $PLUGINS */
		global $PLUGINS;
		if(!isset($PLUGINS))
			self::include_edQ_conf();
		
		if(isset($PLUGINS))
			foreach($PLUGINS as $plugin)
				$plugins[trim($plugin)] = true;
		
		/* debug */
		//$plugins['dataTables'] = false; //debug
		//$plugins['jqGrid'] = false; //debug
		
		/* sauve en session */
		if(isset($_SESSION)
		&& isset($_SESSION['edq-user']))
			$_SESSION['edq-user']['plugins'] = $plugins;
		
		self::$plugins = $plugins;
		return $plugins;
	}
	
	/* teste la nécessité d'un plugin parmi ceux chargés dans index.php */
	/* TODO : transposer dans class.plugins */
	public static function need_plugin($plugin){
		if(!is_array(self::$plugins))
			self::init_plugins();
		//var_dump(self::$plugins);
		if(!isset(self::$plugins[$plugin])
		|| !self::$plugins[$plugin]){
			/* plugin manquant !!! */
			if(false && strpos($_SERVER["PHP_SELF"], 'index.php') !== FALSE
			|| strpos($_SERVER["PHP_SELF"], 'page.php') !== FALSE){
				/* Redirection vers la même page */
				$query = $_SERVER['QUERY_STRING'] . "&q--plugins[]=$plugin";
				header('Location:'.$_SERVER['PHP_SELF'].'?'.$query);
				die;
			}
			
			/* cookie des plugins utilisés */
			if(isset($_COOKIE)
			&& isset($_COOKIE['q--plugins'])
			)
				$cookie = preg_replace('/(\s*,\s*)+$/', '', $_COOKIE['q--plugins']) . ',' . $plugin;
			else if(preg_match('/(^|,\s*)'.$plugin.'(\s*,|$)/', $_COOKIE['q--plugins']))
				$cookie = $_COOKIE['q--plugins'];
			else
				$cookie = $plugin;
			setcookie('q--plugins', $cookie, time()+ 3600 * 24 * 30, '/');
			
			/* alert */
			die( '<script>
			if(confirm("Le plugin ' . $plugin . ' est manquant !\r\nRecharger ?")){
			    var search = document.location.search;
			    var href = document.location.href.replace(search, "");
			    if(!search) search = "?";
			    search += "&q--plugins[]=' . $plugin . '";
			    href += search;
			    //alert(href);
			   document.location.href = href;
			}
			</script>' );
			
		}
		self::$plugins[$plugin] = true;
	}
	
	// Function that replaces htmlenties(utf8_decode()) that differs between servers
	public static function utf8_to_htmlentities ($string) {
		/* Only do the slow convert if there are 8-bit characters */
		/* avoid using 0xA0 (\240) in ereg ranges. RH73 does not like that */
		if (!preg_match("/[\200-\237]/", $string)
		 && !preg_match("/[\241-\377]/", $string)
		) {
			return $string;
		}

		// decode three byte unicode characters
		$string = preg_replace("/([\340-\357])([\200-\277])([\200-\277])/e",
			"'&#'.((ord('\\1')-224)*4096 + (ord('\\2')-128)*64 + (ord('\\3')-128)).';'",
			$string
		);

		// decode two byte unicode characters
		$string = preg_replace("/([\300-\337])([\200-\277])/e",
			"'&#'.((ord('\\1')-192)*64+(ord('\\2')-128)).';'",
			$string
		);

		return $string;
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