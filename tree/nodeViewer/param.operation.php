<?php /* Modification de paramètre de noeud
UTF8 é

Les paramètres sont dans la table node_param.
*/

/***************
	action
****************/
if(isset($_REQUEST['op'])) {
	require_once(dirname(__FILE__) . '/../../conf/edQ.conf.php');
	require_once(dirname(__FILE__) . '/../../inc/class.db.php');
					
	$db = db::get(DBTYPE . '://' . DBUSER . ':' . DBPASSWORD . '@' . DBSERVER . ':' . DBPORT . '/' . DBNAME);
	
	switch($_REQUEST['op']){
	case 'submit' :
		//cherche les arguments de paramètres
		foreach($_REQUEST as $arg => $value){
			$matches = array();
			if(preg_match('/^([^|]+-)?([^|]+)\|(.*)?$/', $arg, $matches) > 0){
				$domain = $matches[1];
				$param = $matches[2];
				if($domain === '')
					throw new Exception('Le domaine doit être fourni dans la clé.');
				else
					$domain = substr($domain, 0, strlen($domain) - 1);
				$property = isset($matches[3]) ? $matches[3] : '';
				if($property === '')
					$property = "value";
	
				$q = $db->query("INSERT INTO node_param (id, domain, param, " . $property . ")"
					. " VALUES (?, ?, ?, ?)"
					. " ON DUPLICATE KEY UPDATE"
					. " domain = ?"
					. ", param = ?"
					. ", value = ?"
					, array($_REQUEST['id'], $domain, $param, $value, $domain, $param, $value)
				);
			}
		}
		if(isset($q))
			die((string)$q->af());
		else {
			die("0");
		}
		break;
	
	// delete
	case 'delete' :
		//die("refusé");
		//cherche les arguments de paramètres
		foreach($_REQUEST as $arg => $value){
			$matches = array();
			if(preg_match('/^([^|]+-)?([^|]+)\|(.*)?$/', $arg, $matches) > 0){
				$domain = $matches[1];
				$param = $matches[2];
				if($domain === '')
					throw new Exception('Le domaine doit être fourni dans la clé.');
				else
					$domain = substr($domain, 0, strlen($domain) - 1);
				/*$property = isset($matches[3]) ? $matches[3] : '';
				if($property === '')
					$property = "value";*/
				
				$q = $db->query("DELETE FROM node_param
					WHERE id = ?
					AND param = ?
					AND domain = ?"
					, array($_REQUEST['id'], $param, $domain)
				);
			}
		}
		if(!isset($q))
			die("Aucun paramètre reconnu pour être supprimé.");
		die((string)$q->af());
		break;
		
	default:
		break;
	}
}

?>