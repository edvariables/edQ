<?php

/* static page class */
class page {
	
	/* root_path
		return D:/Wamp/www/edQ/pages
		sous-entendu que la page appelée est à la racine (index.php, view.php)
	*/
	public static function get_root_path(){
		$page = preg_replace('/^http.*\/([^\/\?]+)\/[^\/\?]+\.php(\?.*)?$/', '$1', $_SERVER['HTTP_REFERER']);
		$path = helpers::combine($_SERVER['DOCUMENT_ROOT'], $page);
		return str_replace('\\', '/', realpath($path . '/pages'));
	}
	
	/* include_page
		include rel($__FILE__, $search, '.php')
		exécute une page relative en définissant la variables $arguments
		Recherche
			..dataSource : chez les parents
			.dataSource : au niveau de la référence ou chez les parents
			:dataSource : dans la descendance
			/_System/dataSource : à partir de la racine
	*/
	public static function execute($search, $__FILE__ = null, $extension = ".php", &$arguments = null){
		if($search !== null && $search !== '' && $search[0] == '/')
			$file = page::get_root_path() . $search . $extension;
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
					page::get_root_path()
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
								return page::execute($search, $ref, $extension, $arguments);
						}
					}
					else { // eg : '.dataSource' : on cherche ici et chez les parents
						$file = helpers::combine($ref, substr($search, 1), $extension);
						//var_dump($file);
						//var_dump(file_exists($file));
						if(!file_exists($file)
						&& dirname($ref) != $ref)
							return page::execute( $search, $ref, $extension, $arguments); //recursive chez les parents
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
	/* call with $arguments defined
		include rel($__FILE__, $search, '.php')
		exécute une page relative en définissant la variables $arguments
	*/
	public static function call($search, &$arguments = null, $__FILE__ = null, $extension = ".php"){
		return page::execute($search, $__FILE__, $extension, $arguments);
	}
	/* url
		retourne l'url de la page relative
		Recherche
			..dataSource : chez les parents
			.dataSource : au niveau de la référence ou chez les parents
			:dataSource : dans la descendance
			/_System/dataSource : à partir de la racine
	*/
	public static function url($search, $__FILE__ = null, $extension = ".php"){
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
				page::get_root_path()
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
							return page::url($search, $ref, $extension);
					}
				}
				else { // eg : '.dataSource' : here or parents
					$file = helpers::combine($ref, substr($search, 1), $extension);
					if(!file_exists($file)
					&& dirname($ref) != $ref)
						return page::url( $search, $ref, $extension);
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
?>