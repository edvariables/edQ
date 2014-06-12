<?php

class node {
	public $domain = null;
	
	/* static get_types
	*/
	public static function get_types(){
		return array(
			  "default" => array(
				"icon" => "file file-file"
			  ),
			  "folder" => array(
				"icon" => "file file-folder"
			  ),
			  "sql" => array(
				"icon" => "file file-sql"
			  ),
			  "css" => array(
				"icon" => "file file-css"
			  ),
			  "php" => array(
				"icon" => "file file-php"
			  ),
			  "js" => array(
				"icon" => "file file-js"
			  ),
			  "html" => array(
				"icon" => "file file-html"
			  ),
			  "query" => array(
				"icon" => "file file-query"
			  ),
			  "dataSource" => array(
				"icon" => "file file-iso"
			  ),
			  "jqGrid" => array(
				"icon" => "file file-query"
			  )
		);
	}
	
	/* static get_icons
	*/
	public static function get_icons(){
		return array("file file-file"
			, "file file-folder"
			, "file file-folder-sys"
			, "file file-sql"
			, "file file-cs"
			, "file file-css"
			, "file file-htm"
			, "file file-php"
			, "file file-c"
			, "file file-iso"
			, "file file-js"
			, "file file-pdf"
			, "file file-query"
		);
	}
	
	/* static get_icons
	*/
	public static function get_ulvls(){
		return array(
			"0" => "Administrateur"
			, "1" => "Gestionnaire"
			, "2" => "Utilisateur"
			, "999" => "Public"
		);
	}
	
	/* static fromClass
		retourne une instance d'après la classe spécifiée.
		charge le fichier $class . ".php"
		et instancie un object de la classe "node_" . $class.
		Par exemple, le fichier query.php déclare la classe node_query qui hérite de node.
	*/
	public static function fromClass($class, $properties){
		if($class == null || $class == '')
			return new node($properties);
		$php = dirname(__FILE__) . '/' . $class . ".php";
		if(!file_exists($php))
			return new node($properties);
		include_once( $php );
		$fullClass = __CLASS__ . "_" . $class;
		return new $fullClass($properties);
	}
	
	/* constructeur
		initialise l'objet avec les propriétés issues de la table tree_data.
		$propertiesOrId peut être un entier, auquel cas la requête sur la table est exécutée.
	*/
	public function __construct($propertiesOrId){
		if(is_numeric($propertiesOrId)){
			$id = $propertiesOrId;
			require_once(dirname(__FILE__) . '/../db.php');	 //TODO load tree only
			global $tree;
			$propertiesOrId = $tree->get_node((int)$id, array('with_path' => false, 'full' => false));
		}
		$this->properties = $propertiesOrId;
	}
	
	/* properties
	*/
	public $properties;
	
	/* generic setter
		pour un appel du type $myNode->myProperty = "x"
		cherche dans properties le nom de la propriété donnée
		sinon cherche une méthode set_myProperty 
	*/
	public function __set($name, $value) {
		if($this->properties !== null
		&& array_key_exists($name, $this->properties))
			$this->properties[$name] = $value;
		else {
			$funcName = 'set_'.$name;
			return $this->$funcName($value);
		}
	}
	/* generic getter
		pour un appel du type $x = $myNode->myProperty
		cherche dans properties le nom de la propriété donnée
		sinon cherche une méthode get_myProperty 
	*/
	public function __get($name) {
		if($this->properties != null
		&& array_key_exists($name, $this->properties))
			return $this->properties[$name];
		$funcName = 'get_'.$name;
		return $this->$funcName();
	}
	
	/* name
	*/
	public function get_name() {
		return $this->properties["nm"];
	}
	
	/* type
	*/
	public function get_type() {
		return $this->properties["typ"];
	}
	
	/* path
		combinaison des id des parents
	*/
	public function path($joinChar = '/'){
		if(!isset($this->properties["path"])){
			global $tree;
			$this->properties["path"] = $tree->get_path($this->this->properties["id"]);
		}
		return $joinChar . implode($joinChar, array_map(function ($v) { return $v['nm']; }, $this->properties['path'])). $joinChar .$this->name;
	}
	
	/* get_pagePath
		répertoire
	*/
	public function get_pagePath(){
		if(!isset($this->properties["path"])){
			$this->properties = $tree->get_node((int)$this->properties['id'], array('with_path' => true, 'full' => false));
		}
		/*$path = $_SERVER['DOCUMENT_ROOT'];
		if(substr($path, -strlen($path)) != '/')
			$path .= '/';
		$path = $path
			. preg_replace('/(\/?(.+)\/\w+\.php$)?/', '$2', $_SERVER['PHP_SELF']);
		//var_dump(($path . '/../pages'));*/
		$path = helpers::get_pagesPath() //str_replace('\\', '/', realpath($path . '/../pages'))
			. '/' . implode('/',array_map(function ($v) { return $v['nm']; }, $this->properties['path']))
		;
		return $path;
	}
	/* findFile
	*/
	function findFile($shortName){
		$prev = $path = $this->get_pagePath();
		while($path != '' && $path != '/'){
			if(file_exists( $path . '/' . $shortName))
				return $path . '/' . $shortName;
			else
				$path = dirname($path);
			if($prev == $path)
				return null;
			$prev = $path;
		}
		return $shortName;
	}
	/* get_url()
		returns /edQ/tree/nodeViewer/fileContent.php
	*/
	public function get_url($sub = ''){
		$path = str_replace('\\', '/', substr( dirname(__FILE__), strlen( $_SERVER[ 'DOCUMENT_ROOT' ] ) ) ) . '/';
		return ( $path[0] == '/' ? '' : '/' ) . $path . $this->properties['nm'] . ($sub == null ? '' : '.' . $sub) . '.php';
	}
	
	/* loadParameters
		charge les paramètres depuis la table node_param
	*/
	public function loadParameters($domain = null, $allFields = false){
		$db = db::get(DBTYPE . '://' . DBUSER . ':' . DBPASSWORD . '@' . DBSERVER . '/' . DBNAME);
		$dbRows = $db->all($sql = '
			SELECT d.param, d.domain, d.value, p.text, p.valueType, p.icon'
				. ($allFields ? ', p.defaultValue, p.comment, p.sortIndex' : '') . '
			FROM 
				node_param d
			'. ($domain == null ? 'LEFT ' : '') . '
			JOIN
				node_params p
					ON d.param = p.param
					AND d.domain = p.domain
			WHERE 
				d.id = ?
				
			'. ($domain === null ? ''
			: 'AND
				d.domain = ?
			' ) . '
						
			ORDER BY
				d.domain, p.sortIndex, p.text, d.param'
			, array((int)$this->properties['id'], $domain)
		);
		
		$exists = count($dbRows) > 0;
		$rows = array();
		if($exists){
			foreach($dbRows as $param)
				$rows[$param["param"]] = $param;
		}
		//var_dump($rows);
		$this->properties[ $domain ] = $rows;
		return $rows;
	}
	
	/* parameters
		retourne les paramètres depuis la table node_param
	*/
	public function parameters($domain = null){
		if( $domain === null ) $domain = $this->domain;
		if(!isset($this->properties[ $domain ]))
			return $this->loadParameters( $domain );
		return $this->properties[ $domain ];
	}
	
	/* parameter value
		valeur du paramètre issu de la table node_param
	*/
	public function paramValue($param, $defaultValue = '', $domain = null){
		$params = $this->parameters($domain);
		if(!isset($params[$param]))
			return $defaultValue;
		return $params[$param]["value"];
	}
	
	
	/* icon
	*/
	public function icon($icon = null){
		if($icon == null)
			$icon = $this->properties['icon'];
		if($icon == '(none)')
			return '';
		if($icon == null || $icon == '')
			if($this->properties['typ'] != null && $this->properties['typ'] != '')
				$icon = 'file file-' . $this->properties['typ'];
			else
				$icon = 'file file-file';
		return '<i class="jstree-icon jstree-themeicon jstree-themeicon-custom ' . $icon . '"></i>'
		;
	}
	
	/* label = icon + text
	*/
	public function label($name = null, $icon = null){
		if($name == null)
			$name = $this->properties['nm'];
		$icon = $this->icon($icon);
		if($icon == null)
			$icon = '';
		return '<label class="jstree-default" href="#">' . $icon . '' . $name . '</label>'
		;
	}
}
?>