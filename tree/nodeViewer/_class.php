<?php /* class nodeViewer
Héritée par 
- viewers : classe affichant les onglets vers les autres vues
- node : classe permettant la modification du noeud lui-même
- children : affiche les noeuds descendants
- comment : affiche le commentaire du noeud
- edit : TODO
- file : le noeud correspond à un fichier qui contient du code php. Héritée par les classes file*
- fileContent :  : affiche le contenu du fichier. Héritée par fileMarkItUp et fileTinyMCE
- fileCall : affiche l'interprétation du fichier php associé au noeud
- query : édition des paramétres d'une requête
- queryCall : affiche le résultat d'une requête
*/

require(dirname(__FILE__) . '/../nodeType/_class.php');

class nodeViewer {
	public static $tree;
	
	public $domain = null;
	public $name = 'default';
	public $text = 'Résumé';
	
	public $needChildren = false;
	
	/* static fromClass
		retourne une instance d'après la classe spécifiée.
		charge le fichier $class . ".php"
		et instancie un object de la classe "nodeViewer_" . $class.
		Par exemple, le fichier query.php déclare la classe nodeViewer_query qui hérite de nodeViewer.
	*/
	public static function fromClass($class){
		if($class == null || $class == '')
			return new nodeViewer();
		include_once(dirname(__FILE__) . '/' . $class . ".php");
		$fullClass = __CLASS__ . "_" . $class;
		return new $fullClass();
	}
	
	/* path
		combinaison des id des parents
	*/
	public function path($node, $joinChar = '/'){
		if(!isset($node["path"])){
			global $tree;
			$node["path"] = $tree->get_path($node['id']);
		}
		return $joinChar . implode($joinChar, array_map(function ($v) { return $v['nm']; }, $node['path'])). $joinChar .$node['nm'];
	}
	
	
	/* get_filePath
	*/
	public function get_pagePath($node){
		if(!isset($node["path"])){
			$node = $tree->get_node((int)$node['id'], array('with_path' => true, 'full' => false));
		}
		$path = $_SERVER['DOCUMENT_ROOT'];
		if(substr($path, -strlen($path)) != '/')
			$path .= '/';
		$path = $path
			. preg_replace('/(\/?(.+)\/\w+\.php$)?/', '$2', $_SERVER['PHP_SELF']);
		//var_dump(($path . '/../pages'));
		$path = str_replace('\\', '/', realpath($path . '/../pages'))
			. '/' . implode('/',array_map(function ($v) { return $v['nm']; }, $node['path']))
		;
		return $path;
	}
	/* findFile
	*/
	function findFile($node, $shortName){
		$path = $this->get_filePath($node);
		while($path != '' && $path != '/')
			if(file_exists( $path . '/' . $shortName))
				return $path . '/' . $shortName;
			else
				$path = dirname($path);
		return $shortName;
	}
	/* get_url()
		returns /edQ/tree/nodeViewer/fileContent.php
	*/
	public function get_url($node, $sub = ''){
		$path = str_replace('\\', '/', substr( dirname(__FILE__), strlen( $_SERVER[ 'DOCUMENT_ROOT' ] ) ) ) . '/';
		return ( $path[0] == '/' ? '' : '/' ) . $path . $this->name . ($sub == null ? '' : '.' . $sub) . '.php';
	}
	
	/*************** 
		HTML
	***************/
	
	/* icon
	*/
	public function icon($node, $icon = null){
		if($icon == null)
			$icon = $node['icon'];
		if($icon == '(none)')
			return '';
		if($icon == null || $icon == '')
			if($node['typ'] != null && $node['typ'] != '')
				$icon = 'file file-' . $node['typ'];
			else
				$icon = 'file file-file';
		return '<i class="jstree-icon jstree-themeicon jstree-themeicon-custom ' . $icon . '"></i>'
		;
	}
	
	/* label = icon + text
	*/
	public function label($node, $name = null, $icon = null){
		if($name == null)
			$name = $node['nm'];
		$icon = $this->icon($node, $icon);
		if($icon == null)
			$icon = '';
		return '<label class="jstree-default" href="#">' . $icon . '' . $name . '</label>'
		;
	}

	/* html
		Détails du noeud.
		Fonction destinée à être surchargée
	*/
	public function html($node){
		if(!isset($node["typ"])){
			global $tree;
			$node = $tree->get_node((int)$node['id'], array('with_path' => true, 'full' => true));
		}
		$html = '<fieldset class="q-fields"><div>'
			 . '<div><label class="ui-state-default ui-corner-all">#' . $node['id'] . '</label>'
				. '</div>'
			. '<div><label class="ui-state-default ui-corner-all">Nom</label>'
				. '<span>' . $node["nm"] . '</span></div>'
			. '<div><label class="ui-state-default ui-corner-all">Icône</label>'
				. '<span>' . $node["icon"] . '</span></div>'
			. '<div><label class="ui-state-default ui-corner-all">Type</label>'
				. '<span>' . $node["typ"] . '</span></div>'
			. ($node["ext"] == null
				? ''
				: '<div><label class="ui-state-default ui-corner-all">Clé externe</label>'
				. '<span>' . $node["ext"] . '</span></div>')
			. ($node["params"] == null
				? ''
				: '<div><label class="ui-state-default ui-corner-all">Paramètres</label>'
				. '<pre>' . $node["params"] . '</pre></div>')
			. '<div><label class="ui-state-default ui-corner-all">Sécurité</label>'
				. '<span>' . $node["ulvl"] . '</span></div>'
			. '<div><label class="ui-state-default ui-corner-all">Propriétaire</label>'
				. '<span>' . $node["user"] . '</span></div>'
			. '</div></fieldset>'
		;
		return array(
			"title" => $node['nm']
			, "content" => $html
		);
	}
	
	/* formScript
		Script js de soumission d'un formulaire
	*/
	public function formScript($uid, $options = null, $callback = null){
		return
		'<script>  
			$(document).ready(function() { 
				// bind form and provide a simple callback function 
				$("#' . $uid . '").submit(function() { 
					$(this).ajaxSubmit({
						beforeSubmit: function(){
						}
						, success: function(data){
							if(isNaN(data))
								$("<pre>" + data + "</pre>").dialog();
							else {
								$("#' . $uid . ' legend small i:last").remove();'/*efface un eventuel flag "n'existe pas"*/
								. ($callback === null || $callback === '' 
									? ''
									: '' . $callback . ';') . '
							}
						}
						, error: function(jq, textStatus, errorThrown, more ) { 
							alert(textStatus + " : " + errorThrown); 
						}
					});
					return false;
				})
		   }); 
		</script> '
		;
	}
}

?>