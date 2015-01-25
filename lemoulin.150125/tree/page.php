<?php

/***
 * static page class
 */
class page {
	
	/* root_path
		return D:/Wamp/www/edQ/pages
		sous-entendu que la page appelée est à la racine (index.php, view.php)
	*/
	public static function get_root_path(){
		
		$path = dirname(dirname(__FILE__));
		//var_dump(($path . '/pages'));
		return str_replace('\\', '/', $path) . '/pages';
	}
	
	/* file
		nom de fichier relatif
		Recherche
			..dataSource : chez les parents
			.dataSource : au niveau de la référence ou chez les parents
			:dataSource : dans la descendance
			/_System/dataSource : à partir de la racine
	*/
	public static function file($search, $refers_to = null, $extension = ".php"){
		if(!$search || $search == null){
			if(is_string($refers_to))
				return $refers_to;
			$search = $refers_to;
			$refers_to = null;
		}
		if(is_array($search) && isset($search['id'])){
			global $tree;
			return helpers::combine(self::get_root_path(), $tree->get_path_string($search), $extension);
		}
		else if(is_numeric($search)){
			global $tree;
			$node = $tree->get_node($search, array( 'with_path' => true ));
			if(!$node)
				throw new Exception($search . ' ne fournit pas de noeud');
			return helpers::combine(self::get_root_path(), $tree->get_path_string($node), $extension);
			//echo 'static function file '; var_dump($search);
		}
		
		if($search !== null && $search !== '' && $search[0] == '/'){
			if(substr($search, 1, strlen(TREE_ROOT_NAME) + 1) != TREE_ROOT_NAME . '/')
				$search = '/' . TREE_ROOT_NAME . '/' . substr($search, 1);
			$file = page::get_root_path() . $search . $extension;
		}
		else {
			//Pas de fichier de référence fourni, on l'extrait depuis la trace
			if($refers_to == null){
				//$refers_to = helpers::get_pages_path();
				$dt = debug_backtrace();
				for($i = 0; $i < count($dt); $i++)
					if($dt[$i]['file'] != __FILE__)
						break;
				$refers_to = $dt[$i]['file'];
			}
			//Fichiers de référence multiples
			else if(is_array($refers_to)){ //$node
				$node = $refers_to;
				if($node['path'] == null){
					global $tree;
					$node = $tree->get_node($node, array( 'with_path' => true ));
				}
				$refers_to = helpers::combine(
					page::get_root_path()
					, implode('/', array_map(function ($v) { return $v['nm']; }, $node['path'])). '/'.$node['nm']  . '.php'
				);
				//require('nodeType/__class.php');
			}
			//Pas de recherche fournie
			if($search === null || $search === '')
				$file = $refers_to;
			//Recherche relative
			else {
				$ref = dirname($refers_to);
				
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
								return page::file($search, $ref, $extension);
						}
					}
					else { // eg : '.dataSource' : on cherche ici et chez les parents
						$file = helpers::combine($ref, substr($search, 1), $extension);
						//var_dump($file);
						//var_dump(file_exists($file));
						if(!file_exists($file)
						&& dirname($ref) != $ref)
							return page::file( $search, $ref, $extension); //recursive chez les parents
					}
				else if($search[0] == ':'){ // eg : ':Liste' : dans la descendance
					$file = helpers::combine(substr($refers_to, 0, strlen($refers_to) - 4), substr($search, 1), $extension); // $refers_to moins l'extension .php
				}
				else // eg : 'dataSource'
					$file = helpers::combine($ref, $search, $extension);
			}
		}
		return $file;
	}
	/* folder
		nom de dossier relatif
		Recherche
			..dataSource : chez les parents
			.dataSource : au niveau de la référence ou chez les parents
			:dataSource : dans la descendance
			/_System/dataSource : à partir de la racine
	*/
	public static function folder($search, $refers_to = null){
		return preg_replace('/\.php$/', '', self::file($search, $refers_to));
	}
	
	/* execute
		include rel($refers_to, $search, '.php')
		exécute une page relative en définissant la variables $arguments
		Recherche
			..dataSource : chez les parents
			.dataSource : au niveau de la référence ou chez les parents
			dataSource : au niveau de la référence
			:dataSource : dans la descendance
			/_System/dataSource : à partir de la racine
	*/
	public static function execute($search, $refers_to = null, $extension = ".php", $arguments = null){
		$file = page::file($search, $refers_to, $extension);
		
		// include
		if(file_exists($file)){
			if($arguments == null)
				$arguments = array();
			return include($file);
		}
		else if(file_exists(utf8_decode($file))){
			if($arguments == null)
				$arguments = array();
			return include(utf8_decode($file));
		}
		// fichier inconnu
		echo('<pre class="edq-error">');
		echo "[page::execute] Fichier introuvable : " . print_r($file, true) . "\r\n";
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
		return FALSE;
	}
	/* call with $arguments defined
		include rel($refers_to, $search, '.php')
		exécute une page relative en définissant la variables $arguments
	*/
	public static function call($search, $arguments = null, $refers_to = null, $extension = ".php"){
		return page::execute($search, $refers_to, $extension, $arguments);
	}
	/* file_url
		retourne l'url du fichier de la page relative
		Recherche
			..dataSource : chez les parents
			.dataSource : au niveau de la référence ou chez les parents
			:dataSource : dans la descendance
			/_System/dataSource : à partir de la racine
	*/
	public static function file_url($search, $refers_to = null, $extension = ".php"){
		$file = page::file($search, $refers_to, $extension);
		if(!file_exists($file) && file_exists(utf8_decode($file)))
			$file = utf8_decode($file);
		
		// include
		if(file_exists($file)){
			$root = preg_replace('/\\$/', '', $_SERVER['DOCUMENT_ROOT']);
			return '/' . str_replace('\\', '/', substr($file, strlen($root) + ($root[strlen($root)-1] == '/' ? 0 : 1)));
		}
		// fichier inconnu
		echo('<pre class="edq-error">');
		echo "[page::file_url] Fichier introuvable : " . $file . "\r\n";
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

	/* folder_url
		retourne l'url du dossier de la page relative
		Recherche
			..dataSource : chez les parents
			.dataSource : au niveau de la référence ou chez les parents
			:dataSource : dans la descendance
			/_System/dataSource : à partir de la racine
	*/
	public static function folder_url($search, $refers_to = null){
		return preg_replace('/\.php$/', '', self::file_url($search, $refers_to));
	}
	/* view_node
		call viewer for node
	*/
	public static function node($search = null, $refers_to = null){
		if(($search == null) || !$search || $search == '.'){
			if($refers_to == null){
				$dt = debug_backtrace();
				for($i = 0; $i < count($dt); $i++)
					if($dt[$i]['file'] != __FILE__)
						break;
				$refers_to = $dt[$i]['file'];
			}
			if(is_string($refers_to)){
				$search = substr($refers_to, strlen( page::get_root_path() ));
				// supprime le .php et remplace \ par /
				$search = str_replace('\\', '/', substr( $search, 0, strlen($search) - 4));
				$search = substr( $search, strlen(TREE_ROOT_NAME) + 1);
			}
		}
		return tree::get_node_by_name($search, $refers_to);
	}

	/* id
		node id
	*/
	public static function id($search, $refers_to = null){
		if(is_numeric($search))
			return $search;
		if(is_array($search))
			return $search['id'];
		$node = node($search, $refers_to);
		if(is_array($node))
			return $node['id'];
		return $node;
	}
	
	/* page::url
	
	*/
	public static function url($search, $refers_to = null, $arguments = null, $view_name = 'file.call'){
		if($search == null && $refers_to == null)
			return null;
		$node = page::id($search, $refers_to);
		return 'view.php?id=' . $node
			. ( $arguments == null ? '' : '&' . (is_array($arguments) ? implode('&', $arguments) : $arguments) )
			. '&vw=' . $view_name
		;
	}

	/* view_node
		call viewer for node
	*/
	public static function viewer($search, $refers_to = null, $view_name = 'file.call'){
		return nodeViewer::fromClass($view_name);
	}
	/* view_node
		call viewer for node
	*/
	public static function html($search, $refers_to = null, $arguments = null, $view_name = 'file.call'){
	//function view_node($viewer, $node){
		if($search == null && $refers_to == null)
			return null;
		$viewer = nodeViewer::fromClass($view_name);
		//$node = page::id($search, $refers_to); //TODO pourquoi pas ?
		$node = page::node($search, $refers_to); 
		
		$html = $viewer->html($node);
		print_r( $html['content'] );
		return true;
	}/* view_node
		call viewer for node
	*/
	public static function view($view_name = 'file.call', $search = null, $refers_to = null, $arguments = null){
	//function view_node($viewer, $node){
		if($search == null && $refers_to == null)
			return null;
		$viewer = nodeViewer::fromClass($view_name);
		$node = page::node($search, $refers_to);
		
		$html = $viewer->html($node);
		print_r( $html['content'] );
		return true;
	}
	
	
	/* form_submit_script
	 * Script js de soumission d'un formulaire et refresh du contenu
	 * options :
	 * 	beforeSubmit : javascript script executed before data are sent
	 * 	success : javascript function when answer is back
	 * 	error : javascript function when 
	 */
	public static function form_submit_script($form_uid, $options = null){
		//TODO use js script
		$beforeSubmit = is_array($options) && isset($options['beforeSubmit']) ? $options['beforeSubmit'] : '';
		$success = is_array($options) && isset($options['success']) ? $options['success'] : false;
		$error = is_array($options) && isset($options['error']) ? $options['error'] : false;
		return
			'<script>  
$(document).ready(function() { 
	$("#' . $form_uid . '").submit(function() {
		var $form_uid = "' . $form_uid . '";
		' . $beforeSubmit . ';
		$(this).ajaxSubmit({
			beforeSubmit: function(){ 
				document.body.style.cursor = "wait";
			}
			, success: function(data){
				' . ( $success
				     ? '(' . $success . ')();'
				     : '//reload with answer
					$("#' . $form_uid . '").parents(".ui-widget-content:first").html(data);
				').'
				$("body").css("cursor", "default");
			}
			, error: function(jq, textStatus, errorThrown, more ) { 
				' . ( $error
				     ? '(' . $error . ')();'
				     : '
					alert(textStatus + " : " + errorThrown); 
				').'
				document.body.style.cursor = "auto";
			}
		});
		return false;
	})
}); 
</script>'
		;
	}

}
?>