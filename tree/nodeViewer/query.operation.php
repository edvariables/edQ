<?php /* Gestion de noeud de requête
UTF8 é

Les paramètres sont dans la table node_param.
"SQLSelect" => SQL
"SQLUpdate" => SQL
"SQLInsert" => SQL
"SQLDelete" => SQL
"Columns" => liste de colonnes ( interprétés via "array(" . $1 . ");" )
	"column" => "titre"
	"column" => {
		"text" => "nom"
		"text" => function($column, $rows, $viewer){ return $column["text"]; }
		"value" => function($row, $column, $viewer){ return $row; }
		"visible" => true
		"attributes" => array()
		"css" => function($column, $viewer){ return ''; }
	}
"Row" => modèle de ligne
	=> function($row, $index, $rows, $viewer){ return '<tr class="' . ($index % 2 === 0 ? 'even' : 'odd') . '">'; }
"Caption" => titre de la table ( interprétés via "return function($node, $rows, $viewer) { [return] " . $1 . "; };" )
"Foot" => pied de table ( interprétés via "return function($node, $rows, $viewer) { [return] " . $1 . "; };" )
*/

/***************
	action
****************/
if(isset($_POST['operation'])) {
	require_once(dirname(__FILE__) . '/../../conf/db.conf.php');
	require_once(dirname(__FILE__) . '/../../bin/class.db.php');
					
	$db = db::get(DBTYPE . '://' . DBUSER . ':' . DBPASSWORD . '@' . DBSERVER . ':' . DBPORT . '/' . DBNAME);
	
	switch($_POST['operation']){
	case 'submit' :
		//cherche les arguments de paramètres
		foreach($_POST as $arg => $value){
			$matches = array();
			if(preg_match('/^([^|]+-)?([^|]+)\|(.*)?$/', $arg, $matches) > 0){
				$domain = $matches[1];
				$param = $matches[2];
				if($domain === '')
					$domain = 'query';
				else
					$domain = substr($domain, 0, strlen($domain) - 1);
				$property = isset($matches[3]) ? $matches[3] : '';
				if($property === '')
					$property = "value";
				
				$q = $db->query("INSERT INTO node_param (id, param, domain, " . $property . ")"
					. " VALUES (?, ?, ?, ?)"
					. " ON DUPLICATE KEY UPDATE"
					. " param = ?"
					. ", domain = ?"
					. ", value = ?"
					, array($_POST['id'], $param, $domain, $value, $param, $domain, $value)
				);
			}
		}
		if(isset($q))
			die((string)$q->af());
		else
			die("0");
		break;
	
	// delete
	case 'delete' :
		//die("refusé");
		//cherche les arguments de paramètres
		foreach($_POST as $arg => $value){
			$matches = array();
			if(preg_match('/^([^|]+-)?([^|]+)\|(.*)?$/', $arg, $matches) > 0){
				$domain = $matches[1];
				$param = $matches[2];
				if($domain === '')
					$domain = 'query';
				else
					$domain = substr($domain, 0, strlen($domain) - 1);
				/*$property = isset($matches[3]) ? $matches[3] : '';
				if($property === '')
					$property = "value";*/
				
				$q = $db->query("DELETE FROM node_param
					WHERE id = ?
					AND param = ?
					AND domain = ?"
					, array($_POST['id'], $param, $domain)
				);
			}
		}
		if(!isset($q))
			die("Aucun paramètre reconnu pour être supprimé");
		die((string)$q->af());
		break;
		
	default:
		break;
	}
}

?>